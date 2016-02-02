<?php
require_once 'includes/all.php';

// TODO(ae): Require users to be logged in to view this page.

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
