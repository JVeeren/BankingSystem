<?php
	//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from login page
    $user = $_POST["eid"];
	$pass = $_POST["pass"];

    //to prevent sql injection
	$user = stripcslashes($user);
	$pass = stripcslashes($pass);
    $user = mysqli_real_escape_string($db,$user);
    $pass = mysqli_real_escape_string($db,$pass); 

	//Query
	$result = mysqli_query($db,"select * from employee_login_details where EMP_ID_FK = '$user' and EMP_PASSWORD = '$pass'") or die ("failed to connect" .mysqli_error());
	$row = mysqli_fetch_array ($result);
	if ($row['EMP_ID_FK'] == $user && $row['EMP_PASSWORD'] == $pass)
    { 
		session_start();
		$_SESSION['id']= $user; 
	
    $result1 = mysqli_query($db,"select * from employee where EMP_ID_PK = '$user'") or die ("failed to connect".mysqli_error($db));
	$row1 = mysqli_fetch_array ($result1);
	
    if($row1['EMP_ROLE'] == 'MANAGER')
       {
		header("Location: manager_login.html");
		}
		else{
		header("Location: employee_login.html");
		}
		
	}
	else {
    echo "<script type='text/javascript'>alert('INCORRECT EMPLOYEE ID OR PASSWORD!!')</script>";

	}
?>

