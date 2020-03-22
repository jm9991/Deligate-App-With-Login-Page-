<?php

session_start();
if(!isset($_SESSION["t_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
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

<body >
    <div class="container">
		<?php 
			//gets logo
			require 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><b><u>Person</u></b></h3>
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
				<!--
				if($_SESSION['t_person_title']=='Administrator')
					echo '<a href="t_persons.php"><b><u>Persons</b></u></a> &nbsp;';
				-->
				<a href="t_tasks.php"><b><u>Tasks</b></u></a> &nbsp;
				<?php //if($_SESSION['t_person_title']=='Administrator' || $_SESSION['t_person_title']=='Person')
					echo '<a href="t_assignments.php"><b><u>AllTasks</b></u></a>&nbsp;';
				?>
				<a href="t_assignments.php?id=<?php echo $sessionid; ?>"><b><u>MyTasks</b></u></a>&nbsp;
				<a href="logout.php" class="w3-button w3-khaki w3-round-xlarge">Logout</a> 
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `t_person`.*, COUNT(`t_assign`.assign_person_id) AS countAssigns FROM `t_person` LEFT OUTER JOIN `t_assign` ON (`t_person`.id=`t_assign`.assign_person_id) GROUP BY `t_person`.id ORDER BY `t_person`.lname ASC, `t_person`.fname ASC';
						//$sql = 'SELECT * FROM fr_persons ORDER BY `fr_persons`.lname ASC, `fr_persons`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') '.' - UNASSIGNED</td>';
							else
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') - '.$row['countAssigns']. ' tasks</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['mobile'] . '</td>';
							echo '<td width=300>';
							# always allow read
							echo '<a class="w3-button w3-khaki w3-round-xlarge" href="t_per_read.php?id='.$row['id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['t_person_title']=='Administrator'
								|| $_SESSION['t_person_id']==$row['id'])
								echo '<a class="w3-button w3-pale-green w3-round-xlarge" href="t_per_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							# only admins can delete
							
							if (//$_SESSION['t_person_title']=='Administrator' &&
								$row['countAssigns']==0)
								echo '<a class="w3-button w3-pale-red w3-round-xlarge" href="t_per_delete.php?id='.$row['id'].'">Delete</a>&nbsp;&nbsp;';
							
							//--- new 
							if($_SESSION["t_person_id"] == $row['id']) {
							if ( !empty($_POST)) {
								$valid = true;
								$fileName = $_FILES['userfile']['name'];
								$tmpName  = $_FILES['userfile']['tmp_name'];
								$fileSize = $_FILES['userfile']['size'];
								$fileType = $_FILES['userfile']['type'];
								$content = file_get_contents($tmpName);
								$types = array('image/jpeg','image/gif','image/png');								
								if($filesize > 0) {
									if(in_array($_FILES['userfile']['type'], $types)) {										
									}
									else {
										$filename = null;
										$filetype = null;
										$filesize = null;
										$filecontent = null;
										$pictureError = 'improper file type';
										$valid=false;										
									}
								}
								if ($valid) 
								{
									$pdo = Database::connect();									
									$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$sql = "INSERT INTO images (new_id,
									filename,filesize,filetype,filecontent) values( ?, ?, ?, ?, ?)";
									$q = $pdo->prepare($sql);
									$q->execute(array($_SESSION['t_person_id'],
									$fileName,$fileSize,$fileType,$content));									
									Database::disconnect();																		
								}
								}
								//----
							
							
							if($_SESSION["t_person_id"] == $row['id']) 
								echo "Me";
							?>
							<!--new -------->
							<form class="form-group" action="t_persons.php" method="post" enctype="multipart/form-data">
								
								<label class="control-horizontal" id="u" ><b>Upload Picture</b></label>
					            <div id="inline"> 
								<input type="hidden" name="MAX_FILE_SIZE" value="16000000" > 
								
								<input name="userfile" type="file" id="userfile"> 
								<br>
								<br>
								<button type="submit" class="w3-button w3-pale-green w3-round-xlarge" id="b">Submit Image</button>
								</div> 
							
								</form>
							
							<?php
							;}
							//-------------------------------
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>