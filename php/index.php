<?php
// 1. Connexion à la base
$pdo = new PDO('mysql:host=localhost;dbname=fofe1506_barsmash_database;charset=utf8', 'fofe1506_richardbarsmash', 'Love02/09/1988');

// 2. Récupération des cocktails
$stmt = $pdo->query("SELECT id, name, image FROM cocktails");
$cocktails = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Liste des cocktails</title></head>
<body>
  <?php foreach($cocktails as $c): ?>
    <div style="margin-bottom:20px;">
      <h3><?= htmlspecialchars($c['name']) ?></h3>
      <img src="../assets/images/cocktails/<?= htmlspecialchars($c['image']) ?>"
           alt="<?= htmlspecialchars($c['name']) ?>"
           style="width:200px;display:block;">
    </div>
  <?php endforeach; ?>
</body>
</html>
