<?php 
require_once 'includes/all.php';
if(!is_logged_in()) {
	header("Location: signin.php");
	exit(0);
}
$db = connect_db();
if(!is_member($db, get_logged_in_user_id(), $_REQUEST['group_id'])){
	header("Status: 403 Forbidden");
	exit("403 Forbidden");
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$_SESSION['event'] = array();
  if(isset($_POST['STime'])){
    $_SESSION['event']['STime'] = $_POST['STime'];
  }else {
	  $_SESSION['event']['STime'] = "";
  }
  if (isset($_POST['ETime'])){
    $_SESSION['event']['ETime'] = $_POST['ETime'];
  }else {
	  $_SESSION['event']['ETime'] = "";
  }
  if (isset($_POST['Summary'])){
    $_SESSION['event']['Summary'] = $_POST['Summary'];
  }else {
	  $_SESSION['event']['Summary'] = "Study Group Event";
  }
  if (isset($_POST['Description'])){
    $_SESSION['event']['Description'] = $_POST['Description'];
  }else {
	  $_SESSION['event']['Description'] = "Meet to study for classes";
  }
  if (isset($_POST['Location'])){
    $_SESSION['event']['Location'] = $_POST['Location'];
  }else {
	  $_SESSION['event']['Location'] = "";
  }
  if (isset($_POST['Attending'])){
	for($i = 0; $i < count($_POST['Attending']); $i++) {
	  if(get_group_id($db,$_POST['group_id']))
	  $_SESSION['event']['GrMem'][$i] = array('email' => $_POST['Attending'][$i]);
	}
	  $_SESSION['event']['GrMem'][$i] = array('email' => get_user($db, get_logged_in_user_id())['email']);
  }else {
	  $_SESSION['event']['GrMem'] = array(array('email' => get_user($db, get_logged_in_user_id())['email']));
  }
  if (isset($_POST['group_id'])){
    $_SESSION['event']['gID'] = $_POST['group_id'];
  }
  header("Location: calendar.php");
  exit(0);
	
}
if(isset($_GET['group_id'])){
  $Members = get_group_members($db, $_GET['group_id']);
}else {
  $Members = array();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create New Event </title>
    <?php include 'includes/_head.html';?>
  </head>
  <body>
    <?php include 'includes/_nav.php';?>
	<h3>New group event</h3>
	  <br>
	<form class="form" action="EventCreate.php" method="POST">
		<fieldset class="form-group">
			<label for="Name">Event Name</label>
			<input class="form-control" id="Name" type='text' name='Summary' placeholder='Title'>
			<label for="StartingTime">Starting Time</label>
			<input class="form-control" id="StartingTime" type='text' name='STime' placeholder='Starting time'>
			<label for="EndingTime">Ending Time</label>
			<input class="form-control" id="StartingTime" type='text' name='ETime' placeholder='Ending time'>
			<label for="Summary">Summary</label>
			<textarea name="Description" cols="40" rows="5" class="form-control" id="Description" ></textarea>
			<label for="Location">Location</label>
			<input class="form-control" id="Location" type='text' name='Location' placeholder='Where is the event'>
			<label for="Atendees">Group members to invite:</label>
				<br>
				<?php
					foreach($Members as $attendees) {
						if($attendees['email'] != get_user($db, get_logged_in_user_id())['email'])
						echo '<input id="Atendees" type="checkbox" name="Attending[]" value="'.htmlspecialchars($attendees['email']).'">'.htmlspecialchars($attendees['name']).'</option><br>';
					}
				?>
			<?php echo "<input type='hidden' value='".urlencode($_GET['group_id'])."' name='group_id'>";?>
			<input type='submit' value='Create' class='btn btn-default'>
		</fieldset>
	</form>
  </body>
</html>