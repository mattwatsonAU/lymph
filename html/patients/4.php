<?php include "../../inc/dbinfo.inc"; ?>
<html>
<head>
    <title>Patient 4</title>
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
<h1>Patient 4 Records</h1>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the Patients table. */
  $patient_id = '4';
  $patient_control = htmlentities($_POST['Control']);
  $patient_affected = htmlentities($_POST['Affected']);
  $patient_interLimbDifference = (($patient_affected - $patient_control)/$patient_affected)*100;
  $patient_date = htmlentities($_POST['Date']);

  if (strlen($patient_control) || strlen($patient_affected)) {
    AddRecord($connection, $patient_id, $patient_control, $patient_affected, $patient_date, $patient_interLimbDifference);
  }

  $record_undo = htmlentities($_POST['Undo']);
  if (strlen($record_undo)){
    DeleteLastRecord($connection);
  }

  $record_deleteAll = htmlentities($_POST['DeleteAll']);
  if (strlen($record_deleteAll)){
    DeleteAllRecords($connection);
  }

  $record_downloadCSV = htmlentities($_POST['DownloadCSV']);
  if (strlen($record_downloadCSV)){
    DownloadCSV($connection);
  }

?>

<div class="dashboard-page">
  <div class="graph">
      <canvas id="line-chart"></canvas>
  </div>
  <div class="dashboard-form" style="float: left">
    <form id="form" action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
      <legend>Data input:</legend>
      <input type="number" id="inputControl" name="Control" placeholder="Control Limb Volume (mL)" />
      <input type="number" id="inputAffected" name="Affected" placeholder="Affected Limb Volume (mL)" /><br>
      <input type="date" id="date" name="Date" placeholder="Date" required=""/>
      <input type="submit" value="Add Data"/>
    </form>
  </div>

  <div class="dashboard-form" style="float: right">
    <form id="form" action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
      <br>
      <input type="submit" value="Undo last entry" name="Undo"/>
      <input type="submit" value="Delete all records" name="DeleteAll"/>
      <input type="submit" value="Download patient data" name="DownloadCSV"/>
    </form>
  </div>


</div>


<?php

$result = mysqli_query($connection, "SELECT * FROM Records WHERE PatientID=4"); 

$arr_control = array();
$arr_affected = array();
$arr_date = array();
$arr_diff = array();


while($query_data = mysqli_fetch_row($result)) {
  array_push($arr_control, $query_data[1]);
  array_push($arr_affected, $query_data[2]);
  array_push($arr_date, $query_data[3]);
  array_push($arr_diff, $query_data[4]);
}
json_encode($arr_control);
json_encode($arr_affected);
json_encode($arr_date);
json_encode($arr_diff);
?>

<script>
        // var controlLimb = [5, 6, 6, 5, 5, 5, 5, 5, 6, 5, 5, 5];
        var controlLimb =  <?php echo json_encode($arr_control) ?>;
        // var affectedLimb = [5, 8, 9, 12, 17, 26, 23, 23, 21, 19, 14, 8];
        var affectedLimb = <?php echo json_encode($arr_affected) ?>;
        var MONTHS = <?php echo json_encode($arr_date) ?>;
        var ilDiff = <?php echo json_encode($arr_diff) ?>;

        var config = {
            type: 'line',
            data: {
                labels: MONTHS,
                datasets: [{ 
                    data: controlLimb,
                    label: "Control Limb",
                    borderColor: "#3e95cd",
                    fill: false
                }, { 
                    data: affectedLimb,
                    label: "Affected Limb",
                    borderColor: "#8e5ea2",
                    fill: false
                }, {
                  data: ilDiff,
                  label: "Inter-Limb Difference",
                  borderColor: "#b72047",
                  fill: false
                }]
            },
            options: {
                        responsive: true,
                        title:{
                            display:true,
                            text:'Patient 4 Treatment'   
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Limb Volume (mL), Inter-Limb Difference (%)'
                                }
                            }]
                        }
                    }
        };

        window.onload = function() {
            var ctx = document.getElementById("line-chart").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

        
    </script>
    <script>
        function othername() {

            // var inputC = document.getElementById("inputControl").value;
            // var inputA = document.getElementById("inputAffected").value;
            // alert(input);
            // controlLimb.push(document.getElementById("inputControl").value)
            // affectedLimb.push(document.getElementById("inputAffected").value)
            var month = MONTHS;
            config.data.labels.push(month);
            // alert(leftArm)
            window.myLine.update();
        }
    </script>
</script>


<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a patient record to the records table. */
function AddRecord($connection, $id, $control, $affected, $date, $interLimbDifference) {
   $i = mysqli_real_escape_string($connection, $id);
   $c = mysqli_real_escape_string($connection, $control);
   $a = mysqli_real_escape_string($connection, $affected);
   $d = mysqli_real_escape_string($connection, $date);
   $il = mysqli_real_escape_string($connection, $interLimbDifference);

   $query = "INSERT INTO `Records` (`Control_Limb`, `Affected_Limb`, `Date`, `ILDIFF`, `PatientID`) VALUES ('$c', '$a', '$d', '$il', '$i');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding patient data.</p>");
}

/* Delete the last record for this patient from the records table. */
function DeleteLastRecord($connection) {
  $result = mysqli_query($connection, "SELECT MAX(RecordID) FROM Records"); 
  $query_data = mysqli_fetch_row($result);
  $lastRecord = $query_data[0];
  if(!mysqli_query($connection, "DELETE FROM Records WHERE RecordID=$lastRecord")) echo("<p>Error deleting patient data, please ensure there is data to be deleted</p>");
}

/* Delete all records for this patient from the records table. */
function DeleteAllRecords($connection) {
  if(!mysqli_query($connection, "DELETE FROM Records WHERE PatientID=4")) echo("<p>Error deleting patient data, please ensure there is data to be deleted</p>");
}

/* Generate CSV file from this patient's records */
function DownloadCSV($connection) {
  header('Content-Type: application/csv');
  header('Content-Disposition: attachment; filename=data.csv');
  $result = mysqli_query($connection, "SELECT * FROM Records WHERE PatientID=4");
  ob_end_clean();
  $fp = fopen('php://output','w');
  fputcsv($fp, array('Record ID', 'Control Limb', 'Affected Limb', 'Date', 'Inter-Limb Difference', 'Patient ID'));
  while($query_data = mysqli_fetch_row($result)) {
    $entry = implode("','",$query_data)."'<br>";
    fputcsv($fp, $query_data);
  }
  
  fclose($fp);
  exit();
}


?>
