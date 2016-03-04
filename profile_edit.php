<?php

require_once 'includes/all.php';

if (!is_logged_in()) {
   header("Location: signin.php");
   exit(0);
}

$db = connect_db();
$user = get_user($db, get_logged_in_user_id());
if (!$user) {
  header('Status: 404');
  die('no such user');
}

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_REQUEST["collegeselect"] === "") {
      $errors['college'] = "Please choose college";
    } elseif ($_REQUEST["campus"] === "") {
      $errors['campus'] = "Please choose campus";
    }

    if (!count($errors)) {
      $name = $user['name'];
      $phone = $user['phone'];
      $campus_id = $_REQUEST['campus'];

      if ($_REQUEST['name'] !== '') {
        $name = $_REQUEST["name"];
      }
      if ($_REQUEST['phone'] !== '') {
        $phone = $_REQUEST['phone'];
      }

      // TODO(ae): validate phone number

      // Look up the college
      $stmt = $db->prepare("SELECT id FROM colleges WHERE id = ?");
      $stmt->execute(array($_REQUEST['collegeselect']));
      $row = $stmt->fetch();
      if ($row === false) {
        die("invalid college");
      }
      $college_id = $row[0];

      $stmt = $db->prepare("
          UPDATE users SET
          name = :name,
          phone = :phone,
          college_id = :college_id,
          campus_id = :campus_id
          WHERE id=:user_id");
      $stmt->bindValue("name", $name);
      $stmt->bindValue("phone", $phone);
      $stmt->bindValue("college_id", $college_id);
      $stmt->bindValue("campus_id", $campus_id);
      $stmt->bindValue("user_id", $user['id']);
      $stmt->execute();

      header('Location: profile.php?id='.$user['id']);
      exit(0);
  }
}

function has_error($name) {
  global $errors;
  if (array_key_exists($name, $errors)) {
    return 'has-error';
  }
  return '';
}

function show_error($name) {
  global $errors;
  if (array_key_exists($name, $errors)) {
    echo '<p class="help-block">'.htmlspecialchars($errors[$name])."\n";
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Data Entry</title>
    <?php include 'includes/_head.html' ?>
  </head>
  <body>
    <?php include 'includes/_nav.php' ?>
    <div class="jumbotron">
      <h1> Your Profile </h1>
      <p> Please fill out this information </p>
    </div>

    <form action="" method="POST">
      <div class="row">
        <div class="form-group col-md-4">
          <label for="input-name">Name</label>
          <input id="input-name" type='text' name='name' class='form-control' value="<?= htmlspecialchars($user['name']) ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="input-phone">Phone</label>
          <input id="input-name" type='text' name='phone' class='form-control' value="<?= htmlspecialchars($user['phone']) ?>">
        </div>
      </div>

      <div class='row'>
        <div class='form-group col-md-4 <?= has_error('college') ?>'>
          <label>College</label>
          <select name='collegeselect' class='form-control'>
            <?php
              echo "<option value=''> Select College </option>";
              $q = $db->query("SELECT id, name FROM colleges ORDER BY name");
              foreach ($q as $college) {
                echo '<option value="'.htmlspecialchars($college['id']).'" ';
                if ($user['college_id'] === $college['id']) {
                  echo "selected";
                }
                echo ">".htmlspecialchars($college['name'])."</option>\n";
              }
            ?>
          </select>
          <?php show_error('college') ?>
        </div>

        <div class='form-group col-md-4 <?= has_error('campus') ?>'>
          <label>Campus</label>
          <select name='campus' class='form-control'>
            <option value=""> Select Campus </option>
            <?php
              echo "<option value='1' ";
              if ($user['campus_id'] == 1) {
                echo "selected='selected'";
              }
              echo "> Corvallis (Main)</option>";
              echo "<option value='2' ";
              if ($user['campus_id'] == 2) {
                echo "selected='selected'";
              }
              echo "> Cascades </option>";
              echo "<option value='3' ";
              if ($user['campus_id'] == 3) {
                echo "selected='selected'";
              }
              echo "> Online </option>";
            ?>
          </select>
          <?php show_error('campus') ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <input type='submit' class='btn btn-primary' value='SAVE'>
          <a class="btn btn-link" href="profile.php?id=<?= $user['id'] ?>">Cancel</a>
        </div>
      </div>
    </form>

    <div class='jumbotron'>
      <h2>Upload a Picture</h2>
    </div>

    <form action='upload.php' name='flpd' role='form' method=post enctype=multipart/form-data>

      <div class='form-group'>
        <input type='file' name='fileupload'>
        <p class='help-block'>Supported File Types: JPEG, JPG, GIF, PNG, less than 1 MB </p>
      </div>

      <div class="form-group">
        <input type='submit' class='btn btn-primary' name='filesub' value='UPLOAD'>
      </div>

    </form>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
