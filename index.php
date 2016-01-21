<html>
  <head>
	<title>CS 290</title>
	<link rel="icon"
		  type="image/png"
		  href="favicon.png">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/jumbotron.css">
	<link rel="stylesheet" href="css/justified-nav.css">
  </head>

  <body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Project</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
		  <div class="navbar-form navbar-right">
			<a class="btn btn-success" href="signin.html" role="button">Sign in</a>
		  </div>
		</div>
	  </div>
	</nav>

	<div class="container">
	  
      <div class="masthead">
        <nav>
          <ul class="nav nav-justified">
            <li><a href="#">Home</a></li>
            <li><a href="create.php">Create</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
			<li><a href="#">Link</a></li>
          </ul>
        </nav>
      </div>

      <div class="jumbotron">
        <h1>Welcome!</h1>
        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Button</a></p>
      </div>

      <div class="container" style="width: 100%;">
		<div class="row">
          <div class="col-md-4">
			<h2>Team Members</h2>
			<p>Brandon Chatham
			  <br>Andrew Ekstedt
			  <br>Michael Elliott
			  <br>Jacob Mahugh
			  <br>Ian McQuoid
			  <br>Xiaoli Sun
			<p><a class="btn btn-default" href="#" role="button">Button &raquo;</a></p>
          </div>
          <div class="col-md-4">
			<h2>Heading</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
			<p><a class="btn btn-default" href="#" role="button">Button &raquo;</a></p>
		  </div>
          <div class="col-md-4">
			<h2>Heading</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
			<p><a class="btn btn-default" href="#" role="button">Button &raquo;</a></p>
		  </div>
		</div>
	  </div>

	  <footer class="footer">
		<div class="container">
		  <p class="text-muted"><?php
            echo date('r');
          ?></p>
		</div>
	  </footer>

	</div>

  </body>
</html>
