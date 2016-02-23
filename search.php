<?php
	require_once "includes/all.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Results</title>
	<?php 
		include 'includes/_head.html';
	?>
</head>

<body>
	<?php 
		include 'includes/_nav.php';
	?>

	<h2>Search Results</h2>
<?php	

	if(isset($_GET['page'])) {
		$page = (int) $_GET['page'] * 10;		
	}else {
		$page = 0;
	}

	try{
	
		$db = connect_db();
		
		if(!isset($_GET['all']) && isset($_GET['group'])) {

			$stmnt = $db->prepare("SELECT * 
								  FROM groups 
								  JOIN courses ON courses.id=groups.course_id
								  WHERE LOWER(name) = LOWER(:name) 
								    AND is_private = FALSE
								  ORDER BY name 
								  LIMIT 10
								  OFFSET :page") or die($db);
			$stmnt->bindValue('name', $_GET['group']);
			$stmnt->bindValue('page', $page, PDO::PARAM_INT);
			$stmnt->execute();
			$search = $stmnt->fetchAll();
			
			$stmnt = $db->prepare("SELECT * 
								  FROM groups 
								  JOIN courses ON courses.id=groups.course_id
								  WHERE LOWER(name) = LOWER(:name)
								   AND is_private = FALSE") or die($db);
			$stmnt->bindValue('name', $_GET['group']);
			$stmnt->execute();
			
		}else if(isset($_GET['all'])){	
			
			$stmnt = $db->prepare("SELECT * 
								  FROM groups
								  JOIN courses ON courses.id=groups.course_id
								  WHERE name LIKE '%' 
								    AND is_private = FALSE
								  ORDER BY name 
								  LIMIT 10
								  OFFSET :page") or die($db);
			$stmnt->bindValue('page', $page, PDO::PARAM_INT);
			$stmnt->execute();
			$search = $stmnt->fetchAll();
			
		}
		
		$stmnt = $db->prepare("SELECT count(*) button_number
							  FROM groups 
							  WHERE name LIKE '%'
						      AND is_private = FALSE") or die($db);
		$stmnt->execute();
		if(isset($_GET['all']) || isset($_GET['group'])) {
			if(!count($search)) {
				echo 'No groups found' . '<br />';
			}else {
				foreach($search as $key) {
					$url = 'group.php?id=' . urlencode($key['id']);
					echo "<a href='$url'>" . htmlspecialchars($key['name']) . "</a> <br>";
					echo "Course: " . htmlspecialchars($key['title']). "<br>";
					echo htmlspecialchars($key['blurb']);
				}
			}

			echo '<br>';
			
			$buttonNumber = $stmnt->fetchObject()->button_number / 10;
			

			$url = 'search.php?';
				if(isset($_GET['group'])) {
					$url = $url . 'group=' . urlencode($_GET['group']); 	
				}else if(isset($_GET['all'])) {
					$url = $url .'all=' . urlencode($_GET['all']); 
				}
			
			echo 'Pages:'; 
			for($i = 0; $i < $buttonNumber; $i++) {

				echo '<a href = ' . $url . '&page=' . ( $i + 1 ) . ' class = "btn btn-small"> ' . ($i + 1) . ' </a>';	
			}
			if($i) echo '<br>';
		}
		
	}catch(PDOException $e) {
	
		echo 'Connection Failed: ' . $e->getMessage();
	
	}
	
?>
	<form action = "form.php">
		<input type = "submit" value = "Search Again"><br>
	</form>
	
	
	<?php 
		include 'includes/_footer.php';
	?>
	
</body>
</html>
