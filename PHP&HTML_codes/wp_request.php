<?php
session_start();
if(!isset($_SESSION["id"]))
	header("location:login.html");

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$acc_no = $_POST["acc_no"];
	$trans_type = $_POST["trans_type"];
	$amt = $_POST["amt"];
	
	//to prevent sql injection
	$acc_no = stripcslashes($acc_no);
	$trans_type = stripcslashes($trans_type);
	$amt = stripcslashes($amt);
	
	$acc_no = mysqli_real_escape_string($db,$acc_no);
	$trans_type = mysqli_real_escape_string($db,$trans_type);
	$amt = mysqli_real_escape_string($db,$amt);
	
	//Query
		$id = $_SESSION['id'];
		$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
		$br_id1= mysqli_fetch_array ($br_id);
	$acc = mysqli_query($db,"select * from account where ACC_ID_PK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$acc1 = mysqli_fetch_array($acc);
	$ls = mysqli_query($db,"select * from loan_sanctions where ACC_ID_FK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$ls1 = mysqli_fetch_array($ls);
	
		if($acc1['ACC_TYPE'] == "LOAN"){
			$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
			$start_date1= mysqli_fetch_array ($start_date);
		
				if( $trans_type == "PAY"){
					$post = $ls1['P_LOAN_AMOUNT'] - $amt;
					$sql3="insert into loan_transactions values (".$acc1['ACC_ID_PK'].",".$amt.",".$ls1['P_LOAN_AMOUNT'].",".$post.",'".$start_date1['datte']."');";
					mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
					$sql2="update loan_sanctions set P_LOAN_AMOUNT = ".$post." where ACC_ID_FK = ".$acc1['ACC_ID_PK'].";";
					mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
					echo "<script type='text/javascript'>alert('TRANSACTION COMPLETED!')</script>";
					if($br_id1['EMP_ROLE'] == 'MANAGER'){
					header("refresh: 1; url =  http://localhost/manager_login.html");
					}
					ELSE{
					header("refresh: 1; url =  http://localhost/employee_login.html");
					}
				}
		
				elseif ( $trans_type == "WITHDRAW" and $ls1['END_DATE'] > $start_date1['datte'] ){
					$post = $acc1['ACC_BALANCE'] - $amt;
					$post1 = $ls1['P_LOAN_AMOUNT'] + $amt;
					if($post >= 0){
						$sql3="insert into loan_transactions values (".$acc1['ACC_ID_PK'].",".$amt.",".$post.",'".$start_date1['datte']."');";
					mysql_query($sql3) or die ("failed to connect" .mysql_error());
						$sql2="update account_info set balance = ".$post." where acc_no = ".$acc1['ACC_NO'].";";
						mysql_query($sql2) or die ("failed to connect" .mysql_error());
						$sql4="update loan_repay set amt_to_pay = ".$post1." where acc_no = ".$acc1['ACC_NO'].";";
					mysql_query($sql4) or die ("failed to connect" .mysql_error());
						echo "<script type='text/javascript'>alert('TRANSACTION COMPLETED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/project/employee_login.html");
							}
					}
		
					else{
						echo "<script type='text/javascript'>alert('INSUFFICIENT BALANCE!')</script>";
						header("refresh: 1; url = http://localhost/project/view_account.php");
					}
				}
				else{
					echo "<script type='text/javascript'>alert('TRANSACTION ABORTED!')</script>";
					header("refresh: 1; url = http://localhost/project/wd.php");
				}
				
			}
		else{
			echo "<script type='text/javascript'>alert('ENTER CORRECT INFORMATION!')</script>";
			header("refresh: 1; url = http://localhost/project/view_account.php");
			}
?>