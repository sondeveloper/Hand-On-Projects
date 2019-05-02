<?php 
	
	require_once 'init.php';

	function clean($data){
		$n = trim($data);
		$n = stripslashes($n);
		$n = htmlspecialchars($n);
		return $n;
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){

		$name = clean($_POST['name']);

		// $addQuery = $db->prepare("INSERT INTO items (name, user, done, created) VALUES (:name, :user, 0, NOW())");

		// $addQuery->execute(
		// 	[
		// 		'name' => $name,
		// 		'user' => $_SESSION['id']
		// 	]);
		$addstring = "INSERT INTO items (name, user, done, created) VALUES ('$name', '".$_SESSION['id']."', 0, '".date("y/m/d h:i:sa")."')";
		$addQuery = mysqli_query($conn, $addstring);

		header("Location: index.php");
	}

?>