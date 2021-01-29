<?php
session_start();
if(!isset($_SESSION["id"]))
	header("location:login.html");

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$acc_no = $_POST["acc_no"];
	
	//to prevent sql injection
	$acc_no = stripcslashes($acc_no);
	
	$acc_no = mysqli_real_escape_string($db,$acc_no);
	
	//Query
		$id = $_SESSION['id'];
		$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
		$br_id1= mysqli_fetch_array ($br_id);
	$acc = mysqli_query($db,"select * from account where ACC_ID_PK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$acc1 = mysqli_fetch_array($acc);
	
	$acc2 = mysqli_query($db,"delete from account where ACC_ID_PK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$acc3 = mysqli_fetch_array($acc);
	echo "<script type='text/javascript'>alert('ACCOUNT DELETED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/employee_login.html");
						}
			
?>