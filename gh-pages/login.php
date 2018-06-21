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
				$usernm = $_POST['username'];
				$pass = $_POST['password'];
				$servername = "localhost";
				$username = "root";
				$password = "-";
				$databaseName = "tw";

				$conn = new mysqli($servername, $username, $password, $databaseName);

				
				if (isset($_POST['username']) and isset($_POST['password'])){
					$stmt = $conn->prepare("select username, password from users where username = ? and password = ?;");
					$result = $stmt->bind_param("ss", $usernm, $pass);
					$result = $stmt->execute();
					if ($result == TRUE){
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