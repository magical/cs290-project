<?php require_once 'includes/all.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group Finder</title>
    <?php include 'includes/_head.html';?>
    <style>
      .step {
        background-color: #62A4EC;
        font-size: 2em;
        margin: .5em;
        border-radius: 1em;
      }
      .step a {
        display: block;
        padding: .5em;
        text-decoration: none;
        color: white;
      }
      .step:hover .text {
        text-decoration: underline;
		  color: white;
      }
      .step .number {
        background: white;
        border-radius: 1em;
        display: inline-block;
        width: 1.5em;
        height: 1.5em;
        text-align: center;
		  color: black;
      }
    </style>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>

    <?php if (!is_logged_in()) { ?>
      <div class="jumbotron">
        <h1>Looking for a study group?</h1>
        <p class="lead">We can match you up with other students in your classes who want to form a study group.</p>
        <p><a class="btn btn-primary btn-lg" href="signup.php" role="button">Sign up to get started</a></p>
      </div>

      <div class="container" style="width: 100%;">
        <div class="row">
          <div class="col-md-6 col-md-push-6">
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
          <div class="col-md-6 col-md-pull-6">
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
        </div>
      </div>

    <?php } else { ?>

      <div class="step">
        <a href="profile_edit.php">
          <span class="number">1</span>
          <span class="text">
            Fill out your profile →
          </span>
        </a>
      </div>

      <div class="step" style="color:#0000FF">
        <a href="course_edit.php">
          <span class="number">2</span>
          <span class="text">
            Add your classes →
          </span>
        </a>
      </div>

      <div class="step">
        <a href="form.php">
          <span class="number">3</span>
          <span class="text">
            Find study groups or students →
          </span>
        </a>
      </div>
		
		<div class="step">
        <a href="group.php">
          <span class="number">4</span>
          <span class="text">
            Manage your groups →
          </span>
        </a>
      </div>


    <?php } ?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
