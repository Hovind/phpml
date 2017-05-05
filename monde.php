<html>
	<head>
		<title>Page sans grand intérêt</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="script.js" async></script>
	</head>
	<body>
		<?php
			session_start();
			$world = array(
				array("name" => "Chambre jaune",
					"outs" => array ("porte" => 1, "fenêtre" => 3),
					"colour" => "yellow",
					"x" => 12,
					"y" => 12,
					"width" => 92,
					"height" => 92),
				array("name" => "Chambre verte",
					"outs" => array ("porte" => 0, "fenêtre" => 3),
					"colour" => "green",
					"x" => 12,
					"y" => 104,
					"width" => 92,
					"height" => 92),
				array("name" => "Chambre rouge",
					"outs" => array ("trou" => 3, "porte" => 4),
					"colour" => "darksalmon",
					"x" => 192,
					"y" => 104,
					"width" => 92,
					"height" => 184),
				array("name" => "Jardin",
					"outs" => array ("fenêtre Jaune" => 0, "fenêtre verte" => 1, "trou" => 2, "fenêtre cave" => 4),
					"colour" => "lightgreen",
					"x" => 104,
					"y" => 12,
					"width" => 92,
					"height" => 184),
				array("name" => "Cave",
					"outs" => array("fênetre" => 3, "porte" => 2),
					"colour" => "lightgoldenrodyellow",
					"x" => 104,
					"y" => 196,
					"width" => 92,
					"height" => 92));
			$objects = array("Green shell", "Red shell");

			function default_unlogged_command() {
				echo "<form method='GET'>";
				echo "	login: <input type='text' name='name'>";
				echo "	<input type='hidden' name='command' value='login'>";
				echo "</form>";
			}

			function login_command() {
				$usr = $_GET["name"];
				if (isset($usr)) {
					$_SESSION["username"] = $usr;
					$_SESSION["room"] = 0;
					$_SESSION["stuff"] = array(
						0 => array(0 => 3, 1 => 1),
						1 => array(0 => 1, 1 => 1),
						2 => array(0 => 1, 1 => 1),
						3 => array(0 => 2, 1 => 2),
						4 => array(0 => 0, 0 => 0));
					$_SESSION["inventory"] = array(0 => 1, 1 => 1);
					default_logged_command();
				}
			}

			function default_logged_command() {
				global $world;
				global $objects;
				$usr = $_SESSION["username"];
				$room_index = $_SESSION["room"];
				$inventory = $_SESSION["inventory"];
				$stuff = $_SESSION["stuff"][$room_index];
				$room = $world[$room_index];
				echo "<h5>";
				echo $usr;
				echo "<a href='monde.php?command=logout'><sup>(log out)</sup></a></h5>";
				echo "<h2>";
				echo $room["name"];
				echo "</h2>";
				foreach ($room["outs"] as $out => $out_index) {
					echo "<p>Aller à ";
					echo "<a href='monde.php?command=go&room=";
					echo $out_index;
					echo "'>";
					echo $world[$out_index]["name"];
					echo "</a> par ";
					echo $out;
					echo "</p>";
				}
				echo "<p>&nbsp;</p>";
				foreach ($stuff as $item_index => $quantity) {
					echo "<p>Prendre ";
					echo "<a href='monde.php?command=take&item=";
					echo $item_index;
					echo "'>";
					echo $objects[$item_index];
					echo "</a> (";
					echo $quantity;
					echo ")</p>";
				}
				echo "<h3>Inventory</h3>";
				/*foreach ($inventory as $item_index => $quantity) {
					echo "<p>";
					echo $objects[$item_index];
					echo " (";
					echo $quantity;
					echo ")</p>";
				}*/
				foreach ($inventory as $item_index => $quantity) {
					echo "<p>Mettre ";
					echo "<a href='monde.php?command=put&item=";
					echo $item_index;
					echo "'>";
					echo $objects[$item_index];
					echo "</a> (";
					echo $quantity;
					echo ")</p>";
				}
				echo "<canvas id='map' width='300' height='300'>";
			}

			function unknown_command() {
				echo "<h1> Uknown command</h1>";
			}

			function logout_command() {
				session_destroy();
				echo "<h1> Goodbye! </h1>";
				default_unlogged_command();
			}

			function go($room_index) {
				$_SESSION["room"] = $room_index;
				default_logged_command();
			}
			function take($item_index) {
				$room_index = $_SESSION["room"];
				$count = $_SESSION["stuff"][$room_index][$item_index];
				$_SESSION["stuff"][$room_index][$item_index] -= $count;
				$_SESSION["inventory"][$item_index] += $count;
				default_logged_command();
			}
			function put($item_index) {
				$room_index = $_SESSION["room"];
				$count = $_SESSION["inventory"][$item_index];
				$_SESSION["inventory"][$item_index] -= $count;
				$_SESSION["stuff"][$room_index][$item_index] += $count;
				default_logged_command();
			}

			function world_to_json() {
				global $world;
				header("Content-Type: application/json");
				echo json_encode($world);
			}

			function position_to_json() {
				global $world;
				$room_index = $_SESSION["room"];
				$room = $world[$room_index];
				$position = array(
					"x" => $room["x"] + $room["width"] / 2,
					"y" => $room["y"] + $room["height"] / 2,
					"colour" => $room["colour"]); /* For animation purposes */
				header("Content-Type: application/json");
				echo json_encode($position);
			}

			$usr = $_SESSION["username"];
			$cmd = $_GET["command"];
			$room = $_GET["room"];
			$item = $_GET["item"];
			if (isset($usr)) {
				if ($cmd === "logout") {
					logout_command();
				} else if ($cmd === "go" && isset($room)) {
					go($room);
				} else if ($cmd === "take" && isset($item)) {
					take($item);
				} else if ($cmd === "put" && isset($item)) {
					put($item);
				} else if ($cmd === "map") {
					ob_clean(); /* Remove leading html */
					world_to_json();
					return; /* Avoid trailing html */
				} else if ($cmd === "where") {
					ob_clean(); /* Remove leading html */
					position_to_json();
					return; /* Avoid trailing html */
				} else if (isset($cmd)) {
					default_logged_command();
				} else {
					unknown_command();
				}
			} else {
				if ($cmd === "login") {
					login_command();
				} else {
					default_unlogged_command();
				}
			}
		?>
	</body>
</html>
