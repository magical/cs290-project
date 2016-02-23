<?php
require_once 'includes/all.php';


if (!is_logged_in()) {
	header("Location: signin.php");
	exit(0);
}


if (!isset($_GET['id'])) {
  // um
  header('Status: 404');
  die('404 not found'); 
} 

$db = connect_db();
$user = get_user($db, $_GET['id']);
if (!$user) {
  header('Status: 404');
  die('no such user');
}

$courses = get_user_courses($db, $user['id']);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?= htmlspecialchars($user['name']) ?></title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <h1>User profile</h1>

    <dl class="dl-horizontal">
      <dt>Name
      <dd><?= htmlspecialchars($user['name']) ?>
    </dl>

    <h2>Contact</h2>

    <dl class="dl-horizontal">
      <dt>email
      <dd><?= htmlspecialchars($user['email']) ?>

      <dt>phone
      <dd><?= htmlspecialchars($user['phone']) ?>
    </dl>

    <h2>Classes</h2>

    <ul>
      <?php foreach ($courses as $course) { ?>
        <li><?= htmlspecialchars($course['department'] . " " .
                                 $course['number'] . " " .
                                 $course['title']) ?>
      <?php } ?>
    </ul>

    <?php include 'includes/_footer.php';?>
  </body>
</html>

<!---}
else {
	?>
	<script language="javascript">
	alert("Please Log In")
	</script>;
	
	<!DOCTYPE html> 
	<html>
		<body>
			<a href="index.php"> Click Here To Log In </a>
		</body>	
	</html>
	<?php
?> ---!>
