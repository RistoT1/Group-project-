<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/pages/signin.css">
    <link rel="stylesheet" href="../styles/root.css">
    <title>Kirjaudu sisään</title>
</head>

<body>
    <div class="login-cont">
        <div class="card">
            <div class="card-left">
                <h1>Luokkavaraus</h1>
                <p>Filler filler filler filler filler</p>
            </div>
            <div class="card-right">
                <div>
                    <form id="sigin-form">
                        <label for="sahkoposti">Käyttäjätunnus</label>
                        <input type="email" name="sahkoposti" placeholder="Sähköposti" required />
                        <label for="salasana">Salasana</label>
                        <input type="password" name="salasana" placeholder="Salasana" required />
                        <button type="submit">Kirjaudu</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>
<script type="module" src="./../scripts/pages/signinPage.js"></script>

</html>