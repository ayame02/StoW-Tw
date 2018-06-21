<?php
    session_start();
    $sid = $_GET["story_id"];
    $text = $_GET["text"];
    $story = 'uploads/'.$sid.".txt";
    file_put_contents($story, $text);
    echo "Saved.";
?>