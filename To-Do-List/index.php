<?php

require_once 'init.php';

if(!isset($_SESSION['id'])){
	header("Location: login.php");
}

// $listQuery = $db->prepare("SELECT * FROM items WHERE user = :id");
// $listQuery->execute(['id' => $_SESSION['id']]);
// $results = $listQuery->rowCount() ? $listQuery : [];
$liststring = "SELECT * FROM items WHERE user = '".$_SESSION['id']."'";
$listQuery = mysqli_query($conn, $liststring);
$results = mysqli_num_rows($listQuery) ? $listQuery : [];

// $nameQuerys = $db->prepare("SELECT * FROM user WHERE id = :id");
// $nameQuerys->execute(['id' => $_SESSION['id']]);
// $usrname = $nameQuerys->rowCount() ? $nameQuerys : [];
$namestring = "SELECT * FROM user WHERE id = '".$_SESSION['id']."'";
$nameQuerys = mysqli_query($conn, $namestring);
$usrname = mysqli_num_rows($nameQuerys) ? $nameQuerys : [];

$n = '';

foreach($usrname as $uname){
	$n = $uname['username'];
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="mainstyle.css">
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<title>TodoList</title>
</head>
<body>

	<div class="toplink">
		
			<h1 class="header"><?php echo $n; ?> To-Do-List</h1>
		
		<a href="login.php?out=logout">Logout</a>
	</div>
	
	<div class="list">
		
			<h3 class="header"><?php echo empty($results) ? 'No item in your list' : 'List of task to accomplish' ?></h3>
					
		<!-- List of item -->
		<ul class="items">
			<?php foreach($results as $output): ?>
				<li>
					<span class="item<?php echo $output['done'] ? ' done' : ''; ?>"><?php echo $output['name']; ?></span>					
					<br>
					<?php if(!$output['done']): ?>
						<a class="done-button" href="mark.php?as=done&itemid=<?php echo $output['id']; ?>">mark as done</a>
					<?php endif; ?>
					<span class="create">Created: <?php echo $output['created'] ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<form class="item-add" method="POST" action="add.php">
			<input class="input" type="text" name="name" placeholder="Add new list" required>
			<input class="submit" type="submit" name="submit" value="Add Item">
		</form>
	</div>
</body>
</html>