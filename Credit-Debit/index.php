<?php 
// DATABASE CONNECTION
require_once 'config.php';

// CHECK IF LOGIN
if(!isset($_SESSION['id'])){
	header("Location: login.php");
}

$userQuery = $db->prepare("SELECT * FROM users WHERE cus_id = :id");
$userQuery->execute(['id' => $_SESSION['id']]);

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
	
	<div class="container"><!-- CONTAINER -->
		
		<nav class="navbar navbar-inverse"><!-- OPEN NAVBAR -->
			<div class="navbar-brand">Transaction App</div>
			<form class="navbar-form navbar-right" style="margin-right:30px">
			    <a class="btn btn-primary" href="login.php?log=out" role="button">Logout</a>
			</form>
		</nav><!-- CLOSE NAVBAR -->
		<?php foreach($userQuery as $output): ?>
		<!-- DISPLAY ACCOUNT NAME -->
		<div class="well" style="padding: 7px;margin: 0"><h4 style="margin-left:30px;">Welcome <?php echo $output['first_name']." - ".$output['last_name'] ?></h4></div>
		<!-- CONTENT ROW -->
		<div class="row">
			
			<div class="col-md-3"><!-- FISRT COLUMN - ACCOUNT OTHER LINK -->
				<div class="panel panel-default">
					<div class="panel-heading"></div>
					<div class="panel-body">
						<ul class="list-group">
							<li class="list-group-item"><a href="credit.php">Deposit / Credit</a></li>
							<li class="list-group-item"><a href="debit.php">Withdraw / Debit</a></li>
							<li class="list-group-item disabled"><a href="#">Balance</a></li>
							<li class="list-group-item"><a href="statement.php">Account Statement</a></li>
						</ul>
					</div>
				</div>
			</div><!-- CLOSE FIRST COLUMN -->

			<div class="col-md-9"><!-- SECOND COLUMN - ACCOUNT INFORMATION -->
				
				<div class="panel panel-default"><!-- PANEL -->
					<div class="panel-heading"></div>
					<div class="panel-body"><!-- PANEL BODY -->

						<div class="row"><!-- SECOND COLUMN SUB ROW -->
							
							<div class="col-md-3"><!-- FIRST SUB ROW COLUMN -->
								<img src="<?php echo $output['photo'] ?>" style="height: 100px;width:100px;"><br><br>
								<ul class="list-group">
									<li class="list-group-item disabled"><a role="button" class="btn btn-default" href="#" disabled>Account info</a></li>
									<li class="list-group-item"><a href="#">Edit Personal info</a></li>
									<li class="list-group-item"><a href="#">Change Photo</a></li>
								</ul>
							</div><!-- CLOSE FIRST SUB ROW COLUMN -->

							<div class="col-md-9"><!-- SECOND SUB ROW COLUMN -->
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover table-condense">
										<thead>
											<tr>
												<th colspan="2"><h3>Account info</h3></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<ul class="customerdetail">
													    <li>Customer Id</li>
														<li>Account Number</li>
														<li>Customer Name</li>
														<li>Account Type</li>
														<li>Opened On</li>
														<li><h4>Customer Balance</h4></li>
													</ul>
												</td>
												<td>
													<ul class="customerdetail">
													    <li><?php echo $_SESSION['id'] ?></li>
														<li><?php echo $output['account_no'] ?></li>
														<li><?php echo $output['first_name']." - ".$output['last_name'] ?></li>
														<li>Saving</li>
														<li><?php echo $output['datecreated'] ?></li>
														<li><h4><?php echo "₦ ".$output['balance'] ?></h4></li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
								</div>								
							</div><!-- CLOSE SECOND SUB ROW COLUMN -->					
						</div><!-- CLOSE SECOND COLUMN SUB ROW -->
					</div><!-- CLOSE PANEL BODY -->
				</div><!-- CLOSE PANEL -->

			</div><!-- CLOSE SECOND COLUMN -->

		</div><!-- CLOSE CONTENT ROW -->
		<?php endforeach; ?>
		
	</div><!-- CLOSE CONTAINER -->

	<script src="jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>