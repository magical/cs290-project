<?php
	require_once "includes/all.php";
	if(!is_logged_in()) {
		header("Location: signin.php");
		exit(0);
	}

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
			if (!empty($_GET['group'])) {
				$where = "$where AND LOWER(name) = LOWER(:name)";
				$params[":name"] = $_GET['group'];
			}
			if (!empty($_GET['course'])) {
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

		$searchparams = '';
		if (isset($_GET['all'])) {
			$searchparams .= '&all=1';
		} else {
			if (isset($_GET['group']) && $_GET['group']) {
				$searchparams .= '&group=' . urlencode($_GET['group']);
			}
			if (isset($_GET['course']) && $_GET['course']) {
				$searchparams .= '&course=' . urlencode($_GET['course']);
			}
		}
		$searchurl = 'search.php?'.trim($searchparams, '&');
	}catch(PDOException $e) {
		echo 'Connection Failed: ' . $e->getMessage();
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Search Results | Study Group Finder</title>
	<?php
		include 'includes/_head.html';
	?>
</head>

<body>
	<?php include 'includes/_nav.php' ?>

  <div class="breadcrumbs">
    <a href="index.php">Home</a>
    » <a href="form.php">Search</a>
    » Results
  </div>

	<h2>Search Results</h2>
	<div>
		<?php
			// Search results
			if(!count($search)) {
				echo 'No groups found' . '<br>';
			}else {
				echo 'Showing results ', ($page-1)*10+1, '-', min($resultCount, $page*10), ':<br>';
				foreach($search as $key) {
					echo '<div class="panel panel-default">';
 					echo '<div class="panel-body">';
						$url = 'group.php?id=' . urlencode($key['id']);
						echo "<a href=\"$url\">" . htmlspecialchars($key['name']) . "</a> <br>";
						echo "Course: " . htmlspecialchars($key['title']). "<br>";
						echo htmlspecialchars($key['blurb']);
					echo '</div>';
					echo '</div>';
				}
			}
		?>
	</div>

	<nav>
		<ul class="pagination">
			<?php
				// Pagination buttons
				if ($page <= 1) {
					echo '<li class="disabled" aria-label="Previous"><span aria-hidden="true">«</span></li>';
				} else {
					echo '<li aria-label="Previous"><a href="'.$searchurl.'&page='.($page-1).'">«</a></li>';
				}
				for ($i = 1; $i <= $buttonNumber; $i++) {
					$url = $searchurl.'&page='.$i;
					if ($i == $page) {
						echo '<li class="active"><a href="' . $url . '">' . $i . '</a></li>';
					} else {
						echo '<li><a href="' . $url . '">' . $i . '</a></li>';
					}
				}
				if ($page >= $buttonNumber) {
					echo '<li class="disabled" aria-label="Next"><span aria-hidden="true">»</span></li>';
				} else {
					echo '<li aria-label="Previous"><a href="'.$searchurl.'&page='.($page+1).'">»</a></li>';
				}
			?>
		</ul>
	</nav>

	<form action="form.php">
		<input type="submit" value="Search Again" class="btn btn-primary"><br>
	</form>

	<?php include 'includes/_footer.php' ?>
</body>
</html>
