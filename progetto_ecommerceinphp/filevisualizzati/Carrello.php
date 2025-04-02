<?php
require '../componenti/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();

}

// Funzione per inizializzare il carrello
function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

initializeCart();

// Funzione per aggiungere un libro al carrello
function addToCart($book) {
    // Controlla se il libro esiste già nel carrello
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $book['name'] && $item['versione'] === $book['versione']) {
            $item['quantity']++;
            return;
        }
    }
    // Imposta la quantità a 1 per un nuovo libro
    $book['quantity'] = 1;
    $_SESSION['cart'][] = $book;  // Aggiungi il libro al carrello
}

// Funzione per rimuovere un libro dal carrello
function removeFromCart($bookIndex) {
    unset($_SESSION['cart'][$bookIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);  // Riorganizza l'indice dell'array
}

// Funzione per aggiornare la quantità di un libro nel carrello
function updateQuantity($bookIndex) {
    $_SESSION['cart'][$bookIndex]['quantity']++;
}

// Funzione per calcolare il totale del carrello
function calculateTotal() {
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $book) {
        // Rimuove il simbolo dell'€ e converte il prezzo in un valore numerico
        $price = floatval(str_replace('€', '', $book['price']));
        $totalPrice += $price * $book['quantity'];
    }
    return $totalPrice;
}

// Funzione per visualizzare il carrello
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo '<p>Il carrello è vuoto.</p>';
        return;
    }

    echo '<div class="cart-items">';
    foreach ($_SESSION['cart'] as $index => $book) {
        $price = floatval(str_replace('€', '', $book['price']));
        echo '<div class="cart-item">';
        echo '<img src="' . htmlspecialchars($book['image']) . '" alt="' . htmlspecialchars($book['name']) . '" style="width: 50px; height: auto;">';  // Visualizza l'immagine
        echo '<p><strong>Nome:</strong> ' . htmlspecialchars($book['name']) . '</p>';
        echo '<p><strong>Prezzo:</strong> €' . number_format($price, 2) . '</p>';
        echo '<p><strong>Quantità:</strong> ' . $book['quantity'] . '</p>';
        echo '<p><strong>Versione:</strong> ' . htmlspecialchars($book['versione']) . '</p>';

        // Pulsante per rimuovere il libro
        echo '<form method="POST" style="display:inline;">
                <button type="submit" name="remove_book" value="' . $index . '">Rimuovi</button>
              </form>';

        // Pulsante per aggiornare la quantità
        echo '<form method="POST" style="display:inline;">
                <button type="submit" name="update_quantity" value="' . $index . '" >+1 Quantità</button>
              </form>';

        echo '</div>';
    }
    echo '</div>';

    echo '<p><strong>Totale:</strong> €' . number_format(calculateTotal(), 2) . '</p>';
}

// Gestione delle operazioni POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug per controllare i dati POST
    var_dump($_POST);  // Mostra i dati inviati via POST
    var_dump($_SESSION['cart']);  // Mostra il carrello dopo l'aggiunta del libro

    // Aggiungi libro al carrello
    if (isset($_POST['add_to_cart'])) {
        if (!empty($_POST['name']) && !empty($_POST['versione']) && !empty($_POST['price']) && !empty($_POST['image'])) {
            $book = [
                'name' => $_POST['name'],
                'versione' => $_POST['versione'],
                'price' => $_POST['price'],
                'image' => $_POST['image']
            ];
            addToCart($book);
            echo 'Libro aggiunto al carrello!<br>';
        } else {
            echo 'Dati del libro incompleti!<br>';
        }
    }

    // Rimuovi un libro dal carrello
    if (isset($_POST['remove_book'])) {
        $bookIndex = $_POST['remove_book'];
        removeFromCart($bookIndex);
        echo 'Libro rimosso dal carrello.<br>';
    }

    // Aggiorna la quantità di un libro nel carrello
    if (isset($_POST['update_quantity'])) {
        $bookIndex = $_POST['update_quantity'];
        updateQuantity($bookIndex);
        echo 'Quantità aggiornata.<br>';
    }

    // Procedi con il checkout
    if (isset($_POST['checkout'])) {
        echo empty($_SESSION['cart']) ? 'Il carrello è vuoto. Impossibile procedere con il pagamento.' : 'Procediamo con il pagamento!<br>';
    }

    // Svuota il carrello
    if (isset($_POST['empty_cart'])) {
        $_SESSION['cart'] = [];
        echo 'Il carrello è stato svuotato.<br>';
    }
}
// Visualizza il carrello
displayCart();
?>

<!-- Form per il checkout e svuotare il carrello -->
<form method="POST">
    <button type="submit" name="checkout">Procedi al pagamento</button>
    <button type="submit" name="empty_cart">Svuota carrello</button>
</form>

<?php require '../componenti/footer.php'; ?>
