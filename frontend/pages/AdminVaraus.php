<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/pages/signin.css">
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/pages/adminDashboard.css">
     <link rel="stylesheet" href="../styles/components/navbar.css">
    <title>Varauskatsaus</title>
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <nav>
                <ul>
                    <li><a href="adminDashboard.php">Käyttäjät</a></li>
                    <li><a href="#">Luokkahuoneet</a></li>
                    <li><a href="AdminVaraus.php" class="active">Varauskatsaus</a></li>
                </ul>
            </nav>
        </aside>

        <section class="admin-main">
            <div class="main-panel">
                <div class="actions-row">
                    <div class="search-wrap">
                        <input type="search" name="q" placeholder="Hae varausta tai käyttäjää" aria-label="Hae varausta tai käyttäjää">
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary">+ Uusi varaus</button>
                    </div>
                </div>

                <div class="cards-row">
                    <div class="card">
                        <div class="card-title">Kaikki varaukset</div>
                        <div class="card-value">342</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Tänään</div>
                        <div class="card-value">18</div>
                    </div>
                    <div class="card">
                        <div class="card-title">Vapaat luokat</div>
                        <div class="card-value">6</div>
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
                                <td><button class="btn btn-small">Näytä</button></td>
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