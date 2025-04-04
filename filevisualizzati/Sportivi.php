<?php
require '../componenti/header.php';
require '../operazioni/estraiSportivi.php';
if(session_status() == PHP_SESSION_NONE) // se la sessione è none, per gestire lo stato della sessione, in caso la sessione esista già, non la crea e non mostra errori
    session_start(); // inizio sessione, se c'è già una sessione aperta (ma sospesa) la riprende, session_start()genera un id che identifica la sessione, che diventa un cookie e lo passa al browser, quindi la sessione lavora sui cookie

// Assicuriamoci che 'cart' esista come array nella sessione
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Recupera i libri dalla sessione con un controllo che la sessione contenga i dati corretti
$libriSportivi= $_SESSION['libriSportivi'] ?? null;

if ($libriSportivi === null || !is_array($libriSportivi)) {
    echo "<p>Errore: I libri sportivi non sono stati trovati o non sono nel formato corretto.</p>";
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
    $versione = $_POST['versione'] ?? 'Fisica'; // Aggiunto campo versione, con valore di default

    if ($id && $name && $price && $image) {
        // Verifica se il libro è già nel carrello
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $name && $item['versione'] === $versione) {
                $item['quantity']++; // Incrementa la quantità se esiste già
                $found = true;
                break;
            }
        }

        // Se il libro non esiste nel carrello, aggiungilo
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $id,
                'name' => $name,
                'versione' => $versione, // Aggiunta la versione richiesta dal carrello
                'price' => $price,
                'image' => $image,
                'quantity' => 1 // Aggiunta la quantità richiesta dal carrello
            ];
        }

        $messaggio = "Libro aggiunto al carrello con successo!";
    } else {
        $messaggio = "Errore: i dati del libro non sono completi.";
    }
}

?>

<body>
<h1 class="titolo">LIBRIVIVI</h1>
<h2 class="titolo">Libri Sportivi</h2>
<?php if ($messaggio): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($messaggio) ?>
    </div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <?php
        $i = 0;  // Contatore per determinare quando applicare una classe speciale
        if (isset($libriSportivi) && is_array($libriSportivi)) {
            foreach ($libriSportivi as $libro) {
                $i++; // Incrementa il contatore
                ?>
                <div class="col-12 col-md-4">
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
                        <p class="parametriLibro"><strong>Prezzo:</strong> <?= $libro['prezzo'] ?>€</p>
                        <br>
                        <?php if (!empty($libro['id']) && !empty($libro['titolo']) && !empty($libro['prezzo']) && !empty($libro['immagine'])) : ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id']) ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($libro['titolo']) ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($libro['prezzo']) ?>">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($libro['immagine']) ?>">
                                <input type="hidden" name="versione" value="Standard"> <!-- Aggiunto campo versione -->
                                <button type="submit" name="add_to_cart" class="add-to-cart parametriLibro">
                                    Aggiungi al carrello
                                </button>
                            </form>
                        <?php else : ?>
                            <p class='error'>Errore nel caricamento del libro</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div> <!-- chiude row -->
</div> <!-- chiude container -->

<!-- Aggiungi un link al carrello per facilitare la navigazione -->
<div class="text-center my-4">
    <a href="/cartella-del-tuo-carrello/cart.php" class="btn btn-primary">Vai al carrello</a>
</div>

<?php require '../componenti/footer.php'?>
</body>