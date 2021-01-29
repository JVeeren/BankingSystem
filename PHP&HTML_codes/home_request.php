<?php
		
	//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");
	
	//Query
		session_start();
		$id = $_SESSION['id'];
	$br_id = mysqli_query($db,"select * from employee where EMP_ID_PK = $id;") or die ("failed to connect" .mysqli_error());
	$br_id1= mysqli_fetch_array ($br_id);
	if($br_id1['EMP_ROLE'] == 'MANAGER'){
	header('Location: manager_login.html');
	}
	else{
	header('Location:employee_login.html');
	}
?>

