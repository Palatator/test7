<?php
/*
Catégories:
I/ Weapons
	A/ Physical
		1) 
	B/ Magical
	C/ Mixed
		1) Fans
II/ Shields

III/ Accessories
*/

$tab_objets = array(

	"Paper Sword" => array("l" => 1, "valeur" => 100, "r" => 7, 'tab_att' => array("P" => 5)),
	"Plastic Sword" => array("l" => 2, "valeur" => 450, "r" => 9, 'tab_att' => array("P" => 6)),
	"Rusty Sword" => array("l" => 3, "valeur" => 1325, "r" => 11, 'tab_att' => array("P" => 7)),
	"Glass Sword" => array("l" => 4, "valeur" => 3050, "r" => 13, 'tab_att' => array("P" => 8)),
	"Iron Sword" => array("l" => 4, "valeur" => 6500, "r" => 15, 'tab_att' => array("P" => 9)),
	"Metal Sword" => array("l" => 5, "valeur" => 9500, "r" => 19, 'tab_att' => array("P" => 10)),
	"Copper Sword" => array("l" => 6, "valeur" => 24000, "r" => 28, 'tab_att' => array("P" => 11)),
	"Silver Sword" => array("l" => 7, "valeur" => 68000, "r" => 36, 'tab_att' => array("P" => 12)),
	"Quicksilver Sword" => array("l" => 1, "valeur" => 165000, "r" => 45, 'tab_att' => array("P" => 13)),
	"Golden Sword" => array("l" => 1, "valeur" => 360000, "r" => 55, 'tab_att' => array("P" => 14)),
	"Platinium Sword" => array("l" => 1, "valeur" => 850000, "r" => 64, 'tab_att' => array("P" => 15)),
	"Uranium Sword" => array("l" => 1, "valeur" => 1900000, "r" => 72, 'tab_att' => array("P" => 16)),
	"Diamond Sword" => array("l" => 1, "valeur" => 4500000, "r" => 80, 'tab_att' => array("P" => 17)),
	
	"Smouldered Sword" => array("l" => 6, "valeur" => 18250, "r" => 75, 'tab_att' => array("P" => 3, "A" => 1, "F" => 5, "O" => 2), 'tab_def' => array("A" => 2)),
	"Nettle Sword" => array("l" => 6, "valeur" => 20250, "r" => 57, 'tab_att' => array("T" => 5, "A" => 3, "O" => 3)),
	"Mage Sword" => array("l" => 8, "valeur" => 52000, "r" => 62, 'tab_att' => array("P" => 6, "O" => 6)),
	"Mage Sword" => array("l" => 8, "valeur" => 92000, "r" => 70, 'tab_att' => array("P" => 7, "M" => 6)),
	"Spectral Sword" => array("l" => 8, "valeur" => 154000, "r" => 94, 'tab_att' => array("A" => 3, "U" => 3, "O" => 5), 'tab_def' => array('U' => 5)),
	"Natural Sword" => array("l" => 9, "valeur" => 204000, "r" => 73, 'tab_att' => array("P" => 7, "T" => 3, "A" => 4)),
	"Great Fire Sword" => array("l" => 10, "valeur" => 420000, "r" => 81, 'tab_att' => array("P" => 4, "A" => 3, "F" => 8)),
	
	"Green Wood Club" => array("l" => 7, "valeur" => 28000, "r" => 61, 'tab_att' => array("P" => 3, "T" => 3, "A" => 3, "U" => 3)),
	
	"Green Wood Hammer" => array("l" => 6, "valeur" => 13800, "r" => 47, 'tab_att' => array("P" => 3, "T" => 5, "U" => 3)),
	"Fire Hammer" => array("l" => 7, "valeur" => 40500, "r" => 55, 'tab_att' => array("P" => 6, "F" => 6)),
	"Blazing Tree Hammer" => array("l" => 8, "valeur" => 103000, "r" => 87, 'tab_att' => array("T" => 3, "A" => 3, "F" => 7.44)),
	
	"Weak Dark Orb" => array("l" => 5, "valeur" => 10750, "r" => 68, 'tab_att' => array("O" => 4.77,  "M" => 4.77)),
	"Weak Light Orb" => array("l" => 5, "valeur" => 10750, "r" => 68, 'tab_att' => array("U" => 4.77,  "M" => 4.77)),
	"Fire And Ice Orb" => array("l" => 7, "valeur" => 47350, "r" => 75, 'tab_att' => array("E" => 3, "F" => 3, "M" => 6)),
	"Pure Fire Orb" => array("l" => 7, "valeur" => 49750, "r" => 84, 'tab_att' => array("F" => 11)),
	"Pure Dark Orb" => array("l" => 7, "valeur" => 49750, "r" => 86, 'tab_att' => array("O" => 11)),
	"Pure Ice Orb" => array("l" => 7, "valeur" => 49750, "r" => 88, 'tab_att' => array("E" => 11)),
	"Pure Light Orb" => array("l" => 7, "valeur" => 49750, "r" => 90, 'tab_att' => array("U" => 11)),
	"Cooling Orb" => array("l" => 8, "valeur" => 82000, "r" => 79, 'tab_att' => array("E" => 6), 'tab_def' => array("A" => 2, "F" => "6+25%", "U" => 2)),
	
	"Dark Forest Scroll" => array("l" => 5, "valeur" => 7750, "r" => 79, 'tab_att' => array("T" => 6, "O" => 3)),
	"Swamp Scroll" => array("l" => 5, "valeur" => 9000, "r" => 70, 'tab_att' => array("E" => 4, "A" => 3, "O" => 3)),
	"Pastel Scroll" => array("l" => 5, "valeur" => 9750, "r" => 68, 'tab_att' => array("T" => 2, "U" => 3, "M" => 5)),
	"Moon Scroll" => array("l" => 6, "valeur" => 15400, "r" => 81, 'tab_att' => array("T" => 2, "O" => 8.44)),
	"Pale Blue Scroll" => array("l" => 7, "valeur" => 15800, "r" => 93, 'tab_att' => array("A" => 8, "E" => 1), 'tab_def' => array("A" => 3)),
	"Bright Star Scroll" => array("l" => 7, "valeur" => 26750, "r" => 88, 'tab_att' => array("A" => 3, "F" => 3, "U" => 5)),
	"Rainbow Scroll" => array("l" => 7, "valeur" => 33750, "r" => 48, 'tab_att' => array("F" => 2, "U" => 2, "T" => 2, "A" => 2, "E" => 2, "O" => 2)),
	"Seaweed Scroll" => array("l" => 7, "valeur" => 38000, "r" => 65, 'tab_att' => array("T" => 3, "A" => 3, "E" => 3, "U" => 3)),
	"Swing Scroll" => array("l" => 8, "valeur" => 87300, "r" => 81, 'tab_att' => array("A" => 5, "M" => 8)),
	"True Rainbow Scroll" => array("l" => 11, "valeur" => 774000, "r" => 84, 'tab_att' => array("M" => 2, "F" => 2, "U" => 2, "T" => 2, "A" => 2, "E" => 2, "O" => 2, "P" => 2)),
	
	"Black Plastic Staff" => array("l" => 4, "valeur" => 3950, "r" => 46, 'tab_att' => array("P" => 5, "O" => 2, "M" => 2)),
	"Coral Staff" => array("l" => 5, "valeur" => 5200, "r" => 65, 'tab_att' => array("P" => 3, "E" => 2, "A" => 3, "U" => 2)),
	"Oak Staff" => array("l" => 5, "valeur" => 6100, "r" => 37, 'tab_att' => array("P" => 6, "T" => 4)),
	"Freeze Hands Staff" => array("l" => 6, "valeur" => 17040, "r" => 74, 'tab_att' => array("P" => 2, "E" => 3), "tab_def" => array("P" => "80%", "F" => 2)),
	"Freeze Mind Staff" => array("l" => 6, "valeur" => 27720, "r" => 79, 'tab_att' => array("P" => 2, "E" => 3), "tab_def" => array("F" => "80%", "M" => 3)),
	
	"Branch Stick" => array("l" => 5, "valeur" => 8830, "r" => 68, 'tab_att' => array("T" => 5, "U" => 5)),
	"Ethereal Branch Stick" => array("l" => 5, "valeur" => 9525, "r" => 81, 'tab_att' => array("T" => 3, "U" => 5, "M" => 2.55)),
	"Sturdy Branch Stick" => array("l" => 7, "valeur" => 36000, "r" => 81, 'tab_att' => array("T" => 3, "U" => 3), 'tab_def' => array("F" => 8, "M" => 2)),
	
	
	"Deflecting Wand" => array("l" => 5, "valeur" => 9950, "r" => 94, 'tab_att' => array("U" => 1), 'tab_def' => array("P" => "5+35%", "F" => 2, "A" => 2), 'tab_ref' => array("U" => "19%", "F" => "19%", "O" => "19%")),
	"Light Dust Wand" => array("l" => 6, "valeur" => 10200, "r" => 66, 'tab_att' => array("A" => 2, "U" => 3), 'tab_def' => array("A" => 4, "O" => 3, "M" => 4)),
	"Green Deflecting Wand" => array("l" => 7, "valeur" => 47200, "r" => 97, 'tab_att' => array("U" => 1), 'tab_def' => array("P" => "5+35%", "T" => 3, "F" => 3), 'tab_ref' => array("U" => "22%", "T" => "22%", "A" => "22%")),
	"Crystal Wand" => array("l" => 7, "valeur" => 52000, "r" => 77, 'tab_att' => array("A" => 1, "E" => 6, "U" => 6)),
	"Moon Wand" => array("l" => 8, "valeur" => 103000, "r" => 85, 'tab_att' => array("T" => 2, "O" => 6.44), 'tab_def' => array("E" => "50%", "U" => "25%")),
	"Blue Glowing Wand" => array("l" => 8, "valeur" => 133500, "r" => 82, 'tab_att' => array("E" => 3, "U" => 3, "M" => 3), 'tab_def' => array("O" => 2, "M" => "50%")),
	"Greater Earth Wand" => array("l" => 12, "valeur" => 1375000, "r" => 98, 'tab_att' => array("P" => 3, "A" => 1, "T" => 3), 'tab_def' => array("T" => "100%", "A" => 5)),
	"Greater Water Wand" => array("l" => 13, "valeur" => 2800000, "r" => 98, 'tab_att' => array("P" => 3, "A" => 1, "E" => 3), 'tab_def' => array("E" => "100%", "F" => 5)),
	"Greater Fire Wand" => array("l" => 16, "valeur" => 16000000, "r" => 98, 'tab_att' => array("P" => 3, "A" => 1, "F" => 3), 'tab_def' => array("F" => "100%", "E" => 5)),
	
	"Unfrosting Powder" => array("l" => 8, "valeur" => 138000, "r" => 84, 'tab_att' => array("F" => 4, "A" => 1, "M" => 5), 'tab_def' => array("E" => "43%")),
	
	"Wooden Fan" => array("l" => 4, "valeur" => 3850, "r" => 28, 'tab_att' => array("P" => 3, "T" => 3), 'tab_def' => array("P" => 3, "T" => 3)),
	"Green Leaf Fan" => array("l" => 5, "valeur" => 6500, "r" => 62, 'tab_att' => array("P" => 3, "T" => 3, "A" => 3), 'tab_def' => array("T" => 2)),
	"Frozen Fan" => array("l" => 5, "valeur" => 8400, "r" => 57, 'tab_att' => array("P" => 3, "A" => 3, "E" => 3), 'tab_def' => array("F" => 2.6)),
	"Smouldered Fan" => array("l" => 6, "valeur" => 13250, "r" => 78, 'tab_att' => array("A" => 3, "F" => 4, "O" => 3), 'tab_def' => array("A" => 2)),
	"Impure Crystal Fan" => array("l" => 6, "valeur" => 13700, "r" => 75, 'tab_att' => array("A" => 1, "E" => 4, "U" => 3), 'tab_def' => array("M" => 5)),
	"Superior Fighting Fan" => array("l" => 7, "valeur" => 18750, "r" => 59, 'tab_att' => array("P" => 6, "A" => 3), 'tab_def' => array("P" => 4)),
	
	"Vine Spoon" => array("l" => 8, "valeur" => 117000, "r" => 88, 'tab_att' => array("P" => 3, "T" => 4), 'tab_def' => array("E" => 6, "U" => 6)),
	
	"Paper Armor" => array("l" => 1, "valeur" => 45, "r" => 7, 'tab_def' => array("P" => "55%", "T" => 1, "A" => 1)),
	"Plastic Armor" => array("l" => 2, "valeur" => 200, "r" => 9, 'tab_def' => array("P" => "58%", "T" => 1.65, "A" => 1.65)),
	"Rusty Armor" => array("l" => 3, "valeur" => 600, "r" => 11, 'tab_def' => array("P" => "61%", "T" => 2.15, "A" => 2.15)),
	"Glass Armor" => array("l" => 4, "valeur" => 1350, "r" => 13, 'tab_def' => array("P" => "63%", "T" => 2.55, "A" => 2.55)),
	"Iron Armor" => array("l" => 4, "valeur" => 2900, "r" => 15, 'tab_def' => array("P" => "66%", "T" => 3.15, "A" => 3.15)),
	"Metal Armor" => array("l" => 5, "valeur" => 4300, "r" => 19, 'tab_def' => array("P" => "69%", "T" => 3.65, "A" => 3.65)),
	"Copper Armor" => array("l" => 6, "valeur" => 11000, "r" => 28, 'tab_def' => array("P" => "71%", "T" => 4.35, "A" => 4.35)),
	"Silver Armor" => array("l" => 7, "valeur" => 32000, "r" => 36, 'tab_def' => array("P" => "74%", "T" => 4.85, "A" => 4.85)),
	"Quicksilver Armor" => array("l" => 1, "valeur" => 78400, "r" => 45, 'tab_def' => array("P" => "76%", "T" => 5.45, "A" => 5.45)),
	"Golden Armor" => array("l" => 1, "valeur" => 175000, "r" => 55, 'tab_def' => array("P" => "78%", "T" => 6, "A" => 6)),
	"Platinium Armor" => array("l" => 1, "valeur" => 425000, "r" => 64, 'tab_def' => array("P" => "80%", "T" => 6.5, "A" => 6.5)),
	"Uranium Armor" => array("l" => 1, "valeur" => 870000, "r" => 72, 'tab_def' => array("P" => "82%", "T" => 7, "A" => 7)),
	"Diamond Armor" => array("l" => 1, "valeur" => 2400000, "r" => 80, 'tab_def' => array("P" => "84%", "T" => 7.5, "A" => 7.5)),
	
	"Heavy Grey Coat" => array("l" => 4, "valeur" => 3150, "r" => 18, "tab_def" => array("P" => 6, "T" => 3, "A" => 2, "E" => 2)),
	"White Trench Coat" => array("l" => 6, "valeur" => 7750, "r" => 55, "tab_def" => array("P" => 5, "T" => 5, "A" => 3, "E" => 3, "O" => 5)),
	"Cream Trench Coat" => array("l" => 8, "valeur" => 47000, "r" => 72, "tab_def" => array("P" => 7, "T" => 3, "A" => 3, "E" => 3, "F" => 3, "O" => 5)),
	
	"Sparkling Black Cloak" => array("l" => 7, "valeur" => 23000, "r" => 70, "tab_att" => array("M" => 2), "tab_def" => array("P" => "25%", "T" => 2, "U" => "63%", "M" => "45%")),
	
	
	"Orange Spiritual Stone" => array("l" => 5, "valeur" => 4250, "r" => 88, "tab_def" => array("A" => 3, "E" => 5, "F" => 3, "O" => 3, "M" => 3)),
	"Yellow Spiritual Stone" => array("l" => 6, "valeur" => 9325, "r" => 91, "tab_def" => array("A" => 3, "F" => 3, "U" => 5, "O" => "75%", "M" => 3)),
	"Green Spiritual Stone" => array("l" => 7, "valeur" => 18350, "r" => 93, "tab_def" => array("T" => 7, "A" => 7, "F" => 3, "U" => 2, "O" => 2, "M" => 3)),
	"Turquoise Spiritual Stone" => array("l" => 9, "valeur" => 48000, "r" => 95, "tab_def" => array("T" => 12, "A" => 12, "O" => 7, "M" => 3)),
	
	"White Spiritual Stone Card White Spiritual Stone Card" => array("l" => 4, "valeur" => 65, "r" => 22, 'tab_def' => array("P" => "100%", "A" => "100%", "U" => "100%"), "fragile" => 100),
	"Blue Card" => array("l" => 5, "valeur" => 105, "r" => 1, 'tab_att' => array("E" => 7), "gel" => 25, "fragile" => 100),
	"Green Card" => array("l" => 5, "valeur" => 105, "r" => 1, 'tab_att' => array("T" => 7), "soin" => 45, "fragile" => 100),
	"Yellow Card" => array("l" => 5, "valeur" => 105, "r" => 1, 'tab_att' => array("U" => 7), "blind" => 25, "fragile" => 100),
	"Red Card" => array("l" => 5, "valeur" => 105, "r" => 1, "tab_att" => array("F" => 13), "fragile" => 100),
	
	"Turquoise Card" => array("l" => 9, "valeur" => 850, "r" => 3, "tab_att" => array("T" => 3, "A" => 10, "E" => 3), "tab_def" => array("A" => 2, "M" => 2), "fragile" => 100),
	"Lime Card" => array("l" => 9, "valeur" => 850, "r" => 3, "tab_att" => array("T" => 8, "U" => 8), "tab_def" => array("A" => 2, "O" => 2), "fragile" => 100),
	"Orange Card" => array("l" => 9, "valeur" => 850, "r" => 3, "tab_att" => array("M" => 3, "F" => 10, "U" => 3), "tab_def" => array("A" => 2, "E" => 2), "fragile" => 100),
	"Purple Card" => array("l" => 9, "valeur" => 850, "r" => 3, "tab_att" => array("E" => 4, "O" => 4, "M" => 4, "F" => 4), "tab_def" => array("A" => 2, "U" => 2), "fragile" => 100),
	
	"Sparkshooter" => array("l" => 1, "valeur" => 1, "r" => 9531, "tab_att" => array("F" => 1, "U" => 1, "A" => 1)),
	"Pretty Output Shield" => array("l" => 4, "valeur" => 3150, "r" => 18, "tab_def" => array("P" => 3, "F" => 5, "U" => 13, "T" => 5, "A" => 6, "E" => 3, "O" => 13, "M" => 3)),
	
	"Jade Mirror" => array("l" => 6, "valeur" => 9555, "r" => 82, "tab_def" => array("P" => 2, "T" => 2, "U" => 2), "tab_ref" => array("O" => "50%")),
	"Shovel" => array("l" => 7, "valeur" => 15350, "r" => 85, "tab_att" => array("T" => 4), "tab_ref" => array("T" => "50%", "A" => "25%")),
	"Emerald Mirror" => array("l" => 7, "valeur" => 152000, "r" => 85, "tab_def" => array("E" => 4, "U" => 2, "M" => 2), "tab_ref" => array("O" => "42%", "M" => "42%")),
	"Reflection Shield" => array("l" => 7, "valeur" => 188400, "r" => 78, "tab_def" => array("P" => 2, "A" => 2), "tab_ref" => array("U" => "61%", "E" => "14%")),
	"Super Shovel" => array("l" => 16, "valeur" => 7000000, "r" => 99, "tab_att" => array("T" => 6), "tab_ref" => array("T" => "80%", "A" => "50%")),
	);

