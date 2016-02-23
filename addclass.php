<?php

require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}

$db = connect_db();
$stmt = $db->prepare('SELECT * FROM courses');
$stmt->execute();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)");
  $stmt->bindValue(":user_id", get_logged_in_user_id());
  $stmt->bindValue(":course_id", $_POST['course_id']);
  $stmt->execute();
  header("Location: addclass.php");
  exit(0);
} else {
  $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$user_courses = get_user_courses($db, get_logged_in_user_id());

?>
<!DOCTYPE html>
<html>
  <head>
    <title>CS 290</title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <h2>Your Courses</h2>

    <table class="table">
      <thead>
        <tr><th>Course<th>Title
      <tbody>
        <?php
          foreach ($user_courses as $course) {
            echo '<tr>';
            echo '<td>';
            echo htmlspecialchars($course['department']. ' ' . $course['number']);
            echo '<td>'.htmlspecialchars($course['title']).'</td>';
          }
        ?>
    </table>

    <form action="" method="POST">
      <select name="course_id">
        <?php
          foreach ($courses as $course) {
            echo '<option value="'.htmlspecialchars($course['id']).'">';
            echo htmlspecialchars($course['department']. ' ' . $course['number'] . ' ' . $course['title']);
            echo "</option>\n";
          }
        ?>
      </select>
      <button>Add</button>
    </form>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
