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
		echo "<br>";
	$trans = mysqli_query($db,"select * from loan_transactions where ACC_ID_FK =".$acc_no.";") or die ("failed to connect" .mysql_error());
	echo "<table border='4' width=100% id= results>
<tr>
<th>ACCOUNT NUMBER</th>
<th>LOAN AMOUNT PAID</th>
<th>LOAN AMOUNT BEFORE</th>
<th>LOAN AMOUNT AFTER</th>
<th>DATE</th>
</tr>";

while($row = mysqli_fetch_array($trans))
  {


  echo "<tr>"; 

    echo "<td>" . $row['ACC_ID_FK'] . "</td>";
    echo"<td>". $row['L_AMT_PAID']."</td>";
    echo"<td>". $row['L_AMT_B']."</td>";
    echo"<td>". $row['L_AMT_A'] ."</td>";
    echo"<td>". $row['LT_DATE'] ."</td>";
  echo "</tr>";
  }
echo "</table>";
echo "<br>";
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