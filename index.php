<?php
require_once "src/include.php";
?>
<html>
<head>
<script type = "text/javascript" src = "jquery-2.1.4.min.js"></script>
<style>
</style>
</head>
<body>
<?php
$statement = $connection->prepare('SELECT COUNT(*) FROM log WHERE id_p = :id OR id_q = :id');
$statement->execute([
':id' => $_SESSION["id"]
]);

$ligne = $statement->fetch(PDO::FETCH_ASSOC);

if ($ligne["COUNT(*)"] > 0)
{
	$statement = $connection->prepare('SELECT * FROM log WHERE id_p = :id OR id_q = :id');
	$statement->execute([
	':id' => $_SESSION["id"]
	]);
	$ligne = $statement->fetch(PDO::FETCH_ASSOC);
	$id = $ligne["id"];
	
	$statement = $connection->prepare('SELECT * FROM personnage WHERE id = :id');
	$statement->execute([
	':id' => ($ligne["id_p"] == $_SESSION["id"] ? $ligne["id_q"] : $ligne["id_p"])
	]);
	$ligne = $statement->fetch(PDO::FETCH_ASSOC);
	
	echo '<a href = "combat.php?continue=' . $id . '">' . $ligne["nom"] . '</a><br />';
}

else
{
	$statement = $connection->prepare('SELECT * FROM personnage WHERE id <> :id AND est_ordi = TRUE');
	$statement->execute([
	':id' => $_SESSION["id"]
	]);

	while ($ligne = $statement->fetch(PDO::FETCH_ASSOC))
	{
		echo '<a href = "combat.php?nouveau=' . $ligne["id"] . '">' . $ligne["nom"] . '</a><br />';
	}
}
?>
</body>
</html>