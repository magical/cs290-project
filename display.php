<!DOCTYPE html>
<html>
  <head>
    <title>Data Display</title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.html';?>
    <?php require_once "config.php"; ?>
    <?php

      // Connect to the database

      try {
          // TODO(ae): persistent?
          $db = new PDO($dsn, $dbuser, $dbpass);
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          echo "Error connecting to database";
          die();
      }

      // Display values

      $stmt = $db->prepare("SELECT * FROM users");
      $stmt->execute();
      echo "<h1>Users</h1>\n";
      echo "<table class=table>\n";
      echo "  <tr><th>id<th>name<th>email<th>phone<th>created at</tr>\n";
      foreach ($stmt as $row) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['name']).'</td>';
          echo '<td>'.htmlspecialchars($row['email']).'</td>';
          echo '<td>'.htmlspecialchars($row['phone']).'</td>';
          echo '<td>'.htmlspecialchars($row['created_at']).'</td>';
          echo "</tr>\n";
      }
      echo "</table>\n";

      $stmt = $db->prepare("SELECT * FROM courses");
      $stmt->execute();
      echo "<h1>Courses</h1>\n";
      echo "<table class=table>\n";
      echo "  <tr><th>id<th>dept<th>course<th>title<th>year</tr>\n";
      foreach ($stmt as $row) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['department']).'</td>';
          echo '<td>'.htmlspecialchars($row['course']).'</td>';
          echo '<td>'.htmlspecialchars($row['title']).'</td>';
          echo '<td>'.htmlspecialchars($row['year']).'</td>';
          echo "</tr>\n";
      }
      echo "</table>\n";

      $stmt = $db->prepare("SELECT * FROM groups");
      $stmt->execute();
      $stmt2 = $db->prepare("SELECT email FROM group_members JOIN users ON users.id = group_members.user_id WHERE group_id = :group_id");
      echo "<h1>Groups</h1>\n";
      echo "<table class=table>\n";
      echo "  <tr><th>id<th>course id<th>members</tr>\n";
      foreach ($stmt as $row) {
          echo '  <tr>';
          echo '<td>'.htmlspecialchars($row['id']).'</td>';
          echo '<td>'.htmlspecialchars($row['course_id']).'</td>';
          echo '<td>';
          $stmt2->bindValue("group_id", $row['id']);
          $stmt2->execute();
          foreach ($stmt2 as $row2) {
              echo htmlspecialchars($row2['email']) . '<br>';
          }
          echo "</tr>\n";
      }
      echo "</table>\n";
    ?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
