<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "-";
    $databaseName = "tw";
    $conn = new mysqli($servername, $username, $password, $databaseName);
    $user = $_SESSION["username"];
    $stmt = $conn->prepare("select title, story_image, description, name from story s join book_authors b on s.story_id=b.story_id join authors a on b.author_id=a.id where user = ? and role = 'primary';");
    $res = $stmt->bind_param('s', $user);
    $res = $stmt->execute();
    $res = $stmt->get_result();
    $story = array();
    while ($row = $res->fetch_assoc()){
        $story[] = $row;
    }
    echo json_encode($story);
    $stmt->close();
?>