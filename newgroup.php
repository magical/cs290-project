<?php

require_once 'includes/all.php';

$db = connect_db();
$courses = get_user_courses($db, get_logged_in_user_id());

// TODO(ae): if no courses, direct user to add some

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

      <div class="form-group">
        <select name="course" class="form-control">
          <?php
            foreach ($courses as $course) {
              echo '<option value="'.htmlspecialchars($course['id']).'">'.htmlspecialchars($course['department'].' '.$course['number'].' '.$course['title']).'</option>';
            }
          ?>
        </select>

        <p class="help-block">
          Which class is this study group for?
          Please choose a class you are attending.
          If the class you want doesn't appear on this list,
          go <a href="">add it to your list of classes</a>
      </div>


      <h2>Info</h2>

      <div class="form-group">
        <label for="name-input">Name (optional)</label>
        <input name=name id="name-input" class="form-control">
        <p class="help-block">Give your study group a name to distinguish it from other groups.
      </div>

      <div class="form-group">
        <label for="time-input">Time (optional)</label>
        <input name=time class="form-control">

        <label for="place-input">Place (optional)</label>
        <input name=place class="form-control">

        <p class="help-block">Set a time and place for your study group to meet. You can always change this later.</p>
      </div>

      <h2>Add people</h2>

      <div class="form-group">
        <select></select>

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
