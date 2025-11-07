# Backend - Tekninen Dokumentaatio

## Yleiskatsaus

Backend on rakennettu PHP:lla käyttäen PDO-tietokantayhteyttä ja JWT-pohjaista autentikointia. 
Järjestelmä noudattaa MVC-perusteita ja keskitettyä reititystä.

---

## Projektirakenne

```
backend/
├── config/
│   └── config.php          # Tietokantayhteys ja 
muuttujat
├── handler/
│   ├── Auth/
│   │   ├── auth.php        # JWT-tokenin validointi
│   │   └── kirjaudu.php    # Kirjautumislogiikka
│   ├── Kayttajat/          # Käyttäjähallinta
│   ├── Luokat/             # Luokkahallinta
│   ├── Ajat/               # Aikahallinta
│   └── Varaus/             # Varaushallinta
├── router/
│   └── main.php            # Keskitetty reititin
└── vendor/                 # Composer-riippuvuudet
```

---

## Keskeiset Komponentit

### 1. Konfiguraatio (`config/config.php`)

**Tarkoitus:** Luo tietokantayhteyden ja lataa ympäristömuuttujat.

```php
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
```

**Toiminnallisuus:**
- Lataa `.env`-tiedoston ympäristömuuttujat
- Luo PDO-tietokantayhteyden MySQL-tietokantaan

**Tietokantayhteyden parametrit:**
- `DB_HOST` - Tietokannan osoite
- `DB_NAME` - Tietokannan nimi
- `DB_USER` - Käyttäjätunnus
- `DB_PASS` - Salasana
- `DB_CHARSET` - Merkistö (UTF-8)

**PDO-asetukset:**
- `ERRMODE_EXCEPTION` - Heittää poikkeukset virheistä
- `FETCH_ASSOC` - Palauttaa rivit taulukoina
- `EMULATE_PREPARES = false` - Käyttää prepared statements -lauseita

---

### 2. Autentikointi

#### 2.1 Kirjautuminen (`Auth/kirjaudu.php`)

**Toiminta:**

1. **Syötteen validointi:**
   ```php
   if (!$sähköposti || !$salasana) {
       http_response_code(400);
       return ["success" => false, "message" => "Täytä kaikki kentät"];
   }
   ```

2. **Tietokantatransaktio:**
   - Aloittaa transaktion tiedon eheyden varmistamiseksi
   - Hakee käyttäjän sähköpostilla
   - Tarkistaa salasanan `password_verify()`-funktiolla

3. **JWT-tokenin luominen:**
   ```php
   $payload = [
       'sub' => $kayttaja['KayttajaID'],     // Käyttäjän ID
       'email' => $kayttaja['Sähköposti'],   // Sähköposti
       'role' => $kayttaja['Rooli'],          // Käyttäjärooli
       'iat' => time(),                       // Luomisaika
       'exp' => time() + 3600                 // Vanhenemisaika (1h)
   ];
   
   $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
   ```

4. **Onnistunut vastaus:**
   - Palauttaa JWT-tokenin
   - Palauttaa käyttäjän perustiedot 

**Turvallisuus:**
- Salasanat hashattu `password_hash()`-funktiolla
- JWT allekirjoitettu (`JWT_SECRET`) - avaimella
- Estää epäjohdonmukaisuudet epäjohdonmukaiset transaktiot

---

#### 2.2 Tokenin Validointi (`Auth/auth.php`)

**Funktio:** `tarkistaAuth()`

**Toiminta:**

1. **Tarkista Authorization-otsikko:**
   ```php
   $authHeader = $headers['Authorization'] ?? '';
   
   if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
       http_response_code(401);
       exit;
   }
   ```

2. **Erota token:**
   ```php
   $token = substr($authHeader, 7); // Poistaa "Bearer jwt:n " alusta
   ```

3. **Dekoodaa ja validoi:**
   ```php
   $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
   return $decoded; // Palauttaa payload-objektin
   ```

**Virheenkäsittely:**
- Puuttuva token → 401 Unauthorized
- Virheellinen token → 401 Unauthorized
- Vanhentunut token → 401 Unauthorized

---

### 3. Keskitetty Reititin (`router/main.php`)

**Arkkitehtuuri:** RESTful API -reititin reittikartoilla.

#### 3.1 Reittimääritykset

