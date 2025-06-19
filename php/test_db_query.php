<?php
// Test de la connexion et de la récupération des données

// Configuration de la base de données
$host = 'localhost';
$dbname = 'barsmash_cocktails';
$username = 'root';
$password = '';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer tous les cocktails
    $query = "SELECT * FROM cocktails";
    $stmt = $pdo->query($query);

    // Récupération des résultats
    $cocktails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Affichage des résultats
    echo "<h1>Liste des cocktails</h1>";
    echo "<ul>";
    foreach ($cocktails as $cocktail) {
        echo "<li><strong>" . htmlspecialchars($cocktail['name']) . "</strong> - " . htmlspecialchars($cocktail['alcohol']) . " - " . htmlspecialchars($cocktail['flavor']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