$tab_competences = array(
	"Dark Cloud" => array("cd" => 4, "mana" => 18, "attaque" => '$tab = array("A" => 2, "E" => 3, "O" => 7)', 'defense' => '$tab = array("U" => 2)'),
	"Icy Cloud" => array("cd" => 4, "mana" => 18, "attaque" => '$tab = array("A" => 2, "U" => 3, "E" => 7)', 'defense' => '$tab = array("F" => 2)'),
	
	"Fiery Counterattack" => array("cd" => 2, "mana" => 3, "attaque" => 'echo $this->attaque_temp["P"]; $tab = array("P" => 2, "F" => 2 + 0.25 * $this->attaque_temp["P"])'),
	"Aerial Bash" => array("cd" => 5, "mana" => 1, "attaque" => '$tab = array("P" => 5, "A" => 8)'),
	"Thrash (1)" => array("cd" => 1, "mana" => 1, "attaque" => '$tab = array("P" => 6, "A" => 3)'),
	"Thrash (2)" => array("cd" => 4, "mana" => 1, "attaque" => '$tab = array("P" => 9, "A" => 3, "T" => 3)')
);
/*
$this->defense = <<<'LOL'
asort($this->attaque_temp);
$max = 6.33;
foreach ($this->attaque_temp as $elem => $valeur)
{
	$val_eff = min($max, $valeur / $this->stats['D']);
	$max = max(0, $max - $valeur / $this->stats['D']);
	$tab[$elem] = $val_eff;
}
LOL;
*/

/*
P

T

A

E

F

U

O

M

L
*/
?>