<?php
	
	require_once "includes/all.php";

	echo '<body>';

	echo '<title>Results</title>';

	echo '<h2>Search Results</h2>';
	
	try{
	
		$db = new PDO($dsn, $dbuser, $dbpass);
		
		if(!isset($_GET['all'])) {
		
			$stmnt = $db->prepare("SELECT * FROM groups WHERE LOWER(name) = LOWER(:name)") or die($db);
			$stmnt->bindValue('name', $_GET['group']);
			$stmnt->execute();
			$search = $stmnt->fetchAll();
			
			if(!count($search)) {
					echo 'No groups found' . '<br />';
			}else {
				foreach($search as $key) {
					echo 'Below is an iframe for the "' . htmlspecialchars($key['name']) . '" group' . '<br />';
					$url = 'group.php?id=' . urlencode($key['id']);
					echo "<iframe src = $url> </iframe>" . '<br />';
				}
			}
		}else{	
			
			$search = $db->query("SELECT * FROM groups WHERE name LIKE '%'");
			foreach($search as $key) {
				echo 'Below is an iframe for the "' . htmlspecialchars($key['name']) . '" group' . '<br />';
				$url = 'group.php?id=' . urlencode($key['id']);
				echo "<iframe src = $url> </iframe>" . '<br />';
			}
			
		}
		
	}catch(PDOException $e) {
	
		echo 'Connection Failed: ' . $e->getMessage();
	
	}

	echo '<form action = "form.php">';
	echo '	<br><br><input type = "submit" value = "Search Again"><br>';
	echo '</form>';
	
	
	echo '</body>';
?>