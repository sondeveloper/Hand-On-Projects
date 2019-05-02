<?php
// REQUIRE SESSION AND DATABASE CONNECTION
require_once 'init.php';
// SET DEFAULT ERROR MSG
$errmsg = "";
// SET INVALID USER ID
$id = 0;
// FUNCTION TO CLEAN DATA
function clean($data){
		$n = trim($data);
		$n = stripslashes($n);
		$n = htmlspecialchars($n);
		return $n;
	}

//VALIDATE AND PROCESS LOGIN FORM
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){

	if(!empty($_REQUEST['usrname']) && !empty($_REQUEST['passwd'])){
		// CLEAN USER DATA
		$usr = clean($_POST['usrname']);
		$pswd = clean($_POST['passwd']);
		// FETCH USER INFO FROM DATABASE TO CHECK LOGIN DETAIL
		// $loginQuery = $db->prepare("SELECT id, username, password FROM user WHERE username = :name AND password = :pass");
		$loginQuery = mysqli_query($conn, "SELECT id, username, password FROM user WHERE username = '$usr' AND password = '$pswd'");
		// $loginQuery->execute([
		// 	'name' => $usr,
		// 	'pass' => $pswd
		// ]);

		// $results = $loginQuery->rowCount() ? $loginQuery : [];
		$results = mysqli_num_rows($loginQuery) ? $loginQuery : [];

		foreach ($results as $output){
			$id = $output['id'];
		}
		// CHECK IF USER ID HAS BEEN SET
		if($id != 0){
			$_SESSION['id'] = $id;
			header("Location: index.php");
		// ERROR MSG FOR INVALID LOGIN DETAIL
		}else{
			$errmsg = "Login details not valid";
		}
	}
}
// VALIDATE AND PROCESS REGISTER FORM
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){

	$usr = clean($_POST['usrname']);
	$pswd = clean($_POST['passwd']);
	$cpswd = clean($_POST['cpasswd']);
	// CHECK IF USERNAME ALREADY EXIST
	// $existQuery = $db->prepare("SELECT username FROM user WHERE username = :urname");
	// $existQuery->execute(['urname' => $usr]);
	// $res = $existQuery->rowCount() ? $existQuery : [];
	$existQuery = mysqli_query($conn, "SELECT username FROM user WHERE username = '$usr'");
	$res = mysqli_num_rows($existQuery) ? $existQuery : [];
	$u = "";
	foreach($res as $out){
		$u = $out['username'];
	}
	// CHECK FOR EXISTING USERNAME
	if($u != $usr){
	// CHECK IF CONFIRM PASSWORD MATCH
	if($pswd == $cpswd){
		// INSERT USER INFO INTO DATABASE
		// $registerQuery = $db->prepare("INSERT INTO user (username, password) VALUES ( :usr, :pswd)");
		// $registerQuery->execute(
		// 	[
		// 		'usr' => $usr,
		// 		'pswd' => $cpswd
		// 	]);
		$registerQuery = mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$usr','$cpswd')");
		// MSG FOR SUCCESSFUL REGISTRATION
		$errmsg = 'Registration Successful. <a href="login.php">Login</a> to use To-Do-List.';
		// ERROR MSG FOR MISMATCH PASSWORD
	}else{
		$errmsg = "Confirmation Password Does not match";
	}
	// ERROR MSG FOR EXISTING USER
	}else{
		$errmsg = "Username already exist.";
	}

}
// VALIDATE AND PROCESS RESET FORM
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset'])){
	$usr = clean($_POST['resetusrname']);
	$pswd = clean($_POST['resetpasswd']);
	$cpswd = clean($_POST['resetcpasswd']);

	// FETCH USERNAME FROM DATABASE
	// $checkuser = $db->prepare("SELECT username FROM user WHERE username = :usnm");

	// $checkuser->execute(
	// 	[
	// 		'usnm' => $usr
	// 	]);
	// $checkresult = $checkuser->rowCount() ? $checkuser : [];
	$checkuser = mysqli_query($conn, "SELECT username FROM user WHERE username = '$usr'");
	$checkresult = mysqli_num_rows($checkuser) ? $checkuser : [];
	$chname = '';
	foreach ($checkresult as $res) {
		$chname = $res['username'];
	}
	// CHECK IF USERNAME EXIST
	if($usr == $chname){
		if($pswd == $cpswd){
			// UPDATE USER DATA
			// $registerQuery = $db->prepare("UPDATE user SET password = :pswd WHERE username = :usr ");
			// $registerQuery->execute(
			// 	[
			// 		'pswd' => $pswd,
			// 		'usr' => $usr
			// 	]);
			$registerQuery = mysqli_query($conn, "UPDATE user SET password = '$pswd' WHERE username = '$usr'");
				// MSG FOR SUCCESSFUL PASSWORD RESET
				$errmsg = 'Password reset Successful. <a href="login.php">Login</a> to use To-Do-List.';			
			
	 	// ERROR MSG FOR -- MISMATCH PASSWORD
		}else{
			$errmsg = "Confirmation Password Does not match - reset";
		}
	 	// ERROR MSG FOR -- USER DOES NOT EXIST --
	}else{
		$errmsg = "Username does not exist. Please Register again";
	}
}

