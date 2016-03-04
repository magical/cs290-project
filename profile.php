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

$college = null;
if ($user['college_id']) {
  $q = $db->prepare("SELECT * FROM colleges WHERE id=?");
  $q->bindValue(1, $user['college_id']);
  $q->execute();
  $college = $q->fetch();
}
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
      <dt>Name:
      <dd><?= htmlspecialchars($user['name']) ?>
    </dl>

    <h2>Contact</h2>

    <dl class="dl-horizontal">
      <dt>Email:
      <dd><?= htmlspecialchars($user['email']) ?>

      <dt>Phone:
      <dd><?= htmlspecialchars($user['phone']) ?>

      <?php if ($college) {
        echo '<dt>College:';
        echo '<dd>' . htmlspecialchars($college['name'])."\n";
      } ?>
    </dl>
    <a href="profile_edit.php" class="btn btn-default">
      <span class="glyphicon glyphicon-cog"></span>
      Edit Profile
    </a>

    <h2>Times</h2>

    <dl class="dl-horizontal">
      <dt>Time 1:</dt>
      <dd><?= htmlspecialchars($user['time1']) ?></dd>

      <dt>Time 2:</dt>
      <dd><?= htmlspecialchars($user['time2']) ?></dd>
    </dl>

    <a href="course_edit.php" class="btn btn-default">
      <span class="glyphicon glyphicon-cog"></span>
      Edit Times
    </a>


    <h2>Classes</h2>

    <ul>
      <?php foreach ($courses as $course) { ?>
        <li><?= htmlspecialchars($course['department'] . " " .
                                 $course['number'] . " " .
                                 $course['title']) ?>
      <?php } ?>
    </ul>

    <a href="course_edit.php" class="btn btn-default">
      <span class="glyphicon glyphicon-cog"></span>
      Edit Classes
    </a>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
