<?php
	require_once "includes/all.php";

	$all = isset($_GET['all']);

	$page = 1;
	if(isset($_GET['page'])) {
		$page = (int) $_GET['page'];
	}

	try{
		$db = connect_db();

		// Construct search query
		$where = "1";
		$params = array();

		if (!$all) {
			if (isset($_GET['group'])) {
				$where = "$where AND LOWER(name) = LOWER(:name)";
				$params[":name"] = $_GET['group'];
			}
			if (isset($_GET['course'])) {
				$where = "$where AND course_id = :course_id";
				$params[":course_id"] = $_GET['course'];
			}
		}

		$where = "($where) AND NOT is_private";

		// Get results
		$stmnt = $db->prepare("SELECT groups.*, courses.title as title
								FROM groups
								JOIN courses ON courses.id=groups.course_id
								WHERE $where
								ORDER BY name
								LIMIT 10 OFFSET :offset") or die($db);

		foreach ($params as $key => $value) {
			$stmnt->bindValue($key, $value);
		}
		$stmnt->bindValue(':offset', ($page-1)*10, PDO::PARAM_INT);
		$stmnt->execute();
		$search = $stmnt->fetchAll();

		// Get result count
		$stmnt = $db->prepare("SELECT count(*) FROM groups WHERE $where");
		foreach ($params as $key => $value) {
			$stmnt->bindValue($key, $value);
		}
		$stmnt->execute();

		$resultCount = $stmnt->fetch()[0];
		$buttonNumber = ceil($resultCount / 10.0);

	}catch(PDOException $e) {
		echo 'Connection Failed: ' . $e->getMessage();
	}

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
	<?php include 'includes/_nav.php' ?>

	<h2>Search Results</h2>
	<?php
		// Search results
		if(!count($search)) {
			echo 'No groups found' . '<br>';
		}else {
			echo 'Showing results ', ($page-1)*10+1, '-', min($resultCount, $page*10);
			foreach($search as $key) {
				$url = 'group.php?id=' . urlencode($key['id']);
				echo "<a href=\"$url\">" . htmlspecialchars($key['name']) . "</a> <br>";
				echo "Course: " . htmlspecialchars($key['title']). "<br>";
				echo htmlspecialchars($key['blurb']);
			}
		}

		echo '<br>';

		// Pagination buttons
		$url = 'search.php?';
			if(isset($_GET['group'])) {
				$url = $url . 'group=' . urlencode($_GET['group']);
			}else if(isset($_GET['all'])) {
				$url = $url . 'all=' . urlencode($_GET['all']);
			}

		echo 'Pages:';
		for($i = 0; $i < $buttonNumber; $i++) {
			echo '<a href=' . $url . '&page=' . ( $i + 1 ) . ' class="btn btn-small">' . ($i + 1) . '</a>';
		}
		if($i) echo '<br>';
	?>

	<form action="form.php">
		<input type="submit" value="Search Again"><br>
	</form>

	<?php include 'includes/_footer.php' ?>
</body>
</html>
