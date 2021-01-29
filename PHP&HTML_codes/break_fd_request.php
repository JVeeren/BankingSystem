<?php
session_start();
if(!isset($_SESSION["id"]))
	header("location:login.html");

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$fd_no = $_POST["fd_no"];
	
	//to prevent sql injection
	$fd_no = stripcslashes($fd_no);
	
	$fd_no = mysqli_real_escape_string($db,$fd_no);
	
	//Query
		$id = $_SESSION['id'];
		$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
		$br_id1= mysqli_fetch_array ($br_id);
		
	$fd = mysqli_query($db,"select * from fixed_deposit_data where FD_ID_PK =".$fd_no.";") or die ("failed to connect" .mysqli_error());
	$fd1 = mysqli_fetch_array($fd);
	$acc = mysqli_query($db,"select * from account where ACC_ID_PK =".$fd1['ACC_ID_FK'].";") or die ("failed to connect" .mysqli_error());
	$acc1 = mysqli_fetch_array($acc);
	$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
			$start_date1= mysqli_fetch_array ($start_date);
	
		if( $fd1['FD_MATURITY_DATE'] > $start_date1['datte']){
		
					$post = $fd1['FD_AMOUNT'] + $acc1['ACC_BALANCE'];
					
                    						
                        $sql2="update account set acc_balance = ".$post." where acc_id_pk = ".$acc1['ACC_ID_PK'].";";
						mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
						
                     if($acc1['ACC_TYPE'] == "CURRENT" ){
                    
                       $sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'CURRENT','".$start_date1['datte']."',".$fd1['FD_AMOUNT'].",".$acc1['ACC_BALANCE'].",".$post.",'FD BREAK');";
						mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());

                        $sql1="delete from fixed_deposit_data where FD_ID_PK = ".$fd_no.";";
						mysqli_query($db,$sql1) or die ("failed to connect" .mysqli_error());
                     }
                        
                        if($acc1['ACC_TYPE'] == "SAVINGS" ){
                    
                       $sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'SAVINGS','".$start_date1['datte']."',".$fd1['FD_AMOUNT'].",".$acc1['ACC_BALANCE'].",".$post.",'FD BREAK');";
						mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());

                        $sql1="delete from fixed_deposit_data where FD_ID_PK = ".$fd_no.";";
						mysqli_query($db,$sql1) or die ("failed to connect" .mysqli_error());
                     }
                        
                        echo "<script type='text/javascript'>alert('FD DELETED!')</script>";
						if($br_id1['EMP_ROLE'] == 'MANAGER'){
						header("refresh: 1; url =  http://localhost/manager_login.html");
						}
						ELSE{
						header("refresh: 1; url =  http://localhost/employee_login.html");
						}
			}
		else{
			header("refresh: 1; url = http://localhost/fd.html");
			}
?>