<?php 

session_start();
if(!isset($_SESSION["t_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
require 'database.php';
require 'functions.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form
	
	# same as create

	// initialize user input validation variables
	$personError = null;
	$taskError = null;
	
	// initialize $_POST variables
	$person = $_POST['person_id'];    // same as HTML name= attribute in put box
	$task = $_POST['task_id'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose a person';
		$valid = false;
	}
	if (empty($task)) {
		$taskError = 'Please choose a task';
		$valid = false;
	} 
		
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE t_assign set assign_person_id = ?, assign_task_id = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$task,$id));
		Database::disconnect();
		header("Location: t_assignments.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM t_assign where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$person = $data['assign_per_id'];
	$task = $data['assign_task_id'];
	Database::disconnect();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="js/bootstrap.min.js"></script>
	
</head>

<body>
    <div class="container">
        <?php 
			//gets logo
			functions::logoDisplay();
		?>
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Update Task</h3>
			</div>
	
			<form class="form-horizontal" action="t_assign_update.php?id=<?php echo $id?>" method="post">
		
				<div class="control-group">
					<label class="control-label">Person</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM t_person ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person_id' id='person_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$person)
									echo "<option selected value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								else
									echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Task</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM t_task ORDER BY task_date ASC, task_time ASC';
							echo "<select class='form-control' name='tsak_id' id='task_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$task) {
									echo "<option selected value='" . $row['id'] . " '> " . Functions::dayMonthDate($row['task_date']) . " (" . Functions::timeAmPm($row['task_time']) . ") - " . trim($row['task_description'])  . "</option>";
								}
								else {
									echo "<option value='" . $row['id'] . " '> " . Functions::dayMonthDate($row['task_date']) . " (" . Functions::timeAmPm($row['task_time']) . ") - " . trim($row['task_description']) .  "</option>";
								}
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="w3-button w3-pale-green w3-round-xlarge">Update</button>
					<a class="w3-button w3-sand w3-round-xlarge" href="t_assignments.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
</html>