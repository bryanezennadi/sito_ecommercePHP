<?php
session_start();  // Avvia la sessione

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libreria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Funzione per estrarre i libri fantasy
function estraiLibriAvventura($conn) {
    // Query SQL per estrarre i libri fantasy
    $sql = "SELECT categoria, titolo, autore, immagine, descrizione, prezzo,id FROM libreria.libri WHERE categoria = 'AVVENTURA'";
    $result = $conn->query($sql);

    // Verifica se la query ha restituito dei risultati
    if ($result->num_rows > 0) {
        $libri = [];
        // Estrae i dati dei libri dalla query
        while ($row = $result->fetch_assoc()) {
            $libri[] = [
                'categoria' => $row['categoria'],
                'titolo' => $row['titolo'],
                'autore' => $row['autore'],
                'immagine' => $row['immagine'],
                'descrizione' => $row['descrizione'],
                'prezzo' => $row['prezzo'],
                'id'=> $row['id']
            ];
        }
        return $libri;
    } else {
        // Restituisce un array vuoto in caso di nessun risultato
        return [];
    }
}

// Estrai i libri fantasy
$libriAvventura = estraiLibriAvventura($conn);

// Salva i dati nella sessione solo se ci sono libri estratti
if (!empty($libriAvventura)) {
    $_SESSION['libriAvventura'] = $libriAvventura;
} else {
    $_SESSION['libriAvventura'] = [];  // Salva un array vuoto se non ci sono libri
}

// Chiudi la connessione
$conn->close();
?>
