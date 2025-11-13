<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/pages/ClassList.css">
    <link rel="stylesheet" href="../styles/components/navbar.css">
</head>

<body>
    <?php include '../components/navbar.php'; ?>
    <main>
        <section class="list-section">
            <div class="search-tool">

            </div>
            <div class="filter-options">
                <label for="buildingSelect">Rakennus:</label>
                <select id="buildingSelect">
                    <option value="all">Kaikki</option>
                    <option value="A">Rakennus A</option>
                    <option value="B">Rakennus B</option>
                    <option value="C">Rakennus C</option>
                </select>

                <label for="capacitySelect">Minimi kapasiteetti:</label>
                <select id="capacitySelect">
                    <option value="0">Kaikki</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                </select>

                <label for="Time-Select">Hae ajalla</label>
                <input type="time" id="startTimeInput" />
                <input type="time" id="endTimeInput" />
                <input type="date" id="dateInput"/>

                <button id="applyFiltersBtn">Suodata</button>
            </div>
            <div class="class-list" id="classList">
                <!-- Luokkat lisätään tähän dynaamisesti -->
            </div>
        </section>
    </main>
</body>

<script type="module" src="../scripts/pages/ClassListPage.js" defer></script>

</html>