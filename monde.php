<html>
	<head>
		<title>Page sans grand intérêt</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<?php
			$world = array(
			array("name" => "chambre Jaune", "outs" => array ("porte" => 1, "fenêtre" => 2)),
			array("name" => "chambre Verte", "outs" => array ("porte" => 0, "fenêtre" => 2)),
			array("name" => "jardin", "outs" => array ("fenêtre Jaune" => 0, "fenêtre verte" => 1))

			$name = $_GET["name"];
			echo "<h1>Hello ";
			if (isset($name)){
				echo $name;
			} else {
				echo "no one";
			}
			echo "</h1>";
			echo "<p> This is dog. </p>";
		?>
	</body>
</html>
