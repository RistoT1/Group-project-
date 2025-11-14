<?php
// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="nav-container">
    <div class="nav-logo-container">
        <div class="nav-logo">
            <a href="./ClassList.php">Luokkavaraus</a>
        </div>
    </div>

    <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="nav-links-container">
        <div class="nav-links">
            <a href="./ClassList.php" class="<?php echo ($currentPage == 'ClassList.php') ? 'selected-link' : ''; ?>">Varaa luokka</a>
            <a href="./OmatVaraukset.php" class="<?php echo ($currentPage == 'OmatVaraukset.php') ? 'selected-link' : ''; ?>">Omat Varaukset</a>
        </div>
        <div class="profile-container">
            <div class="logout-btn-container">
                <button class="logout-btn" id="logoutBtn">Kirjaudu ulos</button>
            </div>
            <div class="profile-icon-container">
                <img src="../assets/profile-icon.png" alt="Profiilikuva" class="profile-icon">
            </div>
        </div>
    </div>
</nav>

<script type="module" src="../scripts/components/nav.js"></script>
