<?php
require_once "src/include.php";
?>
<html>
<head>
<script type = "text/javascript" src = "jquery-2.1.4.min.js"></script>
<style>
div.box
{
	border: 2px black solid;
	width: 600px;
}
</style>
</head>
<body>
<?php
foreach ($tab_objets as $nom => $objet)
{
	echo '<div class = "box">';
	
	echo '</div>';
}
?>
</body>
</html>