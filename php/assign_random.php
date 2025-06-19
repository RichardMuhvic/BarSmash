<?php
$pdo = new PDO('mysql:host=localhost;dbname=fofe1506_barsmash_database;charset=utf8', 'fofe1506_richardbarsmash', 'Love02/09/1988');

// Étape 1 : charge les images disponibles
$files = glob(__DIR__ . '/../assets/images/cocktails/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
$baseNames = array_map(fn($f) => basename($f), $files);

if (empty($baseNames)) {
    die("Aucune image trouvée dans le dossier.");
}

// Étape 2 : prépare la requête d'update
$stmt = $pdo->prepare("UPDATE cocktails SET image = :img WHERE id = :id");

// Étape 3 : récupère tous les IDs des cocktails
foreach ($pdo->query("SELECT id FROM cocktails")->fetchAll(PDO::FETCH_COLUMN) as $id) {
    $img = $baseNames[array_rand($baseNames)]; // choisit une image au hasard
    $stmt->execute([':img' => $img, ':id' => $id]);
}

echo "Images aléatoires assignées à tous les cocktails.";
