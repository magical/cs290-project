<?php session_start();?>
<?php require_once 'includes/all.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Data Entry</title>
    <?php include 'includes/_head.html';?>
  </head>
  <body>
	<?php include 'includes/_nav.php';?>
	<h1> Profile </h1>
	<p> Please fill out this information </p>
	<form action="" method=POST>
		<div class="form-group form-inline">
		<select name="standingselect" class="form-control">
			<option value="0"> Select Class Standing </option>
			<option value="1"> First-Year </option>
			<option value="2"> Second-Year </option>
			<option value="3"> Third-Year </option>
			<option value="4"> Fourth-Year </option>
			<option value="5"> Fifth-Year or more </option>
		</select>
		<select name="collegeselect" class="form-control">
			<!--Using first three letters to indicate college, first capt'd, for consistency -->
			<option value="0"> Select College </option>
			<option value="Agr"> Agricultural Sciences </option>
			<option value="Bus"> Business </option>
			<option value="Ear"> Earth, Ocean, and Atmospheric Sciences </option>
			<option value="Edu"> Education </option>
			<option value="Eng"> Engineering </option>
			<option value="For"> Forestry </option>
			<option value="Gra"> Graduate School </option> <!--Do we want this option?-->
			<option value="Lib"> Liberal Arts </option>
			<option value="Pha"> Pharmacy </option>
			<option value="Pub"> Public Health and Human Services </option>
			<option value="Sci"> Science </option>
			<option value="Vet"> Veterinary Medicine </option>
		</select>
		<select name="campus" class="form-control">
			<option value="0"> Select Campus </option>
			<option value="Cor"> Corvallis (Main)</option>
			<option value="Cas"> Cascades </option>
			<option value="Onl"> Online </option>
		</select>
		</div>
		<div class="form-group">
			<p>Enter your two best times in the format DAY, TI:ME XM (i.e: R, 05:00 PM). Use R for Thursday, N for Sunday</p>
			<input type="text" name="t1" class="form-control">
			<input type="text" name="t2" class="form-control">
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary">
		</div>
	</form>
	<?php
		if (array_key_exists("standingselect", $_REQUEST))
			echo htmlspecialchars($_REQUEST["standingselect"]);
		if (array_key_exists("collegeselect", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["collegeselect"]);
		if (array_key_exists("t1", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t1"]);
		if (array_key_exists("t2", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t2"]);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($_REQUEST["standingselect"] == "0") {
					echo "<p> ERROR: Please choose class standing </p>";
			} elseif ($_REQUEST["collegeselect"] == "0") {
					echo "<p> ERROR: Please choose college </p>";
			} elseif($_REQUEST["campus"] == "0") {
					echo "<p> ERROR: Please choose campus </p>";
			} else {
				$db = connect_db();

				//RETRIEVE USERID FROM USERS SESSION - CODE HERE
				//This is a temporary user ID
				$user_id = 2;

				// TODO(ae): validate standingselect range
				$standing_id = $_REQUEST['standingselect'];

				// Look up the college
				$stmt = $db->prepare("SELECT id FROM colleges WHERE abbreviation = ?");
				$stmt->execute(array($_REQUEST['collegeselect']));
				$row = $stmt->fetch();
				if ($row === false) {
					die("invalid college");
				}
				$college_id = $row[0];

				$stmt = $db->prepare("
					UPDATE users SET
						standing_id = :standing_id,
						college_id = :college_id
						-- time1 = : time1,
						-- time2 = : time2,
						-- campus_id= : campus_id,
					WHERE id=:user_id");
				$stmt->bindValue("standing_id", $standing_id);
				$stmt->bindValue("college_id", $college_id);
				//$stmt->bindValue("time1", $_REQUEST["t1"]);
				//$stmt->bindValue("time2", $_REQUEST["t2"]);
				//$stmt->bindValue("campus_id", $campus_id);
				$stmt->bindValue("user_id", $user_id);
				$stmt->execute();
				echo 'success';
			}
		}
	?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
