<!DOCTYPE html>
<html lang="en">

<head>
    <title>Stories on the web</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="calendar_desktop.js"></script>
    <script type="text/javascript" src="mobile_calendar.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <img src="images/logo.png" class="logo">
    <div class="upper_menu">
        <?php
            session_start();
            if(ISSET($_SESSION['username'])){
                echo "<a href="."upload.html".">Upload</a>";
            }
            else{
                echo "<a href="."login.html".">Login</a>";
                echo "<a href="."signUp.html".">Sign Up</a>";
            }
        ?>
    </div>
    <div class="sidenav">
        <section>
            <p class="sidenav_bullet">Stories from around the world.</p>
                <?php
                    $dir = new DirectoryIterator("uploads/");
                    $servername = "localhost";
                    $username = "root";
                    $password = "-";
                    $databaseName = "tw";
				    $conn = new mysqli($servername, $username, $password, $databaseName);
                    foreach ($dir as $fileinfo) {
                        if (!$fileinfo->isDot()) {
                            $story = $fileinfo->getFilename();
                            $path_parts = pathinfo($story);
                            $name = (int)$path_parts['filename'];
                            $title = "";
                            $nickname = "";
                            $stmt = $conn->prepare("SELECT title, nickname FROM story WHERE story_id = ?;");
                            $res = $stmt->bind_param("i", $name);
                            $res = $stmt->execute();
                            $stmt->store_result();
                            $stmt->bind_result($title, $nickname);
                            $stmt->fetch();
                            $string = "<p class=";
                            $string1 = "stories";
                            $string2 = ">";
                            $string3 = "<a href=";
                            $string4 = ".html";
                            $string7 = ">";
                            $string5 = "</a>, uploaded by [";
                            $string6 = "]. </p>";
                            echo $string.$string1.$string2.$string3.$name.$string4.$string7.$title.$string5.$nickname.$string6;
                            $stmt->close();
                        }
                    }
                ?>
        </section>
        <br>
        <p class="sidenav_bullet"></p>
        <div id="calendar_desktop"></div>
        <div id="calendar_mobile">
            <p id="mobile_calendar-day"></p>
            <p id="mobile_calendar-date"></p>
            <p id="mobile_calendar-month-year"></p>
        </div>
    </div>
    <form action="search.php" method="POST" class = "search-container">
        <input type="text" placeholder="Search" name = "search_text">
        <button class = "search-container-image" type="submit"><img src = "images/search_btn.png"></button>
    </form>
    <section class="site_content">
        <?php
            $search = $_POST['search_text'];
            $var = 1;
            if (isset($_POST['search_text'])){
                
                $stmt = $conn->prepare("select story_id, story_image, title from story where LOWER(title) = LOWER(?);");
                if ($stmt){
                    $var = 0;
                    $res = $stmt->bind_param("s", $search);
                    $res = $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($s_id, $string3, $title);
                    $stmt->fetch();
                    $string = "<div><div class=";
                    $string1 = "story";
                    $string2 = "><img src=";
                    $string4 = " class=";
                    $string5 = "story_image";
                    $string6 = "><a href=";
                    $string7 = str_replace(' ','', $title);
                    $string8 = ".html";
                    $string9 = " class=";
                    $string10 = "story_title";
                    $string11 = ">".$title."</a>";
                    echo $string.$string1.$string2.$string3.$string4.$string5.$string6. $string7.$string8.$string9.$string10.$string11;
                    $stmt->close();
                    $stmt = $conn->prepare("select author_id from book_authors where story_id = ?;");
                    $res = $stmt->bind_param("i", $s_id);
                    $res = $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($a_id);
                    $stmt->fetch();
                    $stmt->close();
                    $stmt = $conn->prepare("select name from authors where role = 'primary' and id = ?;");
                    $res = $stmt->bind_param("i", $a_id);
                    $res = $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($string14);
                    while ($stmt->fetch()){
                        $string12 = "story_author";
                        $string13 = "> By ";
                        $string15 = "</p>";
                        echo "<p class=".$string12.$string13.$string14.$string15;
                    }
                }
                $stmt->close();
                $stmt = $conn->prepare("select id, name from authors where LOWER(name) = LOWER(?);");
                if ($stmt){
                    $var = 0;
                    $res = $stmt->bind_param("s", $search);
                    $res = $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($a_id, $name);
                    while ($stmt->fetch()){
                        $stmt2 = $conn->prepare("SELECT title, story_image from story WHERE story_id = (select story_id from book_authors where author_id = ?);");
                        $res = $stmt2->bind_param("i", $a_id);
                        $res = $stmt2->execute();
                        $stmt2->store_result();
                        $stmt2->bind_result($title, $string3);
                        if($stmt2->fetch()){
                            $string = "<div><div class=";
                            $string1 = "story";
                            $string2 = "><img src=";
                            $string4 = " class=";
                            $string5 = "story_image";
                            $string6 = "><a href=";
                            $string7 = str_replace(' ','', $title);
                            $string8 = ".html";
                            $string9 = " class=";
                            $string10 = "story_title";
                            $string11 = ">".$title."</a><p class=";
                            $string12 = "story_author";
                            $string13 = "> By ";
                            $string14 = $name."</p>";
                            echo $string.$string1.$string2.$string3.$string4.$string5.$string6.$string7.$string8.$string9.$string10.$string11.$string12.$string13.$string14;
                        }
                    }
                }
                if ($var == 1){
                    echo ("No story was found.");
                }
            }
            else {
                echo ("No search words inserted."); 
            }
        ?>
    </section>
</body>

</html>