<?php 
	require_once 'includes/all.php'; 
	if (!is_logged_in()) {
   header("Location: signin.php");
   exit(0);
}
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
	<form name="form" role="form" action = "search.php" onSubmit="return validate()" method = "get">
		<div class="form-group">
			<label for="courseid">Course:</label>
			<select id="courseid" name="course" class="form-control">
					<?php
						echo '<option value="">';
							echo 'Search by Class (optional)';
						echo '</option>';
						
						foreach ($user_courses as $course) {
							echo '<option value="'.htmlspecialchars($course['id']).'">';
							echo htmlspecialchars($course['department'].' '.$course['number'].' '.$course['title']);
							echo '</option>';
						}
						?>
			</select>

			<p class="help-block">
				To search for groups for a course not listed above, <a href="course_edit.php">add yourself to the course</a> first.
		</div>
	
		<div class="form-group">
			<label for="group">Group Name:</label>
			<p>
				<input name="group" type="text" class="form-control" id="group" placeholder = "Group Name">
			</p>
		</div>
		
		<p>
			<input type="submit" value="Search" class="btn btn-primary">
      	</p>
	</form>
	
	<form action="search.php" method="get">
		<h3>
			Or alternatively show all groups: <br>		
		</h3>
		<div class="form-group">
			<input type="hidden" name="all" value="1">
			<input type="submit">
		</div>
	</form>

	<?php 
		include 'includes/_footer.php';
	?>
	<script>
		function validate() {
			if (form.course.value==""
				&& form.group.value==""){
				alert( "You need to complete at least one field to search" );
				return false;
			}
		}
	</script>	
</body>
</html>
