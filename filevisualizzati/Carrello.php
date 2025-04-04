<?php
require '../componenti/header.php';?>
<body>
<?php

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
function updateQuantity($bookIndex, $increment = true) {
    if ($increment) {
        $_SESSION['cart'][$bookIndex]['quantity']++;
    } else {
        // Diminuisci la quantità, se è 1 rimuovi completamente l'articolo
        if ($_SESSION['cart'][$bookIndex]['quantity'] > 1) {
            $_SESSION['cart'][$bookIndex]['quantity']--;
        } else {
            removeFromCart($bookIndex);
        }
    }
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
        echo '<div class="empty-cart">Il carrello è vuoto.</div>';
        return;
    }

    echo '<div class="cart-items">';
    foreach ($_SESSION['cart'] as $index => $book) {
        $price = floatval(str_replace('€', '', $book['price']));
        echo '<div class="cart-item">';
        if (isset($book['image']) && !empty($book['image'])) {
            echo '<img src="' . htmlspecialchars($book['image']) . '" alt="' . htmlspecialchars($book['name']) . '" class="cart-item-image">';
        }
        echo '<div class="cart-item-details">';
        echo '<p class="item-name">' . htmlspecialchars($book['name']) . '</p>';
        echo '<p class="item-detail"><strong>Prezzo:</strong> €' . number_format($price, 2) . '</p>';
        echo '<p class="item-detail"><strong>Quantità:</strong> ' . $book['quantity'] . '</p>';
        echo '<p class="item-detail"><strong>Versione:</strong> ' . htmlspecialchars($book['versione']) . '</p>';
        echo '<p class="item-subtotal"><strong>Subtotale:</strong> €' . number_format($price * $book['quantity'], 2) . '</p>';

        echo '<div class="cart-buttons">';
        // Pulsante per rimuovere il libro
        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="remove_book" value="' . $index . '" class="btn btn-remove">Rimuovi</button>';
        echo '</form>';

        // Pulsante per decrementare la quantità
        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="decrease_quantity" value="' . $index . '" class="btn btn-decrease">-1 Quantità</button>';
        echo '</form>';

        // Pulsante per incrementare la quantità
        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="update_quantity" value="' . $index . '" class="btn btn-increase">+1 Quantità</button>';
        echo '</form>';
        echo '</div>'; // Chiude cart-buttons
        echo '</div>'; // Chiude cart-item-details
        echo '</div>'; // Chiude cart-item
    }
    echo '</div>'; // Chiude cart-items

    echo '<div class="cart-total"><strong>Totale:</strong> €' . number_format(calculateTotal(), 2) . '</div>';
}
echo '<link rel="stylesheet" href="../altre_pagine/styleCarrello.css" >';
// Gestione delle operazioni POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aggiungi libro al carrello
    if (isset($_POST['add_to_cart'])) {
        if (!empty($_POST['name']) && !empty($_POST['versione']) && !empty($_POST['price'])) {
            $book = [
                'name' => $_POST['name'],
                'versione' => $_POST['versione'],
                'price' => $_POST['price'],
                'image' => isset($_POST['image']) ? $_POST['image'] : ''
            ];
            addToCart($book);
            echo '<div class="cart-message message-success">Libro aggiunto al carrello!</div>';
        } else {
            echo '<div class="cart-message message-error">Dati del libro incompleti!</div>';
        }
    }

    // Rimuovi un libro dal carrello
    if (isset($_POST['remove_book'])) {
        $bookIndex = $_POST['remove_book'];
        removeFromCart($bookIndex);
        echo '<div class="cart-message message-success">Libro rimosso dal carrello.</div>';
    }

    // Incrementa la quantità di un libro nel carrello
    if (isset($_POST['update_quantity'])) {
        $bookIndex = $_POST['update_quantity'];
        updateQuantity($bookIndex, true);
        echo '<div class="cart-message message-success">Quantità aumentata.</div>';
    }

    // Decrementa la quantità di un libro nel carrello
    if (isset($_POST['decrease_quantity'])) {
        $bookIndex = $_POST['decrease_quantity'];
        updateQuantity($bookIndex, false);
        echo '<div class="cart-message message-success">Quantità diminuita.</div>';
    }

    // Procedi con il checkout
    if (isset($_POST['checkout'])) {
        if (empty($_SESSION['cart'])) {
            echo '<div class="cart-message message-warning">Il carrello è vuoto. Impossibile procedere con il pagamento.</div>';
        } else {
            echo '<div class="cart-message message-success">Procediamo con il pagamento!</div>';
        }
    }

    // Svuota il carrello
    if (isset($_POST['empty_cart'])) {
        $_SESSION['cart'] = [];
        echo '<div class="cart-message message-success">Il carrello è stato svuotato.</div>';
    }
}
?>

    <div class="cart-container">
        <h1 class="cart-title">Il tuo carrello</h1>

        <?php
        // Visualizza il carrello
        displayCart();
        ?>

        <!-- Form per il checkout e svuotare il carrello -->
        <div class="cart-actions">
            <form method="POST">
                <button type="submit" name="checkout" class="btn btn-checkout">Procedi al pagamento</button>
            </form>
            <form method="POST">
                <button type="submit" name="empty_cart" class="btn btn-empty">Svuota carrello</button>
            </form>
        </div>
    </div>



<?php require '../componenti/footer.php'; ?>

</body>