<?php

session_start();
if(!isset($_SESSION["t_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['t_person_id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="js/bootstrap.min.js"></script>
	
</head>

<body >
    <div class="container">
		  <?php 
			//gets logo
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><b><u>Tasks</b></u></h3>
		</div>
		
		<div class="row">
			
			<p>
				<?php //if($_SESSION['t_person_title']=='Administrator')
					echo '<a href="t_task_create.php" class="w3-button w3-pale-green w3-round-xlarge">Add Task</a>';
				?>
				<?php //if($_SESSION['t_person_title']=='Person' || $_SESSION['t_person_title']=='Administrator')
					 
				echo '&nbsp; <a href="t_per_create.php" class="w3-button w3-pale-green w3-round-xlarge"> Add Person</a>&nbsp;';
				echo '&nbsp;&nbsp;<a href="t_assign_create.php" class="w3-button w3-pale-green w3-round-xlarge">Take a task </a>&nbsp;';
				?>
				
				
				<!-- <a href="t_tasks.php"><b><u>Tasks</b></u></a> &nbsp; -->
				
				<?php //if($_SESSION['t_person_title']=='Administrator')
					echo '<a href="t_persons.php"><b><u>Persons</b></u></a> &nbsp;';
				?>
				
				<?php //if($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person')
					echo '<a href="t_assignments.php"><b><u>AllTasks</b></u></a>&nbsp;';
				?>
				<a href="t_assignments.php?id=<?php echo $sessionid; ?>"><b><u>MyTasks</b></u></a>&nbsp;
				<a href="logout.php" class="w3-button w3-khaki w3-round-xlarge">Logout</a> 
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Date</th>
						<th>Time</th>
						
						<th>Description</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `t_task`.*, SUM(case when assign_person_id ='. $_SESSION['t_person_id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`t_assign`.assign_task_id) AS countAssigns FROM `t_task` LEFT OUTER JOIN `t_assign` ON (`t_task`.id=`t_assign`.assign_task_id) GROUP BY `t_task`.id ORDER BY `t_task`.task_date ASC, `t_task`.task_time ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. Functions::dayMonthDate($row['task_date']) . '</td>';
							echo '<td>'. Functions::timeAmPm($row['task_time']) . '</td>';
							
							if ($row['countAssigns']==0)
								echo '<td>'. $row['task_description'] . ' - UNSTAFFED </td>';
							else
								echo '<td>'. $row['task_description'] . ' (' . $row['countAssigns']. ' person)' . '</td>';
							//echo '<td width=250>';
							echo '<td>';
							echo '<a class="w3-button w3-khaki w3-round-xlarge" href="t_task_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							//if ($_SESSION['t_person_title']=='Person' )
								//echo '<a class="w3-button w3-pale-blue w3-round-xlarge" href="t_assign_create.php?id='.$row['id'].'">Take Ownership</a> &nbsp;';
							//if ($_SESSION['t_person_title']=='Administrator' )
								echo '<a class="w3-button w3-pale-green w3-round-xlarge" href="t_task_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							if ($_SESSION['t_person_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="w3-button w3-pale-red w3-round-xlarge" href="t_task_delete.php?id='.$row['id'].'">Delete</a>';
							if($row['sumAssigns']==1) 
								echo " &nbsp;&nbsp;Me";
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