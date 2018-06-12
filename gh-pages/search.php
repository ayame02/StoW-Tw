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
                echo "<a href="."profile.php".">Profile</a>";
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
                    include ('databaseConn.php');
                    $db = new Database();
                    $dir = new DirectoryIterator("uploads/");
                    foreach ($dir as $fileinfo) {
                        if (!$fileinfo->isDot()) {
                            $story = $fileinfo->getFilename();
                            $path_parts = pathinfo($story);
                            $name = (int)$path_parts['filename'];
                            $title = "";
                            $nickname = "";
                            $sql = "SELECT title, user FROM story WHERE story_id = $name";
                            $result = $db->query($sql);
                            while ($row = $result->fetch_assoc()){
                                $title = $row['title'];
                                $nickname = $row['user'];
                            }
                            $string = "<p class=";
                            $string1 = "stories";
                            $string2 = ">";
                            $string3 = "<a href=";
                            $string4 = ".html";
                            $string7 = ">";
                            $string5 = "</a>, uploaded by [";
                            $string6 = "]. </p>";
                            echo $string.$string1.$string2.$string3.$name.$string4.$string7.$title.$string5.$nickname.$string6;
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
                $var = 0;
                $search_querry = "select story_id, story_image, title from story where LOWER(title) = LOWER('$search')";
                $SearchResult = $db->query($search_querry);
                if ($SearchResult){
                    while ($row = $SearchResult->fetch_assoc()){
                        $s_id = $row['story_id'];
                        $string = "<div><div class=";
                        $string1 = "story";
                        $string2 = "><img src=";
                        $string3 = $row['story_image'];
                        $string4 = " class=";
                        $string5 = "story_image";
                        $string6 = "><a href=";
                        $string7 = $row['title'];
                        $string8 = ".html";
                        $string9 = " class=";
                        $string10 = "story_title";
                        $string11 = ">".$string7."</a>";
                        echo $string.$string1.$string2.$string3.$string4.$string5.$string6.$string7.$string8.$string9.$string10.$string11;
                        $sql = "select author_id from book_authors where story_id = $s_id";
                        $result = $db->query($sql);
                        if ($result){
                            while ($row3 = $result->fetch_assoc()){
                                $a_id = $row3['author_id'];
                                $sql1 = "select name from authors where role = 'Primary' and id = $a_id";
                                $res = $db->query($sql1);
                                if ($res){
                                    while ($row1 = $res->fetch_assoc()){
                                        $string12 = "story_author";
                                        $string13 = "> By ";
                                        $string14 = $row1['name'];
                                        $string15 = "</p>";
                                        echo "<p class=".$string12.$string13.$string14.$string15;
                                    }
                                }
                            }
                        }                        
                    }
                }
                $search_querry2 = "select id, name from authors where LOWER(name) = LOWER('$search')";
                $SearchResult2 = $db->query($search_querry2);
                if ($SearchResult2){
                    $var = 0;
                    while ($row = $SearchResult2->fetch_assoc()){
                        $a_id = $row['id'];
                        $name = $row['name'];
                        $sql = "SELECT title, story_image from story WHERE story_id = (select story_id from book_authors where author_id = $a_id)";
                        $res = $db->query($sql);
                        if($res){
                            while($row1 = $res->fetch_assoc()){
                                $string = "<div><div class=";
                                $string1 = "story";
                                $string2 = "><img src=";
                                $string3 = $row['story_image'];
                                $string4 = " class=";
                                $string5 = "story_image";
                                $string6 = "><a href=";
                                $string7 = $row['title'];
                                $string8 = ".html";
                                $string9 = " class=";
                                $string10 = "story_title";
                                $string11 = ">".$string7."</a><p class=";
                                $string12 = "story_author";
                                $string13 = "> By ";
                                $string14 = $name."</p>";
                                echo $string.$string1.$string2.$string3.$string4.$string5.$string6.$string7.$string8.$string9.$string10.$string11.$string12.$string13.$string14;
                            }
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