<?php require_once 'includes/all.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group Finder</title>
    <?php include 'includes/_head.html';?>
    <style>
      .step {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
        padding: 6px 12px;
        margin-bottom: 0;
        font-weight: normal;
        line-height: 1.42857143;
        white-space: nowrap;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;

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
      .step:hover {
        color: #fff;
        background-color: #286090;
        border-color: #204d74;
      }
      .step.focus {
        color: #fff;
        background-color: #286090;
        border-color: #122b40;
      }
      .btn-primary:active {
        color: #fff;
        background-color: #286090;
        border-color: #204d74;
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
          <div class='row'>
          <div class="col-md-6 col-md-push-6">
          <iframe width="100%" height="260px" src="https://www.youtube.com/embed/GerJvNpU3E4" frameborder="0" autoplay='1' allowfullscreen></iframe>
          </div>
          <div class="col-md-6 col-md-pull-6">
        <p class="lead">We can match you up with other students in your classes who want to form a study group.</p>
        <p><a class="btn btn-primary btn-lg" href="signup.php" role="button">Sign up to get started</a></p>
          </div>
          </div>
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
              <p>This project deserves 110%.</p>
              <footer>Jun He</footer>
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

      <div class="step">
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
        <div align='center'>
        <iframe align='middle' width="560" height="315" src="https://www.youtube.com/embed/GerJvNpU3E4" frameborder="0" allowfullscreen></iframe>
        </div>

    <?php } ?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
