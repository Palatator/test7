<?php
require_once "src/include.php";
?>
<html>
<head>
<script type = "text/javascript" src = "jquery-2.1.4.min.js"></script>
<style>
<!--
*
{
    padding: 0;
    margin: 0;
}

html, body
{
    height: 100%;
}
-->
div.box
{
	border: 2px black solid;
	width: 600px;
	height: 200px;
}

div.nom_objet
{
	width: 200px;
	vertical-align: -50px;
	font-weight: bold;
	float: left;
	display: inline-block;
	height: 100%;
	background-color: yellow;
}

div.stats_objet
{
	width: 400px;
	display: inline-block;
	height: 100%;
	background-color: lime;
}

span.attribut_objet
{
}
</style>
</head>
<body>
<?php
function tri ($a, $b)
{
	if (isset($a["fragile"]) && !isset($b["fragile"])) {return 1;}
	else {return $a["valeur"] > $b["valeur"];}
}
uasort($tab_objets, "tri");
$c = 20;
$nb = 0;
foreach ($tab_objets as $nom => $tab)
{
	$nb++;
	if ($nb > $c && $nb <= $c + 10)
	{
		$objet = new Objet($nom);
		echo '<div class = "box">';
		echo '<div class = "nom_objet">' . $nom . '</div>';
		echo '<div class = "stats_objet">';
		
		if ($objet->tab_att)
		{
			echo '<span class = "attribut_objet">Attack: ';
			foreach ($objet->tab_att as $elem => $token)
			{
				$n = evaluer_fnc_statique($token, $elem);
				for ($i = 0 ; $i < $n ; $i++)
				{
					echo '<img src = "images/a' . $elem . '.gif" />';
				}
			}
			echo '</span><br />';
		}
		
		if ($objet->tab_def)
		{
			echo '<span class = "attribut_objet">Defense: ';
			foreach ($objet->tab_def as $elem => $token)
			{
				$n = evaluer_fnc_statique($token, $elem);
				for ($i = 0 ; $i < $n ; $i++)
				{
					echo '<img src = "images/d' . $elem . '.gif" />';
				}
			}
			echo '</span>';
		}
		echo '</div></div><br style = "clear:both;"/>';
	}
}
?>
</body>
</html>