```php
$routes = [
    'GET' => [
        'kayttajat' => 'getKayttajat',
        'luokat' => 'getLuokat',
        // ...
    ],
    'POST' => [
        'addKayttaja' => 'addKayttaja',
        'kirjaudu' => 'kirjaudu',
        // ...
    ]
];
```

**Rakenne:**
- Avain = URL-parametri tai body-kenttä
- Arvo = Käsittelijäfunktion nimi

---

#### 3.2 Käyttöoikeustasot

**Admin-only reitit:**
```php
$adminOnlyRoutes = [
    'GET' => ['kayttajat'],
    'POST' => ['addKayttaja', 'deleteKayttaja', 'addAika', 'addLuokka', 'editLuokka']
];
```

**Julkiset reitit (ei autentikointia):**
```php
$publicRoutes = [
    'kirjaudu',
    'getLuokat',
    'getAjat',
    // ...
];
```

**Oletusreitit:** Vaativat autentikoinnin, mutta ei admin-roolia.

---

#### 3.3 Pyynnön Käsittely

**Vaiheet:**

1. **Tunnista HTTP-metodi:**
   ```php
   $method = $_SERVER['REQUEST_METHOD'];
   ```

2. **Lue syöte:**
   ```php
   $input = $method === 'POST'
       ? json_decode(file_get_contents('php://input'), true)
       : $_GET;
   ```

3. **Etsi vastaava reitti:**
   ```php
   foreach ($routes[$method] as $param => $handler) {
       if (array_key_exists($param, $input)) {
           // Löytyi!
       }
   }
   ```

4. **Tarkista käyttöoikeudet:**
   ```php
   if (!in_array($param, $publicRoutes)) {
       $decoded = tarkistaAuth();  // Validoi token
       $role = $decoded->role ?? 'user';
       
       // Tarkista admin-oikeudet
       if (in_array($param, $adminOnlyRoutes[$method] ?? []) && $role !== 'ylläpitäjä') {
           http_response_code(403);
           exit;
       }
   }
   ```

5. **Kutsu käsittelijää:**
   ```php
   $result = $method === 'POST'
       ? $handler($pdo, $input)
       : $handler($pdo);
   
   echo json_encode($result);
   ```

---

#### 3.4 Virheenkäsittely

**HTTP-statuskoodit:**

| Koodi | Tilanne | Syy |
|-------|---------|-----|
| 400   | Bad Request | Puutteelliset tai virheelliset parametrit |
| 401   | Unauthorized | Token puuttuu tai virheellinen |
| 403   | Forbidden | Ei riittäviä käyttöoikeuksia |
| 404   | Not Found | Reittiä ei löydy |
| 405   | Method Not Allowed | Väärä HTTP-metodi |
| 500   | Internal Server Error | Palvelinvirhe |

**Try-catch -rakenne:**
```php
try {
    // Käsittele pyyntö
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
```

---

## Tietoturva

### 1. Salasanojen Käsittely

**Tallennuksessa:**
```php
$hash = password_hash($salasana, PASSWORD_DEFAULT);
```

**Vertailussa:**
```php
password_verify($salasana, $kayttaja['SalasanaHash'])
```

**Miksi turvallinen:**
- Käyttää bcrypt-algoritmia
- Automaattinen suolaus (salt)
- Laskennallisesti kallis (hidastaa brute-force -hyökkäyksiä)

---

### 2. SQL-injektioiden Esto

**Prepared Statements:**
```php
$stmt = $pdo->prepare("SELECT * FROM kayttajat WHERE Sähköposti = ? LIMIT 1");
$stmt->execute([$sähköposti]);
```

**Hyödyt:**
- Parametrit erotettu SQL-kyselystä
- PDO tekee automaattisen escapoinnin
- Estää kaikki SQL-injektiohyökkäykset

---

### 3. JWT-turvallisuus

**Allekirjoitus:**
- Salainen avain `.env`-tiedostossa
- Ei voi väärentää ilman salaista avainta

**Vanheneminen:**
- Tokenit vanhenevat 1 tunnissa (`exp`-kenttä)
- Pakottaa säännöllisen uudelleenkirjautumisen

---

## Tietokantaoperaatiot

### Transaktioiden Käyttö

