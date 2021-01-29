<?php
//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");	

//passing values from new_account page
	$user = $_POST["cid"];
	$balance = $_POST["bal"];
	$acc_type = $_POST["acc_type"];
	
	//to prevent sql injection
	$user = stripcslashes($user);
	$balance = stripcslashes($balance);
	$acc_type = stripcslashes($acc_type);
	$user = mysqli_real_escape_string($db,$user);
	$balance = mysqli_real_escape_string($db,$balance);
	$acc_type = mysqli_real_escape_string($db,$acc_type);
	
	//Query
		session_start();
		$id = $_SESSION['id'];
	$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
	$min_bal = mysqli_query($db,"select * from account_details where acc_type= '$acc_type'") or die ("failed to connect" .mysqli_error());
	$br_id1= mysqli_fetch_array ($br_id);
	$min_bal1= mysqli_fetch_array ($min_bal);


    if($balance < $min_bal1['S_MIN_BALANCE'])
	{
	echo "<script type='text/javascript'>alert('INSUFFICIENT BALANCE!')</script>";
	header('refresh: 1; url = http://localhost/new_account.html');
	}

	else
	{
		$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
		$start_date1= mysqli_fetch_array ($start_date);
        
        $sql="insert into account (cust_id_fk, acc_balance,update_date, acc_type, br_id_fk) values (".$user.",".$balance.",'".$start_date1['datte']."','".$acc_type."'," .$br_id1['BR_ID_FK'].");";
		mysqli_query($db,$sql) or die ("failed to connect" .mysqli_error());
		if($acc_type == 'LOAN')
		{
			header('Location:loan.html');
		}
		else{
		$pre = 0;
		$acc = mysqli_query($db,"select * from account where acc_id_pk=(select max(acc_id_pk) from account);") or die ("failed to connect" .mysqli_error());
		$acc1= mysqli_fetch_array ($acc);
		$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
		$start_date1= mysqli_fetch_array ($start_date);
		
        if($acc1['ACC_TYPE'] == "CURRENT" )
                    {
					$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'CURRENT','".$start_date1['datte']."',".$balance.",".$pre.",".$balance.",'DEPOSIT');";
					 mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
                    }
                    
                   if($acc1['ACC_TYPE'] == "SAVINGS" )
                    {
					$sql3="insert into transactions values (".$acc1['ACC_ID_PK'].",'SAVINGS','".$start_date1['datte']."',".$balance.",".$pre.",".$balance.",'DEPOSIT');";
					 mysqli_query($db,$sql3) or die ("failed to connect" .mysqli_error());
                    }
        
        echo "<script type='text/javascript'>alert('ACCOUNT CREATED!')</script>";
		header("refresh: 1; url = http://localhost/customer_details_request.php?cid=".$user);
	}
	}
?>

