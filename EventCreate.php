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

$errors = array();
if (isset($_POST['Day'])) {
	if ($_POST['Day'] === '') {
		$errors[] = "please enter a date";
	} elseif (!preg_match("/^\d\d\d\d-\d\d-\d\d$/", $_POST['Day'])) {
		$errors[] = "date must be in YYYY-MM-DD format";
	}
}

if (isset($_POST['STime'])) {
	if ($_POST['STime'] === '') {
		$errors[] = "please enter a starting time";
	} elseif (!preg_match("/^\d\d:\d\d$/", $_POST['STime'])) {
		$errors[] = "starting time must be in HH:MM format";
	}
}

if (isset($_POST['ETime'])) {
	if ($_POST['ETime'] === '') {
		// fine
	} elseif (!preg_match("/^\d\d:\d\d$/", $_POST['ETime'])) {
		$errors[] = "end time must be in HH:MM format";
	}
}

// Defaults
$Day = "";
$STime = "";
$ETime = "";
$Members = array();

$event = array();
$event['Location'] = "";
$event['Summary'] = "Study Group Event";
$event['Description'] = "Meet to study for classes";

if (isset($_GET['group_id'])) {
	$group = get_group($db, $_GET['group_id']);
	$event['Summary'] = "Meeting for ".$group['name'];
	if (is_valid_day($group['day'])) {
		$Day = next_weekday($group['day'])->format("Y-m-d");
	}
	if (is_valid_time($group['time'])) {
		$STime = sprintf("%02d:00", $group['time']);
		$ETime = sprintf("%02d:00", ($group['time']+1)%24);
	}
	$Members = get_group_members($db, $_GET['group_id']);
	$event['Location'] = $group['place'];
}

if (isset($_POST['Day'])) { $Day = $_POST['Day']; }
if (isset($_POST['STime'])) { $STime = $_POST['STime']; }
if (isset($_POST['ETime'])) { $ETime = $_POST['ETime']; }
if (isset($_POST['Summary'])) { $event['Summary'] = $_POST['Summary']; }
if (isset($_POST['Description'])) { $event['Description'] = $_POST['Description']; }
if (isset($_POST['Location'])) { $event['Location'] = $_POST['Location']; }

if($_SERVER['REQUEST_METHOD'] == 'POST' && !$errors) {

	$date = new DateTime("$Day $STime");
	$edate = clone $date;
	if ($ETime) {
		$edate->modify($ETime);
	} else {
		$edate->modify("+1 hour");
	}

	$event['STime'] = $date->format(DateTime::RFC3339);
	$event['ETime'] = $edate->format(DateTime::RFC3339);

	$event['GrMem'] = array();
	$event['GrMem'][] = array('email' => get_user($db, get_logged_in_user_id())['email']);
	if (isset($_POST['Attending'])){
		for($i = 0; $i < count($_POST['Attending']); $i++) {
			// Check if they're actually a member of the group
			if(get_group($db,$_POST['group_id']))
				$event['GrMem'][] = array('email' => $_POST['Attending'][$i]);
		}
	}

	if (isset($_POST['group_id'])){
		$event['gID'] = $_POST['group_id'];
	}

	$_SESSION['event'] = $event;
	header("Location: calendar.php");
	exit(0);
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
	<?php
		if ($errors) {
			echo '<div class="alert alert-warning">';
			foreach ($errors as $msg) {
				echo '<p>'.htmlspecialchars($msg);
			}
			echo '</div>';
		}
	?>
	<h3>New group event</h3>
    <p>This page will help you add an event for your group to your Google calendar.
	<form class="form" action="" method="POST">
		<div class="form-group">
			<label for="Name">Event Name</label>
			<input class="form-control" id="Name" type='text' name='Summary'
				value="<?= htmlspecialchars($event['Summary']) ?>">
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="Day">Day</label>
				<input class="form-control" id="Day" type="date" name="Day" placeholder="yyyy-mm-dd"
					value="<?= htmlspecialchars($Day) ?>">
			</div>
			<div class="col-md-2">
				<label for="StartingTime">Starting Time</label>
				<input class="form-control" id="StartingTime" type='time' name='STime' placeholder="HH:MM"
					value="<?= htmlspecialchars($STime) ?>">
			</div>
			<div class="col-md-2">
				<label for="EndingTime">Ending Time</label>
				<input class="form-control" id="StartingTime" type='time' name='ETime' placeholder="HH:MM"
					value="<?= htmlspecialchars($ETime) ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="Summary">Summary</label>
			<textarea name="Description" cols="40" rows="5" class="form-control" id="Description"
				><?= htmlspecialchars($event['Description']) ?></textarea>
		</div>
		<div class="form-group">
			<label for="Location">Location</label>
			<input class="form-control" id="Location" type='text' name='Location' placeholder='Where is the event'
				value="<?= htmlspecialchars($event['Location']) ?>">
		</div>
		<div class="form-group">
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
		</div>
	</form>
  </body>
</html>
