# Backend API Dokumentaatio

## Perus-URL
```
http://localhost/group-project/Group-project-/backend/router/main.php
```

## Autentikointi
Kaikki päätepisteet (paitsi kirjautuminen) vaativat JWT Bearer token -autentikoinnin.

**Authorization-otsikon muoto:**
```
Authorization: Bearer <JWT_TOKEN>
```

**Tokenin vanheneminen:** Tokenit vanhenevat tunnin kuluttua luomisesta.

---

## 1. Käyttäjähallinta

### 1.1 Kirjautuminen
**Päätepiste:** `POST /main.php`

**Kuvaus:** Autentikoi käyttäjä ja vastaanota JWT-token.

**Pyynnön body:**
```json
{
  "kirjaudu": true,
  "sahkoposti": "john@example.com",
  "salasana": "securePassword123!"
}
```

**Vastaus:** Palauttaa JWT-tokenin seuraavia pyyntöjä varten.

**Autentikointi vaaditaan:** Ei

---

### 1.2 Hae kaikki käyttäjät
**Päätepiste:** `GET /main.php?kayttajat=true`

**Kuvaus:** Hae lista kaikista käyttäjistä.

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

**Vastaus:** Taulukko käyttäjäolioita.

---

### 1.3 Lisää käyttäjä
**Päätepiste:** `POST /main.php`

**Kuvaus:** Luo uusi käyttäjätili.

**Pyynnön body:**
```json
{
  "addKayttaja": true,
  "nimi": "risto",
  "puhelin": "4094040",
  "sahkoposti": "root",
  "salasana": "securePassword123!"
}
```

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

### 1.4 Muokkaa käyttäjää
**Päätepiste:** `POST /main.php`

**Kuvaus:** Päivitä käyttäjän tiedot.

**Pyynnön body:**
```json
{
  "editKayttaja": true,
  "KayttajaID": "17",
  "Nimi": "risto1"
}
```

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

**Huomio:** Vain määritellyt kentät päivitetään.

---

### 1.5 Poista käyttäjä
**Päätepiste:** `POST /main.php`

**Kuvaus:** Poista käyttäjä järjestelmästä.

**Pyynnön body:**
```json
{
  "deleteKayttaja": true,
  "KayttajaID": "13"
}
```

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

## 2. Luokkahallinta

### 2.1 Hae kaikki luokat
**Päätepiste:** `GET /main.php?luokat=true`

**Kuvaus:** Hae lista kaikista luokista.

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

**Vastaus:** Taulukko luokkaolioita yksityiskohtineen.

---

### 2.2 Lisää luokka
**Päätepiste:** `POST /main.php`

**Kuvaus:** Luo uusi luokka.

**Pyynnön body:**
```json
{
  "addLuokka": true,
  "Nimi": "Luokka123",
  "Varusteet": "mummon telamiinat",
  "Kapasiteetti": 32,
  "Sijainti": "K2A103TM",
  "Tila": "aktiivinen"
}
```

**Kentät:**
- `Nimi` (merkkijono): Luokan nimi
- `Varusteet` (merkkijono): Saatavilla olevat varusteet/tilat
- `Kapasiteetti` (kokonaisluku): Maksimikapasiteetti
- `Sijainti` (merkkijono): Sijainti/huonenumero
- `Tila` (merkkijono): Status (esim. "aktiivinen")

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

### 2.3 Muokkaa luokkaa
**Päätepiste:** `POST /main.php`

**Kuvaus:** Päivitä luokan tiedot.

**Pyynnön body:**
```json
{
  "editLuokka": true,
  "LuokkaID": 8,
  "Nimi": "Luokka123",
  "Varusteet": "mummon telamiinat",
  "Kapasiteetti": 33,
  "Sijainti": "K2A103TM",
  "Tila": "aktiivinen"
}
```

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

### 2.4 Poista luokka
**Päätepiste:** `POST /main.php`

**Kuvaus:** Poista luokka järjestelmästä.

**Pyynnön body:**
```json
{
  "deleteLuokka": null,
  "LuokkaID": 8
}
```

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

## 3. Aikojen hallinta

### 3.1 Hae kaikki ajat
**Päätepiste:** `GET /main.php?ajat=true`

**Kuvaus:** Hae kaikki saatavilla olevat ajat.

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

**Vastaus:** Taulukko aikaoliota.

---

### 3.2 Lisää aikoja
**Päätepiste:** `POST /main.php`

**Kuvaus:** Luo useita aikavälejä luokalle.

**Pyynnön body:**
```json
{
  "addAika": true,
  "ajat": [
    {
      "LuokkaID": 10,
      "AloitusAika": "08:00:00",
      "LopetusAika": "08:30:00",
      "Paivamaara": "2025-11-08"
    },
    {
      "LuokkaID": 10,
      "AloitusAika": "08:30:00",
      "LopetusAika": "09:00:00",
      "Paivamaara": "2025-11-08"
    }
  ]
}
```

