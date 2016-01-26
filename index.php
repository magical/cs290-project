<?php session_start();?>
<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>CS 290</title>
    <?php include 'includes/_head.html';?>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

      <div class="jumbotron">
        <h1>Looking for a study group?</h1>
        <p class="lead">We can match you up with other students in your classes who want to form a study group.</p>
        <p><a class="btn btn-primary btn-lg" href="signup.php" role="button">Sign up to get started</a></p>
      </div>

      <div class="container" style="width: 100%;">
        <div class="row">
          <div class="col-md-6">
            <h2>Team Members</h2>
            <ul>
              <li>Brandon Chatham
              <li>Andrew Ekstedt
              <li>Michael Elliott
              <li>Jacob Mahugh
              <li>Ian McQuoid
              <li>Xiaoli Sun
            </ul>
            <p><a class="btn btn-default" href="about.php" role="button">More about us &raquo;</a></p>
          </div>
		  <div class="col-md-6">
            <h2>Testimonials</h2>
            <blockquote>
              <p>Study Group Finder changed my life.</p>
              <footer>Barack Obama</footer>
            </blockquote>
            <blockquote>
              <p>ur app sucks lol</p>
              <footer>anonymous</footer>
            </blockquote>
          </div>
        </div>
      </div>

      <?php include 'includes/_footer.php';?>
  </body>
</html>
