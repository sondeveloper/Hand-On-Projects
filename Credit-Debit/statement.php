<?php

// require configuration file
require_once "config.php";
// CHECK IF LOGIN
if(!isset($_SESSION['id'])){
	header("Location: login.php");
}
// GET USER INFORMATION FROM DATABSE
$stateQuery = $db->prepare("SELECT * FROM users WHERE cus_id = :id");
$stateQuery->execute(
	[
		'id' => $_SESSION['id']
	]);
// GET CREDIT TRANSACTION
$query2 = $db->prepare("SELECT * FROM credit WHERE cus_id = :cusid");
$query2->execute(
	[
		'cusid'=>$_SESSION['id']
	]);
$items2 = $query2->rowCount() ? $query2 : [];
// GET DEBIT TRANSACTION
$query3 = $db->prepare("SELECT * FROM debit WHERE cus_id = :cusid");
$query3->execute(
	[
		'cusid'=>$_SESSION['id']
	]);
$items3 = $query3->rowCount() ? $query3 : [];

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
    <!--BEGIN CONTAINER-->
	<div class="container">
		<nav class="navbar navbar-inverse">
			<div class="navbar-brand">My Simple Bank</div>
			<form class="navbar-form navbar-right" style="margin-right:30px" method="post" action="#">
			    <a class="btn btn-primary btn-sm" role="button" href="login.php?log=out">Logout</a>
			</form>
		</nav>
		<?php foreach($stateQuery as $statement): ?>
			<div class="well" style="padding: 7px;margin: 0"><h4 style="margin-left:30px;">Welcome <?php echo $statement['first_name']." ".$statement['last_name']; ?></h4>
			<!--BEGIN MAIN ROW-->
			<div class="row">
			    <!--BEGIN FIRST COLUMN-->
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading"></div>
						<div class="panel-body">
							<ul class="list-group">
								<li class="list-group-item"><a href="credit.php">Deposit / Credit</a></li>
								<li class="list-group-item"><a href="debit.php">Withdraw / Debit</a></li>
								<li class="list-group-item"><a href="index.php">Balance</a></li>
								<li class="list-group-item disabled"><a href="#">Account Statement</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!--END FIRST COLUMN-->
				<!--BEGING SECOND COLUMN-->
				<div class="col-md-9">
				    <!--BEGIN USER INFO-->
					<div class="panel panel-default">
						<div class="panel-heading"><h4>Account Statement</h4></div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<img src="<?php echo $statement['photo'] ?>" style="height: 100px;width:100px;"><br><br>
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
													    <li><?php echo $statement['cus_id'] ?></li>
														<li><?php echo $statement['account_no'] ?></li>
														<li><?php echo $statement['first_name']." - ".$statement['last_name'] ?></li>
														<li>Saving</li>
														<li><?php echo $statement['datecreated'] ?></li>
														<li><h4><?php echo "₦ ".$statement['balance'] ?></h4></li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
									</div><!-- CLOSE TABLE RESPONSIVE -->
								</div>							
							</div>
						</div>
					</div>
					<!--END USER INFO-->
						            	
				</div>
				<!--END SECOND COLUMN-->
			</div>
			<!--END MAIN ROW-->
		<?php endforeach; ?>
		<!-- CREDIT STATEMENT -->
		<div class="table-responsive">	    
		    <table class="table table-striped table-hover table-condensed">
		        <caption><h4>Credit / Deposit Transaction</h4></caption>
		        <thead>
		            <tr>
		                <th>Transaction Id</th>
		                <th>Customer ID</th>
		                <th>Previous Balance</th>
		                <th>Credited amount</th>
		                <th>Total balance</th>
		                <th>Date</th>
		            </tr>
		        </thead>
		        <tbody>
		           <?php foreach($items2 as $item2): ?>
		                <tr>
		                    <td><?php echo $item2['id']?></td>
		                    <td><?php echo $item2['cus_id']?></td>
		                    <td>N <?php echo $item2['balance']?></td>
		                    <td>N <?php echo $item2['amount']?></td>
		                    <td>N <?php echo $item2['balance'] + $item2['amount'] ?></td>
		                    <td><?php echo $item2['creditdate']?></td>
		                </tr>
		           <?php endforeach ?>
		        </tbody>
		    </table>
		</div> 
		<!-- DEBIT STATEMENT -->
		<div class="table-responsive">
		    <table class="table table-striped table-hover table-condensed">
		        <caption><h4>Debit / Withdraw Transaction</h4></caption>
		        <thead>
		            <tr>
		                <th>Transaction Id</th>
		                <th>Customer ID</th>
		                <th>Previous Balance</th>
		                <th>Debited amount</th>
		                <th>Total balance</th>
		                <th>Date</th>
		            </tr>
		        </thead>
		        <tbody>
		           <?php foreach($items3 as $item3): ?>
		                <tr>
		                    <td><?php echo $item3['id']?></td>
		                    <td><?php echo $item3['cus_id']?></td>
		                    <td>N <?php echo $item3['balance']?></td>
		                    <td>N <?php echo $item3['amount']?></td>
		                    <td>N <?php echo $item3['balance'] - $item3['amount'] ?></td>
		                    <td><?php echo $item3['debitdate']?></td>
		                </tr>
		           <?php endforeach ?>
		        </tbody>
		    </table>
		</div> 

		<br/><br/>  
	</div>
	<!--END CONTAINER-->
</body>
</html>