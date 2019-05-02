<?php

session_start();

// $db = new PDO("mysql:dbname=todo;host=localhost",'root','');
// CONNECT TO LOCAL SERVER
$conn = mysqli_connect("localhost","root","") or die(mysqli_error($conn));
// CHECK AVAILABLE DATABASE QUERY
$check = mysqli_query($conn, 'SHOW DATABASES');

while($row = mysqli_fetch_array($check)){
	// SHOW AVIALABLE DATABASE
	// echo $row[0]."<br/>";
	if($row[0] != "todo"){
		// CREATE DATABASE TODO IF THE DATABASE IS NOT AMONG AVAILABLE ONE
		$result = mysqli_query($conn, "CREATE DATABASE todo");		
	}
}
// SELECT TODO DATABASE
mysqli_select_db($conn, 'todo');		
// echo "<br/>";
// SHOW AVAILABLE TABLES IN TODO DATABASE
$result = mysqli_query($conn, "SHOW TABLES");

if(mysqli_num_rows($result) == 0){
	// CREATE TABLES IF NO TABLE EXIST
	// TABLE items
	$tables = "CREATE TABLE items (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name varchar (250) NOT NULL,
		user int NOT NULL,
		done tinyint NOT NULL,
		created timestamp NOT NULL
	);";
	// TABLE user
	$tables .= "CREATE TABLE user (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		username varchar (250) NOT NULL,
		password varchar (250) NOT NULL
	)";
	
		$table = mysqli_multi_query($conn, $tables) or die(mysqli_error($conn));
}
