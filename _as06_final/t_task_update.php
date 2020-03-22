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

	# initialize/validate (same as file: fr_event_create.php)

	// initialize user input validation variables
	$dateError = null;
	$timeError = null;
	
	$descriptionError = null;
	
	// initialize $_POST variables
	$date = $_POST['task_date'];
	$time = $_POST['task_time'];
	
	$description = $_POST['task_description'];	
	
	// validate user input
	$valid = true;
	if (empty($date)) {
		$dateError = 'Please enter Date';
		$valid = false;
	}
	if (empty($time)) {
		$timeError = 'Please enter Time';
		$valid = false;
	} 		
			
	if (empty($description)) {
		$descriptionError = 'Please enter Description';
		$valid = false;
	}
	
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE t_task  set task_date = ?, task_time = ?,  task_description = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($date,$time,$description,$id));
		Database::disconnect();
		header("Location: t_tasks.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM t_task where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$date = $data['task_date'];
	$time = $data['task_time'];

	$description = $data['task_description'];
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
				<h3><b>Update Task Details</b></h3>
			</div>
	
			<form class="form-horizontal" action="t_task_update.php?id=<?php echo $id?>" method="post">
			
				<div class="control-group <?php echo !empty($dateError)?'error':'';?>">
					<label class="control-label"><b>Date</b></label>
					<div class="controls">
						<input name="task_date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
						<?php if (!empty($dateError)): ?>
							<span class="help-inline"><?php echo $dateError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($timeError)?'error':'';?>">
					<label class="control-label"><b>Time</b></label>
					<div class="controls">
						<input name="task_time" type="time" placeholder="Time" value="<?php echo !empty($time)?$time:'';?>">
						<?php if (!empty($timeError)): ?>
							<span class="help-inline"><?php echo $timeError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label"><b>Description</b></label>
					<div class="controls">
						<input name="task_description" type="text" placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="w3-button w3-pale-green w3-round-xlarge">Update</button>
					<a class="w3-button w3-sand w3-round-xlarge" href="t_tasks.php">Back</a>
				</div>
				
			</form>
			
		</div><!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
</body>
</html>