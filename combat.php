<?php
require_once "src/include.php";
?>
<html>
<head>
<script type = "text/javascript" src = "jquery-2.1.4.min.js"></script>
<style>
#topmenu{position:absolute;top:0;left:-10px;font-size:13px;}
#topmenu section{position:relative;float:left;margin:0;}
#topmenu section>a{color:#888;display:block;padding:9px 12px;text-transform:uppercase;text-shadow:0 1px 1px #fff;}
#topmenu section:hover>a{background:#fff;}
#topmenu.hover section:hover div{display:block;}
#topmenu div{display:none;position:absolute;top:100%;left:0;font-size:12px;background:#fff;width:148px;box-shadow:2px 5px 6px rgba(0,0,0,0.3);}
#topmenu div a{display:block;padding:6px 10px;transition:background 0.13s;}
#topmenu div a:hover{background:#f0f0f0;color:#444;}

div.choix_objet
{
	border: 2px black solid;
	width: 600px;
}

div.log_combat
{
	border: 2px black solid;
	margin-bottom: 10px;
	width: 600px;
}

table.log_combat
{
	border-collapse: collapse;
	width: 600px;
}

.bleu
{
	background-color: rgb(165, 171, 254);
}

.rouge
{
	background-color: rgb(220, 170, 169);
}

.vert
{
	background-color: rgb(171, 221, 170);
}

.jaune
{
	background-color: rgb(240, 220, 170);
}

div.inline 
{ 
	border: 2px green solid;
	float:left; 
}

div.objet
{
	width: 80px;
	height: 120px;
}

span.victoire
{
	font-size: 2em;
	font-weight: bold;
	color: green;
	text-align: center;
}

span.nul
{
	font-size: 2em;
	font-weight: bold;
	color: yellow;
	text-align: center;
}

span.defaite
{
	font-size: 2em;
	font-weight: bold;
	color: red;
	text-align: center;
}
</style>
</head>
<body>
<?php
$p = null;
$q = null;

if (0) //TEST
{
	$p = new Personnage(1);
	$p->ajouter_bdd();
	$p = new Personnage(2);
	$p->ajouter_bdd();
	$p = new Personnage(3);
	$p->ajouter_bdd();
	$p = new Personnage(4);
	$p->ajouter_bdd();
	exit();
}

if (isset($_GET["continue"])) //On continue une partie
{
	$statement = $connection->prepare('SELECT * FROM log WHERE id = :id');
	$statement->execute([
    ':id' => $_GET["continue"]
	]);
	
	$partie = $statement->fetch(PDO::FETCH_ASSOC);
	/*On peut continuer la partie si:
		-l'utilisateur est l'un des deux joueurs et
		-la partie n'est pas encore terminée
	*/
	
	if (is_null($partie["score"]) && $partie["id_p"] != $_SESSION["id"] && $partie["id_q"] != $_SESSION["id"])
	{
		exit("your illegal");
	}
	
	else
	{
		$p = new Personnage($partie["id_p"]);
		$q = new Personnage($partie["id_q"]);
	}
}

if (isset($_GET["nouveau"])) //On crée une nouvelle partie
{
	$p = new Personnage($_SESSION["id"]);
	$q = new Personnage($_GET["nouveau"]);
	$statement = $connection->prepare('INSERT INTO log(id_p, id_q) VALUES (:id_p, :id_q)');
	$statement->execute([
    ':id_p' => $p->id,
	':id_q' => $q->id
	]);
	
	$id = $connection->lastInsertId(); 
	echo '<input type = "hidden" id = "id_log" value = ' . $connection->lastInsertId() . ' />';
}
?>
<div class = "log_combat">

</div>
<div class = "choix_objet">
<?php
foreach ($p->objets as $id => $objet)
{
	echo '<div class = "inline objet" id = "objet_' . $id . '"><img src = "images/blanc.png" /><br />' .  $objet->nom . '</div>';
}
?>
<br style = "clear:both" />
<!-- 🔴📖🔷📑📖📑📖
💎🍏🔷🍎🔵💎 -->
<input type = "button" id = "envoyer_coup" value = "Envoyer message" /><br />
<input type = "button" id = "abandonner" value = "Terminer" />
</div>
<script>
$("document").ready(function ()
{
	var obj1 = -1;
	var obj2 = -1;
	var valide = false;
	
	$("#envoyer_coup").on("click", function ()
	{
		if (obj1 == -1 || obj2 == -1) {alert("erreur");}
		
		else
		{
			var tab1 = obj1.split('_');
			var tab2 = obj2.split('_');
			$.ajax(
			{
				method: "POST",
				url: "src/generer_tour.php",
				dataType: "json",
				data: {test : "test", id_log : $("#id_log").val(), obj1 : tab1[1], obj2 : tab2[1]},
			}).done(function(data) 
			{				
				if (!data.ok)
				{
					alert("Pas encore prêt");
					valide = true;
				}
				
				else
				{
					$(".log_combat").html(data.table)
					$(".log_combat").css("display", "");
					valide = false;
					
					$(".objet").css("border", "2px green solid");
					obj2 = -1;
					obj1 = -1;
					
					if (data.score != null)
					{
						$(".choix_objet").css("display", "none");
					}
				}
				//var tab = JSON.parse(data);
				//alert(data.ok);
			}).fail(function(jqXHR, statut, erreur)
			{
				alert("erreur: " + erreur);
				valide = false;
			});
		}
	});
	
	$(".objet").on("click", function ()
	{
		if (!valide)
		{
			var id = $(this).attr("id");
			$(".objet").css("border", "2px green solid");
			
			if (id != obj1 && id != obj2)
			{
				obj2 = obj1;
				obj1 = id;
			}
			
			$('#' + obj1).css("border", "2px red solid"); 
			$('#' + obj2).css("border", "2px blue solid"); 
		}
	});
});
</script>
</body>