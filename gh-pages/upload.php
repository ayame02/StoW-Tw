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
				$target_dir = "uploads/";
				$uploadOk = 1;
				$username = $_SESSION["username"];
				$nickname = "";
				$sql = "SELECT nickname FROM users WHERE username = '$username'";
				$result = $db->query($sql);
				while ($row = $result->fetch_assoc()){
					$nickname = $row['nickname'];
				}
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
						die();
					}
					if (file_exists($target_file)) {
						echo ("<h2>Sorry, file already exists.</h2>");
						die();
					}

					if ($_FILES["fileToUpload"]["size"] > 500000000) {
						echo ("<h2>Sorry, your file is too large.</h2>");
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
							}
							else{
								$sql = "INSERT INTO story(title, user) VALUES('$book_title', '$nickname')";
								$title_insert = $db->query($sql);
								$sql = "SELECT story_id FROM story WHERE title = '$book_title'";
								$id_res = $db->query($sql);
								while ($row = $id_res->fetch_assoc()){
									$book_id = $row['story_id'];
								}

								$primary_authors = $json_a['authors'][0]['number_of_primary_authors'] + 2;
								$secondary_authors = $json_a['authors'][1]['number_of_secondary_authors'] + $primary_authors;
								$auth = array();
								for ($i = 2; $i < $primary_authors; $i++){
									$name = $json_a['authors'][$i]['primary'];
									$sql = "INSERT INTO authors(name, role) VALUES ('$name','primary')";
									$res = $db->query($sql);
									$sql = "SELECT id FROM authors WHERE name = '$name'";
									$res = $db->query($sql);
									while ($row = $res->fetch_assoc()){
										array_push($auth, $row['id']);
									}
								}
								for ($i = $primary_authors; $i < $secondary_authors; $i++){
									$name = $json_a['authors'][$i]['secondary'];
									$sql = "INSERT INTO authors(name, role) VALUES ('$name','secondary')";
									$res = $db->query($sql);
									$sql = "SELECT id FROM authors WHERE name = '$name'";
									$res = $db->query($sql);
									while ($row = $res->fetch_assoc()){
										array_push($auth, $row['id']);
									}
								}
								foreach ($auth as &$value){
									$sql = "INSERT INTO book_authors(story_id, author_id) VALUES($book_id, $value)";
									$result = $db->query($sql);
								}
								$image = $json_a['cover_photo'];
								$age = $json_a['reccomended_age'];
								$sql = "UPDATE story SET story_image = '$image', reccomended_age = $age WHERE story_id = $book_id";
								$res = $db->query($sql);
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
									
									error_reporting(E_ALL & ~E_NOTICE);
									$personality = $json_a['characters'][$i]['personality'];
									$minor_description = $json_a['characters'][$i]['minor_description'];
									$sql = "INSERT INTO characters(name, role, personality, minor_description) VALUES('$name','$role','$personality','$minor_description')";
									$res = $db->query($sql);
									$sql = "SELECT id FROM characters WHERE name = '$name'";
									$res = $db->query($sql);
									while ($row = $res->fetch_assoc()){
										array_push($chars, $row['id']);
									}
								}
								if ($test == 1){
									echo "<h2> One character has no name. Cannot upload. </h2>";
								}
								else{
									if ($test == 2){
										echo "<h2> One character has no role. Cannot upload. </h2>";
									}
									else{
										foreach ($chars as &$value){
											$sql = "INSERT INTO book_characters(story_id, char_id) VALUES($book_id, $value)";
											$result = $db->query($sql);
										}
										$number_of_links = $json_a['media_links'][0]['number_of_links'];
										$links = array();
										for ($i = 1; $i <= $number_of_links; $i++){
											$url = $json_a['media_links'][$i]['url'];
											$sql = "INSERT INTO media_links(url) VALUES('$url')";
											$res = $db->query($sql);
											$sql = "SELECT id FROM media_links WHERE url = '$url'";
											$res = $db->query($sql);
											while ($row = $res->fetch_assoc()){
												array_push($links, $row['id']);
											}
										}
										foreach ($links as &$value){
											$sql = "INSERT INTO book_links(story_id, media_id) VALUES($book_id, $value)";
											$result = $db->query($sql);
										}
										$book_name = 'uploads/'.$book_id.".txt";
										$content = $json_a['story_content'];
										file_put_contents($book_name, $content);
										echo ("<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>");
									}
								}
							}
						}


						if (strcmp($fileType, "xml") == 0){
							$xml = simplexml_load_file($file_name);
							$book_title = $xml->title;
							if (empty($book_title)){
								echo "<h2> Book has no title. Cannot upload. </h2>";
							}
							else{
								$sql = "INSERT INTO story(title, user) VALUES('$book_title', '$nickname')";
								$title_insert = $db->query($sql);
								$sql = "SELECT story_id FROM story WHERE title = '$book_title'";
								$id_res = $db->query($sql);
								while ($row = $id_res->fetch_assoc()){
									$book_id = $row['story_id'];
								}

								$authors = $xml->authors_list->number_of_authors;
								$auth = array();
								for ($i = 1; $i < $authors; $i++){
									$tag = "author".$i;
									$name = $xml->authors_list->$tag->name;
									$role = $xml->authors_list->$tag->role;
									$sql = "INSERT INTO authors(name, role) VALUES ('$name','$role')";
									$res = $db->query($sql);
									$sql = "SELECT id FROM authors WHERE name = '$name'";
									$res = $db->query($sql);
									while ($row = $res->fetch_assoc()){
										array_push($auth, $row['id']);
									}
								}
								foreach ($auth as &$value){
									$sql = "INSERT INTO book_authors(story_id, author_id) VALUES($book_id, $value)";
									$result = $db->query($sql);
								}
								$image = $xml->cover_photo;
								$age = $xml->reccomended_age;
								$sql = "UPDATE story SET story_image = '$image', reccomended_age = $age WHERE story_id = $book_id";
								$res = $db->query($sql);

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
									$personality = $xml->character_list->$tag->personality;
									$minor_description = $xml->character_list->$tag->minor_description;
									$sql = "INSERT INTO characters(name, role, personality, minor_description) VALUES('$name','$role','$personality','$minor_description')";
									$res = $db->query($sql);
									$sql = "SELECT id FROM characters WHERE name = '$name'";
									$res = $db->query($sql);
									while ($row = $res->fetch_assoc()){
										array_push($chars, $row['id']);
									}
								}
								if ($test == 1){
									echo "<h2> One character has no name. Cannot upload. </h2>";
								}
								else{
									if ($test == 2){
										echo "<h2> One character has no role. Cannot upload. </h2>";
									}
									else{
										foreach ($chars as &$value){
											$sql = "INSERT INTO book_characters(story_id, char_id) VALUES($book_id, $value)";
											$result = $db->query($sql);
										}

										$number_of_links = $xml->media_links->number_of_links;
										$links = array();
										for ($i = 1; $i <= $number_of_links; $i++){
											$tag = "link".$i;
											$url = $xml->media_links->$tag->url;
											$sql = "INSERT INTO media_links(url) VALUES('$url')";
											$res = $db->query($sql);
											$sql = "SELECT id FROM media_links WHERE url = '$url'";
											$res = $db->query($sql);
											while ($row = $res->fetch_assoc()){
												array_push($links, $row['id']);
											}
										}
										foreach ($links as &$value){
											$sql = "INSERT INTO book_links(story_id, media_id) VALUES($book_id, $value)";
											$result = $db->query($sql);
										}
										$book_name = 'uploads/'.$book_id.".txt";
										$content = $xml->story_content;
										file_put_contents($book_name, $content);
										echo ("<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>");
									}
								}
							}
						}
					}
					fclose($handle);
				}
				else
					echo("<h2>Please select a file to upload.</h2>");
			?>
		</div>
	</body>
</html>
