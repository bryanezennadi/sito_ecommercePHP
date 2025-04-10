<?php
session_start(); // Aggiungi questa riga all'inizio del tuo file PHP
// Includi il file per la connessione e le operazioni necessarie
require '../componenti/header.php';
require '../operazioni/estraiDettagliLibro.php';

// Inizializza una variabile di feedback
$messaggio = "";

// Verifica che l'ID sia presente e valido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Sanificare l'input
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Assicuriamoci che 'cart' esista come array nella sessione
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Gestione del carrello
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $name = $_POST['name'];
        $postId=$_POST['id'];
        $price=$_POST['price'];
        $image=$_POST['image'];
        $versione=$_POST['versione'] ?? 'Fisica';
     if ($postId && $name && $price && $image) {
            // Verifica se il libro con la versione è già nel carrello
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $postId && $item['versione'] === $versione) {
                    $item['quantity']++; // Incrementa la quantità se esiste già
                    $found = true;
                    break;
                }
            }
            unset($item); // Importante disassociare il riferimento

            // Se il libro con la versione non esiste nel carrello, aggiungilo
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $postId,
                    'name' => $name,
                    'versione' => $versione,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => 1
                ];
            }

            $messaggio = "Libro aggiunto al carrello con successo!";
        } else {
            $messaggio = "Errore: i dati del libro non sono completi.";
        }
    }

    // Estrai i dettagli del libro in base all'ID
    $libro = estraiDettagliLibro($id);

    if ($libro) {
        // Se i dettagli sono stati trovati
        ?>
        <div class="dettagli-libro">
            <h1><?= htmlspecialchars($libro['titolo']) ?></h1>
            <p><strong>Autore:</strong> <?= htmlspecialchars($libro['autore']) ?></p>
            <p><strong>Prezzo:</strong> <?= htmlspecialchars($libro['prezzo']) ?>€</p>
            <p><strong>Descrizione:</strong></p>
            <p><?= htmlspecialchars($libro['descrizione']) ?></p>
            <img src="<?= htmlspecialchars($libro['immagine']) ?>" alt="<?= htmlspecialchars($libro['titolo']) ?>" class="img-fluid">

            <!-- Mostra il messaggio di feedback -->
            <?php if (!empty($messaggio)): ?>
                <div class="alert alert-info">
                    <?= htmlspecialchars($messaggio) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id']) ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($libro['titolo']) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($libro['prezzo']) ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($libro['immagine']) ?>">

                <!-- Selezione del tipo di libro -->
                <div class="form-group">
                    <label for="tipo-versione">Scegli versione:</label>
                    <select name="versione" id="tipo-versione" class="form-control">
                        <option value="Fisica">Versione fisica</option>
                        <option value="Ebook">Versione ebook</option>
                    </select>
                </div>

                <button type="submit" name="add_to_cart" class="add-to-cart">Aggiungi al carrello</button>
            </form>
        </div>
        <?php
    } else {
        echo "<p>Errore: libro non trovato.</p>";
    }
} else {
    echo "<p>Errore: ID libro mancante.</p>";
}

require '../componenti/footer.php';
?>
