<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/pages/omatVaraukset.css">
    <link rel="stylesheet" href="../styles/components/navbar.css">
</head>

<body>
    <?php include '../components/navbar.php'; ?>
    <main>
        <div class="main-container">
            <header>
                <h1>Omat Varaukset</h1>
                <p class="subtitle">Hallitse ja tarkastele varauksiasi</p>
            </header>

            <div id="statsContainer" class="stats" style="display: none;">
                <div class="stat-card">
                    <div class="stat-number" id="totalBookings">0</div>
                    <div class="stat-label">Varauksia yhteensä</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="upcomingBookings">0</div>
                    <div class="stat-label">Tulevia varauksia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="uniqueRooms">0</div>
                    <div class="stat-label">Eri luokkia</div>
                </div>
            </div>

            <div class="filter-section" id="filtersContainer" style="display: none;">
                <div class="filter-title">Suodata ja järjestä</div>
                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Luokka</label>
                        <select id="roomFilter">
                            <option value="all">Kaikki luokat</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tila</label>
                        <select id="statusFilter">
                            <option value="all">Kaikki tilat</option>
                            <option value="Vahvistettu">Vahvistettu</option>
                            <option value="Odottaa">Odottaa</option>
                            <option value="Peruttu">Peruttu</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Järjestys</label>
                        <select id="sortFilter">
                            <option value="date-desc">Uusimmat ensin</option>
                            <option value="date-asc">Vanhimmat ensin</option>
                            <option value="time-asc">Aika (aikaisin)</option>
                            <option value="time-desc">Aika (myöhäin)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="loadingContainer" class="loading">
                Ladataan varauksia...
            </div>

            <div id="bookingsContainer" class="bookings-list"></div>
        </div>
    </main>
</body>

</html>
<script type="module" src="../scripts/pages/OmatVaraukset.js"></script>