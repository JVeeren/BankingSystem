<?php

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

//passing values from new_account page
	$name = $_POST["name"];
	$dob = $_POST["dob"];
	$gender = $_POST["gender"];
	$phno = $_POST["phno"];
    $email=$_POST["email"];
	$addr = $_POST["addr"];
	$balance = $_POST["bal"];
	$acc_type = $_POST["acc_type"];
	
	//to prevent sql injection
	$name = stripcslashes($name);
	$dob = stripcslashes($dob);
	$gender = stripcslashes($gender);
	$phno = stripcslashes($phno);
	$email= stripcslashes($email);
	$addr = stripcslashes($addr);
	$balance = stripcslashes($balance);
	$acc_type = stripcslashes($acc_type);
	
    $name = mysqli_real_escape_string($db,$name);
	$dob = mysqli_real_escape_string($db,$dob);
	$gender = mysqli_real_escape_string($db,$gender);
	$phno = mysqli_real_escape_string($db,$phno);
    $email= mysqli_real_escape_string($db,$email);
	$addr = mysqli_real_escape_string($db,$addr);
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
	header('refresh: 2; url = http://localhost/new_customer.html');
	}
	else
	{
		$start_date= mysqli_query($db,"select curdate() datte;") or die ("failed to connect".mysqli_error());
		$start_date1= mysqli_fetch_array ($start_date);
        
        $sql="insert into customer (cust_name, cust_dob, cust_gender, cust_phone,cust_mailid,cust_address) values ('".$name."','".$dob."','".$gender."'," .$phno.",'".$email."','".$addr."');";
		mysqli_query($db,$sql) or die ("failed to connect" .mysqli_error());
		$cid = mysqli_query($db,"select * from customer where CUST_ID_PK =(select max(CUST_ID_PK) from customer);") or die ("failed to connect" .mysqli_error());
		
        $cid1= mysqli_fetch_array ($cid);
		$sql1="insert into account (cust_id_fk, acc_balance,update_date, acc_type, br_id_fk) values (".$cid1['CUST_ID_PK'].",".$balance.",'".$start_date1['datte']."','".$acc_type."',".$br_id1['BR_ID_FK'].");";
		mysqli_query($db,$sql1) or die ("failed to connect" .mysqli_error());
		
        if($acc_type == 'LOAN')
		{
			header('Location: loan.html');
		}
		else{
		$pre= 0;
		$acc = mysqli_query($db,"select * from account where ACC_ID_PK =(select max(ACC_ID_PK) from account);") or die ("failed to connect" .mysqli_error());
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

		header("refresh: 1; url = http://localhost/customer_details_request.php?cid=". $cid1['CUST_ID_PK']);

	}
	}
?>