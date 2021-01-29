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
		$br_id =  mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
		$br_id1= mysqli_fetch_array ($br_id);
	$acc = mysqli_query($db,"select * from account where ACC_ID_PK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	$acc1 = mysqli_fetch_array($acc);
	
		if( $acc1['ACC_TYPE'] != "LOAN"){
			$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
			$start_date1= mysqli_fetch_array ($start_date);
		
				if( $trans_type == "DEPOSIT"){
					$post = $acc1['ACC_BALANCE'] + $amt;
                    
                    if($acc1['ACC_TYPE'] == "CURRENT" )
                    {
					$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'CURRENT','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'DEPOSIT');";
					 mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
                    }
                    
                   if($acc1['ACC_TYPE'] == "SAVINGS" )
                    {
					$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'SAVINGS','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'DEPOSIT');";
					 mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
                    }
                    
                    
					$sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
					 mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
					echo "<script type='text/javascript'>alert('TRANSACTION COMPLETED!')</script>";
					if($br_id1['EMP_ROLE'] == 'MANAGER'){
					header("refresh: 1; url =  http://localhost/manager_login.html");
					}
					ELSE{
					header("refresh: 1; url =  http://localhost/employee_login.html");
					}
				}
		
				elseif ($trans_type == "WITHDRAW" ){
					$post = $acc1['ACC_BALANCE'] - $amt;
					if($acc1['ACC_TYPE'] == "CURRENT" and $post >= 0){
						
                        $sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'CURRENT','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'WITHDRAW');";
						mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
						$sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
						mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
						echo "<script type='text/javascript'>alert('TRANSACTION COMPLETED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/employee_login.html");
				}
					}
					elseif($acc1['ACC_TYPE'] == "SAVINGS" and $post >= 1000){
						$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'SAVINGS','".$start_date1['datte']."',".$amt.",".$acc1['ACC_BALANCE'].",".$post.",'WITHDRAW');";
						mysqli_query($db,$sql3) or die ("failed to connect" .mysql_error());
						$sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
						mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
						echo "<script type='text/javascript'>alert('TRANSACTION COMPLETED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url = http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url = http://localhost/employee_login.html");
						}
					}
		
					else{
						echo "<script type='text/javascript'>alert('INSUFFICIENT BALANCE!')</script>";
						header("refresh: 1; url = http://localhost/transactions.html");
					}
				}
				else{
					header("refresh: 1; url = http://localhost/wd.html");
				}
				
			}
		else{
			echo "<script type='text/javascript'>alert('ACCOUNT INACTIVE!')</script>";
			header("refresh: 1; url = http://localhost/transactions.php");
			}
?>