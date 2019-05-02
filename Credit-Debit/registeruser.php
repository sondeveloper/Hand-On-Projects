<?php
// REQUIRE SITE CONFIGURATION
require_once "config.php";
// REGISTRATION MESSAGE
$regmsg = "";
// PROCESS NEW REGISTRATION
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register']))
{
	$status = 0;
	// CLEAN USER DATA
	$fname = clean($_POST['fname']);
	$lname = clean($_POST['lname']);
	$uname = clean($_POST['usname']);
	$regpas = clean($_POST['regpas']);
	$cregpas = clean($_POST['cregpas']);
	$createdate = date("y/m/d h:i:sa");
	$accno = rand(123456789,987654321);
	$cusid = $uname."".$accno;
	// CHECK IF USERNAME EXIST IN DATABASE
	$checkQuery = $db->prepare("SELECT * FROM users WHERE username = :username");
	$checkQuery->execute(
		[
			'username' => $uname
		]);

	if(!$checkQuery->rowCount())
	{		
		// CHECK IF PASSWORD MATCH
		if($regpas === $cregpas){			

			// GET PHOTO DESTINATION FOLDER
			$targetdir = "images/".$uname."/";
			// CHECK IF DIRECTORY EXIST OTHERWISE CREATE IT
			mkdir($targetdir);

			// SET PATH TO THE DESTINATION FOLDER
			$targetfile = $targetdir.basename($_FILES['photo']['name']);
		
			// CHECK IMAGE FILE TYPE
			$filetype = strtolower(pathinfo($targetfile,PATHINFO_EXTENSION));

			if($filetype == "jpg" || $filetype == "jpeg" || $filetype == "png" || $filetype == "gif")
			{				

				// CHECK IMAGE SIZE
				if($_FILES['photo']['size'] <= 900000)
				{
					// UPLOAD IMAGE
					move_uploaded_file($_FILES['photo']['tmp_name'], $targetfile);
					// INSERT USER INFORMATION INTO DATABASE
					$regQuery = $db->prepare("INSERT INTO users (first_name, last_name, datecreated, balance, account_no, cus_id, username, password, photo ) VALUES (:fn, :ln, :dt, :bl, :acn, :cid, :unm, :pwd, :pht)");
					$regQuery->execute(
						[
							'fn' => $fname,
							'ln' => $lname,
							'dt' => $createdate,
							'bl' => 0,
							'acn' => $accno,
							'cid' => $cusid,
							'unm' => $uname,
							'pwd' => $regpas,
							'pht' => $targetfile
						]);
	
				}
				else 
				{
					$status = 4; // IMAGE SIZE EXCEEDED
					echo $status;
				}
			} 
			else 
			{
				$status = 3; // IMAGE FILE TYPE NOT VALID
				echo $status;
			}
		} else 
		{
			$status = 2; // PASSWORD MISMATCH
			echo $status;
		}
	} 
	else 
	{
		$status = 1; // USERNAME ALREADY EXIST
		echo $status;
	}
	// REGISTRATION STATUS
	switch ($status) 
	{
		case 1:
			$regmsg = "Username already exist; used by another user";
			break;
		case 2:
			$regmsg = "Password mismatch";
			break;
		case 3:
			$regmsg = "Invalid image file";
			break;
		case 4:
			$regmsg = "Image size too large";
		default:
			$regmsg = "Registration Successfull. Click the login link";
			break;
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
					<h4>Sign Up</h4>
				</div>
				<div class="panel-body">
					<h5><label>Option 1: Register with your Social Account</label></h5>
					<form><!-- FORM FOR AUTOMATIC LOGIN / REGISTER -->						
					    <div class="row">
					    	<!-- FACEBOOK BUTTON -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
    							        <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
    							        <input type="button" value="Register with Facebook" class="btn btn-default form-control" name="facebook" readonly>
							        </div>
							    </div>
							</div>
							<!-- GOOGLE BUTTON -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
							            <span class="input-group-addon"><i class="fa fa-google"></i></span>
							            <input text="button" value="Register with google" class="btn btn-default form-control" name="google" readonly>
							        </div>
							    </div>
							</div>
							<!-- TWITTER BUTOTN -->
							<div class="col-sm-4">
							    <div class="form-group">
							        <div class="input-group">
							            <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
							            <input text="button" value="Register Twitter" class="btn btn-default form-control" name="twitter" readonly>
							        </div>
							    </div>
							</div>
						</div>
					</form><!-- CLOSE FORM FOR AUTOMATIC LOGIN -->
						
					<br/>
					<fieldset><legend class="text-center"></legend></fieldset>
					<h5><label>Option 2: Register by filling the form</label></h5>
					<!-- LOGIN USER -->
						<span class="reglink pull-right"><a href="login.php">Already registered? Login here</a></span>				
					<br/>
					
					<!-- MANUAL REGISTRATION -->
					
					<div class="bg-warning text-danger"><?php echo isset($regmsg) ? $regmsg : "" ?></div>	<br/>
					<!-- REGISTRATTION FORM -->
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
						<!-- INPUT FIRST NAME -->
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									First Name
								</span>
								<input type="text" class="form-control" name="fname" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ''; ?>" required>
							</div>
						</div>
						<!-- INPUT LAST NAME -->
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									Last Name
								</span>
								<input type="text" class="form-control" name="lname" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ''; ?>" required>
							</div>
						</div>
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
						<!-- INPUT PHOTO -->
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									Photo
								</span>
								<input type="file" class="form-control" name="photo" value="<?php echo isset($_FILES['photo']) ? $_FILES['photo'] : ''; ?>" required>
							</div>
						</div>
						<!-- INPUT SUBMIT / REGISTER BUTTON -->
						<input type="submit" class="btn btn-primary btn-lg pull-right" name="register" value="Register">
					</form>

				</div><!-- CLOSE PANEL BODY -->
			</div><!-- CLOSE PANEL -->
		</div><!-- CLOSE MAIN CONTENT -->
	</div><!-- CLOSE CONTAINER -->
	
	<script src="jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>