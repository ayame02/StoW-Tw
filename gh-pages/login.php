<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div class="redirecting_content">
			<?php
				session_start();
				include ('databaseConn.php');
				$db = new Database();
				$usernm = $_POST['username'];
				$pass = $_POST['password'];
				if (isset($_POST['username']) and isset($_POST['password'])){
					if ($db->users_check($usernm, $pass) === 1){
						$_SESSION["username"] = $usernm;
						echo ("<h2>Login success...Redirecting.</h2>");
						header ('Refresh: 2; URL=loggedIn.html');
					}
					else {
							echo ("<h2>Invalid username or password. Redirecting.</h2>"); 
							header ('Refresh: 2; URL=login.html');
					}
				}
				else {
					echo ("<h2>Invalid username or password. Redirecting.</h2>");
					header ('Refresh: 2; URL=login.html');
					
				}
			?>
		</div>
	</body>
</html>