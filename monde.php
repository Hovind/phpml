<html>
	<head>
		<title>Page sans grand intérêt</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<?php
			session_start();
			$world = array(
				array("name" => "chambre Jaune",
					"outs" => array ("porte" => 1, "fenêtre" => 2)),
				array("name" => "chambre Verte",
					"outs" => array ("porte" => 0, "fenêtre" => 2)),
				array("name" => "jardin",
					"outs" => array ("fenêtre Jaune" => 0, "fenêtre verte" => 1)));
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
					default_logged_command();
				}
			}

			function default_logged_command() {
				global $world;
				$usr = $_SESSION["username"];
				$room_index = $_SESSION["room"];
				$room = $world[$room_index];
				echo "<h1> Hello ";
				echo $usr;
				echo "</h1>";
				echo "<a href='fichiergame.php?command=logout'> LOG OUT! </a>";
				echo "<h2>";
				echo $room["name"];
				echo "</h2>";
				foreach ($room["outs"] as $out => $room_index) {
					echo "<p>Aller à ";
					echo "<a href='fichiergame.php?command=go&arg=";
					echo $room_index;
					echo "'>";
					echo $world[$room_index]["name"];
					echo "</a> par ";
					echo $out;
					echo "</p>";
				}
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
			function take() {
				echo "<h1> Goodbye! </h1>";
				default_logged_command();
			}
			function put() {
				echo "<h1> Goodbye! </h1>";
				default_logged_command();
			}

			$usr = $_SESSION["username"];
			$cmd = $_GET["command"];
			$arg = $_GET["arg"];
			if (isset($usr)) {
				if ($cmd === "logout") {
					logout_command();
				} else if ($cmd === "go" && isset($arg)) {
					go($arg);
				/*} else if ($cmd === "take") {
				} else if ($cmd === "put") {*/
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


