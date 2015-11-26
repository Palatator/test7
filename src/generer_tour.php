<?php
require_once "include.php";
$_SESSION["id"] = 1;

ob_start();

function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

function generer_table($p, $q, $items_affichage, $id_joueur)
{
	$table = '<table class = "log_combat"><tr><td class = "bleu"><b>' . $p->nom . '</b><br />' . $p->stats["PV"] . ' / ' . $p->stats["PV_max"] . '</td>';
	$table .= '<td class = "bleu"><center><b>♫On the last move...♫</b></center></td>';
	$table .= '<td class = "bleu" style = "text-align:right"><b>' . $q->nom . '</b><br />' . $q->stats["PV"] . ' / ' . $q->stats["PV_max"] . '</td></tr>';
	foreach ($items_affichage as $id => $tab)
	{
		if ($tab["id"] == $id_joueur)
		{
			if ($tab["type"] == "a") {$table .=  '<tr><td class = "bleu"></td><td class = "rouge">' . $tab["texte"] . '</td><td style = "text-align:right" class = "rouge">' . $tab["icones"] . '</td></tr>';}
			else if ($tab["type"] == "d") {$table .=  '<tr><td class = "vert">' . $tab["icones"] . '</td><td class = "vert">' . $tab["texte"] . '</td><td class = "bleu"></td></tr>';}
			else if ($tab["type"] == "r") {$table .=  '<tr><td class = "vert">' . $tab["icones_d"] . '</td><td class = "vert">' . $tab["texte"] . '</td><td style = "text-align:right" class = "bleu">' . $tab["icones_a"] . '</td></tr>';}
		}
		
		else
		{
			if ($tab["type"] == "a") {$table .=  '<tr><td class = "rouge">' . $tab["icones"] . '</td><td class = "rouge">' . $tab["texte"] . '</td><td class = "bleu"></td></tr>';}
			else if ($tab["type"] == "d") {$table .=  '<tr><td class = "bleu"></td><td class = "vert">' . $tab["texte"] . '</td><td style = "text-align:right" class = "vert">' . $tab["icones"] . '</td></tr>';}
			else if ($tab["type"] == "r") {$table .=  '<tr><td class = "bleu">' . $tab["icones_a"] . '</td><td class = "vert">' . $tab["texte"] . '</td><td style = "text-align:right" class = "vert">' . $tab["icones_d"] . '</td></tr>';}
		}
	}
	$table .= '<tr><td class = "bleu"><b>Damage:<br/>' . ceil($p->degats_finaux) . ' hp</b></td><td class = "bleu"></td><td class = "bleu" style = "text-align:right"><b>Damage:<br/>' . ceil($q->degats_finaux) . ' hp</b></td></tr></table>';
	return $table;
}

try
{
	$statement = $connection->prepare('SELECT * FROM log WHERE id = :id');
	$statement->execute([
    ':id' => $_POST["id_log"],
	]);
	
	$ligne = $statement->fetch(PDO::FETCH_ASSOC);
	//Changer ici
	$p = new Personnage($ligne["id_p"]);
	$q = new Personnage($ligne["id_q"]);
	
	if ($p->stats["PV"] <= 0 || $q->stats["PV"] <= 0 || !is_null($ligne["score"]))
	{
		throw new Exception("Illégale la partie est finie");
	}
	
	$temps = microtime();
	list($p->objets_actifs, $q->objets_actifs) = ia($p, $q);
	
	foreach (array($p, $q) as $j)
	{
		if ($j->id == $_SESSION["id"])
		{
			$j->objets_actifs = array($j->objets[$_POST["obj1"]], $j->objets[$_POST["obj2"]]);
		}
	}
	$items_affichage = array();

	$items_affichage = array_merge($items_affichage, $p->evaluer_attaque($q));
	$items_affichage = array_merge($items_affichage, $q->evaluer_attaque($p));
	$items_affichage = array_merge($items_affichage, $p->evaluer_reflexion($q));
	$items_affichage = array_merge($items_affichage, $q->evaluer_reflexion($p));
	$items_affichage = array_merge($items_affichage, $p->evaluer_defense($q));
	$items_affichage = array_merge($items_affichage, $q->evaluer_defense($p));

	$p->finir_tour();
	$q->finir_tour();

	$p->stats["PV"] -= $p->degats_finaux;
	$q->stats["PV"] -= $q->degats_finaux;
	
	$res = array("ok" => false, "score" => null);
	
	if (1)
	{
		$score = ($p->stats["PV"] <= 0) ? (($q->stats["PV"] <= 0) ? 0 : -1) : (($q->stats["PV"] <= 1) ? 1 : null);
		$table_html = generer_table($p, $q, $items_affichage, $_SESSION["id"]);
		
		if (!is_null($score))
		{
			switch ($score)
			{
				case 1: $table_html .= "<span class = 'victoire'>You win</span>"; break;
				case 0: $table_html .= "<span class = 'nul'>You draw</span>"; break;
				case -1: $table_html .= "<span class = 'defaite'>You lose</span>"; break;
			}
			
			$table_html .= '<br /><a href = "index.php">Partir</a>';
		}
		
		if (!is_null($score))
		{
			$p->stats["PV"] = $p->stats["PV_max"];
			$q->stats["PV"] = $q->stats["PV_max"];
		}
		
		$statement = $connection->prepare('UPDATE log SET id_p = :id_p, id_q = :id_q, objp = :objp, objq = :objq, log = :log, score = :score WHERE id = :id');
		$statement->execute([
		':id_p' => $p->id,
		':id_q' => $q->id,
		':log' => serialize($items_affichage),
		':id' => $_POST["id_log"],
		':objp' => 'a',
		':objq' => 'a',
		':score' => $score
		]);
		
		$statement = $connection->prepare('UPDATE personnage SET stats = :stats WHERE id = :id_j');
		$statement->execute([
		':stats' => serialize($p->stats),
		':id_j' => $p->id
		]);
		
		$statement->execute([
		':stats' => serialize($q->stats),
		':id_j' => $q->id
		]);
		
		$res = array("ok" => true, "score" => $score, "table" => $table_html);
	}
	
	ob_end_clean();
	echo json_encode($res);
}

catch (Exception $e)
{
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 ' . $e->getMessage(), true, 500);
}
