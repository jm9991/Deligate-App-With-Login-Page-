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

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM t_person where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);

//--------------new

$sql = "SELECT images.* FROM images, t_person where t_person.id= images.new_id AND t_person.id=?";
$q = $pdo->prepare($sql);
$q->execute(array($data['id']));
//$perdata = $q->fetch(PDO::FETCH_ASSOC);
//----------------end

Database::disconnect();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link   href="css/bootstrap.min.css" rel="stylesheet">
		
		<script src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		
	</head>

	<body>
		<div class="container">
			<?php
				Functions::logoDisplay2();
			?>
			<div class="row">
				<h3>View Person Details</h3>
			</div>
			 
			<div class="form-horizontal" >
				
				<div class="control-group col-md-6">
				
					<label class="control-label">First Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['fname'];?> 
						</label>
					</div>
					
					<label class="control-label">Last Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['lname'];?> 
						</label>
					</div>
					
					<label class="control-label">Email</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['email'];?>
						</label>
					</div>
					
					<label class="control-label">Mobile</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['mobile'];?>
						</label>
					</div>     
					
					<label class="control-label">Title</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['title'];?>
						</label>
					</div>   
					
					<!-- password omitted on Read/View -->
					
					<div class="form-actions">
						<a class="w3-button w3-khaki w3-round-xlarge" href="t_persons.php">Back</a>
					</div>
					
				</div>
				
				<!-- Display photo, if any --> 

				<div class='control-group col-md-6'>
					<div class="controls ">
					<?php 
					if ($data['filesize'] > 0) 
						echo '<img  height=5%; width=15%; src="data:image/jpeg;base64,' . 
							base64_encode( $data['filecontent'] ) . '" />'; 
					else 
						echo 'No photo on file.';
					?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				
				<div class='control-group col-md-6'>
					<div class="controls ">
					<?php 
					while($perdata = $q->fetch(PDO::FETCH_ASSOC)){if ($perdata['filesize'] > 0) 
						echo '<img  height=5%; width=15%; src="data:image/jpeg;base64,' . 
							base64_encode( $perdata['filecontent'] ) . '"  /> &nbsp;'; 
					else 
						echo 'No photo on file.';
					}?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				
				<div class="row">
					<h4>Tasks for which this Person has been assigned</h4>
				</div>
				
				<?php
					$pdo = Database::connect();
					$sql = "SELECT * FROM t_assign, t_task WHERE assign_task_id = t_task.id AND assign_person_id = " . $id . " ORDER BY task_date ASC, task_time ASC";
					$countrows = 0;
					foreach ($pdo->query($sql) as $row) {
						echo Functions::dayMonthDate($row['task_date']) . ': ' . Functions::timeAmPm($row['task_time']) .  ' - ' . $row['task_description'] . '<br />';
						$countrows++;
					}
					if ($countrows == 0) echo 'none.';
				?>
				
			</div>  <!-- end div: class="form-horizontal" -->

		</div> <!-- end div: class="container" -->
		
	</body> 
	
</html>