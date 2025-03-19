<?php
$headers = ['Home', 'Libri fantasy', 'Libri sportivi', 'Libri d\'avventura', 'carrello'];?>
<nav class="navbar navbar-expand-lg navbar-light custom-navbar">
    <div class="container crudo">
        <a class="navbar-brand" href="index.html" id="logo"></a>
    </div>

    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto" id="navbar">
                <li class="nav-item">
                    <a class="nav-link" href = 'index.php'><?= $headers[0] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href = 'index.php'><?= $headers[1] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href = 'index.php'><?= $headers[2] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href = 'index.php'><?= $headers[3] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href = 'index.php'><?= $headers[4] ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>