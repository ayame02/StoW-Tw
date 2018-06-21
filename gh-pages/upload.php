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
				$target_dir = "uploads/";
				$uploadOk = 1;
				$username = $_SESSION["username"];
				$nickname = "";
				$servername = "localhost";
				$username2 = "root";
				$password = "-";
				$databaseName = "tw";
				$conn = new mysqli($servername, $username2, $password, $databaseName);
				$stmt = $conn->prepare("SELECT nickname FROM users WHERE username = ?;");
				$result = $stmt->bind_param('s', $username);
				$result = $stmt->execute();
				$stmt->bind_result($nickname);
				$stmt->fetch();
				$stmt->close();
				if (isset($_POST["submit"])){
					$file_name = $_FILES["fileToUpload"]["name"];
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
					$stupid_check = 0;
					if (strcmp($fileType, "xml") != 0){
						$stupid_check = $stupid_check + 1;
					}
					if (strcmp($fileType, "json") != 0){
						$stupid_check = $stupid_check + 1;
					}
					if($stupid_check == 2) {
						echo ("<h2>Sorry, only JSON, XML files are allowed.</h2>");
						header ('Refresh: 4; URL=upload.html');
						die();
					}
					if (file_exists($target_file)) {
						echo ("<h2>Sorry, file already exists.</h2>");
						header ('Refresh: 4; URL=upload.html');
						die();
					}

					if ($_FILES["fileToUpload"]["size"] > 500000000) {
						echo ("<h2>Sorry, your file is too large.</h2>");
						header ('Refresh: 4; URL=upload.html');
						die();
					}

					if ($uploadOk != 0) {
						if (strcmp($fileType, "json") == 0){
							$handle = fopen($file_name, 'r');
							$data = file_get_contents($file_name);
							$json_a = json_decode($data, true);
							$book_title = $json_a['title'];
							
							if (empty($book_title)){
								echo "<h2> Book has no title. Cannot upload. </h2>";
								header ('Refresh: 4; URL=upload.html');
								die();
							}
							else{
								$number_of_characters = $json_a['characters'][0]['number_of_characters'];
								$chars = array();
								$test = 0;
								for ($i = 1; $i <= $number_of_characters; $i++){
									$name = $json_a['characters'][$i]['name'];
									$role = $json_a['characters'][$i]['role'];
									if (empty($name)){
										$test = 1;
										break;
									}
									if (empty($role)){
										$test = 2;
										break;
									}
								}
								if ($test == 1){
									echo "<h2> One of the characters has no name. Cannot upload. </h2>";
									header ('Refresh: 4; URL=upload.html');
									die();
								}
								else{
									if ($test == 2){
										echo "<h2> One of the characters has no role. Cannot upload. </h2>";
										header ('Refresh: 4; URL=upload.html');
										die();
									}
									else{
										$primary_authors = $json_a['authors'][0]['number_of_primary_authors'];
										if ($primary_authors >= 2){
											echo "<h2> Sorry, a book can only have one primary author. </h2>";
											header ('Refresh: 4; URL=upload.html');
											die();
										}
										else{
											$stmt = $conn->prepare("INSERT INTO story(title, nickname, user) VALUES(?, ?, ?);");
											$title_insert = $stmt->bind_param("sss", $book_title, $nickname, $username);
											$title_insert = $stmt->execute();
											$stmt->close();
											$stmt = $conn->prepare("SELECT story_id FROM story WHERE title = ?;");
											$id_res = $stmt->bind_param("s", $book_title);
											$id_res = $stmt->execute();
											$stmt->bind_result($book_id);
											$stmt->fetch();
											$stmt->close();

											$primary_authors = $json_a['authors'][0]['number_of_primary_authors'] + 2;
											$secondary_authors = $json_a['authors'][1]['number_of_secondary_authors'] + $primary_authors;
											$auth = array();
											for ($i = 2; $i < $primary_authors; $i++){
												$name = $json_a['authors'][$i]['primary'];
												$stmt = $conn->prepare("INSERT INTO authors(name, role) VALUES (?,'primary');");
												$res = $stmt->bind_param("s", $name);
												$res = $stmt->execute();
												$stmt->close();
												
												$stmt = $conn->prepare("SELECT id FROM authors WHERE name = ? AND role = 'primary';");
												$res = $stmt->bind_param("s", $name);
												$res = $stmt->execute();
												$stmt->bind_result($id);
												$stmt->fetch();
												array_push($auth, $id);
												$stmt->close();
												
											}
											for ($i = $primary_authors; $i < $secondary_authors; $i++){
												$name = $json_a['authors'][$i]['secondary'];
												$stmt = $conn->prepare("INSERT INTO authors(name, role) VALUES (?,'secondary');");
												$res = $stmt->bind_param("s", $name);
												$res = $stmt->execute();
												$stmt->close();

												$stmt = $conn->prepare("SELECT id FROM authors WHERE name = ? AND role = 'secondary';");
												$res = $stmt->bind_param("s", $name);
												$res = $stmt->execute();
												$stmt->bind_result($id);
												$stmt->fetch();
												array_push($auth, $id);
												$stmt->close();
											}
											foreach ($auth as &$value){
												$stmt = $conn->prepare("INSERT INTO book_authors(story_id, author_id) VALUES(?, ?);");
												$res = $stmt->bind_param("ii", $book_id, $value);
												$res = $stmt->execute();
												$stmt->close();
											}
											$image = $json_a['cover_photo'];
											$age = $json_a['reccomended_age'];
											$stmt = $conn->prepare("UPDATE story SET story_image = ?, reccomended_age = ? WHERE story_id = ?;");
											$res = $stmt->bind_param("sii", $image, $age, $book_id);
											$res = $stmt->execute();
											$stmt->close();
											
											for ($i = 1; $i <= $number_of_characters; $i++){
												$name = $json_a['characters'][$i]['name'];
												$role = $json_a['characters'][$i]['role'];
												
												error_reporting(E_ALL & ~E_NOTICE);
												$personality = $json_a['characters'][$i]['personality'];
												$minor_description = $json_a['characters'][$i]['minor_description'];
												$stmt = $conn->prepare("INSERT INTO characters(name, role, personality, minor_description) VALUES(?, ?, ?, ?);");
												$res = $stmt->bind_param("ssss", $name, $role, $personality, $minor_description);
												$res = $stmt->execute();
												$stmt->close();
												
												$stmt = $conn->prepare("SELECT id FROM characters WHERE name = ? AND role = ?;");
												$res = $stmt->bind_param("ss",$name, $role);
												$res = $stmt->execute();
												$stmt->bind_result($id);
												$stmt->fetch();
												array_push($chars, $id);
												$stmt->close();
											}
											foreach ($chars as &$value){
												$stmt = $conn->prepare("INSERT INTO book_characters(story_id, char_id) VALUES(?, ?);");
												$res = $stmt->bind_param("ii", $book_id, $value);
												$res = $stmt->execute();
												$stmt->close();
											}
											$number_of_links = $json_a['media_links'][0]['number_of_links'];
											$links = array();
											for ($i = 1; $i <= $number_of_links; $i++){
												$url = $json_a['media_links'][$i]['url'];
												$stmt = $conn->prepare("INSERT INTO media_links(url) VALUES(?);");
												$res = $stmt->bind_param("s", $url);
												$res = $stmt->execute();
												$stmt->close();
												
												$stmt = $conn->prepare("SELECT id FROM media_links WHERE url = ?;");
												$res = $stmt->bind_param("s", $url);
												$res = $stmt->execute();
												$stmt->bind_result($id);
												$stmt->fetch();											
												array_push($links, $id);
												$stmt->close();
											}
											foreach ($links as &$value){
												$stmt = $conn->prepare("INSERT INTO book_links(story_id, media_id) VALUES(?, ?);");
												$res = $stmt->bind_param("ii", $book_id, $value);
												$res = $stmt->execute();
												$stmt->close();
											}
											$book_name = 'uploads/'.$book_id.".txt";
											$content = $json_a['story_content'];
											file_put_contents($book_name, $content);
											echo ("<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>");
											header ('Refresh: 4; URL=upload.html');
											die();
										}
									}
								}
							}
						}


						if (strcmp($fileType, "xml") == 0){
							$xml = simplexml_load_file($file_name);
							$book_title = $xml->title;
							if (empty($book_title)){
								echo "<h2> Book has no title. Cannot upload. </h2>";
								header ('Refresh: 4; URL=upload.html');
								die();
							}
							else{
								$number_of_characters = $xml->character_list->number_of_characters;
								$chars = array();
								$test = 0;
								for ($i = 1; $i <= $number_of_characters; $i++){
									$tag = "character".$i;
									$name = $xml->character_list->$tag->name;
									$role = $xml->character_list->$tag->role;
									if (empty($name)){
										$test = 1;
										break;
									}
									if (empty($role)){
										$test = 2;
										break;
									}
								}
								if ($test == 1){
									echo "<h2> One character has no name. Cannot upload. </h2>";
									header ('Refresh: 4; URL=upload.html');
									die();
								}
								else{
									if ($test == 2){
										echo "<h2> One character has no role. Cannot upload. </h2>";
										header ('Refresh: 4; URL=upload.html');
										die();
									}
									else{
										$stmt = $conn->prepare("INSERT INTO story(title, user) VALUES(?, ?);");
										$res = $stmt->bind_param("ss", $book_title, $nickname);
										$res = $stmt->execute();
										$stmt->close();
										
										$stmt = $conn->prepare("SELECT story_id FROM story WHERE title = ?;");
										$res = $stmt->bind_param("s", $book_title);
										$res = $stmt->execute();
										$stmt->bind_result($book_id);
										$stmt->fetch();
										$stmt->close();

										$authors = $xml->authors_list->number_of_authors;
										$auth = array();
										for ($i = 1; $i < $authors; $i++){
											$tag = "author".$i;
											$name = $xml->authors_list->$tag->name;
											$role = $xml->authors_list->$tag->role;
											$stmt = $conn->prepare("INSERT INTO authors(name, role) VALUES (?, ?);");
											$res = $stmt->bind_param("ss", $name, $role);
											$res = $stmt->execute();
											$stmt->close();
											
											$stmt = $conn->prepare("SELECT id FROM authors WHERE name = ? AND role = ?;");
											$res = $stmt->bind_param("ss", $name, $role);
											$res = $stmt->execute;
											$stmt->bind_result($id);
											$stmt->fetch();
											array_push($auth, $id);
											$stmt->close();
										}
										foreach ($auth as &$value){
											$stmt = $conn->prepare("INSERT INTO book_authors(story_id, author_id) VALUES(?, ?);");
											$res = $stmt->bind_param("ii", $book_id, $value);
											$res = $stmt->execute();
											$stmt->close();
										}
										$image = $xml->cover_photo;
										$age = $xml->reccomended_age;
										$stmt = $conn->prepare("UPDATE story SET story_image = ?, reccomended_age = ? WHERE story_id = ?;");
										$res = $stmt->bind_param("sii", $image, $age, $book_id);
										$res = $stmt->execute();
										$stmt->close();
										
										
										for ($i = 1; $i <= $number_of_characters; $i++){
											$tag = "character".$i;
											$name = $xml->character_list->$tag->name;
											$role = $xml->character_list->$tag->role;
											$personality = $xml->character_list->$tag->personality;
											$minor_description = $xml->character_list->$tag->minor_description;
											$stmt = $conn->prepare("INSERT INTO characters(name, role, personality, minor_description) VALUES(?, ?, ?, ?);");
											$res = $stmt->bind_param("ssss", $name, $role, $personality, $minor_description);
											$res = $stmt->execute();
											$stmt->close();
											
											$stmt = $conn->prepare("SELECT id FROM characters WHERE name = ? AND role = ?;");
											$res = $stmt->bind_param("ss", $name, $role);
											$res = $stmt->execute();
											$stmt->bind_result($id);
											$stmt->fetch();
											array_push($chars, $id);
											$stmt->close();		
										}
										foreach ($chars as &$value){
											$stmt = $conn->prepare("INSERT INTO book_characters(story_id, char_id) VALUES(?, ?);");
											$res = $stmt->bind_param("ii", $book_id, $value);
											$res = $stmt->execute();
											$stmt->close();
										}

										$number_of_links = $xml->media_links->number_of_links;
										$links = array();
										for ($i = 1; $i <= $number_of_links; $i++){
											$tag = "link".$i;
											$url = $xml->media_links->$tag->url;
											$stmt = $conn->prepare("INSERT INTO media_links(url) VALUES(?);");
											$res = $stmt->bind_param("s", $url);
											$res = $stmt->execute();
											$stmt->close();
											
											$stmt = $conn->prepare("SELECT id FROM media_links WHERE url = ?;");
											$res = $stmt->bind_param("s", $url);
											$res = $stmt->execute();
											$stmt->bind_result($id);
											$stmt->fetch();
											array_push($links, $id);
											$stmt->close();
										}
										foreach ($links as &$value){
											$stmt = $conn->prepare("INSERT INTO book_links(story_id, media_id) VALUES(?, ?);");
											$res = $stmt->bind_param("ii", $book_id, $value);
											$res = $stmt->execute();
											$stmt->close();
										}
										$book_name = 'uploads/'.$book_id.".txt";
										$content = $xml->story_content;
										file_put_contents($book_name, $content);
										echo ("<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>");
										header ('Refresh: 4; URL=upload.html');
										die();
									}
								}
							}
						}
					}
					fclose($handle);
					$conn->close();
				}
				else
					echo("<h2>Please select a file to upload.</h2>");
					header ('Refresh: 4; URL=upload.html');
					die();
			?>
		</div>
	</body>
</html>
