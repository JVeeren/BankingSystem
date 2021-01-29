<?php
	//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");
    
    //passing values from new_account page
	$name = $_POST["name"];
	$dob = $_POST["dob"];
	$gender = $_POST["gender"];
	$phno = $_POST["phno"];
	$addr = $_POST["addr"];
	$sal = $_POST["sal"];
	$role = $_POST["role"];
	$pass1 = $_POST["pass1"];
	$pass2 = $_POST["pass2"];
	
	//to prevent sql injection
	$name = stripcslashes($name);
	$dob = stripcslashes($dob);
	$gender = stripcslashes($gender);
	$phno = stripcslashes($phno);
	$addr = stripcslashes($addr);
	$sal = stripcslashes($sal);
	$role = stripcslashes($role);
	$pass1 = stripcslashes($pass1);
	$pass2 = stripcslashes($pass2);
	
	$name = mysqli_real_escape_string($db,$name);
	$dob = mysqli_real_escape_string($db,$dob);
	$gender = mysqli_real_escape_string($db,$gender);
	$phno = mysqli_real_escape_string($db,$phno);
	$addr = mysqli_real_escape_string($db,$addr);
	$sal = mysqli_real_escape_string($db,$sal);
	$role = mysqli_real_escape_string($db,$role);
	$pass1 = mysqli_real_escape_string($db,$pass1);
	$pass2 = mysqli_real_escape_string($db,$pass2);
	
	//Query
		session_start();
		$id = $_SESSION['id'];
	$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
	$br_id1= mysqli_fetch_array ($br_id);
	if($pass1 == $pass2)
	{
		$sql="insert into employee (emp_name, emp_gender, emp_dob, emp_phone_no, emp_address, emp_salary, emp_role, br_id_fk,emp_pwd) values ('".$name."','".$gender."','".$dob."'," .$phno.",'".$addr."',".$sal.",'".$role."',".$br_id1['BR_ID_FK'].",'".$pass1."');";
		mysqli_query($db,$sql) or die ("failed to connect" .mysqli_error());
		$eid = mysqli_query($db,"select * from employee where emp_id_pk=(select max(emp_id_pk) from employee);") or die ("failed to connect" .mysqli_error());
		$eid1= mysqli_fetch_array ($eid);
		$sql2="insert into employee_login_details values (".$eid1['EMP_ID_PK'].",'".$pass1."');";
		mysqli_query($db,$sql2) or die ("failed to connect" .mysqli_error());
		echo "<script type='text/javascript'>alert('EMPLOYEE ADDED!')</script>";
		header("refresh: 1; url = http://localhost/manager_login.html");
	}
	else
	{
		echo "<script type='text/javascript'>alert('ENTER CORRECT INFORMATION!')</script>";
		header("refresh: 1; url = http://localhost/add_employee.html");
	}
?>