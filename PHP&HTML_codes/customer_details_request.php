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
    background-color:#5d6d7e;
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
	<p>
		<?php
		
		echo "EMPLOYEE ID: ".$_SESSION['id'];
		?>
	</p>
	<?php

//connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");

	//passing values from new_account page
	$cid = $_GET["cid"];
	
	//to prevent sql injection
	$cid = stripcslashes($cid);
	
	$cid = mysqli_real_escape_string($db,$cid);
	
	//Query
		$id = $_SESSION['id'];
		echo "<br>";
	$cus = mysqli_query($db,"select * from customer where CUST_ID_PK = $cid;") or die ("failed to connect" .mysqli_error());
	echo "<table border='4' width=100% id= results>
<tr>
<th>CUSTOMER ID</th>
<th>NAME</th>
<th>DATE OF BIRTH</th>
<th>GENDER</th>
<th>PHONE NUMBER</th>
<th>EMAIL ID</th>
<th>ADDRESS</th>
</tr>";

while($row = mysqli_fetch_array($cus))
  {


  echo "<tr>"; 

    echo "<td>" . $row['CUST_ID_PK'] . "</td>";
    echo"<td>". $row['CUST_NAME']."</td>";
    echo"<td>". $row['CUST_DOB']."</td>";
    echo"<td>". $row['CUST_GENDER'] ."</td>";
    echo"<td>". $row['CUST_PHONE'] ."</td>";
    echo"<td>". $row['CUST_MAILID'] ."</td>";
    echo"<td>". $row['CUST_ADDRESS'] ."</td>";
  echo "</tr>";
  }
echo "</table>";
echo "<br>";
echo "<br>";

$acc = mysqli_query($db,"select * from account where CUST_ID_FK = $cid;") or die ("failed to connect" .mysqli_error());
	echo "<table border='4' width=100% id= results>
<tr>
<th>ACCOUNT NUMBER</th>
<th>CUSTOMER ID</th>
<th>BRANCH ID</th>
<th>ACCOUNT TYPE</th>
<th>BALANCE</th>
</tr>";

while($row2 = mysqli_fetch_array($acc))
  {


  echo "<tr>"; 

    echo "<td>" . $row2['ACC_ID_PK'] . "</td>";
    echo "<td>" . $row2['CUST_ID_FK'] . "</td>";
    echo"<td>". $row2['BR_ID_FK']."</td>";
    echo"<td>". $row2['ACC_TYPE']."</td>";
    echo"<td>". $row2['ACC_BALANCE'] ."</td>";
    echo "</tr>";
  }
echo "</table>";

echo "<br>";
?>
	<div>
			<center><form action="view_account.html" method= "POST">
			<p>
				<input style="width: 150px; height: 30px;" type="Submit" id="btn" value="BACK" />
			</p>
			</form>
			</center>
	</div>
</body>

</html>