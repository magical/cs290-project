<?php 
	require_once 'includes/all.php'; 
?>
<!DOCTYPE html>
<html>
<head>
	<title> Search Groups </title>
	<?php 
		include 'includes/_head.html';
	?>
</head>

<body>
	<?php 
		include 'includes/_nav.php';
	?>

	<h2> Basic Search Page </h2>
	
	<form action = "search.php" method = "get">
		Group Name: <br>
		<input type = "text" name = "group" placeholder = "Group Name"><br><br>
		<input type = "submit" value = "Search"><br>
	</form>
	
	<form action = "search.php" method = "get">
	<h3>
		Or alternatively show all groups: </br>		
	</h3>
	<input type = "submit" name='all'> </br>
	</form>

	<?php 
		include 'includes/_footer.php';
	?>
	
</body>
</html>
