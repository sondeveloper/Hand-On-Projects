<?php
// START SESSION
session_start();
// CONNECT TO LOCAL SERVER
$db = new PDO("mysql:host=localhost",'root','');
// CHECK AVAILABLE DATABASE QUERY
$dataQuery = $db->prepare("SHOW DATABASES");
$dataQuery->execute();

foreach($dataQuery as $data){
	// SHOW AVAILABLE DATABASE
	// echo $data[0]."<br/>";
	
	// IF u508_mbank DATABASE DOES NOT EXIST CREATE THE DATABASE
	if($data[0] != "u508_mbank"){
		$createData = $db->prepare("CREATE DATABASE u508_mbank");
		$createData->execute();
	}
}
// SELECT DATABASE u508_mbank
try
{
	$db = new PDO("mysql:dbname=u508_mbank;host=localhost",'root','');
}
catch(PDOException $e)
{
	echo $e;
}

// SHOW AVAILABLE TABLE IN THIS DATABASE
$tableQuery = $db->prepare("SHOW TABLES");
$tableQuery->execute();
// CHECK NUMBER OF TABLE IN THIS DATABASE
if($tableQuery->rowCount() == 0){
// CREATE TABLES IF NO TABLE IS AVAILABLE

// TABLE USER===========================================
$userstring = "
	CREATE TABLE users (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		first_name varchar(250) NOT NULL,
		last_name varchar(250) NOT NULL,
		datecreated timestamp NOT NULL,
		balance int NOT NULL,
		account_no varchar(250) NOT NULL,
		cus_id text NOT NULL,
		username varchar(250) NOT NULL,
		password varchar(250) NOT NULL,
		photo varchar(250) NOT NULL
	)";

$usertable = $db->prepare($userstring);
$usertable->execute();
// TABLE REGISTER=======================================
$registerstring = "
	CREATE TABLE register (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		first_name varchar(250) NOT NULL,
		last_name varchar(250) NOT NULL,
		datecreated datetime NOT NULL,
		balance int NOT NULL,
		account_no varchar(250) NOT NULL,
		cus_id text NOT NULL
	)";
$registertable = $db->prepare($registerstring);
$registertable->execute();
// TABLE CREDIT========================================
$creditstring = "
	CREATE TABLE credit (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		cus_id text NOT NULL,
		balance int NOT NULL,
		amount int NOT NULL,
		creditdate datetime NOT NULL
	)";
$credittable = $db->prepare($creditstring);
$credittable->execute();
// TABLE DEBIT===========================================
$debitstring = "
	CREATE TABLE debit (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
		cus_id text NOT NULL,
		balance int NOT NULL,
		amount int NOT NULL,
		creditdate datetime NOT NULL
	)";

$debittable = $db->prepare($debitstring);
$debittable->execute();

}
// DELIBRATE ALTERING OF TABLE DEBIT COLUMN
$debitalter = "ALTER TABLE debit CHANGE creditdate debitdate datetime NOT NULL";
$debitalterQuery = $db->prepare($debitalter);
$debitalterQuery->execute();

// CLEAN LOGIN DETAILS
function clean($data){
	$n = trim($data);
	$n = stripslashes($data);
	$n = htmlspecialchars($data);
	return $n;
}
 

?>