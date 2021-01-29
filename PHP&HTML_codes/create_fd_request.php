<?php
session_start();
if(!isset($_SESSION["id"]))
	header("location:login.html");

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$acc_no = $_POST["acc_no"];
	$rate = $_POST["rate"];
	$amt = $_POST["amt"];
	$mat_date = $_POST["mat_date"];
	
	//to prevent sql injection
	$acc_no = stripcslashes($acc_no);
	$rate = stripcslashes($rate);
	$amt = stripcslashes($amt);
	$mat_date = stripcslashes($mat_date);
	
	$acc_no = mysqli_real_escape_string($db,$acc_no);
	$rate = mysqli_real_escape_string($db,$rate);
	$amt = mysqli_real_escape_string($db,$amt);
	$mat_date = mysqli_real_escape_string($db,$mat_date);
	
	
	//Query
		$id = $_SESSION['id'];
		$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
		$br_id1= mysqli_fetch_array ($br_id);
	$acc = mysqli_query($db,"select * from account where ACC_ID_PK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$acc1 = mysqli_fetch_array($acc);
	
		if($acc1['ACC_TYPE'] != "LOAN"){
			$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
			$start_date1= mysqli_fetch_array ($start_date);
		
					$post = $acc1['ACC_BALANCE'] - $amt;
					if($acc1['ACC_TYPE'] == "CURRENT" and $post >= 0){
						$sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
						mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
                       
						
                        $sql1="insert into fixed_deposit_data ( acc_id_fk, fd_amount, fd_interest_rate, fd_start_date, fd_maturity_date,update_date,fd_maturity_amount ) values (".$acc1['ACC_ID_PK'].",".$amt.",".$rate.",'".$start_date1['datte']."','".$mat_date."','".$start_date1['datte']."',".$amt.");";
						mysqli_query($db,$sql1) or die ("failed to connect" .mysqli_error());
                        
                         
                         
                    if($acc1['ACC_TYPE'] == "CURRENT" )
                    {
					$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'CURRENT','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'FD');";
					 mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
                    }
                   
						echo "<script type='text/javascript'>alert('FD CREATED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/employee_login.html");
						}
					}
					elseif($acc1['ACC_TYPE'] == "SAVINGS" and $post >= 1000){
						$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'SAVINGS','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'FD');";
						mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
						$sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
						mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
						
						$sql1="insert into fixed_deposit_data ( acc_id_fk, fd_amount, fd_interest_rate, fd_start_date, fd_maturity_date,update_date,fd_maturity_amount ) values (".$acc1['ACC_ID_PK'].",".$amt.",".$rate.",'".$start_date1['datte']."','".$mat_date."','".$start_date1['datte']."',".$amt.");";
						mysqli_query($db,$sql1) or die ("failed to connect" .mysqli_error());
						echo "<script type='text/javascript'>alert('FD CREATED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/employee_login.html");
				}
					}
		
					else{
						echo "<script type='text/javascript'>alert('INSUFFICIENT BALANCE!')</script>";
						header("refresh: 1; url = http://localhost/fd.html");
					}
			}
		else{
			echo "<script type='text/javascript'>alert('ACCOUNT NOT ALLOWED TO CREATE FD!')</script>";
			header("refresh: 1; url = http://localhost/fd.html");
			}
?>