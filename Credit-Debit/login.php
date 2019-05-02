<?php

require_once 'config.php';

// MESSAGE VARIABLE
$message = "";
// CHECK IF LOGIN BUTTON IS CLICKED
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
	$usr = clean($_POST['username']);
	$passwd = clean($_POST['password']);
	// CHECK LOGIN DETAIL WITH DATABASE
	$loginQuery = $db->prepare("SELECT * FROM users WHERE username = :usr AND password = :pwd");
	$loginQuery->execute(
		[
			'usr' => $usr,
			'pwd' => $passwd
		]);

	$result = $loginQuery->rowCount() ? $loginQuery : [];
	// IF LOGIN DETAIL IS CORRECT MOVE TO INDEX PAGE OTHERWISE SHOW ERROR MESSAGE
	if(!empty($result)){
		foreach($result as $output){
			$_SESSION['id'] = $output['cus_id'];
		}		
		header("Location: index.php");
	} else {
		$message = "Invalid login details";
	}
}// CLOSE CHECK IF LOGIN BUTTON IS CLICKED

// LOGOUT REQUEST
if(isset($_GET['log']) && $_GET['log'] == "out"){
	unset($_SESSION['id']);
	session_unset();
	session_destroy();
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
					<h4>Login</h4>
				</div>
				<div class="panel-body">
					<h5><label>Option 1: Login with your Social Account</label></h5>
					<form><!-- FORM FOR AUTOMATIC LOGIN / REGISTER -->						
					    <div class="row">	
					    	<!-- FACEBOOK LOGIN -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
    							        <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
    							        <input type="button" value="Login with Facebook" class="btn btn-default form-control" name="facebook" readonly>
							        </div>
							    </div>
							</div>
							<!-- GOOGLE LOGIN -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
							            <span class="input-group-addon"><i class="fa fa-google"></i></span>
							            <input text="button" value="Login with google" class="btn btn-default form-control" name="google" readonly>
							        </div>
							    </div>
							</div>
							<!-- TWITTER LOGIN -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
							            <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
							            <input text="button" value="Login with twitter" class="btn btn-default form-control" name="twitter" readonly>
							        </div>
							    </div>
							</div>
						</div>
						</form><!-- CLOSE FORM FOR AUTOMATIC LOGIN -->
						
						<br/>
						<fieldset><legend class="text-center"></legend></fieldset>
						<h5><label>Option 2: Login with login details</label></h5>
						<!-- 111111111111 LOGIN PAGE -->
						
						<!-- REGISTER NEW USER LINK -->
						<span class="reglink pull-right"><a href="registeruser.php">Not registered? Sign up here</a></span>
						
						<br/>
						<!-- MANUAL LOGIN FORM -->
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<!-- LOGIN MESSAGE -->
							<span style="color: red"><?php echo !empty($message) ? $message : ""; ?></span>							
							<br/>
							<!-- INPUT USERNAME -->
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off" required>
								</div>
							</div>
							<!-- INPUT PASSWORD -->
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
									<input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
								</div>
							</div>
							<!-- INPUT SUBMIT / LOGIN BUTTON -->
							<div class="form-group">
								<input type="submit" class="btn btn-primary  pull-right" name="login" value="Log In">
							</div>

						</form><!-- CLOSE LOGIN FORM -->
						<a href="resetpass.php">Forgot Password?</a><!-- PASSWORD RESET -->																									

				</div><!-- CLOSE PANEL BODY -->
			</div><!-- CLOSE PANEL -->
		</div><!-- CLOSE MAIN CONTENT -->
	</div><!-- CLOSE CONTAINER -->
	
	<script src="jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>