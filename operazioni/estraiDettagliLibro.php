<?php
function estraiDettagliLibro($id) {
// Connessione al database (o altro metodo per recuperare i dettagli)
// Qui dovrebbe esserci una query per recuperare il libro per ID
// Restituisci i dettagli come un array associativo
$conn = new mysqli("localhost", "root", "", "libreria");

if ($conn->connect_error) {
die("Connessione fallita: " . $conn->connect_error);
}

$sql = "SELECT * FROM libri WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
return $result->fetch_assoc(); // Restituisci il libro come array associativo
} else {
return null;
}
}
?>