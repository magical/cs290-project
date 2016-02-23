<?php
require_once 'includes/all.php';

if (!is_logged_in()) {
  header('Location: index.php');
  exit(0);
}

$db = connect_db();
$user = get_user($db, get_logged_in_user_id());
$groups = get_user_groups($db, $user['id']);
$courses = get_user_courses($db, $user['id']);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Your Dashboard</title>
    <?php include 'includes/_head.html' ?>
  </head>
  <body>
    <?php include 'includes/_nav.php' ?>

    <h1>Your Classes / Study Groups</h1>


    <?php
      if (!count($groups)) {
        // TODO(ae): First-run tutorial
        echo "You aren't in any groups :(";
      } else {
        echo "<ul>";
        foreach ($groups as $group) {
          $course = get_course($db, $group['course_id']);
          $group_url = 'group.php?id='.urlencode($group['id']);
          echo "<li>";
          echo '<a href="'.htmlspecialchars($group_url).'">'.
            htmlspecialchars($group['name']).'</a>';
        }
        echo "</ul>";

        echo '<a href="courses.php" class="btn btn-default">Manage your classes</a>';
        echo '<a href="groups.php" class="btn btn-default">Manage your groups</a>';
        echo "<ul>";
        foreach ($courses as $course) {
          echo '<li>'.htmlspecialchars($course['department'].' '.$course['number'].' '.$course['title']);
        }
        echo "</ul>";
      }

    ?>

    <?php include 'includes/_footer.php' ?>
  </body>
</html>
