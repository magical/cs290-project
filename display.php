<?php session_start();?>
<?php require_once 'includes/all.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Data Display</title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>
    <?php

      // Connect to the database

      $db = connect_db();

      // Display values

      $stmt = $db->prepare("SELECT * FROM users");
      $stmt->execute();
      $stmt2 = $db->prepare("
        SELECT courses.department, courses.number
        FROM courses
        JOIN user_courses ON user_courses.course_id = courses.id
        WHERE user_courses.user_id = :user_id
      ");
      echo "<h1>Users</h1>\n";
      echo "<table class=\"table table-bordered table-striped\">\n";
      echo "  <thead><tr><th>id<th>name<th>email<th>phone<th>courses<th>created at</tr></thead>\n";
      while ($row = $stmt->fetch()) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['name']).'</td>';
          echo '<td>'.htmlspecialchars($row['email']).'</td>';
          echo '<td>'.htmlspecialchars($row['phone']).'</td>';
          echo '<td>';
          $stmt2->bindValue('user_id', $row['id']);
          $stmt2->execute();
          while ($row2 = $stmt2->fetch()) {
              echo htmlspecialchars($row2['department'].$row2['number']).'<br>';
          }
          echo '</td>';
          echo '<td>'.htmlspecialchars($row['created_at']).'</td>';
          echo "</tr>\n";
      }
      echo "</table>\n";

      $stmt = $db->prepare("SELECT * FROM courses");
      $stmt->execute();
      echo "<h1>Courses</h1>\n";
      echo "<table class=\"table table-bordered table-striped\">\n";
      echo "  <thead><tr><th>id<th>dept<th>num<th>title<th>year</tr></thead>\n";
      while ($row = $stmt->fetch()) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['department']).'</td>';
          echo '<td>'.htmlspecialchars($row['number']).'</td>';
          echo '<td>'.htmlspecialchars($row['title']).'</td>';
          echo '<td>'.htmlspecialchars($row['year']).'</td>';
          echo "</tr>\n";
      }
      echo "</table>\n";

      $stmt = $db->prepare("
          SELECT groups.id as id, courses.id as course_id, courses.department, courses.number
          FROM groups
          JOIN courses ON groups.course_id = courses.id");
      $stmt->execute();
      $stmt2 = $db->prepare("SELECT email FROM group_members JOIN users ON users.id = group_members.user_id WHERE group_id = :group_id");
      echo "<h1>Groups</h1>\n";
      echo "<table class=\"table table-bordered table-striped\">\n";
      echo "  <thead><tr><th>id<th>course<th>members</tr></thead>\n";
      while ($row = $stmt->fetch()) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['department'].$row['number']).'</td>';
          echo '<td>';
          $stmt2->bindValue("group_id", $row['id']);
          $stmt2->execute();
          while ($row2 = $stmt2->fetch()) {
              echo htmlspecialchars($row2['email']) . '<br>';
          }
          echo "</tr>\n";
      }
      echo "</table>\n";
    ?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
