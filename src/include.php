<?php
session_start();
$_SESSION["id"] = 1;
require_once("bdd.php");
$connection = new PDO('mysql:host=localhost;dbname=test7;charset=utf8', 'root', '');
$mon_perso = new Personnage($_SESSION["id"]);

class Objet
{
	public $attaque;
	public $defense;
	public $tab_att;
	public $tab_def;
	public $nom;
	
	public function Objet ($i)
	{
		global $tab_objets;
		$this->nom = $i;
		$this->attaque = '$tab = array();';
		$this->defense = '$tab = array();';
		$this->reflexion = '$tab = array();';
		if (isset($tab_objets[$i]))
		{
			if (isset($tab_objets[$i]["attaque"])) {$this->attaque = $tab_objets[$i]["attaque"] . ";";}
			if (isset($tab_objets[$i]["defense"])) {$this->defense = $tab_objets[$i]["defense"] . ";";}
			if (isset($tab_objets[$i]["reflexion"])) {$this->reflexion = $tab_objets[$i]["reflexion"] . ";";}
		}
		
		$this->tab_att = isset($tab_objets[$i]["attaque"]) ? array() : (isset($tab_objets[$i]["tab_att"]) ? $tab_objets[$i]["tab_att"] : array());
		$this->tab_def = isset($tab_objets[$i]["defense"]) ? array() : (isset($tab_objets[$i]["tab_def"]) ? $tab_objets[$i]["tab_def"] : array());
		$this->tab_ref = isset($tab_objets[$i]["reflexion"]) ? array() : (isset($tab_objets[$i]["tab_ref"]) ? $tab_objets[$i]["tab_ref"] : array());
	}
}

class Competence
{
	public $attaque;
	public $defense;
	public $tab_att;
	public $tab_def;
	public $nom;
	
	public function Competence ($i)
	{
		global $tab_competences;
		$this->nom = $i;
		$this->attaque = '$tab = array();';
		$this->defense = '$tab = array();';
		if (isset($tab_competences[$i]))
		{
			if (isset($tab_competences[$i]["attaque"])) {$this->attaque = $tab_competences[$i]["attaque"] . ";";}
			if (isset($tab_competences[$i]["defense"])) {$this->defense = $tab_competences[$i]["defense"] . ";";}
		}
		
		$this->tab_att = isset($tab_competences[$i]["attaque"]) ? array() : (isset($tab_competences[$i]["tab_att"]) ? $tab_competences[$i]["tab_att"] : array());
		$this->tab_def = isset($tab_competences[$i]["defense"]) ? array() : (isset($tab_competences[$i]["tab_def"]) ? $tab_competences[$i]["tab_def"] : array());
	}
}


class Personnage
{
	public $stats;
	public $elements_a;
	public $elements_d;
	public $objets;
	public $objets_actifs;
	
	public $attaque_temp;
	public $degats_finaux;
	public $attaque_passive;
	public $defense_passive;
	
	public $nom;
	public $id;
	
	/*
	P: +3% force, +3% défense, +2% PV, -14% mana
	T: -5% force, +5% défense, +5% PV, 0% mana
	A: +4% force, -3% défense, 0% PV, +6% mana
	E: 0% force, +8% défense, +1% PV, +7% mana 
	F: +8% force, -11% défense, -1% PV, +5% mana
	U: -1% force, +2% défense, +3% PV, +10% mana
	O: +5% force, -1% défense, +3% PV, +10% mana
	M: +4% force, -2% défense, 0% PV, +12% mana
	*/
	
	/*
	Crédit initial: 10
	-9: -5
	-6 - -8: -4
	-5: -3
	-4: -3
	-3: -2
	-2: -2
	-1: -1
	0: 0
	1-4: 1-4
	5-8: 6-12
	9+: 15 - 18 - 21...
	
	F/D/PV:
	5
	6
	7
	8
	9
	10
	11
	12
	13
	14
	15
	
	*/
	