if(isset($_GET['out'])){
	session_unset();
	session_destroy();
	header("Location: login.php");
}

?>
<!-- HTML SECTION -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="mainstyle.css">
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<title>Login Page</title>
</head>
<body>
	<!--FORM Header for LOGIN / REGISTER / RESET page -->
	<div class="toplink">
		<h1 class="header"> To-Do-List</h1>
		<a href="index.php?out=logout"><?php echo isset($_GET['reg']) ? 'Already registered? Login here' : '<a href="login.php?reg=register" class="text-right">Not registered.  Register Here</a>' ?></a>
	</div>
	<!-- LOGIN FORM PAGE -->
	<div class="login">
		<!--FORM SUB HEADER -->
		<?php if(!isset($_GET['reset'])): ?>
		<h1 class="header"><?php echo isset($_GET['reg']) ? 'Register' : 'Login Page'?></h1>
		<?php else: ?>
			<h1 class="header">Reset password</h1>
		<?php endif; ?>

		<!-- LOGIN FORM -->
		<?php if(!isset($_GET['reg']) && !isset($_GET['reset'])): ?>
			<form class="logform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<div class="errmsg"><?php echo !empty($errmsg)? $errmsg : "" ?></div>
				<div class="form-group">
					<label for="usrname">Username</label>
					<input type="text" class="form-control" name="usrname" placeholder="Username" required>
				</div>
				<div class="form-group">
					<label for="passwd">Password</label>
					<input type="password" class="form-control" name="passwd" placeholder="Password" required>
				</div>			

				<input type="submit" class="btn btn-default form-control" value="Login" name="login">
			</form>
			<!-- RESET LINKS -->
			<div class="resetlink">
				<div class="row">
					<div class="col-sm-6">
						<a href="login.php?reset=password" class="text-left">Forgot password</a>
					</div>					
				</div>			
				
			</div>
		<?php endif; ?>

		<!-- REGISTER FORM -->
		<?php if(isset($_GET['reg'])): ?>
			
			<form class="logform" method="post" action="login.php?reg=register">
				<div class="errmsg"><?php echo !empty($errmsg)? $errmsg : "" ?></div>

				<div class="form-group">
					<label for="usrname">Username</label>
					<input type="text" class="form-control" name="usrname" placeholder="Username" value="<?php echo isset($_POST['usrname']) ? $_POST['usrname'] : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="passwd">Password</label>
					<input type="password" class="form-control" name="passwd" placeholder="Password" value="<?php echo isset($_POST['passwd']) ? $_POST['passwd'] : ''; ?>" required>
				</div>	

				<div class="form-group">
					<label for="cpasswd">Confirm Password</label>
					<input type="password" class="form-control" name="cpasswd" placeholder="Confirm Password" value="<?php echo isset($_POST['cpasswd']) ? $_POST['cpasswd'] : '';  ?>" required>
				</div>			

				<input type="submit" class="btn btn-default form-control" value="Register" name="register">
			</form>
		<?php endif; ?>

		<!-- RESET FORM -->
		<?php if(isset($_GET['reset'])): ?>
			
			<form class="logform" method="post" action="login.php?reset=password">
				<div class="errmsg"><?php echo !empty($errmsg)? $errmsg : "" ?></div>

				<div class="form-group">
					<label for="resetusrname">Username</label>
					<input type="text" class="form-control" name="resetusrname" placeholder="Username" value="<?php echo isset($_POST['resetusrname']) ? $_POST['resetusrname'] : ''; ?>" required>
				</div>
				<div class="form-group">
					<label for="resetpasswd">Password</label>
					<input type="password" class="form-control" name="resetpasswd" placeholder="Password" value="<?php echo isset($_POST['resetpasswd']) ? $_POST['resetpasswd'] : ''; ?>" required>
				</div>	

				<div class="form-group">
					<label for="resetcpasswd">Confirm Password</label>
					<input type="password" class="form-control" name="resetcpasswd" placeholder="Confirm Password" value="<?php echo isset($_POST['resetcpasswd']) ? $_POST['resetcpasswd'] : '';  ?>" required>
				</div>			

				<input type="submit" class="btn btn-default form-control" value="Reset Password" name="reset">
			</form>
		<?php endif; ?>

	</div>

</body>
</html>