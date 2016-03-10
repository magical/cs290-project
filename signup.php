<?php
require_once 'includes/all.php';

if (is_logged_in()) {
  header("Location: index.php");
  exit();
}

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db = connect_db();

  // Validate name
  if (!isset($_POST['name']) || $_POST['name'] === "") {
    $errors['name'] = 'please enter your name';
  }

  // Validate email
  $email = $_POST['email'];
  if (!preg_match('/@/', $email)) {
    // Does it look like an email address?
    $errors['email'] = 'please enter a valid email address';
  } else {
    // Check if email is taken
    $stmt = $db->prepare("SELECT id FROM users WHERE email=:email");
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    if ($stmt->fetch()) {
      $errors['email'] = 'this email address is taken';
    }
  }

  // Check if passwords match
  if ($_POST["password"] != $_POST["passwordConfirm"]) {
    $errors['password'] = 'passwords do not match';
  }

  if (!$errors) {
    // Create a new user
    $stmt = $db->prepare("INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)");

    $stmt->bindValue(":name", $_POST['name']);
    $stmt->bindValue(":email", $email);
    $stmt->bindValue(":password_hash", password_hash($_POST["password"], PASSWORD_BCRYPT));
    $stmt->execute();

    $user_id = $db->lastInsertId();
    $_SESSION['user_id'] = $user_id;

    header("Location: signin.php");
    exit();
  }
}
?>
<!doctype html>
<html>
  <head>
    <title>Sign Up | Study Group Finder</title>
    <link rel="icon"
          type="image/png"
          href="images/favicon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signin.css">
  </head>

  <body>
    <div class="container">

      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Please sign up</h2>

        <div class="<?php if (isset($errors['name'])) echo 'has-error'; ?>">
          <label for="inputName" class="sr-only">Email address</label>
          <input type="text" id="inputName" name="name" class="form-control first" placeholder="Name" required autofocus value="<?php if (isset($_POST['name'])) echo htmlspecialchars($_POST['name']); ?>">
          <?php if (isset($errors['name'])) { ?>
            <p class="help-block"><?= htmlspecialchars($errors['name']) ?></p>
          <?php } ?>
        </div>

        <div class="<?php if (isset($errors['email'])) echo 'has-error'; ?>">
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
          <?php if (isset($errors['email'])) { ?>
            <p class="help-block"><?= htmlspecialchars($errors['email']) ?></p>
          <?php } ?>
        </div>

        <div class="<?php if (isset($errors['password'])) echo 'has-error'; ?>">
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required onchange="form.passwordConfirm.pattern = this.value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, &quot;\\$&&quot;);">

          <label for="inputPasswordConfirm" class="sr-only">Confirm Password</label>
          <input type="password" id="inputPasswordConfirm" name="passwordConfirm" class="form-control last" placeholder="Confirm Password" required>
          <?php if (isset($errors['password'])) { ?>
            <p class="help-block"><?= htmlspecialchars($errors['password']) ?></p>
          <?php } ?>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
      </form>
    </div>
  </body>
</html>
