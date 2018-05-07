<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" href="styles.css">
	</head>
	<body>
		<div class="redirecting_content">
            <h2>
                <?php
                    session_start();
                    include ('databaseConn.php');
                    $db = new Database();
                    $search = $_POST['search_text'];
                    $var = 1;
                    if (isset($_POST['search_text'])){
                        $search_querry = "select title, main_author from story";
                        $SearchResult = $db->query($search_querry);
                        while ($row = $SearchResult->fetch_assoc()){
                            if ($search == $row['title']){
                                echo ("Story goes here.");
                                $var = 2;
                            }
                            if ($search == $row['main_author']){
                                echo ("Story goes here.");
                                $var = 2;
                            }
                        }
                        $search_querry2 = "select * from story where secondary_authors LIKE $search";
                        $SearchResult2 = $db->query($search_querry2);
                        while ($row = $SearchResult2->fetch_assoc()){
                                echo ($row['title']);
                                $var = 2;
                        }
                        if ($var == 1){
                            echo ("No story was found.");
                            header ('Refresh: 2; URL=index.html');
                        }
                    }
                    else {
                        echo ("No search words inserted.");
                        header ('Refresh: 2; URL=index.html'); 
                    }
                ?>
            </h2>
		</div>
	</body>
</html>