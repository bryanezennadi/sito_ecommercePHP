<?php
require '../componenti/header.php';
require '../operazioni/estraiSportivi.php';

// Recupera i libri dalla sessione
if (isset($_SESSION['libriSportivi'])) {
    $libriSportivi = $_SESSION['libriSportivi'];
} else {
    echo "Nessun libro sportivo trovato nella sessione.";
    $libriSportivi = [];  // Evitiamo errori se non ci sono libri
}

$classeNormale = "book-image parametriLibro";
$classeSpeciale = "book-image parametriLibro rimpicciolimento2";

?>

<body>
<h1 class="titolo">LIBRIVIVI</h1>
<h2 class="titolo">Libri Sportivi</h2>
<div class="container">
    <div class="row">
        <?php
        $i = 0;  // Contatore per determinare quando applicare una classe speciale
        if (isset($libriSportivi) && is_array($libriSportivi)) {
            foreach ($libriSportivi as $libro) {
                $i++; // Incrementa il contatore
                ?>
                <div class="col-12 col-md-4">
                    <!-- Ogni libro occupa un terzo della riga su dispositivi medi e grandi (col-md-4) -->
                    <div class="book">
                        <?php
                        // Verifica se è uno dei libri speciali per applicare la classe rimpicciolita
                        if ($i == 1 || $i == 3 || $i == 4) {
                            ?>
                            <img src="<?= $libro['immagine'] ?>" alt="<?= $libro['titolo'] ?>" class="<?= $classeSpeciale ?>"/>
                            <?php
                        } else {
                            ?>
                            <img src="<?= $libro['immagine'] ?>" alt="<?= $libro['titolo'] ?>" class="<?= $classeNormale ?>"/>
                            <?php
                        }
                        ?>
                        <br>
                        <h3 class="parametriLibro"><?= $libro['titolo'] ?></h3>
                        <br>
                        <p class="parametriLibro"><strong>Autore:</strong> <?= $libro['autore'] ?></p>
                        <br>
                        <p class="parametriLibro"><strong>Prezzo:</strong> <?= $libro['prezzo'] ?></p>
                    </div>
                </div>
                <?php
            } // fine foreach
        } else {
            echo "Nessun libro trovato.";
        }
        ?>
    </div> <!-- chiude row -->
</div> <!-- chiude container -->
<?php require '../componenti/footer.php'?>
</body>


