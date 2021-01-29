<?php
session_start();
if(!isset($_SESSION["id"]))
	header("location:login.html");
?>
<!DOCTYPE html>
<html>
<head>
	<title>BANK</title>
	<link rel= "stylesheet" type= "text/css" href="style.css">
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #5d6d7e;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 16px 16px;
    text-decoration: underline;
}

li a:hover:not(.active) {
    background-color: #111;
}

.active {
    background-color: #4CAF50;
}
</style>
</head>
<body>
	<ul>
  <li><a href="home_request.php">Home</a></li>
  <li style="float:right"><a  href="logout_request.php">Logout</a></li>
</ul>

	<?php

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$acc_no = $_POST["acc_no"];
	
	echo "<br>EMPLOYEE ID: ".$_SESSION['id'];
	echo "<br>ACCOUNT NUMBER: ".$acc_no;
	
	//to prevent sql injection
	$acc_no = stripcslashes($acc_no);
	
	$acc_no = mysqli_real_escape_string($db,$acc_no);
	
	//Query
		$id = $_SESSION['id'];
	$fd = mysqli_query($db,"select * from fixed_deposit_data where ACC_ID_FK =".$acc_no.";") or die ("failed to connect" .mysqli_error());
	echo "<table border='4' width=100% id= results>
<tr>
<th>FD NUMBER</th>
<th>ACCOUNT NUMBER</th>
<th>INTEREST RATE</th>
<th>FD AMOUNT</th>
<th>START DATE</th>
<th>MATURITY DATE</th>
<th>MATURITY AMOUNT</th>
</tr>";

while($row = mysqli_fetch_array($fd))
  {


  echo "<tr>"; 

    echo "<td>" . $row['FD_ID_PK'] . "</td>";
    echo"<td>". $row['ACC_ID_FK']."</td>";
    echo"<td>". $row['FD_INTEREST_RATE']."</td>";
    echo"<td>". $row['FD_AMOUNT'] ."</td>";
    echo"<td>". $row['FD_START_DATE'] ."</td>";
    echo"<td>". $row['FD_MATURITY_DATE'] ."</td>";
	echo"<td>". $row['FD_MATURITY_AMOUNT'] ."</td>";
  echo "</tr>";
  }
echo "</table>";
?>
	<div>
			<center><form action="fd.html" method= "POST">
			<p>
				<input style="width: 150px; height: 30px;" type="Submit" id="btn" value="BACK" />
			</p>
			</form>
			</center>
	</div>
</body>

</html>