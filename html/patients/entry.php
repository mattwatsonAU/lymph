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

  /* Ensure that the Patients table exists. */
  VerifyPatientsTable($connection, DB_DATABASE); 

  /* If input fields are populated, add a row to the Patients table. */
  $patient_lastName = htmlentities($_POST['lastName']);
  $patient_firstName = htmlentities($_POST['firstName']);
  $patient_dob = htmlentities($_POST['DOB']);
  $patient_country = htmlentities($_POST['Country']);
  $patient_gender = htmlentities($_POST['Gender']);
  $patient_height = htmlentities($_POST['Height']);
  $patient_address = htmlentities($_POST['Address']);
  $patient_postCode = htmlentities($_POST['postCode']);
  $patient_phoneNumber = htmlentities($_POST['phoneNumber']);
  $patient_email = htmlentities($_POST['Email']);
  $patient_pension = htmlentities($_POST['Pension']);
  $patient_healthInsurance = htmlentities($_POST['Insurance']);
  $patient_cellulitus = htmlentities($_POST['Cellulitus']);
  $patient_dominantArm = htmlentities($_POST['dominantArm']);
  $patient_affectedSide = htmlentities($_POST['affectedSide']);
  $patient_affectedAtRisk = htmlentities($_POST['affectedAtRisk']);
  $patient_diagnosis = htmlentities($_POST['Diagnosis']);

  if (strlen($patient_lastName) || strlen($patient_firstName)) {
    AddPatient($connection, $patient_lastName, $patient_firstName, $patient_dob, $patient_country, $patient_gender, $patient_height, $patient_address, $patient_postCode, $patient_phoneNumber, $patient_email, $patient_pension, $patient_healthInsurance, $patient_cellulitus, $patient_dominantArm, $patient_affectedSide, $patient_affectedAtRisk, $patient_diagnosis);
  }
?>

<div class="patient-page">
  <div class="patient-form" style="float: left; max-width: 500px">
    <form id="form" action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
      <legend>Patient Details:</legend><br>
      <input type="text" id="lastName" name="lastName" placeholder="Surname" required=""/><br>
      <input type="text" id="firstName" name="firstName" placeholder="First Name" required=""/><br>
      <input type="date" id="DOB" name="DOB" placeholder="Date of Birth" required=""/><br>
      <input type="text" id="country" name="Country" placeholder="Country" required=""/><br>
      <input type="text" id="gender" name="Gender" placeholder="Gender" required=""/><br>
      <input type="number" id="height" name="Height" placeholder="Height (cm)" required="" min="0" max="300"/><br>
      <input type="text" id="address" name="Address" placeholder="Address" required=""/><br>
      <input type="number" id="postCode" name="postCode" placeholder="Post Code" required="" min="1000" max="9999"/><br>
      <input type="number" id="phoneNumber" name="phoneNumber" placeholder="Phone" required="" min="1" max="9999999999"/><br>
      <input type="email" id="email" name="Email" placeholder="Email" required=""/><br>
      <input type="text" id="pension" name="Pension" placeholder="Pension Status" required=""/><br>
      <input type="text" id="healthInsurance" name="Insurance" placeholder="Health Insurance Status" required=""/><br>
      <legend>Patient Diagnosis:</legend><br>
      <input type="text" id="cellulitus" name="Cellulitus" placeholder="Cellulitus History" required=""/><br>
      <input type="text" id="dominantArm" name="dominantArm" placeholder="Dominant Arm (Left/Right)" required=""/><br>
      <input type="text" id="affectedSide" name="affectedSide" placeholder="Affected Side (Left/Right)" required=""/><br>
      <input type="text" id="affectedAtRisk" name="affectedAtRisk" placeholder="Affected / At Risk" required=""/><br>
      <input type="text" id="diagnosis" name="Diagnosis" placeholder="Diagnosis / Causative Factors" required=""/><br>
      <input type="submit" value="Add Data"/>
    </form>
  </div>
</div>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a patient to the table. */
function AddPatient($connection, $lastName, $firstName, $DOB, $country, $gender, $height, $address, $postCode, $phoneNumber, $email, $pension, $healthInsurance, $cellulitus, $dominantArm, $affectedSide, $affectedAtRisk, $diagnosis) {
   $ln = mysqli_real_escape_string($connection, $lastName);
   $fn = mysqli_real_escape_string($connection, $firstName);
   $d = mysqli_real_escape_string($connection, $DOB);
   $c = mysqli_real_escape_string($connection, $country);
   $g = mysqli_real_escape_string($connection, $gender);
   $h = mysqli_real_escape_string($connection, $height);
   $a = mysqli_real_escape_string($connection, $address);
   $pc = mysqli_real_escape_string($connection, $postCode);
   $pn = mysqli_real_escape_string($connection, $phoneNumber);
   $e = mysqli_real_escape_string($connection, $email);
   $pen = mysqli_real_escape_string($connection, $pension);
   $hi = mysqli_real_escape_string($connection, $healthInsurance);
   $cell = mysqli_real_escape_string($connection, $cellulitus);
   $da = mysqli_real_escape_string($connection, $dominantArm);
   $as = mysqli_real_escape_string($connection, $affectedSide);
   $aar = mysqli_real_escape_string($connection, $affectedAtRisk);
   $diag = mysqli_real_escape_string($connection, $diagnosis);

   $query = "INSERT INTO `Patients` (`lastName`, `firstName`, `DOB`, `country`, `gender`, `height`, `address`, `postCode`, `phoneNumber`, `email`, `pension`, `healthInsurance`, `cellulitus`, `dominantArm`, `affectedSide`, `affectedAtRisk`, `diagnosis`) VALUES ('$ln', '$fn', '$d', '$c', '$g', '$h', '$a', '$pc', '$pn', '$e', '$pen', '$hi', '$cell', '$da', '$as', '$aar', '$diag');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding patient data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyPatientsTable($connection, $dbName) {
  if(!TableExists("Patients", $connection, $dbName)) 
  { 
     $query = "CREATE TABLE `Patients` (
         `PatientID` int(11) NOT NULL AUTO_INCREMENT,
         `lastName` varchar(45) DEFAULT NULL,
         `firstName` varchar(45) DEFAULT NULL,
         `DOB` varchar(10) DEFAULT NULL,
         `country` varchar(45) DEFAULT NULL,
         `gender` varchar(45) DEFAULT NULL,
         `height` int(4) DEFAULT NULL,
         `Address` varchar(90) DEFAULT NULL,
         `postCode` int(4) DEFAULT NULL,
         `phoneNumber` varchar(10) DEFAULT NULL,
         `email` varchar(90) DEFAULT NULL,
         `pension` varchar(90) DEFAULT NULL,
         `healthInsurance` varchar(90) DEFAULT NULL,
         `cellulitus` varchar(90) DEFAULT NULL,
         `dominantArm` varchar(90) DEFAULT NULL,
         `affectedSide` varchar(90) DEFAULT NULL,
         `affectedAtRisk` varchar(90) DEFAULT NULL,
         `diagnosis` varchar(90) DEFAULT NULL,
         PRIMARY KEY (`PatientID`),
         UNIQUE KEY `PatientID_UNIQUE` (`PatientID`)
       ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection, 
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
