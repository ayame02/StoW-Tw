<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "-";
    $databaseName = "tw";
    $conn = new mysqli($servername, $username, $password, $databaseName);
    $sid = $_GET["story_id"];
    $stmt = $conn->prepare("delete from story where story_id = ?;");
    $res = $stmt->bind_param('i', $sid);
    $res = $stmt->execute();
    $stmt->close();
    $stmt = $conn->prepare("select author_id from book_authors where story_id = ?;");
    $res = $stmt->bind_param("i", $sid);
    $res = $stmt->execute();
    $stmt->bind_result($a_id);
    $authors = array();
    while ($stmt->fetch()){
        array_push($authors, $a_id);
    }
    $stmt->close();
    foreach ($authors as &$value){
        $stmt = $conn->prepare("delete from authors where id = ?;");
        $res = $stmt->bind_param('i', $value);
        $res = $stmt->execute();
        $stmt->close();
        $stmt = $conn->prepare("delete from book_authors where author_id = ?;");
        $res = $stmt->bind_param('i', $value);
        $res = $stmt->execute();
        $stmt->close();
    }
    $string = 'uploads/'.$sid.'.txt';
    unlink($string);
    echo "Deletion complete.";
?>