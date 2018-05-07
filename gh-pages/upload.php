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
				$target_dir = "uploads/";
				$uploadOk = 1;
				if (isset($_POST["fileToUpload"])){
					
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					if($fileType != "json" || $fileType != "XML") {
						echo ("<h2>Sorry, only JSON, XML files are allowed.</h2>");
						die();
					}
					if (file_exists($target_file)) {
						echo ("<h2>Sorry, file already exists.</h2>");
						die();
					}

					if ($_FILES["fileToUpload"]["size"] > 500000) {
						echo ("<h2>Sorry, your file is too large.</h2>");
						die();
					}

					if ($uploadOk != 0) {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							echo ("<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>");
							die();
						} else {
							echo ("<h2>Sorry, there was an error uploading your file.</h2>");
							die();
						}
					}
				}
				else
					echo("Please select a type for the upload.");
			?>
		</div>
	</body>
</html>
