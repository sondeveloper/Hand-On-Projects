<?php
	
require_once "config.php";

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reset'])){
	// GET USER INPUT VALUE
	$usname = clean($_POST['usname']);
	$respass = clean($_POST['regpas']);
	$crespas = clean($_POST['cregpas']);
	// CHECK IF USERNAME EXIST IN DATABASE
	$resetQuery = $db->prepare("SELECT * FROM users WHERE username = :usnm");
	$resetQuery->execute([
		'usnm' => $usname
	]);

	if($resetQuery->rowCount()){
		if($respass === $crespas)
		{
			// USER EXIST; UPDATE USER PASSWORD
			$updateQuery = $db->prepare("UPDATE users SET password = :pass WHERE username = :usnam");
			$updateQuery->execute([
				'pass' => $respass,
				'usnam' =>$usname 
			]);

			// SUCCESSFUL RESET
			$resetmsg = "Password reset successfull";
		}
		else 
		{
			$resetmsg = "Mismatch Password";
		}
	} 
	else 
	{
		// USER DOES NOT EXIST
		$resetmsg = "User does not exist!";
	}

}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Simple Transaction App</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">	
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<!-- CONTAINER -->
	<div class="container">
		<!-- HEADER -->
		<nav class="navbar navbar-inverse">
			<div class="navbar-brand">My Simple Bank</div>
		</nav>
		<!-- MAIN CONTENT CONTAINER -->
		<div class="logincontainer">
			<!-- PANEL-->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Reset Password</h4>
				</div>
				<div class="panel-body">
					<h5><label>Reset your password</label></h5>
					
						<!-- LOGIN USER -->
							<span class="reglink pull-right"><a href="login.php">Already registered? Login here</a></span>				
						<br/>
						
						<!--RESET MESSAGE -->
						<div class="bg-warning text-danger"><?php echo isset($resetmsg) ? $resetmsg : "" ?></div>	<br/>
						<!-- RESET FORM -->
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<!-- INPUT USERNAME -->
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">
										Username
									</span>
									<input type="text" class="form-control" name="usname" value="<?php echo isset($_POST['usname']) ? $_POST['usname'] : ''; ?>" required>
								</div>
							</div>
							<!-- INPUT PASSWORD -->
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">
										Password
									</span>
									<input type="password" class="form-control" name="regpas" value="<?php echo isset($_POST['regpas']) ? $_POST['regpas'] : ''; ?>" required>
								</div>
							</div>
							<!-- INPUT CONFIRM PASSWORD -->
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">
										Confirm Password
									</span>
									<input type="password" class="form-control" name="cregpas" value="<?php echo isset($_POST['cregpas']) ? $_POST['cregpas'] : ''; ?>" required>
								</div>
							</div>
							<!-- SUBMIT / RESET BUTTON -->
							<input type="submit" class="btn btn-primary btn-lg pull-right" name="reset" value="Reset">
						</form>
					
				</div><!-- CLOSE PANEL BODY -->
			</div><!-- CLOSE PANEL -->
		</div><!-- CLOSE MAIN CONTENT -->
	</div><!-- CLOSE CONTAINER -->
	
	<script src="jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>