**Kentät:**
- `LuokkaID` (kokonaisluku): Luokan ID
- `AloitusAika` (aika): Alkamisaika (HH:MM:SS)
- `LopetusAika` (aika): Päättymisaika (HH:MM:SS)
- `Paivamaara` (päivämäärä): Päivämäärä (YYYY-MM-DD)

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

**Huomio:** Tukee useiden aikojen massalisäystä.

---

### 3.3 Muokkaa aikoja
**Päätepiste:** `POST /main.php`

**Kuvaus:** Päivitä useita aikavälejä.

**Pyynnön body:**
```json
{
  "editAika": true,
  "ajat": [
    {
      "AikaID": 38,
      "AloitusAika": "02:02:00",
      "LopetusAika": "01:20:00",
      "Paivamaara": "2025-08-20",
      "Tila": "vapaa"
    },
    {
      "AikaID": 34,
      "AloitusAika": "08:30:00",
      "LopetusAika": "09:00:00",
      "Paivamaara": "2025-11-30",
      "Tila": "varattu"
    }
  ]
}
```

**Kentät:**
- `AikaID` (kokonaisluku): Ajan ID
- `AloitusAika` (aika): Alkamisaika
- `LopetusAika` (aika): Päättymisaika
- `Paivamaara` (päivämäärä): Päivämäärä
- `Tila` (merkkijono): Status ("vapaa" = saatavilla, "varattu" = varattu)

**Autentikointi vaaditaan:** Kyllä (Ylläpitäjä-rooli)

---

## 4. Varaushallinta

### 4.1 Hae kaikki varaukset
**Päätepiste:** `GET /main.php?varaukset=true`

**Kuvaus:** Hae kaikki järjestelmän varaukset.

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

**Vastaus:** Taulukko varausolioita käyttäjä- ja aikatietoineen.

---

### 4.2 Lisää varaus
**Päätepiste:** `POST /main.php`

**Kuvaus:** Luo uusi luokkavaraus.

**Pyynnön body:**
```json
{
  "addVaraus": true,
  "KayttajaID": 2,
  "AikaID": 34,
  "Tarkoitus": "LEipa"
}
```

**Kentät:**
- `KayttajaID` (kokonaisluku): Varaavan käyttäjän ID
- `AikaID` (kokonaisluku): Varattavan aikavälin ID
- `Tarkoitus` (merkkijono): Varauksen tarkoitus/syy

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

---

### 4.3 Peruuta varaus
**Päätepiste:** `POST /main.php`

**Kuvaus:** Peruuta olemassa oleva varaus.

**Pyynnön body:**
```json
{
  "cancelVaraus": true,
  "VarausID": 6
}
```

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

---

### 4.4 Hae käyttäjän varaukset
**Päätepiste:** `POST /main.php`

**Kuvaus:** Hae tietyn käyttäjän kaikki varaukset.

**Pyynnön body:**
```json
{
  "kayttajaVaraukset": true,
  "KayttajaID": 3
}
```

**Autentikointi vaaditaan:** Kyllä (Opettaja-rooli)

**Vastaus:** Taulukko määritettyyn käyttäjään liittyviä varauksia.

---

## Käyttäjäroolit

Järjestelmässä on kolme käyttäjäroolia eri käyttöoikeustasoilla:

1. **ylläpitäjä** (Admin)
   - Täysi järjestelmäpääsy
   - Voi hallita käyttäjiä, luokkia ja aikoja
   - Voi nähdä kaikki varaukset

2. **opettaja** (Opettaja)
   - Voi nähdä luokat ja ajat
   - Voi luoda ja hallita varauksia
   - Voi nähdä omat ja muiden käyttäjien varaukset
   - Voi muokata käyttäjätietoja

3. **opiskelija** (Opiskelija)
   - Rajoitettu pääsy (tarkkoja oikeuksia ei määritelty kokoelmissa)

---

## Virheiden käsittely

API palauttaa asianmukaiset HTTP-statuskoodit:
- `200 OK`: Onnistunut pyyntö
- `400 Bad Request`: Virheellinen pyyntödata
- `401 Unauthorized`: Puuttuva tai virheellinen JWT-token
- `403 Forbidden`: Riittämättömät käyttöoikeudet
- `404 Not Found`: Resurssia ei löydy
- `500 Internal Server Error`: Palvelinvirhe

---

## Huomioita

1. **Päivämäärän muoto:** Kaikki päivämäärät tulee olla `YYYY-MM-DD` muodossa
2. **Ajan muoto:** Kaikki ajat tulee olla `HH:MM:SS` muodossa (24-tuntinen)
3. **JWT-tokenit:** Säilytä turvallisesti ja sisällytä Authorization-otsakkeeseen kaikissa autentikoiduissa pyynnöissä
4. **Massatoiminnot:** Aikaoperaatiot tukevat taulukoita massakäsittelyä/päivityksiä varten
5. **Kenttien päivitys:** Muokkaustoiminnoissa sisällytä vain ne kentät, jotka tarvitsevat päivitystä