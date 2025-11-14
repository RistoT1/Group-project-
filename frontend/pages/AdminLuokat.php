<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/pages/signin.css">
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/pages/adminDashboard.css">
    <title>Luokkahuoneet</title>
</head>

<body>
    <header class="admin-header">
        <div class="header-left">
            <h1>Luokkahuoneet</h1>
            <p class="subtitle">Tarkastele luokkahuoneita</p>
        </div>
        <div class="header-right">
            <button class="btn btn-outline">Kirjaudu ulos</button>
        </div>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <nav>
                <ul>
                    <li><a href="adminDashboard.php">Käyttäjät</a></li>
                    <li><a href="AdminLuokat.php" class="active">Luokkahuoneet</a></li>
                    <li><a href="AdminVaraus.php">Varauskatsaus</a></li>
                </ul>
            </nav>
        </aside>

        <section class="admin-main">
            <div class="main-panel">
                <div class="actions-row">
                    <div class="search-wrap">
                        <input type="search" name="q" placeholder="Hae varausta" aria-label="Hae varausta">
</script>
<script type="module" src="../scripts/pages/ClassListPage.js"></script>
<script type="module" src="../scripts/pages/adminActions.js"></script>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary">+ Lisää Uusi Luokka</button>
                    </div>
                </div>

                <div class="cards-row">
                    <div class="card">
                        <div class="card-title">Kaikki Luokat</div>
                        <div class="card-value">25 </div>
                    </div>
                    <div class="card">
                        <div class="card-title">Tänään</div>
                        <div class="card-value">4</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Käytössä olevat luokat</div>
                        <div class="card-value">14</div>
                    </div>
                </div>

                <div class="table-card">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Luokka</th>
                                <th>Ajankohta</th>
                                <th>Varaaja</th>
                                <th>Tila</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Luokka 101</td>
                                <td>13.11.2025 10:00 - 12:00</td>
                                <td>Maija Meikäläinen</td>
                                <td>Hyväksytty</td>
                                <td>
                                    <button class="btn btn-small btn-edit" data-type="luokka" data-id="1">Muokkaa</button>
                                    <button class="btn btn-small btn-delete" data-type="luokka" data-id="1">Poista</button>
                                </td>
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