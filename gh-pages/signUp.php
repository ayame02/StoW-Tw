	<html>
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    		<meta http-equiv="X-UA-Compatible" content="ie=edge">
			<link rel="stylesheet" href="styles.css">
		</head>
		<body>
			<div class = "redirecting_content">	
				<h2>
					<?php
						session_start();
						include ('databaseConn.php');
						$db = new Database();
						$firstName = $_POST["first_name"];
						$lastName = $_POST["last_name"];
						$nick = $_POST["nickname"];
						$username = $_POST["username"];
						$pwd = $_POST["password"];
						$pwd2 = $_POST["password2"];

						if ($pwd == NULL || $pwd2 == NULL){
							echo("Password was not set. Try again.");
							header ('Refresh: 2; URL=signUp.html');
							die();
						}

						if ($pwd != $pwd2){
							echo("Passwords do not match. Try again.");
							header ('Refresh: 2; URL=signUp.html');
							die();
						}
						$usr_exists = "select username from users";
						$USRResult = $db->query($usr_exists);
						while ($row = $USRResult->fetch_assoc()){
							if ($username == $row['username']){
								echo ("Username already exists, try again with a different username.");
								header ('Refresh: 2; URL=signUp.html');
								die();
							}
						}
						
						$nick_exists = "select nickname from users";
						$nickResult = $db->query($nick_exists);
						while ($row = $nickResult->fetch_assoc()){
							if ($row['nickname'] != NULL){
								if ($nick == $row['nickname']){
									echo ("Nickname already exists, try again with a different nickname.");
									header ('Refresh: 2; URL=signUp.html');
									die();
								}
							}
						}
						
						if (!isset($_POST["last_name"]))
							$lastName = NULL;
						if (!isset($_POST["first_name"]))
							$firstName = NULL;
						if(!isset($_POST["nickname"]))
							$nick = NULL;

						$sql = "INSERT INTO users(username, password, first_name, last_name, nickname) VALUES ('$username', '$pwd', '$firstName', '$lastName', '$nick')";
						$result = $db->query($sql);
						if ($result == TRUE){
							echo ("You have been registered, yay!");
							header ('Refresh: 2; URL=index.html');
						}
						else {
							echo ("Something went wrong, please try again later.");
							header ('Refresh: 2; URL=index.html');
							die();
						}
					?>
				</h2>
			</div>
		</body>
	</html>