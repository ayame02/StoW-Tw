<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "-";
    $databaseName = "tw";
    $conn = new mysqli($servername, $username, $password, $databaseName);
    $stmt = $conn->prepare("select s.story_id, title, story_image, name from story s join book_authors b on s.story_id=b.story_id join authors a on b.author_id=a.id where role = 'primary';");
    $res = $stmt->execute();
    $stmt->bind_result($sid, $title, $s_image, $a_name);
    while ($stmt->fetch()){
        $title = preg_replace('/\s+/', '', $title);
        $string = $title.".html";
        $story = fopen($string, "w");
        fwrite($story, "<!DOCTYPE html>");
        fwrite($story, "<html>");
        fwrite($story, "<head>");
        fwrite($story, "<title> Stories on the web </title>");
        fwrite($story, "<meta charset="."UTF-8".">");
        fwrite($story, "<link rel = "."stylesheet" ." href = "."styles.css".">");
        fwrite($story, "</head>");
        fwrite($story, "<body background = "."images/mainBackground2.jpg"." class="."corp".">");
        fwrite($story, "<div class = "."upper_menu".">");
        if (isset($_SESSION["username"])){
            fwrite($story, "<a href = "."loggedIn.html".">Home</a>");
        }
        else{
            fwrite($story, "<a href = "."index.html".">Home</a>");
        }
        fwrite($story, "</div>");
        fwrite($story, "<h1 class="."titlu_poveste".">".$title."</h1>");
        fwrite($story, "<img class="."imagine"." src=".$s_image."  />");
        fwrite($story, "<div class="."poveste".">");
        fwrite($story, "<section>");
        $text = $sid.".txt";
        $st_text = fopen($text, "r");
        while (!feof($st_text)){
            fwrite($story, "<p>");
            $to_write = fgets($st_text);
            fwrite($story, $to_write);
            fwrite($story, "</p>");
            fwrite($story, "<br>");
        }
        fclose($st_text);
        fwrite($story, "</section></div>");
        fwrite($story, "<ul class="."lista_informatii".">");
        fwrite($story, "<li>Author: ".$name."</li>");
        fwrite($story, "<li>Characters: ");
        $stmt2 = $conn->prepare("select name, role, personality, minor_description from characters c join book_characters b on c.id = b.char_id where story_id = ?;");
        $res2 = $stmt2->bind_param("i", $sid);
        $res2 = $stmt2->execute();
        $stmt2->bind_result($c_name, $role, $personality, $desc);
        while ($stmt2->fetch()){
            fwrite($story, "<li>Name: ".$c_name."</li>");
            fwrite($story, "<li>Role: ".$role."</li>");
            if(!empty($personality)){
                fwrite($story, "<li>Personality: ".$personality."</li>");
            }
            if(!empty($desc)){
                fwrite($story, "<li>Minor description: ".$desc."</li>");
            }
        }
        $stmt2->close();
        fwrite($story, "</li><li>Media links: ");
        $stmt2 = $conn->prepare("select url from media_links m join book_links b on m.id = b.media_id where story_id = ?;");
        $res2 = $stmt2->bind_param("i", $sid);
        $res2 = $stmt2->execute();
        $stmt2->bind_result($url);
        while ($stmt2->fetch()){
            if(!empty($url)){
                fwrite($story, "<li>Link: ".$url."</li>");
            }
        }
        $stmt2->close();
        fwrite($story, "</li></body></html>");
    }
    echo "Complete.";
    $stmt->close();
?>