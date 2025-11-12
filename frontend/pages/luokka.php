<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luokka</title>
    <link rel="stylesheet" href="../styles/pages/luokka.css">
    <link rel="stylesheet" href="../styles/root.css">
    <link rel="stylesheet" href="../styles/components/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

</head>

<body>
    <?php include '../components/navbar.php'; ?>
    <main>
        <div class="luokka-container" id="luokkaContainner">
            <!-- <h2>Tittle</h2>
            <div class="luokka-grid">
                <div class="div1">
                    <div class="img-container">
                        <img src="../assets/ClassroomPlaceholder.jpg" alt="ClassroomPlaceholder">
                    </div>
                </div>
                <div class="div2">
                    <div class="info-container">
                        <h3>Tiedot ja varusteet</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem aperiam, impedit placeat
                            maiores ipsam dolores aspernatur tenetur officiis esse beatae non ab nesciunt numquam?
                            Provident, fugit amet. Repellendus, dignissimos corrupti?</p>
                        <div class="equipments">
                            <p><i class="fa-solid fa-people-group"></i> kapasiteetti: </p>
                            <p><i class="fa-solid fa-chalkboard"></i> valkotaulu </p>
                            <p><i class="fa-solid fa-computer"></i> Tietokoneet </p>
                            <p><i class="fa-solid fa-volume-low"></i> Äänentoisto </p>
                            <p><i class="fa-solid fa-land-mine-on"></i> Telamiinat</p>
                        </div>
                    </div>
                </div>
                <div class="div3">
                    <div class="calendar-container">
                        <div class="header-row">
                            <h3>Varattavat ajat</h3>
                            <p>
                                <span>&lt;</span> Lokakuu 26 - Marraskuu 1 <span>&gt;</span>
                            </p>
                        </div>
                        <div class="scrollable-grid">
                            <div class="dates-header">
                                <div class="date-column empty"></div>
                                <div class="date-column">
                                    <div class="day-name">Maanantai</div>
                                </div>
                                <div class="date-column">
                                    <div class="day-name">Tiistai</div>
                                </div>
                                <div class="date-column">
                                    <div class="day-name">Keskiviikko</div>
                                </div>
                                <div class="date-column">
                                    <div class="day-name">Torstai</div>
                                </div>
                                <div class="date-column">
                                    <div class="day-name">Perjantai</div>
                                </div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">08:00</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">09:00</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">10:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">11:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">12:00</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">13:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">14:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">15:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">16:00</div>
                                <div class="slot valittu">Valittu</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">17:00</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot vapaa">Vapaa</div>
                                <div class="slot disabled">disabled</div>
                            </div>

                            <div class="grid-row">
                                <div class="time-label">18:00</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                                <div class="slot disabled">disabled</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="div4">
                    <div class="booking-container">
                        <h3>Tee Varaus</h3>
                        <div class="booking">
                            <div class="booking-info">
                                <div class="info-item">
                                    <p class="label">Päivämäärä</p>
                                    <p class="value">Keskiviikko, 20. marraskuuta 2024</p>
                                </div>
                                <div class="info-item">
                                    <p class="label">Aika</p>
                                    <p class="value">8:00 - 10:00</p>
                                </div>
                            </div>
                            <div class="reason-container">
                                <p class="label">Varauksen Tarkoitus</p>
                                <input id="reasonInput" class="reason-input" type="text"
                                    placeholder="Esim. Matematiikan tunti, ryhmä 9A">
                            </div>
                            <div class="book-button-container">
                                <button class="book-button" id="bookButton">Varaa Luokkahuone</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>-->
        </div>
    </main>
    <script type="module" src="../scripts/pages/Luokka.js"></script>
</body>

</html>