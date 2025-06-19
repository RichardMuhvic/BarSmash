<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$username 'fole1506_richardbarsmash'; // Utilisateur par défaut pour XAMPP
$password = 'Love02/09/1988'; // Mot de passe par défaut pour XAMPP
$dbname = 'fole1506_barsmash_database'; // Nom de la base de données

// Connexion à MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// URL de l'API pour récupérer tous les cocktails
$api_url = "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=";

// Récupération des données depuis l'API
$response = file_get_contents($api_url);
if ($response === FALSE) {
    die("Erreur lors de la récupération des données de l'API.");
}

// Décoder les données JSON
$data = json_decode($response, true);

// Vérifier si des cocktails existent
if (!isset($data['drinks']) || empty($data['drinks'])) {
    die("Aucun cocktail trouvé.");
}

// Préparation de l'insertion SQL
$stmt = $conn->prepare("INSERT INTO cocktails (name, category, alcoholic, instructions) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $category, $alcoholic, $instructions);

// Boucle pour insérer chaque cocktail
foreach ($data['drinks'] as $drink) {
    $name = $drink['strDrink'];
    $category = $drink['strCategory'] ?? null;
    $alcoholic = $drink['strAlcoholic'] ?? null;
    $instructions = $drink['strInstructions'] ?? null;

    // Exécuter l'insertion
    if (!$stmt->execute()) {
        echo "Erreur lors de l'insertion du cocktail : $name<br>";
    }
}

// Terminer les opérations
$stmt->close();
$conn->close();

echo "Importation des cocktails terminée avec succès !";
?>
