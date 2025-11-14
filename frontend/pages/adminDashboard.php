<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/pages/signin.css">
    <link rel="stylesheet" href="../styles/root.css">
     <link rel="stylesheet" href="../styles/components/navbar.css">
    <link rel="stylesheet" href="../styles/pages/adminDashboard.css">
    <title>Käyttäjien hallinta</title>
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <nav>
                <ul>
                    <li><a href="adminDashboard.php" class="active">Käyttäjät</a></li>
                    <li><a href="#">Luokkahuoneet</a></li>
                    <li><a href="AdminVaraus.php">Varauskatsaus</a></li>
                </ul>
            </nav>
        </aside>

        <section class="admin-main">
            <div class="main-panel">
            <div class="actions-row">
                <div class="search-wrap">
                    <input type="search" name="q" placeholder="Hae käyttäjää" aria-label="Hae käyttäjää">
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary">+ Lisää uusi käyttäjä</button>
                </div>
            </div>
            <div class="cards-row">
                <div class="card">
                    <div class="card-title">Yhteensä käyttäjiä</div>
                    <div class="card-value">128</div>
                </div>
                <div class="card">
                    <div class="card-title">Uudet viime 7 pv</div>
                    <div class="card-value">7</div>
                </div>
                <div class="card">
                    <div class="card-title">Aktiiviset nyt</div>
                    <div class="card-value">12</div>
                </div>
            </div>

            <div class="table-card">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nimi</th>
                            <th>Sähköposti</th>
                            <th>Rooli</th>
                            <th>Viimeksi aktiivinen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Esimerkki Käyttäjä</td>
                            <td>esimerkki@example.com</td>
                            <td>Opettaja</td>
                            <td>2 päivää sitten</td>
                            <td><button class="btn btn-small">Muokkaa</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div> 
        </section>
    </main>

</body>
<script type="module" src="../scripts/pages/ClassListPage.js"></script>
</html>