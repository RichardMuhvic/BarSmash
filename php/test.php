    <?php
$host = 'localhost';
$dbname = 'fofe1506_barsmash_database';
$username = 'fofe1506_richardbarsmash';
$password = 'Love02/09/1988';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SHOW COLUMNS FROM cocktails");
    echo "<h3>Colonnes de la table 'cocktails'</h3><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row['Field'] . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "Une erreur est survenue : " . $e->getMessage();
}
?>
