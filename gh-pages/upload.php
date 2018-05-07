<html>
	<head>
		<meta http-equiv = "refresh" content = "2; url = test.html">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
		<div id="redirecting_content">
			<?php
				$target_dir = "uploads/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				if (isset($_POST["radio"])){
					if($_POST["radio"] == "modify")
						//do stuff
					if (file_exists($target_file)) {
						echo "<h2>Sorry, file already exists.</h2>";
						$uploadOk = 0;
					}

					if ($_FILES["fileToUpload"]["size"] > 500000) {
						echo "<h2>Sorry, your file is too large.</h2>";
						$uploadOk = 0;
					}

					if($fileType != "doc" && $fileType != "docx" && $fileType != "txt" && $fileType != "docm" && $fileType != "gdoc") {
						echo "<h2>Sorry, only DOC, DOCX, DOCM, TXT and GDOC files are allowed.</h2>";
						$uploadOk = 0;
					}

					if ($uploadOk == 0) {
						echo "<h2>Sorry, your file was not uploaded.</h2>";
					} else {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							echo "<h2>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</h2>";
						} else {
							echo "<h2>Sorry, there was an error uploading your file.</h2>";
						}
					}
				}
				else
					echo("Please select a type for the upload.");
			?>
		</div>
	</body>
</html>
