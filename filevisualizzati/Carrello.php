<?php
require '../componenti/header.php';
?>
<link rel="stylesheet" href="../altre_pagine/styleCarrello.css">
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
    // Assicuriamoci che original_price sia impostato
    if (!isset($book['original_price']) && isset($book['price'])) {
        $book['original_price'] = $book['price'];
    }

    // Controlla se il libro esiste già nel carrello
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $book['name'] && $item['versione'] === $book['versione']) {
            $item['quantity']++;
            applyDiscounts(); // Ricalcola gli sconti dopo ogni modifica
            return;
        }
    }
    unset($item); // Importante: rimuove il riferimento al loop

    // Imposta la quantità a 1 per un nuovo libro
    $book['quantity'] = 1;
    $_SESSION['cart'][] = $book;  // Aggiungi il libro al carrello

    // Applica eventuali sconti
    applyDiscounts();
}

// Funzione per rimuovere un libro dal carrello
function removeFromCart($bookIndex) {
    if (isset($_SESSION['cart'][$bookIndex])) {
        unset($_SESSION['cart'][$bookIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);  // Riorganizza l'indice dell'array
        applyDiscounts(); // Ricalcola gli sconti dopo la rimozione
    }
}

// Funzione per aggiornare la quantità di un libro nel carrello
function updateQuantity($bookIndex, $increment = true) {
    if (!isset($_SESSION['cart'][$bookIndex])) {
        return;
    }

    if ($increment) {
        $_SESSION['cart'][$bookIndex]['quantity']++;
    } else {
        // Diminuisci la quantità, se è 1 rimuovi completamente l'articolo
        if ($_SESSION['cart'][$bookIndex]['quantity'] > 1) {
            $_SESSION['cart'][$bookIndex]['quantity']--;
        } else {
            removeFromCart($bookIndex);
            return; // removeFromCart già applica gli sconti
        }
    }

    applyDiscounts(); // Ricalcola gli sconti dopo la modifica
}

// Funzione per applicare gli sconti
function applyDiscounts() {
    // Ripristina prima tutti i prezzi originali e rimuovi i flag di sconto
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['original_price'])) {
            $item['price'] = $item['original_price'];
        } else {
            // Se per qualche motivo original_price non esiste, crea una copia del prezzo attuale
            $item['original_price'] = $item['price'];
        }
        // Rimuovi il flag di sconto e il contatore delle copie gratuite
        unset($item['discounted']);
        unset($item['free_copies']);
    }
    unset($item);

    // Se ci sono almeno 3 articoli diversi nel carrello, applica lo sconto
    if (count($_SESSION['cart']) >= 3) {
        // Crea un array temporaneo con tutti gli articoli
        $tempCart = [];
        foreach ($_SESSION['cart'] as $index => $item) {
            // Converte il prezzo in float per confronti numerici
            $price = floatval(str_replace(['€', ','], ['', '.'], $item['original_price']));
            $tempCart[$index] = [
                'index' => $index,
                'price' => $price
            ];
        }

        // Ordina gli articoli per prezzo (dal più economico al più costoso)
        usort($tempCart, function($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        // Rendi gratuita solo UNA copia dell'articolo meno costoso
        $cheapestIndex = $tempCart[0]['index'];
        $_SESSION['cart'][$cheapestIndex]['discounted'] = true;
        $_SESSION['cart'][$cheapestIndex]['free_copies'] = 1; // Solo una copia è gratuita
    }
}

// Funzione per calcolare il totale del carrello
function calculateTotal() {
    $totalPrice = 0;

    foreach ($_SESSION['cart'] as $book) {
        $price = floatval(str_replace(['€', ','], ['', '.'], $book['price']));
        $quantity = $book['quantity'];

        // Se il libro ha uno sconto, calcola il prezzo considerando solo una copia gratuita
        if (isset($book['discounted']) && $book['discounted']) {
            $freeCopies = isset($book['free_copies']) ? $book['free_copies'] : 1;
            $paidCopies = $quantity - $freeCopies;
            if ($paidCopies > 0) {
                $originalPrice = floatval(str_replace(['€', ','], ['', '.'], $book['original_price']));
                $totalPrice += $originalPrice * $paidCopies;
            }
        } else {
            $totalPrice += $price * $quantity;
        }
    }

    return $totalPrice;
}

// Funzione per visualizzare il carrello
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo '<div class="empty-cart">Il carrello è vuoto.</div>';
        return;
    }

    $itemCount = count($_SESSION['cart']);
    $isDiscountApplied = ($itemCount >= 3); // Verifica se lo sconto è applicato

    echo '<div class="cart-items">';
    foreach ($_SESSION['cart'] as $index => $book) {
        $isDiscounted = isset($book['discounted']) && $book['discounted'];
        $freeCopies = isset($book['free_copies']) ? $book['free_copies'] : 0;
        $originalPrice = floatval(str_replace(['€', ','], ['', '.'], $book['original_price']));

        echo '<div class="cart-item' . ($isDiscounted ? ' discounted-item' : '') . '">';
        echo '<div class="cart-item-details">';

        // Mostra immagine del libro (se disponibile)
        if (!empty($book['image'])) {
            echo '<img src="' . htmlspecialchars($book['image']) . '" alt="Immagine libro" class="cart-item-image">';
        }

        echo '<p class="item-name">' . htmlspecialchars($book['name']) . ' (' . htmlspecialchars($book['versione']) . ')</p>';

        // Mostra i dettagli del prezzo e delle copie gratuite/a pagamento
        echo '<p class="item-detail"><strong>Prezzo unitario:</strong> €' . number_format($originalPrice, 2, ',', '.') . '</p>';

        if ($isDiscounted && $freeCopies > 0) {
            echo '<p class="item-detail"><strong>Quantità totale:</strong> ' . $book['quantity'] . '</p>';
            echo '<p class="item-detail" style="color: green; font-weight: bold;">' . $freeCopies . ' copie GRATIS (offerta "3 articoli, il meno costoso è gratis")</p>';

            $paidCopies = $book['quantity'] - $freeCopies;
            if ($paidCopies > 0) {
                echo '<p class="item-detail">' . $paidCopies . ' copie a pagamento: €' . number_format($originalPrice * $paidCopies, 2, ',', '.') . '</p>';
            }
        } else {
            echo '<p class="item-detail"><strong>Quantità:</strong> ' . $book['quantity'] . '</p>';
            echo '<p class="item-subtotal"><strong>Subtotale:</strong> €' . number_format($originalPrice * $book['quantity'], 2, ',', '.') . '</p>';
        }

        echo '<div class="cart-buttons">';
        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="remove_book" value="' . $index . '" class="btn btn-remove">Rimuovi</button>';
        echo '</form>';

        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="decrease_quantity" value="' . $index . '" class="btn btn-decrease">-1 Quantità</button>';
        echo '</form>';

        echo '<form method="POST" style="display:inline;">';
        echo '<button type="submit" name="update_quantity" value="' . $index . '" class="btn btn-increase">+1 Quantità</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    // Mostra un messaggio che spiega lo sconto
    if ($isDiscountApplied) {
        echo '<div class="discount-message" style="background-color: #ffcc00; padding: 10px; margin-top: 15px;">';
        echo 'Hai diritto a uno sconto! Una copia del libro meno costoso nel tuo carrello è GRATIS grazie all\'offerta "3 articoli, il meno costoso è gratis".';
        echo '</div>';
    } elseif ($itemCount > 0) {
        echo '<div class="discount-info" style="background-color: #f0f0f0; padding: 10px; margin-top: 15px;">';
        echo 'Aggiungi ' . (3 - $itemCount) . ' libri in più al carrello per ottenere il libro meno costoso GRATIS!';
        echo '</div>';
    }

    echo '<div class="cart-total"><strong>Totale:</strong> €' . number_format(calculateTotal(), 2, ',', '.') . '</div>';
}

