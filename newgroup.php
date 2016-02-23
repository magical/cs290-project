<?php

require_once 'includes/all.php';

$db = connect_db();
$user_courses = get_user_courses($db, get_logged_in_user_id());

// TODO(ae): if no courses, direct user to add some
// TODO(ae): there is no UI for adding users

$form = array();
$form['course'] = 0;
$form['name'] = '';
$form['time'] = '';
$form['place'] = '';
$form['members'] = array();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name'] = $_POST['name'];
    $form['time'] = $_POST['time'];
    $form['place'] = $_POST['place'];

    // validation

    // Check if the course id is numeric
    if (is_numeric($_POST['course'])) {
      $form['course'] = (int)$_POST['course'];
    } else {
      $errors['course'] = "please choose a course";
    }

    // Check if the course is one the user is attending
    if (!isset($errors['course'])) {
      $have_course = false;
      foreach ($user_courses as $course) {
        //var_dump($course);
        //echo gettype($course['id']).",";
        //echo gettype($form['course'])."\n";
        if ($course['id'] === $form['course']) {
          $have_course = true;
        }
      }
      if (!$have_course) {
        $errors['course'] = "choose a course you are registered for";
      }
    }

    // Check if all the user ids are numeric
    if (isset($_POST['members']) && is_array($_POST['members'])) {
      foreach ($_POST['members'] as $member_id) {
        if (is_numeric($member_id)) {
          $form['members'][] = $member_id;
        } else if(!$errors['members']) {
          $errors['members'] = 'invalid user id';
        }
      }
    }

    // create the group
    // TODO(ae): transation
    if (!count($errors)) {
      $stmt = $db->prepare("INSERT INTO groups (course_id, name, time, place) VALUES (:course_id, :name, :time, :place)");
      $stmt->bindValue(":course_id", $form['course']);
      $stmt->bindValue(":name", $form['name']);
      $stmt->bindValue(":time", $form['time']);
      $stmt->bindValue(":place", $form['place']);
      $stmt->execute();

      $group_id = $db->lastInsertId();

      // Add members
      // TODO(ae): send invites instead
      $stmt = $db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (:group_id, :user_id)");
      $stmt->bindValue(":group_id", $group_id, PDO::PARAM_INT);
      foreach ($form['members'] as $user_id) {
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
      }

      $url = "group.php?id=".urlencode($group_id);
      header("Location: ".$url);
      exit(0);
    }
}

// Returns 'has-error' if the key exists in $errors.
function has_error($key) {
  global $errors;
  if (array_key_exists($key, $errors)) {
    return 'has-error';
  }
  return '';
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create a study group</title>
    <?php include 'includes/_head.html' ?>
  </head>
  <body>
    <?php include 'includes/_nav.php' ?>

    <h1>Create a group</h1>

    <form action="" method="POST">

      <p>So you want to start a study group, huh? Great!
      Just follow these three easy steps.

      <h2>Class</h2>

      <div class="form-group <?= has_error('course') ?>">
        <select name="course" class="form-control">
          <?php
            foreach ($user_courses as $course) {
              echo '<option value="'.htmlspecialchars($course['id']).'"';
              if ($course['id'] === $form['course']) {
                echo ' selected';
              }
              echo '>'.htmlspecialchars($course['department'].' '.$course['number'].' '.$course['title']).'</option>';
            }
          ?>
        </select>

        <p class="help-block">
          <?php if (has_error('course')) {
            echo $errors['course'];
          } else { ?>
          <?php } ?>
        <p>
            Which class is this study group for?
            Please choose a class you are attending.
            If the class you want doesn't appear on this list,
            go <a href="">add it to your list of classes</a>
      </div>


      <h2>Info</h2>

      <div class="form-group <?= has_error('name') ?>">
        <label for="name-input">Name</label>
        <input name=name id="name-input" class="form-control">
        <p class="help-block">Give your study group a name to distinguish it from other groups.
      </div>

      <div class="form-group <?= has_error('name') ?> <?= has_error('place') ?>">
        <label for="time-input">Time (optional)</label>
        <input name=time class="form-control">

        <label for="place-input">Place (optional)</label>
        <input name=place class="form-control">

        <p class="help-block">Set a time and place for your study group to meet. You can always change this later.</p>
      </div>

      <h2>Add people</h2>

      <div class="form-group">
        <?php
          foreach ($form['members'] as $id) {
            echo '<input type="hidden" name="members[]" value="'.htmlspecialchars($id).'">'."\n";
          }
        ?>
        <ul>
          <?php
            foreach ($form['members'] as $id) {
              $user = get_user($db, $id);
              echo '<li>'.htmlspecialchars($user['name'])."</li>\n";
            }
          ?>
        </ul>

        <p class="help-block">
          Invite people to join your study group.
          They'll get a notification that you've invited them and have the option to join.
          You can always invite more people later.
      </div>

      <div class="form-group">
        <button class="btn btn-primary">Create</button>
      </div>
    </form>

    <?php include 'includes/_footer.php' ?>
  </body>
</html>
