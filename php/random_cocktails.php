<?php
header('Content-Type: application/json');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'fofe1506_barsmash_database';
$username = 'fofe1506_richardbarsmash';
$password = 'Love02/09/1988';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connexion échouée : " . $e->getMessage()]);
    exit;
}

// 🔹 Vérifier si la requête est pour un cocktail aléatoire
if (isset($_GET['random']) && $_GET['random'] === "true") {
    try {
        $stmt = $conn->query("SELECT * FROM cocktails ORDER BY RAND() LIMIT 1");
        $randomCocktail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($randomCocktail) {
            echo json_encode($randomCocktail);
        } else {
            echo json_encode(["error" => "Aucun cocktail trouvé."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
    }
    exit;
}
?>
