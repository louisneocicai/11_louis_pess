<?php
$callerName = $_POST [“callerName”];
$contactNo = $_POST [“contactNo”];
$locationOfIncident = $_POST [“LocationOfIncident”];
$typeOfIncident = $_POST[“typeOfIncident”]
$descriptionOfIncident = $_POST[“discriptionOfIncident”]
Require_once “db.php”;
$conn = new mySqli (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
$sql = “SELECT patrolcar.patrolcar_id, patrolcar_status.patrolcar_status_desc FROM ‘patrolcar’ INNER JOIN patrolcar_status ON patrolcar.patrolcar_status_id = patrolcar_status.patrolcar_status_id”; 
$result = $conn -﹥query($sql);
$cars = [ ];
while($row = $result -﹥fetch_assoc( ) ) {
 $id = $row [“patrolcar_id”];
  $status = $row[“patrolcar_id”];
  $car = [“id”=>$id, “status”=>$status];
   Array_push ($cars, $car);
}
$conn->close();

$btnDispatchClicked = isset ($_POST[“btnDispatch”]);
$btnProcessCallClicked = isset ($_POST[“btnProcessCall”]);
If($btnDispatchClicked == false && $btnProcessCallClicked == false) {
	
}

if($btnDispatchClicked == true) {
	$insertIncidentSuccess = false;
	$hasCarSelection = isset($_POST["cbCarSelection"]);
	$patrolcarDispatched = [];
	$numOfPatrolCarDispatched = 0;
	if($hasCarSelection == true) {
		$patrolcarDispatched = $_POST["cbCarSelection"] 
		$numOfPatrolCarDispatched = count($patrolcarDispatched);	
	}
	
	$incidentStatus = 0;
	
	if($hasCarSelection > 0) {
		$incidentStatus = 2; //dispatched
	}
	else {
	$incidentStatus = 1; //pending		
	}	
	$callerName = $_POST [“callerName”];
    $contactNo = $_POST [“contactNo”];
	$locationOfIncident = $_POST [“LocationOfIncident”];
	$typeOfIncident = $_POST[“typeOfIncident”];
	$descriptionOfIncident = $_POST[“discriptionOfIncident”];
	
 	$sql = "INSERT INTO incident(caller_name, phone_number, incident_type_id, incident_location, incident_desc, incident_status_id, time_called) VALUES ('" . $callerName . "','" . $contactNo . "','" . $typeOfIncident . "','" . $locationOfIncident . "','" . $descriptionOfIncident . "','" . $incidentStatus . "',now())";
    //echo $sql;
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    $insertIncidentSuccess = $conn->query($sql);
    if($insertIncidentSuccess == false) {
      echo "Error:" . $sql . "<br>" . $conn->error;
    }
    $incidentId = mysqli_insert_id($conn);
    //echo "<br>new incident id: " . $incidentId;
    $updateSuccess = false;
    $insertDispatchSuccess = false;
    
    foreach($patrolcarDispatched as $eachCarId) {
      //echo $eachCarId . "<br>";
      
      $sql = "UPDATE patrolcar SET patrolcar_status_id=1 WHERE patrolcar_id='" . $eachCarId . "'";
      $updateSuccess = $conn->query($sql);
      
      if($updateSuccess == false) {
        echo "Error:" . $sql . "<br>" . $conn->error;        
      }
      
      $sql = "INSERT INTO dispatch(incident_id, patrolcar_id, time_dispatched) VALUES (" . $incidentId . ",'" . $eachCarId . "',now())";
      $insertDispatchSuccess = $conn->query($sql);
      
      if($insertDispatchSuccess == false) {
        echo "Error:" . $sql . "<br>" . $conn->error;
      }
    }
    $conn->close();
    
    if($insertDispatchSuccess == true && $updateSuccess == true && $insertDispatchSuccess == true)  {
      header("location: logcall.php");
    }
  }
?>
<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>Dispatch</title>
<link href="css/bootstrap-4.3.1.css" rel="stylesheet" type="text/css">
<style type="text/css">
</style>
</head>

<body>

	<div class="container" style="width: 930px">
		<header>
			<img src="images/banner.jpg" width="900" height="200" alt="" />
		</header>
      <?php
    require_once 'nav.php';
    ?>
      
       <section style="margin-top: 20px">
			<form action="dispatch.php" method="post">
				<div class="form-group row">
					<label for="callerName" class="col-sm-4 col-form-label">Caller's
						Name </label>
					<div class="col-sm-8">
						<span id="callerName">
                     		<?php echo $callerName;?>
                     		<input type="hidden" name="callerName"
							id="callerName" value="<?php echo $callerName;?>">
						</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="contactNo" class="col-sm-4 col-form-label"> Contact No:
					</label>
					<div class="col-sm-8">
						<span id="contactNo">
                     		<?php echo $contactNo;?>
                     		<input type="hidden" name="contactNo"
							id="contactNo" value="<?php echo $contactNo;?>">
						</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="locationOfIncident" class="col-sm-4 col-form-label">
						Location of Incident: </label>
					<div class="col-sm-8">
						<span id="locationOfIncident">
                     		<?php echo $locationOfIncident;?>
                     			<input type="hidden" name="locationOfIncident"
							id="locationOfIncident" value="<?php echo $locationOfIncident;?>">
						</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="typeOfIncident" class="col-sm-4 col-form-label"> Type
						of Incident: </label>
					<div class="col-sm-8">
						<span id="typeOfIncident">
                     		<?php echo $typeOfIncident;?>
                     		<input type="hidden" name="typeOfIncident"
							id="typeOfIncident" value="<?php echo $typeOfIncident;?>">
						</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="descriptionOfIncident" class="col-sm-4 col-form-label">
						Description of Incident: </label>
					<div class="col-sm-8">
						<span id="descriptionOfIncident">
                     		<?php echo $descriptionOfincident;?>
                     		<input type="hidden" name="descriptionOfIncident"
							id="descriptionOfIncident"
							value="<?php echo $descriptionOfincident;?>">
						</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="patrolCars" class="col-sm-4 col-form-label"> Choose a
						Patrol Car </label>
					<div class="col-sm-8">
						<table id="patrolCars" class="table table-striped">
							<tbody>
								<tr>
									<th>Car Number</th>
									<th>Status</th>
									<th></th>
								</tr>
                     		
                     		</tbody>
						</table>
					</div>
				</div>

				<div class="form-group row">

					<div class="col-sm-4"></div>

					<div class="col-sm-8" style="text-align: center">
						<input type="submit" name="btnDispatch" id="btnDispatch"
							value="Dispatch" class="btn btn-primary">
					</div>
				</div>

			</form>
		</section>



	</div>
</body>

</html>
