<?php 
// require configuration file
require_once "config.php";
// CHECK IF LOGIN
if(!isset($_SESSION['id'])){
	header("Location: login.php");
}
// GET USER INFORMATION FROM DATABSE
$creditQuery = $db->prepare("SELECT * FROM users WHERE cus_id = :id");
$creditQuery->execute(
	[
		'id' => $_SESSION['id']
	]);
// PROCESS CREDIT TRANSACTION
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){
	// GET USER CREDIT AMOUNT
	$debit = clean($_POST['debit']);
	// GET INITIAL BALANCE
	$creditQuery2 = $db->prepare("SELECT * FROM users WHERE cus_id = :id");
	$creditQuery2->execute(
		[
			'id' => $_SESSION['id']
		]);
	$bal = 0;
	foreach($creditQuery2 as $credit2){
		$bal = $credit2['balance'];
	}
	// SUBTRACT DEBITED AMOUNT FROM BALANCE

	$newbalance = $bal - $debit;
	$dt = date("y/m/d h:i:sa");
	// KEEP DEBIT RECORD
	$keepQuery = $db->prepare("INSERT INTO debit (cus_id, balance, amount, debitdate) VALUES (:cid, :bal, :amt, :cdate)");
	$keepQuery->execute(
		[
			'cid' => $_SESSION['id'],
			'bal' => $bal,
			'amt' => $debit,
			'cdate' => $dt
		]);

	$updateQuery = $db->prepare("UPDATE users SET balance = :bal WHERE cus_id = :id");
	$updateQuery->execute(
		[
			'bal' => $newbalance,
			'id' => $_SESSION['id']
		]);
	header("Location: debit.php");
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
		<style>
			.customerdetail{
				list-style: none;
				margin: 0;
				padding: 0;
			}
			.customerdetail li {
				padding: 5px 0;
			}
		</style>
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-inverse">
			<div class="navbar-brand">My Simple Bank</div>
			<form class="navbar-form navbar-right" style="margin-right:30px" method="post" action="#">			 
			    <a class="btn btn-primary btn-sm" role="button" href="login.php?log=out">Logout</a>
			</form>
		</nav>
		<?php foreach($creditQuery as $credit): ?>
			<div class="well" style="padding: 7px;margin: 0"><h4 style="margin-left:30px;">Welcome <?php echo $credit['first_name']." - ".$credit['last_name']; ?></h4></div>
			<div class="row">
					<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading"></div>
						<div class="panel-body">
							<ul class="list-group">
								<li class="list-group-item"><a href="credit.php" >Deposit / Credit</a></li>
								<li class="list-group-item disabled"><a href="#">Withdraw / Debit</a></li>
								<li class="list-group-item"><a href="index.php">Balance</a></li>
								<li class="list-group-item"><a href="statement.php">Account Statement</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-heading"><h4>Withdraw / Debit</h4></div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<img src="<?php echo $credit['photo']; ?>" style="height: 100px;width:100px;"><br><br>
									<ul class="list-group">
										<li class="list-group-item"><a href="">Account info</a></li>
										<li class="list-group-item"><a href="">Personal info</a></li>
										<li class="list-group-item"><a href="">Contact info</a></li>
									</ul>
								</div>
								<div class="col-md-9">
	<div class="table-responsive">
									<table class="table table-striped table-hover table-condense">
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
													    <li><?php echo $credit['id'] ?></li>
														<li><?php echo $credit['account_no'] ?></li>
														<li><?php echo $credit['first_name']." - ".$credit['last_name']; ?></li>
														<li>Saving</li>
														<li><?php echo $credit['datecreated'] ?></li>
														<li><h4><?php echo "₦ ".$credit['balance'] ?></h4></li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
	</div>								
								</div>							
							</div>
							<div class="row">
							    <div class="col-md-6">
							        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
							            <div class="form-group">
							                <label for="debit">Enter debit amount</label><span style="color:red"> * <?php  ?></span>
							                <input type="number" class="form-control" value="<?php echo isset($_POST['debit']) ? $_POST['debit'] : ''; ?>" name="debit" placeholder="Enter amount to withdraw" autocomplete="off" required>
							            </div>
							            <input type="submit" name="submit" class="btn btn-success" value="Withdraw">
							        </form>
							    </div>
							    <!-- <div class="col=md=5">
							        <span style="color:red"> <?php echo $msg ?></span>
							    </div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		
	</div>
</body>
</html>