	public function Personnage ($i)
	{
		$this->attaque_temp = array("P" => 0, "T" => 0, "A" => 0, "E" => 0, "F" => 0, "U" => 0, "O" => 0, "M" => 0);
		$this->reflexion_temp = array("P" => 0, "T" => 0, "A" => 0, "E" => 0, "F" => 0, "U" => 0, "O" => 0, "M" => 0);
		$this->attaque_passive = array("P" => 1, "T" => 1, "A" => 1, "E" => 1, "F" => 1, "U" => 1, "O" => 1, "M" => 1);
		$this->defense_passive = array("P" => 1, "T" => 1, "A" => 1, "E" => 1, "F" => 1, "U" => 1, "O" => 1, "M" => 1);
		$this->objets_actifs = array();
		
		global $connection;
		$statement = $connection->prepare('SELECT * FROM personnage WHERE id = :id');
		$statement->execute([
		':id' => $i
		]);
		
		$ligne = $statement->fetch(PDO::FETCH_ASSOC);
		//Changer ici
		$this->id = $ligne["id"];
		$this->nom = $ligne["nom"];
		$this->elements = unserialize($ligne["elements"]);
		$this->eq_elem = unserialize($ligne["eq_elements"]);
		$this->stats_initiales = unserialize($ligne["stats_initiales"]);
		$this->stats_suppl = unserialize($ligne["stats_suppl"]);
		$this->stats = unserialize($ligne["stats"]);
		
		$tab_obj = unserialize($ligne["objets_disponibles"]);
		foreach ($tab_obj as $nom_obj)
		{
			$this->objets[] = new Objet($nom_obj);
		}
		$this->en_combat = $ligne["en_combat"];
		
		$coef_stats = array("F" => 1, "D" => 1, "PV" => 1, "mana" => 1);
		
		$stats_positives = array(
		"P" => array("F" => 0.035, "D" => 0.04, "PV" => 0.03, "mana" => -0.14),
		"T" => array("F" => -0.01, "D" => 0.08, "PV" => 0.08, "mana" => -0),
		"A" => array("F" => 0.05, "D" => -0.04, "PV" => 0, "mana" => 0.06),
		"E" => array("F" => 0.02, "D" => 0.09, "PV" => 0.01, "mana" => 0.07),
		"F" => array("F" => 0.07, "D" => -0.1, "PV" => -0.01, "mana" => 0.05),
		"U" => array("F" => 0, "D" => 0.07, "PV" => 0.04, "mana" => 0.1),
		"O" => array("F" => 0.06, "D" => 0, "PV" => 0.04, "mana" => 0.1),
		"M" => array("F" => 0.05, "D" => -0.02, "PV" => 0, "mana" => 0.12)
		);
		
		foreach ($this->elements as $elem => $n)
		{
			$coef_stats["F"] += $n * (($n > 0) ? 1 : (1/3)) * $stats_positives[$elem]["F"];
			$coef_stats["D"] += $n * (($n > 0) ? 1 : (1/3)) * $stats_positives[$elem]["D"];
			$coef_stats["PV"] += $n * (($n > 0) ? 1 : (1/3)) * $stats_positives[$elem]["PV"];
			$coef_stats["mana"] += $n * (($n > 0) ? 1 : (1/3)) * $stats_positives[$elem]["mana"];
			$k = $this->eq_elem[$elem];
			$this->attaque_passive[$elem] = ($n <= 0 ? (1 + 0.04 * $n) : 1) * (1 + 0.028 * $n) * (1 + (($n <= 0 && $k <= 0) ? 0.08 : 0.022) * $this->eq_elem[$elem]);
			$this->defense_passive[$elem] = (1 + 0.028 * $n) * (1 - (($n <= 0 && $k <= 0) ? 0.025 : 0.033) * $this->eq_elem[$elem]);
		}
		
		/*
		$this->stats = $this->stats_initiales;
		$this->stats["F"] += $this->stats_suppl["F"] * $coef_stats["F"];
		$this->stats["D"] += $this->stats_suppl["D"] * $coef_stats["D"];
		$this->stats["PV_max"] = ceil($this->stats_initiales["PV"] + $this->stats_suppl["PV"] * $coef_stats["PV"]);
		$this->stats["mana_max"] = ceil($this->stats_initiales["mana"] + $this->stats_suppl["mana"] * $coef_stats["mana"]);
		
		$this->stats["F"] = pow($this->stats["F"], 0.45);
		$this->stats["D"] = 1.04 * pow($this->stats["D"], 0.5);*/
		pretty_var($this->stats);

		foreach ($this->defense_passive as $elem => $valeur)
		{
			$this->defense_passive[$elem] = 1 / $this->defense_passive[$elem];
			//echo $elem . ' : ' . round($this->stats["F"] * $this->attaque_passive[$elem], 1) . '/' . round($this->stats["D"] / $this->defense_passive[$elem], 1) . '<br />';
		}
	}
	
