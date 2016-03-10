    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php">
            <img style="width: 100px; height: 50px" src="images/Logo.jpg"
                 alt="Study Group Finder"/>
          </a>
          <?php
            if (is_logged_in()) {
              $id = get_logged_in_user_id();
              echo "<a href='profile.php?id=$id'>";
              echo "<img src='pic_display.php?id=$id' height='50px' width='50px'>";
              echo "</a>";
            }
          ?>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <div class="navbar-form navbar-right">
            <?php
              if (is_logged_in()) {
                  echo '<a class="btn btn-danger" href="logout.php" role="button">Sign out</a>';
              } else {
                  echo '<a class="btn btn-success" href="signin.php" role="button">Sign in</a>';
              }
            ?>
          </div>
        </div>
      </div>
    </nav>

    <div class="container">

      <?php if (is_logged_in()) { ?>
        <div class="masthead">
          <nav>
            <ul class="nav nav-justified">
              <li><a href="index.php">Home</a></li>
              <li><a href="profile.php?id=<?= get_logged_in_user_id() ?>">Your Profile</a></li>
              <li><a href="profile_edit.php">Edit Profile</a></li>
              <li><a href="course_edit.php">Your Courses</a></li>
				  <li><a href="group.php">Your Groups</a></li>
              <li><a href="newgroup.php">Create a Group</a></li>
              <li><a href="form.php">Search</a></li>
            </ul>
          </nav>
        </div>
      <?php } ?>
