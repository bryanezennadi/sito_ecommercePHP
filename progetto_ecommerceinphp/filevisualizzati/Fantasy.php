<?php
require '../componenti/header.php';
require '../operazioni/estraiFantasy.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Recupera i libri dalla sessione con un controllo che la sessione contenga i dati corretti
$libriFantasy = $_SESSION['libriFantasy'] ?? null;

if ($libriFantasy === null || !is_array($libriFantasy)) {
    echo "<p>Errore: I libri fantasy non sono stati trovati o non sono nel formato corretto.</p>";
    exit;
}

$classeNormale = "book-image parametriLibro";
$classeSpeciale = "book-image parametriLibro rimpicciolimento2";

// Inizializza una variabile di feedback
$messaggio = "";

// Gestione del carrello
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $price = $_POST['price'] ?? null;
    $image = $_POST['image'] ?? null;

    if ($id && $name && $price && $image) {
        // Aggiungi al carrello (Sessione)
        $_SESSION['carrello'][] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => $image
        ];
        $messaggio = "Libro aggiunto al carrello con successo!";
    } else {
        $messaggio = "Errore: i dati del libro non sono completi.";
    }
}
?>

<body>
<h1 class="titolo">LIBRIVI</h1>
<h2 class="titolo">Libri Fantasy</h2>
<!-- Mostra il messaggio di feedback -->
<?php if ($messaggio): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($messaggio) ?>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <?php if (!empty($libriFantasy)) : ?>
            <?php foreach ($libriFantasy as $index => $libro) : ?>
                <div class="col-12 col-md-4">
                    <div class="book">
                        <img src="<?= htmlspecialchars($libro['immagine']) ?>"
                             alt="<?= htmlspecialchars($libro['titolo']) ?>"
                             class="<?= in_array($index, [0, 2, 3]) ? $classeSpeciale : $classeNormale ?>" />
                        <br>
                        <h3 class="parametriLibro"> <?= htmlspecialchars($libro['titolo']) ?> </h3>
                        <br>
                        <p class="parametriLibro"><strong>Autore:</strong> <?= htmlspecialchars($libro['autore']) ?></p>
                        <br>
                        <p class="parametriLibro"><strong>Prezzo:</strong> <?= htmlspecialchars($libro['prezzo']) ?>€</p>
                        <br>
                        <?php if (!empty($libro['id']) && !empty($libro['titolo']) && !empty($libro['prezzo']) && !empty($libro['immagine'])) : ?>
                            <!-- Form per aggiungere al carrello -->
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id']) ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($libro['titolo']) ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($libro['prezzo']) ?>">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($libro['immagine']) ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart parametriLibro">
                                    Aggiungi al carrello
                                </button>
                            </form>
                        <?php else : ?>
                            <p class='error'>Errore nel caricamento del libro</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Nessun libro trovato.</p>
        <?php endif; ?>
    </div>
</div>

<?php require '../componenti/footer.php'; ?>
</body>
