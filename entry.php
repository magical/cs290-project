<?php require_once 'includes/all.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Data Entry</title>
    <?php include 'includes/_head.html';?>
  </head>
  <body>
      <?php include 'includes/_nav.php';?>
      <div class="jumbotron">	 
	 <h1> Your Profile </h1>
	 <p> Please fill out this information </p>
	 </div>
	 <div class="container" style="width:100%">
	<form action="" method="POST">
	<table width="360" border="0">
		<tr> <td> <h4> Name </h4> </td> 
	<td> <h4> Phone Number (optional) </h4> </td>
	</tr>
	<tr> <td> 
	<input type="text" name="name" class="form-control" style="width: 150px; caption-side: bottom">
	</td>
	<td>
	<input type="text" name="phone" class="form-control" style="width: 150px; position: relative; caption-side: bottom;">
	</td>
	</tr>
	</table>	
	<p> </p>
      <div class="row"> 
		  <div class="col-md-4">
	<select name="standingselect" class="form-control">
		<option value=""> Select Class Standing </option>
		<option value="1"> First-Year </option>
		<option value="2"> Second-Year </option>
		<option value="3"> Third-Year </option>
		<option value="4"> Fourth-Year </option>
		<option value="5"> Fifth-Year or more </option>
	</select>
	
	</div>
	<div class="col-md-4">
	<select name="collegeselect" class="form-control">
		<!--Using first three letters to indicate college, first capt'd, for consistency -->
		<option value=""> Select College </option>
		<option value="Agr"> Agricultural Sciences </option>
		<option value="Bus"> Business </option>
		<option value="Ear"> Earth, Ocean, and Atmospheric Sciences </option>
		<option value="Edu"> Education </option>
		<option value="Eng"> Engineering </option>
		<option value="For"> Forestry </option>
		<option value="Lib"> Liberal Arts </option>
		<option value="Pha"> Pharmacy </option>
		<option value="Pub"> Public Health and Human Services </option>
		<option value="Sci"> Science </option>
		<option value="Vet"> Veterinary Medicine </option>
	</select>
	</div>
	<div class="col-md-4">
	<select name="campus" class="form-control">
		<option value=""> Select Campus </option>
		<option value="1"> Corvallis (Main)</option>
		<option value="2"> Cascades </option>
		<option value="3"> Online </option>
	</select>
	</div>
       </div>
     </div>
   <div class="container" style="width:100%">
     <div class="row">
	<div class="col-lg-12 form-inline">
	    	<p>Enter your two best times in the format DAY, TI:ME XM (i.e: R, 05:00 PM) </p>
		<p>Use R for Thursday, N for Sunday</p>
