<?php

	session_start();
	$_SESSION['firstName'] = $_POST["firstName"];
	$_SESSION['lastName'] = $_POST["lastName"];
	$_SESSION['nickname'] = $_POST["nickname"];
	$_SESSION['family'] = $_POST["family"];

	echo $_SESSION['firstName'];

    include ('databaseConn.php');
    $db = new Database();
    $db->update_profile($_SESSION["username"],$_SESSION['lastName'] ,$_SESSION['firstName'] , $_SESSION['nickname']);
    $db->set_family($_SESSION["username"],$_SESSION['family']);


?>