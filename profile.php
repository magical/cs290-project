<?php
require_once 'includes/all.php';

if (!is_logged_in()) {
   header("Location: signin.php");
   exit(0);
}

if (!isset($_GET['id'])) {
  // um
  header('Status: 404');
  die('missing id');
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

$is_myself = ($user['id'] == get_logged_in_user_id());
$id = $_GET['id'];

?>
<!DOCTYPE html>
<html>
  <head>
    <title><?= htmlspecialchars($user['name']) ?></title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <div class="breadcrumbs">
      <a href="index.php">Home</a>
      <?php
        if ($is_myself) {
          echo '» Your Profile';
        } else {
          echo '» User Profile: '.htmlspecialchars($user['name']);
        }
      ?>
    </div>

    <div class="row">
      <div class="col-md-6">
        <h1><?= htmlspecialchars($user['name']) ?></h1>

        <?php
          if ($user['pic_id']) {
              echo "<dd><img src='pic_display.php?id=${user['id']}' width='250px' height='250px' class='img-circle'/></dd>";
          } elseif ($is_myself) {
              echo '<dd><a href="profile_edit.php?id='.$user['id'].'">Upload a picture »</a>';
          }
        ?>

        <h2>Contact</h2>

        <dl class="dl-horizontal">
          <dt>Email:</dt>
            <dd><?= htmlspecialchars($user['email']) ?></dd>

          <dt>Phone:</dt>
            <dd><?= htmlspecialchars($user['phone']) ?></dd>

          <?php
                if ($college) {
                    echo '<dt>College:';
                    echo '<dd>' . htmlspecialchars($college['name'])."\n";
                }
          ?>
        </dl>

        <?php if ($is_myself) { ?>
        <a href="profile_edit.php" class="btn btn-primary">
          <span class="glyphicon glyphicon-cog"></span>
          Edit Profile
        </a>
        <?php } ?>

        <h2>Times</h2>

        <dl class="dl-horizontal">
          <dt>Time 1:</dt>
          <dd><?= htmlspecialchars(format_time($user['day1'], $user['time1'])) ?></dd>

          <dt>Time 2:</dt>
          <dd><?= htmlspecialchars(format_time($user['day2'], $user['time2'])) ?></dd>
        </dl>

        <?php if ($is_myself) { ?>
        <a href="course_edit.php" class="btn btn-primary">
          <span class="glyphicon glyphicon-cog"></span>
          Edit Times
        </a>
        <?php } ?>
      </div>
      <div class="col-md-6">
        <h2>Classes</h2>

        <ul>
          <?php foreach ($courses as $course) { ?>
          <li><?= htmlspecialchars($course['department'] . " " .
                                   $course['number'] . " " .
                                   $course['title']) ?>
            <?php } ?>
        </ul>

        <?php if ($is_myself) { ?>
        <a href="course_edit.php" class="btn btn-primary">
          <span class="glyphicon glyphicon-cog"></span>
          Edit Classes
        </a>
        <?php } ?>
      </div>
    </div>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
