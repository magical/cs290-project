    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php">
            <img style="width: 100px; height: 50px" src="images/Logo.jpg"
                 alt="Study Group Finder"/>
          </a>
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

      <div class="masthead">
        <nav>
          <ul class="nav nav-justified">
            <li><a href="index.php">Home</a></li>
            <li><a href="entry.php">Data Entry</a></li>
            <li><a href="dataentry.php">Data Entry 2</a></li>
            <?php
              if (is_logged_in()) {
                echo '<li><a href="addclass.php">Add Courses</a></li>';
                echo '<li><a href="profile.php?id='.get_logged_in_user_id().'">Your Profile</a></li>';
              }
            ?>
            <li><a href="group.php?id=1">Group</a></li>
            <li><a href="form.php">Find Groups</a></li>
            <li><a href="display.php">Data Dump</a></li>
          </ul>
        </nav>
      </div>
