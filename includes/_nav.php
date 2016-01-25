    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <span class="navbar-brand">Study Group Finder</span>
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
            <li><a href="display.php">Data Display</a></li>
            <li><a href="feature.php">Cool Feature</a></li>
          </ul>
        </nav>
      </div>
