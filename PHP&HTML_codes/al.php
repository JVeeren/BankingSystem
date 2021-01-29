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
	<p>
		<?php
		
		echo "EMPLOYEE ID: ".$_SESSION['id'];
		?>
	</p>
	<?php
	 
     //connect to server
$db= mysqli_connect('localhost','root','','banking_system') or die("Unable to connect :(");
	
    //Query
		$id = $_SESSION['id'];
	echo "<br>";
	$ad = mysqli_query($db,"select * from account_details;") or die ("failed to connect" .mysqli_error());
	echo "<table border='4' width=60% align=center id= results>
	
<tr>
<th>ACCOUNT TYPE</th>
<th>INTEREST RATE</th>
<th>MINIMUM BALANCE</th>
</tr>";

while($row1 = mysqli_fetch_array($ad))
  {


  echo "<tr>"; 

    echo "<td>" . $row1['ACC_TYPE'] . "</td>";
    echo"<td>". $row1['S_INTEREST_RATE']."</td>";
    echo"<td>". $row1['S_MIN_BALANCE']."</td>";
    echo "</tr>";
  }
echo "</table>";
echo "<br>";
echo "<br>";
$ld = mysqli_query($db,"select * from loan_details;") or die ("failed to connect" .mysqli_error());
	echo "<table border='4' width=60% align=center id= results>
	
<tr>
<th>LOAN TYPE</th>
<th>LOAN RATE</th>
</tr>";

while($row2 = mysqli_fetch_array($ld))
  {


  echo "<tr>"; 

    echo "<td>" . $row2['LOAN_TYPE_PK'] . "</td>";
    echo"<td>". $row2['LOAN_RATE']."</td>";
  echo "</tr>";
  }
echo "</table>";
echo "<br>";
?>
	<div>
			<center><form action="view_account.php" method= "POST">
			<p>
				<input style="width: 150px; height: 30px;" type="Submit" id="btn" value="BACK" />
			</p>
			</form>
			</center>
	</div>
</body>

</html>
