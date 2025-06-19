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

// 🔹 Vérifier si une requête pour un cocktail aléatoire est demandée
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

// 🔹 Vérifier si une recherche par nom est envoyée via GET
if (isset($_GET['search']) && !empty($_GET['search'])) {
    try {
        $searchQuery = "%" . $_GET['search'] . "%";
        $stmt = $conn->prepare("SELECT DISTINCT name, alcoholic, flavor, ingredients, instructions, image FROM cocktails WHERE LOWER(name) LIKE LOWER(?)");
        $stmt->execute([$searchQuery]);
        $cocktails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($cocktails);
        exit;
    } catch (PDOException $e) {
        echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
        exit;
    }
}

// 🔹 Récupérer les données envoyées via POST
$data = json_decode(file_get_contents("php://input"), true);
$type = isset($data['type']) ? $data['type'] : '';
$alcohols = isset($data['alcohols']) ? $data['alcohols'] : [];
$flavors = isset($data['flavors']) ? $data['flavors'] : [];
$ingredients = isset($data['ingredients']) ? $data['ingredients'] : [];

// 🔹 Construire la requête SQL dynamique
$query = "SELECT DISTINCT name, alcoholic, flavor, ingredients, instructions, image FROM cocktails WHERE 1=1";
$params = [];

// 🔹 Filtrage par type de boisson ("Alcoholic" / "Non-Alcoholic")
if (!empty($type)) {
    $query .= " AND alcoholic = :type";
    $params[':type'] = ($type == "with-alcohol") ? "Alcoholic" : "Non-Alcoholic";
}

// 🔹 Filtrage par alcool sélectionné (on cherche dans `ingredients`)
if (!empty($alcohols)) {
    $alcoholConditions = [];
    foreach ($alcohols as $index => $alcohol) {
        $paramName = ":alcohol$index";
        $alcoholConditions[] = "LOWER(ingredients) LIKE LOWER($paramName)";
        $params[$paramName] = "%$alcohol%";
    }
    $query .= " AND (" . implode(" OR ", $alcoholConditions) . ")";
}

// 🔹 Filtrage par saveur (on cherche dans `flavor`)
if (!empty($flavors)) {
    $flavorConditions = [];
    foreach ($flavors as $index => $flavor) {
        $paramName = ":flavor$index";
        $flavorConditions[] = "LOWER(flavor) LIKE LOWER($paramName)";
        $params[$paramName] = "%$flavor%";
    }
    $query .= " AND (" . implode(" OR ", $flavorConditions) . ")";
}

// 🔹 Pagination
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$query .= " LIMIT $limit OFFSET $offset";

// 🔹 Exécuter la requête SQL
try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $cocktails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($cocktails);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}
?>