<!--		<input type="text" name="t1" class="form-control" style="width: 150px"> -->
	<select name="t1d" class="form-control">
		<option value=""> Select Day </option>
		<option value="1"> Monday </option>
		<option value="2"> Tuesday </option>
		<option value="3"> Wednesday </option>
		<option value="4"> Thursday </option>
		<option value="5"> Friday </option>
		<option value="6"> Saturday </option>
		<option value="7"> Sunday </option>
	</select>
	<select name="t1t" class="form-control">
		<option value=""> Select Time </option>
		<option value="0000"> 12:00 AM </option>
		<option value="0100"> 1:00 AM </option>
		<option value="0200"> 2:00 AM </option>
		<option value="0300"> 3:00 AM </option>
		<option value="0400"> 4:00 AM </option>
		<option value="0500"> 5:00 AM </option>
		<option value="0600"> 6:00 AM </option>	
		<option value="0700"> 7:00 AM </option>
		<option value="0800"> 8:00 AM </option>
		<option value="0900"> 9:00 AM </option>
		<option value="1000"> 10:00 AM </option>
		<option value="1100"> 11:00 AM </option>
		<option value="1200"> 12:00 PM </option>
		<option value="1300"> 1:00 PM </option>
		<option value="1400"> 2:00 PM </option>
		<option value="1500"> 3:00 PM </option>
		<option value="1600"> 4:00 PM </option>
		<option value="1700"> 5:00 PM </option>
		<option value="1800"> 6:00 PM </option>
		<option value="1900"> 7:00 PM </option>
		<option value="2000"> 8:00 PM </option>
		<option value="2100"> 9:00 PM </option>
		<option value="2200"> 10:00 PM </option>
		<option value="2300"> 11:00 PM </option>
	</select>
	<select name="t2d" class="form-control">
		<option value=""> Select Day </option>
		<option value="1"> Monday </option>
		<option value="2"> Tuesday </option>
		<option value="3"> Wednesday </option>
		<option value="4"> Thursday </option>
		<option value="5"> Friday </option>
		<option value="6"> Saturday </option>
		<option value="7"> Sunday </option>
	</select>
	<select name="t2t" class="form-control">
		<option value=""> Select Time </option>
		<option value="0000"> 12:00 AM </option>
		<option value="0100"> 1:00 AM </option>
		<option value="0200"> 2:00 AM </option>
		<option value="0300"> 3:00 AM </option>
		<option value="0400"> 4:00 AM </option>
		<option value="0500"> 5:00 AM </option>
		<option value="0600"> 6:00 AM </option>	
		<option value="0700"> 7:00 AM </option>
		<option value="0800"> 8:00 AM </option>
		<option value="0900"> 9:00 AM </option>
		<option value="1000"> 10:00 AM </option>
		<option value="1100"> 11:00 AM </option>
		<option value="1200"> 12:00 PM </option>
		<option value="1300"> 1:00 PM </option>
		<option value="1400"> 2:00 PM </option>
		<option value="1500"> 3:00 PM </option>
		<option value="1600"> 4:00 PM </option>
		<option value="1700"> 5:00 PM </option>
		<option value="1800"> 6:00 PM </option>
		<option value="1900"> 7:00 PM </option>
		<option value="2000"> 8:00 PM </option>
		<option value="2100"> 9:00 PM </option>
		<option value="2200"> 10:00 PM </option>
		<option value="2300"> 11:00 PM </option>
	</select>	
	<script> 
		document.getElementById("t1d").size=7;
		document.getElementById("t1t").size=7;
		document.getElementById("t2d").size=7;
		document.getElementById("t2t").size=7;
	</script>
		<input type="submit" class="btn btn-primary">
	</div>
     </div>
   </div>
	</form>

	<?php
		if (array_key_exists("name", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["name"]);
		if (array_key_exists("phone", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["phone"]);
		if (array_key_exists("standingselect", $_REQUEST))
			echo htmlspecialchars($_REQUEST["standingselect"]);
		if (array_key_exists("collegeselect", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["collegeselect"]);
		if (array_key_exists("t1d", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t1d"]);
		if (array_key_exists("t2d", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t2d"]);
		if (array_key_exists("t1t", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t1t"]);
		if (array_key_exists("t2t", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["t2t"]);
		if (array_key_exists("campus", $_REQUEST))
			echo ' '.htmlspecialchars($_REQUEST["campus"]);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($_REQUEST["standingselect"] === "") {
					echo "<p> ERROR: Please choose class standing </p>";
			} elseif ($_REQUEST["collegeselect"] === "") {
					echo "<p> ERROR: Please choose college </p>";
			} elseif ($_REQUEST["name"] === "") {
					echo "<p> ERROR: Please input </p>";
			} elseif($_REQUEST["campus"] === "") {
					echo "<p> ERROR: Please choose campus </p>";
			} elseif($_REQUEST["t1t"] === "") {
					echo "<p> ERROR: Please choose time 1 </p>";
			}elseif($_REQUEST["t1d"] === "") {
					echo "<p> ERROR: Please choose day 1 </p>";
			}elseif($_REQUEST["t2t"] === "") {
					echo "<p> ERROR: Please choose time 2 </p>";
			}elseif($_REQUEST["t2d"] === "") {
					echo "<p> ERROR: Please choose day 2 </p>";
			}else {
				if (is_logged_in()) {
				$t1 = $_REQUEST["t1d"] . $_REQUEST["t1t"];
				$t2 = $_REQUEST["t2d"] . $_REQUEST["t2t"];
				$db = connect_db();

				//RETRIEVE USERID FROM USERS SESSION - CODE HERE
				// TODO(ae): don't let non-logged-in users view this page - I just made it so that it wouldn't submit non-logged in user info (jm)
				$user_id = get_logged_in_user_id();
				$name = $_REQUEST["name"];
				$phone = $_REQUEST["phone"];

				// TODO(ae): validate standingselect range
				$standing_id = $_REQUEST['standingselect'];
				$campus_id = $_REQUEST['campus'];

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
						name = :name,
						phone = :phone,
						standing_id = :standing_id,
						college_id = :college_id,
						time1 = :time1,
						time2 = :time2,
						campus_id = :campus_id
					WHERE id=:user_id");
				$stmt->bindValue("name", $name);
				$stmt->bindValue("phone", $phone);
				$stmt->bindValue("standing_id", $standing_id);
				$stmt->bindValue("college_id", $college_id);
				$stmt->bindValue("time1", $t1);
				$stmt->bindValue("time2", $t2);
				$stmt->bindValue("campus_id", $campus_id);
				$stmt->bindValue("user_id", $user_id);
				$stmt->execute();
				//echo 'success';
				}
				else {
					echo '<script language="javascript">';
					echo 'alert("Please Log In")';
					echo '</script>';
				}
			}
		}
	?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
