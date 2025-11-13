<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="frontend\styles\pages\varaa.css">
    <title>Luokan varaus</title>
</head>
<body>
    <div class="main">
        <div class="form" method="#" action="#">
            <div class="haku">
            <label for="haku">Luokat</label>
            <input type="text" placeholder="Hae luokkia nimellä" id="haku">
             </div>
             <div class="filter-form">
                <label>Suodata</label>
                <div class="filter-wrap">
                <select name="koko" id="koko">
                    <option value="" disabled selected>Filler</option>
                </select>
                <select name="varustelu" id="varustelu">
                    <option value="" disabled selected>Filler</option>
                </select>
                <input type="date" id="pvm">
                <button type="reset">Nollaa</button>
                <button type="submit">Suodata</button> 
                </div>
                <div class="table-wrapper">
                    <table>
                    <tr>
                    <th>Nimi</th>
                    <th>Kapasiteetti</th>
                    <th>Varusteet</th>
                    <th>Tila</th>
                    </tr>
                    <tr>
                        <td>Fillerdata</td> <!-- Väliaikaisia -->
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                    </tr>
                                        <tr>
                        <td>Fillerdata</td> <!-- Väliaikaisia -->
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                    </tr>
                                        <tr>
                        <td>Fillerdata</td> <!-- Väliaikaisia -->
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                        <td>Fillerdata</td>
                    </tr>
                </table>
                </div>
        </div>
    </div>
</body>
</html>