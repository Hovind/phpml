<html>
	<head>
		<title>Page sans grand intérêt</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<?php
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
