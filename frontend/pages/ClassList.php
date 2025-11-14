<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luokan varaus</title>
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/pages/classList.css">
    <link rel="stylesheet" href="../styles/components/navbar.css">
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <main>


        <div class="main-container">
            <div class="form">
                <!-- Search input -->
                <div class="haku">
                    <label for="haku">Luokat</label>
                    <input type="text" placeholder="Hae luokkia tunnuksella esim A101" id="haku">
                </div>

                <!-- Filter section -->
                <div class="filter-form">
                    <label>Suodata</label>
                    <div class="filter-wrap">
                        <select id="buildingSelect">
                            <option value="all">Kaikki rakennukset</option>
                            <option value="A">Rakennus A</option>
                            <option value="B">Rakennus B</option>
                            <option value="C">Rakennus C</option>
                        </select>

                        <select id="capacitySelect">
                            <option value="0">Kaikki kapasiteetit</option>
                            <option value="10">10+</option>
                            <option value="20">20+</option>
                            <option value="30">30+</option>
                            <option value="50">50+</option>
                        </select>

                        <input type="date" id="dateInput">
                        <input type="time" id="startTimeInput" placeholder="Alku">
                        <input type="time" id="endTimeInput" placeholder="Loppu">

                        <button type="reset" id="resetFilters">Nollaa</button>
                        <button id="applyFiltersBtn">Suodata</button>
                    </div>

                    <!-- Class list (replaces the table) -->
                    <div class="table-wrapper" id="classList">
                        <!-- Classes injected dynamically by JS -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="module" src="../scripts/pages/ClassListPage.js" defer></script>
</body>

</html>