<?php

require_once 'includes/all.php';

if (!is_logged_in()) {
  header("Location: signin.php");
  exit(0);
}

if (empty($_GET['id'])) {
  echo 'no such group';
  exit(0);
}

$db = connect_db();
$group = get_group($db, $_GET['id']);

if ($group === null) {
  header('Status: 404');
  die('no such group');
}

$user_id = get_logged_in_user_id();
$user_groups = get_user_groups($db, $user_id);

// only let group members edit the group
if (!is_member($db, $user_id, $group['id'])) {
  header('Status: 403');
  die('forbidden');
}

$error = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!empty($_POST['name']) && $_POST['name'] !== $group['name']) {
		$stmt=$db->prepare("UPDATE groups SET name=:name WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":name", $_POST['name']);
		$stmt->execute();
	}

	if (!empty($_POST['blurb']) && $_POST['blurb'] !== $group['blurb']) {
		$stmt=$db->prepare("UPDATE groups SET blurb=:blurb WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":blurb", $_POST['blurb']);
		$stmt->execute();
	}

	if (!empty($_POST['course']) && $_POST['course'] !== $group['course_id']) {
		$stmt=$db->prepare("UPDATE groups SET course_id=:course_id WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":course_id", $_POST['course']);
		$stmt->execute();
	}

	if (!empty($_POST['campus']) && !empty($_POST['building'])){
		$q = $db->prepare("SELECT name FROM campuses WHERE id = :id");
		$q->bindValue(":id", $_POST['campus']);
		$q->execute();
		$campus = $q->fetch();

		$place = $campus['name'] . " " . $_POST['building'];
		$stmt=$db->prepare("UPDATE groups SET place=:place WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":place", $place);
		$stmt->execute();
	}

	if (!empty($_POST['day']) && !empty($_POST['time'])) {
		$time = $_POST['day'] . " " . $_POST['time'] . ":00";
		$stmt=$db->prepare("UPDATE groups SET time=:time WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":time", $time);
		$stmt->execute();
	}

	$private = !empty($_POST['private']);
	if ($group['is_private'] != $private) {
		$stmt=$db->prepare("UPDATE groups SET is_private=:private WHERE id=:group_id");
		$stmt->bindValue(":group_id", $group['id']);
		$stmt->bindValue(":private", $private);
		$stmt->execute();
	}

	header("Location: group.php?id=".urlencode($group['id']));
	exit(0);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title> Group Edit | Study Group Finder </title>
    <script src="js/jquery-1.12.1.min.js" type="text/javascript"></script>
    <script>
        function get_buildings(val) {
                $.ajax({
                        type:"POST",
                        url:"get_building.php",
                        data: 'id='+val,
                        success: function(data){
                                $("#input-building").html(data);
                        }
                });
        }

        function reload(id){
            self.location="group_edit.php?id="+id;
        }
    </script>
    <?php include 'includes/_head.html';?>
  </head>


  <body>
    <?php include 'includes/_nav.php';?>

    <div class="breadcrumbs">
      <a href="index.php">Home</a>
      » <a href="group.php?id=<?=$group['id']?>">Group: <?= htmlspecialchars($group['name']) ?></a>
      » Edit
    </div>

    <div class='row'>
      <div class='col-sm-3'>
        <form>
          <label for='name'>Select the group</label>
          <select onChange="reload(this.value);" class='form-control'>
            <option value=''>Select Group</option>
            <?php
                foreach($user_groups as $g){
                    echo '<option value="'.htmlspecialchars($g['id']).'">'.htmlspecialchars($g['name'])."</option>\n";
                }
            ?>
          </select>
        </form>
      </div>
    </div>

    <div class='jumbotron'>
      <h2>Study Group Editing For Group: <?= htmlspecialchars($group['name'])?></h2>
    </div>

    <form action="" class='form-horizontal' role='form' method="POST">
      <div class='row'>

        <div class ='col-sm-3'>
          <label for='input-name'>New Group Name:</label>
          <input type='text' class='form-control' name='name' value="<?= htmlspecialchars($group['name']) ?>" id='input-name'>
        </div>


        <div class ='col-sm-3'>
          <label for='input-blurb'>Group Description (optional):</label>
          <input type='text' class='form-control' name='blurb' value="<?= htmlspecialchars($group['blurb']) ?>" id='input-blurb' placeholder="GROUP MESSAGE">
        </div>

        <div class='col-sm-3'>
          <input id="input-private" type="checkbox" name="private"
            <?php if ($group['is_private']) { echo 'checked'; } ?>
          >
          <label for="input-private">Private</label>
          <p class="help-block">Check to hide this group from search results</p>
          <br>
        </div>
      </div>

      <br><br>

      <div class='row'>
        <div class='col-sm-3'>
          <label for='input-course'>Select new course (optional):</label>
          <select name='course' id='input-course' class='form-control'>
            <option class='ncor' value=''>Select Course</option>;
            <?php
              $q=$db->query("SELECT id, department,number FROM courses order by department AND number");
              foreach($q as $course){
                $selected = "";
                if ($group['course_id'] === $course['id']) {
                  $selected = " selected";
                }
                echo '<option value="'.htmlspecialchars($course['id']).'"'.$selected.'>'.htmlspecialchars($course['department'] . ' ' . $course['number'])."</option>\n";
              }
            ?>
          </select>
        </div>

        <div class='col-sm-3'>
          <label for='input-campus'>Select a new campus (optional):</label>
          <select name="campus" id="input-campus" class="form-control" onChange="get_buildings(this.value)">
            <option value=''>Select a Campus</option>
            <?php
            $q=$db->query("SELECT id,name FROM campuses order by id");
            foreach($q as $campus){
              $selected = "";
              if ($group['campus_id'] === $campus['id']) {
                $selected = " selected";
              }
              echo '<option value="'.htmlspecialchars($campus['id']).'"'.$selected.'>'.htmlspecialchars($campus['name'])."</option>\n";
            }?>
          </select>
        </div>

        <div class='col-sm-3'>
          <label for='input-building'>Select a new building (optional)</label>
          <select name='building' id='input-building' class='form-control'>
            <option value=''>Select a Building</option>
          </select>
        </div>
      </div>

      <br><br>

      <h4>Change meeting date and time (optional):</h4>

      <div class='row'>
        <div class='col-sm-3'>
          <label for='input-day'>Select Day:</label>
          <select id="input-day" name='day' class='form-control'>
            <option value=''>Select Day</option>
            <?php $week=array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday');
            foreach ($week as $value) {
              echo '<option value="'.$value.'">'.$value.'</option>';
            }?>
          </select>
        </div>

        <div class='col-sm-3'>
          <label for='input-time'>Select Time:</label>
          <select id="input-time" name='time' class='form-control'>
            <option value=''>Select Time</option>
            <?php
            for($i=1;$i<=24;$i++){
              echo "<option value='$i'>$i:00</option>";
            }?>
          </select>
        </div>
      </div>

      <br><br>

      <input type='submit' class='btn btn-primary' value='SAVE'>
      <a class="btn btn-link" href="group.php?id=<?= $group['id'] ?>">Cancel</a>

    </form>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