// Gestione delle operazioni POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aggiungi libro al carrello
    if (isset($_POST['add_to_cart'])) {
        if (!empty($_POST['name']) && !empty($_POST['versione']) && !empty($_POST['price'])) {
            $book = [
                'name' => $_POST['name'],
                'versione' => $_POST['versione'],
                'price' => $_POST['price'],
                'original_price' => $_POST['price'],  // Salva il prezzo originale
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
            // Redirect alla pagina di checkout
            // header("Location: checkout.php");
            // exit;
        }
    }

    // Svuota il carrello
    if (isset($_POST['empty_cart'])) {
        $_SESSION['cart'] = [];
        echo '<div class="cart-message message-success">Il carrello è stato svuotato.</div>';
    }
}

// Assicurati che gli sconti siano applicati all'apertura della pagina
applyDiscounts();
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
            <button type="submit" name="checkout" class="btn btn-checkout" <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>Procedi al pagamento</button>
        </form>
        <form method="POST">
            <button type="submit" name="empty_cart" class="btn btn-empty" <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>Svuota carrello</button>
        </form>
    </div>
</div>

<style>
    .discounted-item {
        background-color: #e6ffe6;
        border-left: 3px solid #00cc00;
    }
</style>

<?php require '../componenti/footer.php'; ?>
</body>