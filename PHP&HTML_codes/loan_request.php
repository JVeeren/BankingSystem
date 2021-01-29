<?php
    
	//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$loan_type = $_POST["loan_type"];
	$end_date = $_POST["end_date"];
	
    //to prevent sql injection
	$user = stripcslashes($loan_type);
	$balance = stripcslashes($end_date);
	
	$loan_type = mysqli_real_escape_string($db,$loan_type);
	$end_date = mysqli_real_escape_string($db,$end_date);
	
    //Query
	$acc_no= mysqli_query($db,"select * from account where ACC_ID_PK = (select max(ACC_ID_PK) from account);") or die ("failed to connect" .mysqli_error());
	$acc_no1= mysqli_fetch_array ($acc_no);
	$fine = 0;
	$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
	$start_date1= mysqli_fetch_array ($start_date);
	$sql="insert into loan_sanctions values (".$acc_no1['ACC_ID_PK']."," .$acc_no1['ACC_BALANCE'].",'".$start_date1['datte']."','".$end_date."','".$start_date1['datte']."','".$loan_type."'," .$acc_no1['ACC_BALANCE'].");";
	mysqli_query($db,$sql) or die ("failed to connect" .mysqli_error());
	
		session_start();
		$id = $_SESSION['id'];
		echo "<script type='text/javascript'>alert('LOAN SANCTIONED!')</script>";
	$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
	$br_id1= mysqli_fetch_array ($br_id);
		header("refresh: 1; url = http://localhost/customer_details_request.php?cid=". $acc_no1['CUST_ID_FK']);
?>