**Esimerkki kirjautumisesta:**
```php
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("SELECT * FROM kayttajat WHERE Sähköposti = ?");
    $stmt->execute([$sähköposti]);
    $kayttaja = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($kayttaja && password_verify($salasana, $kayttaja['SalasanaHash'])) {
        $pdo->commit();  // Vahvista muutokset
        return ["success" => true];
    } else {
        $pdo->rollBack();  // Peru muutokset
        return ["success" => false];
    }
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}
```

**Hyödyt:**
- ACID-periaatteet
- Tiedon eheys virhetilanteissa


---

## Ympäristömuuttujat (`.env`)

**Vaaditut muuttujat:**

```env
# Tietokanta
DB_HOST=localhost
DB_NAME=varausjarjestelma
DB_USER=root
DB_PASS=salasana
DB_CHARSET=utf8mb4

# JWT
JWT_SECRET=pitkä-satunnainen-merkkijono
```

**Turvallisuus:**
- Älä koskaan commmittaa `.env`-tiedostoa Gitiin
- Käytä vahvaa, satunnaista JWT_SECRET-arvoa

---

## Riippuvuudet (Composer)

**Vaaditut paketit:**

```json
{
    "require": {
        "firebase/php-jwt": "^6.0",
        "vlucas/phpdotenv": "^5.0"
    }
}
```

**Asennus:**
```bash
composer install
```

---

## Käyttöönotto

### 1. Asenna riippuvuudet
```bash
composer install
```

### 2. Luo `.env`-tiedosto
```bash
cp .env.example .env
```

### 3. Konfiguroi tietokanta
- Päivitä `.env`-tiedoston tietokanta-asetukset
- Luo tietokanta ja taulut (käytä SQL-skeemaa)

### 4. Generoi JWT-salaisuus
```bash
php -r "echo bin2hex(random_bytes(32));"
```
Kopioi tulos `JWT_SECRET`-muuttujaksi.

### 5. Testaa API
- Käynnistä paikallinen palvelin
- Testaa Postman-kokoelmilla
- Tarkista virheilmoitukset

---

## API-käytön Esimerkkejä Frontendissä

### Kirjautuminen
```javascript
fetch('http://localhost/.../main.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        kirjaudu: true,
        sahkoposti: "john@example.com",
        salasana: "securePassword123!"
    })
})
.then(res => res.json())
.then(data => {
    const token = data.token;
    // Tallenna token myöhempää käyttöä varten
});
```

### Autentikoitu Pyyntö
```javascript
fetch('http://localhost/.../main.php?kayttajat=true', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`
    }
})
.then(res => res.json())
.then(data => console.log(data));
```

---

## Yleiset Virheet ja Ratkaisut

| Virhe | Syy | Ratkaisu |
|-------|-----|----------|
| "Token puuttuu" | Authorization-otsikko puuttuu | Lisää `Authorization: Bearer <token>` |
| "Virheellinen token" | Token vanhentunut tai väärä | Kirjaudu uudelleen saadaksesi uuden tokenin |
| "Ei käyttöoikeutta" | Käyttäjällä ei admin-roolia | Tarkista käyttäjän rooli tietokannassa |
| "Reittiä ei löytynyt" | Väärä parametri | Tarkista reitti `$routes`-määrityksistä |
| "Connection refused" | Tietokanta ei tavoitettavissa | Tarkista `.env`-asetukset ja MySQL-palvelu |

---

## Parhaita Käytäntöjä kehityksessä

1. **Prepared statements -lauseet** - Estää SQL-injektiot
2. **Validoi kaikki syötteet** - Älä luota käyttäjän dataan
3. **Käytä transaktioita** - Varmista tiedon eheys
4. **Pidä JWT_SECRET turvassa** - Älä commitoi versionhallintaan
5. **Aseta oikeat HTTP-statuskoodit** - Helpottaa debuggausta
6. **Logita virheet** - Käytä tuotannossa oikea error logging
7. **Käytä HTTPS:ää tuotannossa** - Suojaa data siirrossa

---

## Yhteenveto

Backend tarjoaa:
- ✅ Turvallisen JWT-pohjaisen autentikoinnin
- ✅ Roolipohjaisen pääsynhallinnan (RBAC)
- ✅ RESTful API-rajapinnan
- ✅ Keskitetyn reitityksen
- ✅ SQL-injektiosuojauksen
- ✅ Skaalautuvan arkkitehtuurin

Järjestelmä on valmis frontend-kehitykseen ja tukee kaikkia dokumentoituja API-päätepisteitä.