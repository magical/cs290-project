<?php
require_once 'includes/all.php';

if(!is_logged_in()) {
    header("Location: signin.php");
    exit(0);
}

$db = connect_db();

$q="SELECT * FROM colleges order by name";
$p="SELECT id, department,number FROM courses order by department";
$s="SELECT id,name FROM standings order by id";

$stmt = $db->query('SELECT * FROM courses');
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// TODO: errors

if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('times', $_POST)) {
    $user = get_user($db, get_logged_in_user_id());
    $user_courses = get_user_courses($db, get_logged_in_user_id());
    $user_id=get_logged_in_user_id();

    if(isset($_POST['standing_id']) && is_valid_id($db, 'standings', $_POST['standing_id'])) {
        // check valid standing id
        $sid = $_POST['standing_id'];
        $stmt = $db->prepare("
            UPDATE users
            SET standing_id = :standing_id
            WHERE id=:user_id");
        $stmt->bindValue("standing_id", $sid);
        $stmt->bindValue("user_id", $user['id']);
        $stmt->execute();
    }

    if (is_valid_day($_POST['week1']) && is_valid_time($_POST['selt1'])) {
        $stmt = $db->prepare("
            UPDATE users
            SET day1 = :day1, time1 = :time1
            WHERE id=:user_id");
        $stmt->bindValue("day1", $_POST['week1']);
        $stmt->bindValue("time1", $_POST['selt1']);
        $stmt->bindValue("user_id", $user['id']);
        $stmt->execute();
    }

    if (is_valid_day($_POST['week2']) && is_valid_time($_POST['selt2'])) {
        $stmt = $db->prepare("
            UPDATE users
            SET day2 = :day2, time2 = :time2
            WHERE id=:user_id");
        $stmt->bindValue("day2", $_POST['week2']);
        $stmt->bindValue("time2", $_POST['selt2']);
        $stmt->bindValue("user_id", $user['id']);
        $stmt->execute();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('course_id', $_POST)) {
    $stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)");
    $stmt->bindValue(":user_id", get_logged_in_user_id());
    $stmt->bindValue(":course_id", $_POST['course_id']);
    $stmt->execute();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('remove_id', $_POST)) {
    $stmt = $db->prepare("DELETE FROM user_courses WHERE user_id = :user_id AND course_id = :course_id");
    $stmt->bindValue(":user_id", get_logged_in_user_id());
    $stmt->bindValue(":course_id", $_POST['remove_id']);
    $stmt->execute();
}

$user = get_user($db, get_logged_in_user_id());
$user_courses = get_user_courses($db, get_logged_in_user_id());

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Course Edit | Study Group Finder</title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <div class="breadcrumbs">
      <a href="index.php">Home</a>
      » <a href="profile.php?id=<?= get_logged_in_user_id() ?>">Your Profile</a>
      » Edit Times and Courses
    </div>

    <div class="row">
      <div class="col-md-6">
        <h2>Study Profile</h2>
    <form action='' class='form-horizontal' role='form' method='post' name='dentry'>

      <div class='form-group row'>
        <div class='col-md-6'>
          <label for="sta">Select Your Standing:</label>
          <select name='standing_id' id='sta' class='form-control'>
            <option value=''>Select Standing</option>
            <?php
              foreach ($db->query($s) as $squery) {
                $sid=$squery['id'];
                $sname=htmlspecialchars($squery['name']);
                if ($user['standing_id'] === $sid) {
                  echo  "<option value='$sid' selected>$sname</option>";
                } else {
                  echo  "<option value='$sid'>$sname</option>";
                }
              }
            ?>
          </select>
        </div>
      </div>

      <?php for ($j = 1; $j <= 2; $j++) { ?>
        <div class="form-group row">
          <div class="col-md-6">
            <label for="week<?=$j?>">Day</label>
            <select name='week<?=$j?>' id='week<?=$j?>' class='form-control'>
              <option class='ww' value=''>Select Day</option>
              <?php
                foreach ($week_names as $value) {
                  if ($user['day'.$j] === $value) {
                    echo '<option selected>'.$value.'</option>';
                  } else {
                    echo '<option>'.$value.'</option>';
                  }
                }
              ?>
            </select>
          </div>

          <div class="col-md-6">
            <label for="selt<?=$j?>">Time</label>
            <select name='selt<?=$j?>' id='selt<?=$j?>' class='form-control'>
              <option value=''>Select Time</option>
              <?php
                for($i=0;$i<24;$i++){
                  $time = ($i+8)%24;
                  if ($user['time'.$j] === $time) {
                    echo "<option value='$time' selected>";
                  } else {
                    echo "<option value='$time'>";
                  }
                  echo htmlspecialchars($time_names[$time]) . '</option>';
                }
              ?>
            </select>
          </div>
        </div>
      <?php } ?>

      <div class="row">
        <div class="col-md-12">
          <p>Enter up to two times when you would be available to meet for a study group. These will be displayed to people who view your profile, to help people find groups of people who can meet at the same time.
        </div>
      </div>

      <div class="row form-group">
        <div class="col-md-12">
          <input type='submit' class='btn btn-primary' value='SUBMIT'>
          <input type='hidden' name='times' value='times'>
        </div>
      </div>

    </form>

      </div>
      <div class="col-md-6">

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

    <form action="" method="POST" class="form-horizontal">
      <select name="course_id" class="form-control">
        <?php
          foreach ($courses as $course) {
            echo '<option value="'.htmlspecialchars($course['id']).'">';
            echo htmlspecialchars($course['department']. ' ' . $course['number'] . ' ' . $course['title']);
            echo "</option>\n";
          }
        ?>
      </select>
      <span class="input-group-btn">
        <button class='btn btn-success'>Add</button>
      </span>
    </form>
   <br>
    <?php if ($user_courses) { ?>
    <form action="" method="POST" class="form-horizontal">
      <select name="remove_id" class="form-control">
        <?php
          foreach ($user_courses as $course) {
            echo '<option value="'.htmlspecialchars($course['id']).'">';
            echo htmlspecialchars($course['department']. ' ' . $course['number'] . ' ' . $course['title']);
            echo "</option>\n";
          }
        ?>
      </select>
      <span class="input-group-btn">
        <button class='btn btn-danger'>Remove</button>
      </span>
    </form>
    <?php } ?>
    </div>
    </div>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
