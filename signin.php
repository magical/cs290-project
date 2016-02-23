<?php
require_once 'includes/all.php';

if (is_logged_in()) {
  header("Location: index.php");
  exit();
}

$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $db = connect_db();

  $stmt = $db->prepare("SELECT id, password_hash FROM users WHERE email=:email");
  $stmt->bindValue("email", $email);
  $stmt->execute();

  $row = $stmt->fetch();
  if (!$row) {
    $errors['email'] = 'no account exists with that email address';
  } else {
    if (password_verify($password, $row["password_hash"])) {
      $_SESSION["user_id"] = $row["id"];
    } else {
      $errors['password'] = 'wrong password';
    }
  }

  if (!$errors) {
    header("Location: index.php");
    exit();
  }
}
?>
<!DOCtYpE htmL>
<html>
  <head>
    <title>Sign In</title>
    <link rel="icon"
          type="image/png"
          href="images/favicon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/signin.css">
  </head>	

  <body>
    <div class="container">

      <form class="form-signin" action="" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>

        <div class="<?php if (isset($errors['email'])) echo 'has-error'; ?>">
          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus value="<?php if (isset($email)) echo htmlspecialchars($email); ?>">
          <?php if (isset($errors['email'])) { ?>
            <p class="help-block"><?= htmlspecialchars($errors['email']) ?></p>
          <?php } ?>
        </div>

        <div class="<?php if (isset($errors['password'])) echo 'has-error'; ?>">
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
          <?php if (isset($errors['password'])) { ?>
            <p class="help-block"><?= htmlspecialchars($errors['password']) ?></p>
          <?php } ?>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <br>
        <a href="signup.php">Create new account</a>
      </form>

    </div>
  </body>
</html>
