<html>
	<head>
		<title>Page sans grand intérêt</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<?php
			session_start();
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
					default_logged_command();
				}
			}

			function default_logged_command() {
				$usr = $_SESSION["username"];
				echo "<h1> Hello ";
				echo $usr;
				echo "</h1>";
				echo "<a href='fichiergame.php?command=logout'> LOG OUT! </a>";
			}

			function logout_command() {
				session_destroy();
				echo "<h1> Goodbye! </h1>";
				default_unlogged_command();
			}

			$usr = $_SESSION["username"];
			$cmd = $_GET["command"];
			if (isset($usr)) {
				if ($cmd === "logout") {
					logout_command();
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


