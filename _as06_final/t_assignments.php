<?php 

session_start();
if(!isset($_SESSION["t_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['t_person_id'];

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
		//gets logo
			include 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><u><?php if($id) echo 'My'; else echo'All';?> Tasks</u></h3>
		</div>
		
		<div class="row">
			
			<p>
				<?php //if($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person')
					echo '<a href="t_task_create.php" class="w3-button w3-pale-green w3-round-xlarge">Add Task</a>';
				?>
				<?php //if($_SESSION['t_person_title']=='Person' || $_SESSION['t_person_title']=='Administrator')
					 
				echo '&nbsp; <a href="t_per_create.php" class="w3-button w3-pale-green w3-round-xlarge"> Add Person</a>&nbsp;';
				//echo '&nbsp;&nbsp;<a href="t_tasks.php" class="w3-button w3-pale-blue w3-round-xlarge">Take the task </a>&nbsp;';
				?>
				
				<?php //if($_SESSION['t_person_title']=='Administrator')
					echo '<a href="t_persons.php"><b><u>Persons</b></u></a> &nbsp;';
				?>
				<a href="t_tasks.php"><b><u>Tasks</b></u></a> &nbsp;
				<?php //if($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person')
					echo '<a href="t_assignments.php"><b><u>AllTasks</b></u></a>&nbsp;';
				?>
				<a href="t_assignments.php?id=<?php echo $sessionid; ?>"><b><u>MyTasks</b></u></a>&nbsp;
				<a href="logout.php" class="w3-button w3-khaki w3-round-xlarge">Logout</a> &nbsp;&nbsp;&nbsp;
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Date</th>
						<th>Time</th>
						
						<th>Task</th>
						<th>Person</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM t_assign 
						LEFT JOIN t_person ON t_person.id = t_assign.assign_person_id 
						LEFT JOIN t_task ON t_task.id = t_assign.assign_task_id
						WHERE t_person.id = $id 
						ORDER BY task_date ASC, task_time ASC, lname ASC, lname ASC;";
					else
						$sql = "SELECT * FROM t_assign 
						LEFT JOIN t_person ON t_person.id = t_assign.assign_person_id 
						LEFT JOIN t_task ON t_task.id = t_assign.assign_task_id
						ORDER BY task_date ASC, task_time ASC, lname ASC, lname ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. Functions::dayMonthDate($row['task_date']) . '</td>';
						echo '<td>'. Functions::timeAmPm($row['task_time']) . '</td>';
						
						echo '<td>'. $row['task_description'] . '</td>';
						echo '<td>'. $row['lname'] . ', ' . $row['fname'] . '</td>';
						echo '<td width=350>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="w3-button w3-khaki w3-round-xlarge" href="t_assign_read.php?id='.$row[2].'">Details</a>';
						if ($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person' )
							echo '&nbsp;<a class="w3-button w3-pale-green w3-round-xlarge" href="t_assign_update.php?id='.$row[2].'">Update</a>';
						if ($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person'
							|| $_SESSION['t_person_id']==$row['assign_person_id'])
							echo '&nbsp;<a class="w3-button w3-pale-red w3-round-xlarge" href="t_assign_delete.php?id='.$row[2].'">Delete</a>';
						if($_SESSION["t_person_id"] == $row['assign_person_id']) 		echo " &nbsp;&nbsp;Me";
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>