<?php

require_once 'init.php';

if(isset($_GET['as'], $_GET['itemid'])){

	$itemid = $_GET['itemid'];
	$usrid = $_SESSION['id'];

	// $doneQuery = $db->prepare("UPDATE items SET done = 1 WHERE id = :item AND user = :uid");
	// $doneQuery->execute(
	// 	[
	// 		'item' => $itemid,
	// 		'uid' => $usrid
	// 	]);
	$donestring = "UPDATE items SET done = 1 WHERE id = '$itemid' AND user = '$usrid'";
	$doneQuery = mysqli_query($conn, $donestring);

	header("Location: index.php");

}

?>