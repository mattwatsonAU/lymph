<?php include "../../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>Patient Entry</title>
    <link rel="icon" href="../assets/favicon-16x16.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.js"></script>
    <link rel="stylesheet" media="screen" href="/assets/stylesheet.css" />
    <style>
    canvas{
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
</head>
<body>


<nav>
  <ul>
    <li><a href="../">Home</a></li>
    <li><a href="entry.php">Patient Entry</a></li>
    <li><a href="patients.php">Patients</a></li>
  </ul>
</nav>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  $patient_id = htmlentities($_POST['PatientID']);

  if (strlen($patient_id)){
    header('Location: ' . $patient_id . '.php');
  }
?>

<!-- Patient table-->
<table class="patient-form" border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>PatientID</td>
    <td>Surname</td>
    <td>First Name</td>
    <td>Date of Birth</td>
    <td>Country</td>
    <td>Gender</td>
    <td>Height (cm)</td>
    <td>Address</td>
    <td>Post Code</td>
    <td>Phone</td>
    <td>Email</td>
    <td>Pension Status</td>
    <td>Health Insurance Status</td>
    <td>Cellulitis History</td>
    <td>Dominant Arm</td>
    <td>Affected Side</td>
    <td>Affected/At Risk Side</td>
    <td>Diagnosis</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM Patients"); 

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[5], "</td>",
       "<td>",$query_data[6], "</td>",
       "<td>",$query_data[7], "</td>",
       "<td>",$query_data[8], "</td>",
       "<td>",$query_data[9], "</td>",
       "<td>",$query_data[10], "</td>",
       "<td>",$query_data[11], "</td>",
       "<td>",$query_data[12], "</td>",
       "<td>",$query_data[13], "</td>",
       "<td>",$query_data[14], "</td>",
       "<td>",$query_data[15], "</td>",
       "<td>",$query_data[16], "</td>",
       "<td>",$query_data[17], "</td>";
  echo "</tr>";
}
?>

</table>  
<br><br>
<div class="form">
  <form id="form" action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
      <legend>Patient ID:</legend>
      <input type="number" id="patientID" name="PatientID" /><br><br>
      <input type="submit" value="Open Patient Record" name="GoToPatient"/>
    </form>
</div>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>




<html>

<head>
    <title>Team Lymph</title>
    <link rel="icon" href="/assets/favicon-16x16.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.js"></script>
    <link rel="stylesheet" media="screen" href="/assets/login.css" />
    <link href="/assets/style3.css" rel="stylesheet"/>
    </style>
</head>

<body>

<script>
        $('.message a').click(function(){
            $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        });

</script>



</body>

</html>