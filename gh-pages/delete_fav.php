<?php

	session_start();

    include ('databaseConn.php');
    $db = new Database();
    $db->delete_fav($_SESSION["username"]);
    header("Location: profile.php");

?>