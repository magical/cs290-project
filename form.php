<?php 
	require_once 'includes/all.php'; 
?>
<!DOCTYPE html>
<html>
<head>
	<title> Search Groups </title>
	<?php 
		include 'includes/_head.html';
		$db = connect_db();
		$user_courses = get_user_courses($db, get_logged_in_user_id());
	?>
</head>

<body>
	<?php 
		include 'includes/_nav.php';
	?>

	<h2> Basic Search Page </h2>
	<form role="form" action = "search.php" method = "get">
		<div class="form-group">
			<label for="courseid">Course:</label>
			<select id="courseid" name="course" class="form-control">
					<?php
						foreach ($user_courses as $course) {
							echo '<option value="'.htmlspecialchars($course['id']).'">';
							echo htmlspecialchars($course['department'].' '.$course['number'].' '.$course['title']);
							echo '</option>';
						}
						?>
			</select>

			<p class="help-block">
				To search for groups for a course not listed above, <a>add yourself to the course</a> first.
		</div>
	
		<div class="form-group">
			<label for="group">Group Name:</label>
			<input name="group" type="text" class="form-control" id="group" placeholder = "Group Name">
		</div>
		
		<div class="form-group">
       		<button class="btn btn-primary">Search</button>
      	</div>
	</form>
	
	<form action="search.php" method="get">
		<h3>
			Or alternatively show all groups: </br>		
		</h3>
		<div class="form-group">
			<input type="hidden" name="all" value="1">
			<input type="submit">
		</div>
	</form>

	<?php 
		include 'includes/_footer.php';
	?>
	
</body>
</html>