	public function ajouter_bdd ()
	{
		$this->stats["PV"] = $this->stats["PV_max"];
		$this->stats["mana"] = $this->stats["mana_max"];
		$liste_objets = array();
		foreach ($this->objets as $obj)
		{
			$liste_objets[] = $obj->nom;
		}
		
		global $connection;
		$statement = $connection->prepare('INSERT INTO personnage(nom, elements, eq_elements, stats_initiales, stats_suppl, stats, objets, objets_disponibles, en_combat) 
		VALUES (:nom, :elements, :eq_elements, :stats_initiales, :stats_suppl, :stats, :objets, :objets, false)');
		$statement->execute([
		':nom' => $this->nom,
		':elements' => serialize($this->elements),
		':eq_elements' => serialize($this->eq_elem),
		':stats_initiales' => serialize($this->stats_initiales),
		':stats_suppl' => serialize($this->stats_suppl),
		':stats' => serialize($this->stats),
		':objets' => serialize($liste_objets)
		]);
	}
	
	public function evaluer_fnc ($chaine, $elem)
	{
		if ((string)floatval($chaine) == $chaine) {return floatval($chaine);}
		
		/*
		FNC:
		<expr> = <item>+<expr>
		<item> = <val>
			   | <val>-<val>
			   | <val>-<val>@<val>
			   | [<item>|<val>]
			   | nil
		*/
		
		$tab_items = explode("+", $chaine);
		$valeur = 0;
		
		foreach ($tab_items as $item)
		{
			if (strpos($item, '[') !== FALSE)
			{
				list($vrai_item, $chance) = explode('|', $item);
				$chance = floatval($chance);
				$item = (alea(0, 100, 1000000) < $chance) ? str_replace(array("[", "]", "|"), "", $vrai_item) : 0;
			}
			
			if (strpos($item, '-') !== FALSE)
			{
				$skew = 50;
				if (strpos($item, '@') !== FALSE)
				{
					list($item, $skew) = explode('@', $item);
				}
				list($min, $max) = explode('-', $item);
				$valeur += $min + alea(0, $max - $min, 1000000);
			}
			
			else if (strpos($item, '%') !== FALSE)
			{
				$valeur += $this->equiv_taux(floatval($item) / 100, $elem);
			}
			
			else
			{
				$valeur += $item;
			}
		}
		
		return $valeur;
	}
	
	public function evaluer_attaque ($cible)
	{
		$items_affichage = array();
		
		foreach ($this->objets_actifs as $objet)
		{
			$chaine_attaque = '';
			$tab = $objet->tab_att ? $objet->tab_att : eval('$tab = array();' . $objet->attaque . 'return $tab;');
			
			foreach ($tab as $elem => $valeur)
			{
				$cible->attaque_temp[$elem] += $this->evaluer_fnc($valeur, $elem) * $this->stats['F'] * $this->attaque_passive[$elem] * $cible->defense_passive[$elem];
				$chaine_attaque .= str_repeat($elem, ceil($valeur));
			}
			
			if (strlen($chaine_attaque) > 0)
			{
				$items_affichage[] = array("id" => $this->id, "type" => "a", "icones" => formater_chaine($chaine_attaque, "a"), "texte" => "<b>" . $this->nom . "</b> attacks <b>" . $cible->nom . "</b> with <b>" . $objet->nom . "</b>!");
			}
		}
		
		return $items_affichage;
	}
	
	public function evaluer_defense ($origine)
	{
		$items_affichage = array();
		
		foreach ($this->objets_actifs as $objet)
		{
			$chaine_defense = '';
			$tab = $objet->tab_def ? $objet->tab_def : eval('$tab = array();' . $objet->defense . 'return $tab;');
			foreach ($tab as $elem => $valeur)
			{
				$valeur = $this->evaluer_fnc($valeur, $elem);
				$chaine_defense .= str_repeat($elem, $this->attaque_temp[$elem] > 0 ? ceil($valeur / $origine->stats['F'] * $this->stats['D'] / $this->defense_passive[$elem] / $origine->attaque_passive[$elem]) : 0);
				$this->attaque_temp[$elem] = max(0, $this->attaque_temp[$elem] - $valeur * $this->stats['D']);
			}
			
			if (strlen($chaine_defense) > 0)
			{
				$items_affichage[] = array("id" => $this->id, "type" => "d", "icones" => formater_chaine($chaine_defense, "d"), "texte" => "<b>" . $this->nom . "</b> defends against <b>" . $origine->nom . "</b>'s attack with <b>" . $objet->nom . "</b>!");
			}
		}
		
		return $items_affichage;
	}
	
	public function evaluer_reflexion ($origine)
	{
		$items_affichage = array();
		
		foreach ($this->objets_actifs as $objet)
		{
			$chaine_reflexion = '';
			$tab = $objet->tab_ref ? $objet->tab_ref : eval('$tab = array();' . $objet->reflexion . 'return $tab;');
			foreach ($tab as $elem => $valeur)
			{
				$valeur = $this->evaluer_fnc($valeur, $elem);
				$max_eff = $this->attaque_temp[$elem] - $this->reflexion_temp[$elem];
				$chaine_reflexion .= str_repeat($elem, $max_eff > 0 ? ceil($valeur / $origine->stats['F'] * $this->stats['D'] / $this->defense_passive[$elem] / $origine->attaque_passive[$elem]) : 0);
				$this->attaque_temp[$elem] = max(0, $max_eff - $valeur * $this->stats['D']);
				$origine->attaque_temp[$elem] += min($max_eff, $valeur * $this->stats['D']);
				$origine->reflexion_temp[$elem] += min($max_eff, $valeur * $this->stats['D']);
			}
			
			if (strlen($chaine_reflexion) > 0)
			{
				$items_affichage[] = array("id" => $this->id, "type" => "r", "icones_a" => formater_chaine($chaine_reflexion, "a"), "icones_d" => formater_chaine($chaine_reflexion, "d"), "texte" => "<b>" . $this->nom . "</b> defends against <b>" . $origine->nom . "</b>'s attack with <b>" . $objet->nom . "</b>!");
			}
		}
		return $items_affichage;
	}
	
	public function equiv_taux ($valeur, $elem)
	{
		return ($valeur * $this->attaque_temp[$elem]) / $this->stats['D'];
	}
	
	public function finir_tour ()
	{
		$this->degats_finaux = ceil(array_sum($this->attaque_temp));
		$this->objets_actifs = array();
		$this->attaque_temp = array("P" => 0, "T" => 0, "A" => 0, "E" => 0, "F" => 0, "U" => 0, "O" => 0, "M" => 0);
		$this->reflexion_temp = array("P" => 0, "T" => 0, "A" => 0, "E" => 0, "F" => 0, "U" => 0, "O" => 0, "M" => 0);
	}
	
	public function get_objet ($nom, $pop = false)
	{
		foreach ($this->objets as $b => $a)
		{
			if ($nom == $a->nom) 
			{
				$t = $a;
				if ($pop) {unset($this->objets[$b]);}
				return $t;
			}
		}
		return null;
	}
}

function alea($min, $max, $granulation = 1000000)
{
	return $min + ($max - $min) * mt_rand(0, $granulation) / ($granulation - 1);
}


function formater_chaine ($chaine, $type = "a")
{
	$l = 0;
	$f = 0;
	$n = strlen($chaine);
	$s = "";
	for ($i = 0 ; $i < $n ; $i++)
	{
		$f++;
		$l++;
		$s .= '<img src = "images/' . $type . $chaine[$i] . '.gif" />';
		if ($i != $n - 1 && ($l == 2 && $f != 1) || $f == 10)
		{
			$s .= '<br />';
			$f = 0;
		}
		$l = ($i != ($n - 1) && $chaine[$i] != $chaine[$i + 1]) ? 0 : $l;
	}
	
	if ($n > 0) {$s .= "<br /><br />";}
	
	return $s;
}

/**
 * selectionne une clé d'un tableau avec une probabilité proportionnelle à la valeur associée
 * @param tab le tableau. On suppose que tab est un tableau d'entiers à valeurs positives;
 * @param pop s'il faut retirer l'élément choisi.
 * @return la clé
*/

function array_choice (&$tab, $pop)
{
	$s = array_sum($tab);
	$graine = mt_rand(0, 1000 * $s) / 1000;
	foreach ($tab as $k => $v)
	{
		$graine += $v;
		if ($graine > $s) 
		{
			if ($pop) {unset($tab[$k]);}
			return $k;
		}
	}
	
	unset($tab[$k]);
	return $k;
	
}

/**
 * retourne 2 objets à utiliser en utilisant une version modifiée du minimax
 * @param p le premier joueur
 * @param q le deuxième joueur
 * @return array(array(obj1, obj2), array(obj1, obj2))
 */
function ia ($pp, $pq, $objp = array(), $objq = array())
{
	$p = clone $pp;
	$q = clone $pq;
	
	$resultats = array();
	foreach ($p->objets as $obj_p)
	{
		$resultats[$obj_p->nom] = array();
		foreach ($q->objets as $obj_q)
		{
			$p->objets_actifs = array_merge($objp, array($obj_p));
			$q->objets_actifs = array_merge($objq, array($obj_q));
			
			$p->evaluer_attaque($q);
			$q->evaluer_attaque($p);
			
			$p_off_pre = array_sum($q->attaque_temp);
			$q_off_pre = array_sum($p->attaque_temp);
			
			$p->evaluer_reflexion($q);
			$q->evaluer_reflexion($p);
			
			$p_off_post = array_sum($q->attaque_temp);
			$q_off_post = array_sum($p->attaque_temp);

			$p->evaluer_defense($q);
			$q->evaluer_defense($p);

			$p->finir_tour();
			$q->finir_tour();
			
			$p_def = ($q_off_post - $p->degats_finaux);
			$q_def = ($p_off_post - $q->degats_finaux);
			$p_off = $q->degats_finaux;
			$q_off = $p->degats_finaux;
			
			$diff = alea(0.9, 1.1) * $p->stats["PV"] / (0.0001 + $p->degats_finaux) - alea(0.9, 1.1) * $q->stats["PV"] / (0.0001 + $q->degats_finaux);
			//$diff *= 1000000000 / pow($p_off * $p_off + $q_off * $q_off + 0.0001, 2);
			
			$resultats[$obj_p->nom][$obj_q->nom] = $diff;
		}
	}
	
	echo '<pre>';
	foreach ($p->objets as $obj_p)
	{
		foreach ($q->objets as $obj_q)
		{
			//echo $obj_p->nom . ' / ' . $obj_q->nom . ' : ' . round($resultats[$obj_p->nom][$obj_q->nom], 2) . '<br />';
		}
		echo '<br />';
	}
	echo '</pre>';
	
	//Calcul du minimax de p
	$choixp = null;
	$maxp = -999999999;
	foreach ($p->objets as $obj_p)
	{
		$minp = 999999999;
		foreach ($q->objets as $obj_q)
		{
			if ($resultats[$obj_p->nom][$obj_q->nom] < $minp) {$minp = $resultats[$obj_p->nom][$obj_q->nom];}
		}
		
		if ($maxp < $minp)
		{
			$maxp = $minp;
			$choixp = $obj_p->nom;
		}
	}
	
	//Calcul du maximin de q
	$choixq = null;
	$minq = 999999999;
	foreach ($q->objets as $obj_q)
	{
		$maxq = -999999999;
		foreach ($p->objets as $obj_p)
		{
			if ($resultats[$obj_p->nom][$obj_q->nom] > $maxq) {$maxq = $resultats[$obj_p->nom][$obj_q->nom];}
		}
		
		if ($minq > $maxq)
		{
			$minq = $maxq;
			$choixq = $obj_q->nom;
		}
	}
	
	$objp[] = $p->get_objet($choixp, true);
	$objq[] = $q->get_objet($choixq, true);
	
	//echo $choixp . ' - ' . $choixq . '<br />';
	
	if (count($objp) == 2) {return array($objp, $objq);}
	else 
	{
		return ia($p, $q, $objp, $objq);
	}
}
//
/**
 * retourne 2 objets à utiliser
 * @param p le premier joueur
 * @param q le deuxième joueur
 * @return array(array(obj1, obj2), array(obj1, obj2))
 */
 
/*
ia compliquée et nulle
function ia ($pp, $pq, $objp = array(), $objq = array(), $objcp = array(), $objcq = array())
{
	$p = $pp;
	$q = $pq;
	
	$resultat = array();
	foreach ($p->objets as $obj_p)
	{
		$p_resultats[$obj_p->nom] = array();
		foreach ($q->objets as $obj_q)
		{
			$p->objets_actifs = array_merge($objp, array($obj_p));
			$q->objets_actifs = array_merge($objcp, array($obj_q));
			
			$p->evaluer_attaque($q);
			$q->evaluer_attaque($p);
			
			$p_off_pre = array_sum($q->attaque_temp);
			$q_off_pre = array_sum($p->attaque_temp);
			
			$p->evaluer_reflexion($q);
			$q->evaluer_reflexion($p);
			
			$p_off_post = array_sum($q->attaque_temp);
			$q_off_post = array_sum($p->attaque_temp);

			$p->evaluer_defense($q);
			$q->evaluer_defense($p);
			
			$p->finir_tour();
			$q->finir_tour();
			
			$p_def = ($q_off_post - $p->degats_finaux);
			$q_def = ($p_off_post - $q->degats_finaux);
			$p_off = $q->degats_finaux;
			$q_off = $p->degats_finaux;
			
			$diff = $q->degats_finaux - $p->degats_finaux;
			//$diff = $p_off - $q_off + 1.37 * ($p_def - $q_def);
			
			$p_refl = $p_off_post - $p_off_pre;
			$q_refl = $q_off_post - $q_off_pre;
			
			$p_resultats[$obj_p->nom][$obj_q->nom] = array("off" => $p_off, "def" => $p_def, "diff" => $diff, "res" => $p_off + $p_def + ($p_refl - $q_refl) / 2);
			
		}
	}
	
	foreach ($p->objets as $obj_p)
	{
		foreach ($q->objets as $obj_q)
		{
			if (!isset($q_resultats[$obj_q->nom])) {$q_resultats[$obj_q->nom] = array();}
			$p->objets_actifs = array_merge($objcq, array($obj_p));
			$q->objets_actifs = array_merge($objq, array($obj_q));
			
			$p->evaluer_attaque($q);
			$q->evaluer_attaque($p);
			
			$p_off_pre = array_sum($q->attaque_temp);
			$q_off_pre = array_sum($p->attaque_temp);
			
			$p->evaluer_reflexion($q);
			$q->evaluer_reflexion($p);
			
			$p_off_post = array_sum($q->attaque_temp);
			$q_off_post = array_sum($p->attaque_temp);

			$p->evaluer_defense($q);
			$q->evaluer_defense($p);
			
			$p->finir_tour();
			$q->finir_tour();
			
			$p_def = ($q_off_post - $p->degats_finaux);
			$q_def = ($p_off_post - $q->degats_finaux);
			$p_off = $q->degats_finaux;
			$q_off = $p->degats_finaux;
			
			$diff = $q->degats_finaux - $p->degats_finaux;
			//$diff = $p_off - $q_off + 1.37 * ($p_def - $q_def);
			
			$p_refl = $p_off_post - $p_off_pre;
			$q_refl = $q_off_post - $q_off_pre;
			
			$q_resultats[$obj_q->nom][$obj_p->nom] = array("off" => $p_off, "def" => $p_def, "diff" => -$diff, "res" => $q_off + $q_def + ($q_refl - $p_refl) / 2);
			
		}
	}
	
	var_dump($q_resultats);
	$scores_finaux = array();
	$taux_finaux = array();

	foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
	{
		$scores_finaux[$b] = array();
		$taux_finaux[$b] = array();

		foreach ($a as $direct => $t)
		{
			$scores_finaux[$b][$direct] = 0;
			foreach ($t as $indirect => $tab)
			{
				$scores_finaux[$b][$direct] += $tab["res"];
			}
			$scores_finaux[$b][$direct] *= $scores_finaux[$b][$direct];
			//$scores_directs
		}
		
		foreach ($scores_finaux[$b] as $direct => $t)
		{
			$scores_finaux[$b][$direct] = max(0, $scores_finaux[$b][$direct]);
		}
		
		$s = array_sum($scores_finaux[$b]);
		
		foreach ($scores_finaux[$b] as $direct => $t)
		{
			$taux_finaux[$b][$direct] = 100 * $t / $s;
		}
	}

	$efficacite = array();
	foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
	{
		$efficacite[$b] = array();
		foreach ($a as $direct => $t)
		{
			$efficacite[$b][$direct] = 0;
			foreach ($t as $indirect => $tab)
			{
				$t = $taux_finaux[($b == "p") ? "q" : "p"][$indirect] / 100;
				$efficacite[$b][$direct] += pow($tab["res"] * ($t * $t) , 5);
			}
		}
		
		foreach ($efficacite[$b] as $direct => $t)
		{
			$efficacite[$b][$direct] = max(0, $efficacite[$b][$direct]);
		}
		
		$s = array_sum($efficacite[$b]);
		foreach ($efficacite[$b] as $direct => $t)
		{
			$efficacite[$b][$direct] = 100 * $t / $s;
		}
	}

	$def_res = array();
	foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
	{
		$def_res[$b] = array();
		foreach ($a as $direct => $t)
		{
			$def_res[$b][$direct] = 0;
			foreach ($t as $indirect => $tab)
			{
				$def_res[$b][$direct] += $tab["def"] * $efficacite[($b == "p") ? "q" : "p"][$indirect] / 100;
			}
		}
	}
	
	$ancienne_efficacite = $efficacite;
	
	$efficacite = array();
	foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
	{
		$efficacite[$b] = array();
		foreach ($a as $direct => $t)
		{
			$efficacite[$b][$direct] = 0;
			foreach ($t as $indirect => $tab)
			{
				$t = $ancienne_efficacite[($b == "p") ? "q" : "p"][$indirect] / 100;
				$tq = $ancienne_efficacite[$b][$direct] / 100;
				$efficacite[$b][$direct] += pow(($tab["res"] + (($tq == 1) ? 0 : ($def_res[$b][$direct] / (1 - $tq)  - $tq * $tab["def"] / (1 - $tq)) * 0)) * ($t) , 5);
			}
		}
		
		foreach ($efficacite[$b] as $direct => $t)
		{
			$efficacite[$b][$direct] = max(0, $efficacite[$b][$direct]);
		}
		
		$s = array_sum($efficacite[$b]);
		foreach ($efficacite[$b] as $direct => $t)
		{
			$efficacite[$b][$direct] = 100 * $t / $s;
		}
		
		//$resultat[] = array($$b->get_objet(array_choice($efficacite[$b], true)), $$b->get_objet(array_choice($efficacite[$b], true)));
	}
	
	for ($bv = 0 ; $bv < 10 ; $bv++)
	{
		$def_res = array();
		foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
		{
			$def_res[$b] = array();
			foreach ($a as $direct => $t)
			{
				$def_res[$b][$direct] = 0;
				foreach ($t as $indirect => $tab)
				{
					$def_res[$b][$direct] += $tab["def"] * $efficacite[($b == "p") ? "q" : "p"][$indirect] / 100;
				}
			}
		}
		
		$ancienne_efficacite = $efficacite;
		
		$efficacite = array();
		foreach (array("p" => $p_resultats, "q" => $q_resultats) as $b => $a)
		{
			$efficacite[$b] = array();
			foreach ($a as $direct => $t)
			{
				$efficacite[$b][$direct] = 0;
				foreach ($t as $indirect => $tab)
				{
					$t = $ancienne_efficacite[($b == "p") ? "q" : "p"][$indirect] / 100;
					$tq = $ancienne_efficacite[$b][$direct] / 100;
					$efficacite[$b][$direct] += pow(($tab["res"] + (($tq == 1) ? 0 : ($def_res[$b][$direct] / (1 - $tq)  - $tq * $tab["def"] / (1 - $tq)) * 0)) * ($t) , 5);
					if ($tab["res"] < 0) {echo $tab["res"] . '<br />';}
				}
			}
			
			foreach ($efficacite[$b] as $direct => $t)
			{
				if ($efficacite[$b][$direct] < 0)
				{
					if (1) {echo $efficacite[$b][$direct] . '<br />'; $efficacite[$b][$direct] = 0;}
					
					else
					{
						unset($efficacite[$b][$direct]);
						unset(${$b . '_resultats'}[$direct]);
						foreach (${(($b == "p") ? "q" : "p") . "_resultats"} as $direct2 => $t2)
						{
							foreach ($t2 as $indirect2 => $tab2)
							{
								if ($indirect2 == $direct) {unset(${(($b == "p") ? "q" : "p") . "_resultats"}[$direct2][$indirect2]);}
							}
						}
					}
				}
			}
			
			$s = array_sum($efficacite[$b]);
			foreach ($efficacite[$b] as $direct => $t)
			{
				$efficacite[$b][$direct] = 100 * $t / $s;
			}
			
			if ($bv == 9)
			{
				var_dump($efficacite);
				${"objc" . $b}[] = $$b->get_objet(array_choice($efficacite[$b], false));
				$nom = array_choice($efficacite[$b], true);
				${"obj" . $b}[] = $$b->get_objet($nom);
				foreach (${$b}->objets as $id => $obj)
				{
					if ($nom == $obj->nom) {unset(${$b}->objets[$id]);}
				}
			}
		}
	}
	
	if (count($objp) == 2) {return array($objp, $objq);}
	else 
	{
		return ia($p, $q, $objp, $objq, $objcp, $objcq);
	}
	//return $resultat;
}
*/

/*
switch ($i)
		{
			case 1:
			$this->stats_initiales = array("F" => 7, "D" => 13, "PV" => 475, "mana" => 110);
			$this->stats_suppl = array("F" => 8.8, "D" => 15.75, "PV" => 382, "mana" => 58);
			$this->elements = array("P" => -2, "T" => 1, "A" => 1, "E" => 2, "F" => -4, "U" => 6, "O" => -2, "M" => 1);
			$this->eq_elem = array("P" => -1, "T" => 0, "A" => 0, "E" => 1, "F" => -5, "U" => 4, "O" => -5, "M" => -4);
			
			$this->id = 1;
			$this->nom = "test";
			
			$this->objets = array(
			new Objet("Branch Stick"), 
			new Objet("Ethereal Branch Stick"), 
			new Objet("Pale Blue Scroll"), 
			new Objet("Orange Spiritual Stone"), 
			new Objet("White Trench Coat"), 
			new Objet("Light Dust Wand"),
			new Objet("Deflecting Wand"),
			new Objet("Jade Mirror"));
			break;
			
			case 2:
			$this->stats_initiales = array("F" => 15, "D" => 6, "PV" => 425, "mana" => 110);
			$this->stats_suppl = array("F" => 16.2, "D" => 4.45, "PV" => 185, "mana" => 87);
			$this->elements = array("P" => -4, "T" => -3, "A" => 2, "E" => 1, "F" => 1, "U" => -2, "O" => 8, "M" => 5);
			$this->eq_elem = array("P" => -5, "T" => 2, "A" => 3, "E" => 0, "F" => 5, "U" => 0, "O" => 5, "M" => 5);
			$this->id = 2;
			$this->nom = "testdeux";
			$this->objets = array(
			new Objet("Rainbow Scroll"), 
			new Objet("Copper Armor"), 
			new Objet("Smouldered Fan"), 
			new Objet("Sparkling Black Cloak"), 
			new Objet("Moon Scroll"), 
			new Objet("Swing Scroll"),
			new Objet("Moon Wand"),
			new Objet("Pure Ice Orb"));
			break;
			
			case 3:
			$this->stats_initiales = array("F" => 9, "D" => 13, "PV" => 575, "mana" => 60);
			$this->stats_suppl = array("F" => 11.7, "D" => 12.8, "PV" => 428, "mana" => 28);
			$this->elements = array("P" => 5, "T" => 5, "A" => 1, "E" => 1, "F" => -3, "U" => 3, "O" => -3, "M" => -2);
			$this->eq_elem = array("P" => 0, "T" => 2, "A" => -1, "E" => -1, "F" => -5, "U" => 1, "O" => -4, "M" => -5);
			$this->id = 3;
			$this->nom = "testtrois";
			$this->objets = array(
			new Objet("Green Wood Hammer"),
			new Objet("Green Wood Club"),			
			new Objet("Green Deflecting Wand"), 
			new Objet("Cream Trench Coat"), 
			new Objet("Wooden Fan"),
			new Objet("Sturdy Branch Stick"),
			new Objet("Silver Armor"),
			new Objet("Shovel"));
			break;
			
			case 4:
			$this->stats_initiales = array("F" => 12, "D" => 10, "PV" => 500, "mana" => 125);
			$this->stats_suppl = array("F" => 14.2, "D" => 11.5, "PV" => 391, "mana" => 61);
			$this->elements = array("P" => 7, "T" => -4, "A" => 0, "E" => -4, "F" => 6, "U" => -1, "O" => 4, "M" => -1);
			$this->eq_elem = array("P" => 5, "T" => -5, "A" => 2, "E" => -5, "F" => 5, "U" => -3, "O" => 5, "M" => 3);
			$this->id = 4;
			$this->nom = "testqautre";
			$this->objets = array(
			new Objet("Smouldered Sword"), 
			new Objet("Smouldered Fan"), 
			new Objet("Silver Sword"), 
			new Objet("Fire Hammer"),
			new Objet("Great Fire Sword"),
			new Objet("Unfrosting Powder"),
			new Objet("Bright Star Scroll"));
			break;
		}
*/

function pretty_var($myArray)
{
    return 0;
	echo "<pre>";
    var_export($myArray);
    echo "</pre>";
} 

function evaluer_fnc_statique ($chaine, $elem)
{
	if ((string)floatval($chaine) == $chaine) {return floatval($chaine);}
	
	/*
	FNC:
	<expr> = <item>+<expr>
	<item> = <val>
		   | <val>-<val>
		   | <val>-<val>@<val>
		   | [<item>|<val>]
		   | nil
	*/
	
	$tab_items = explode("+", $chaine);
	$valeur = 0;
	
	foreach ($tab_items as $item)
	{
		if (strpos($item, '[') !== FALSE)
		{
			list($vrai_item, $chance) = explode('|', $item);
			$chance = floatval($chance);
			$item = (alea(0, 100, 1000000) < $chance) ? str_replace(array("[", "]", "|"), "", $vrai_item) : 0;
		}
		
		if (strpos($item, '-') !== FALSE)
		{
			$skew = 50;
			if (strpos($item, '@') !== FALSE)
			{
				list($item, $skew) = explode('@', $item);
			}
			list($min, $max) = explode('-', $item);
			$valeur += $min + alea(0, $max - $min, 1000000);
		}
		
		else if (strpos($item, '%') !== FALSE)
		{
			//$valeur += $this->equiv_taux(floatval($item) / 100, $elem);
		}
		
		else
		{
			$valeur += $item;
		}
	}
	
	return $valeur;
}