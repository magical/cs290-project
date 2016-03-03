<?php require_once 'includes/all.php'; 


if (!is_logged_in()) {
   header("Location: signin.php");
   exit(0);
}
	

?>

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
	<?php
	
	$db = connect_db();
	$user = get_user($db, get_logged_in_user_id());
	if (!$user) {
	 	header('Status: 404');
	 	die('no such user');
	}
	$usersname = $user['name'];
	echo "<input type='text' name='name' class='form-control' style='width: 150px; caption-side: bottom' placeholder='$usersname'>";
	echo "</td>";
	echo "<td>";
	$usersphone = $user['phone'];
	echo "<input type='text' name='phone' class='form-control' style='width: 150px; position: relative; caption-side: bottom;' placeholder='$usersphone'>";
	echo "</td>";
	echo "</tr>";
	echo "</table>	";
	echo "<p> </p>";
   echo "<div class='row'> ";
	/*	  <div class="col-md-4">
	<select name="standingselect" class="form-control">
		<option value=""> Select Class Standing </option>
		<option value="1"> First-Year </option>
		<option value="2"> Second-Year </option>
		<option value="3"> Third-Year </option>
		<option value="4"> Fourth-Year </option>
		<option value="5"> Fifth-Year or more </option>
	</select>
	
	</div> */
	echo "<div class='col-md-4'>";
	$i = $user['college_id'];
	/*echo "<style>";
	echo "option:nth-of-type($i) {";
	echo "	selected: selected; } </style>"; */	
	echo "<select name='collegeselect' class='form-control'>";
		//<!--Using first three letters to indicate college, first capt'd, for consistency -->
	echo "<option value='' disabled selected> Select College </option>";
	echo "<option value='Agr' ";
	if ($i == 1) {
	echo "selected='selected'";
	}
	echo "> Agricultural Sciences </option>";
	echo "<option value='Bus' ";
	if ($i == 2) {
	echo "selected='selected'";
	}
	echo "> Business </option>";
	echo "<option value='Ear' ";
	if ($i == 3) {
	echo "selected='selected'";
	}
	echo "> Earth, Ocean, and Atmospheric Sciences </option>";
	echo "<option value='Edu' ";
	if ($i == 4) {
	echo "selected='selected'";
	}
	echo "> Education </option>";
	echo "<option value='Eng' ";
	if ($i == 5) {
	echo "selected='selected'";
	}
	echo "> Engineering </option>";
	echo "<option value='For' ";
	if ($i == 6) {
	echo "selected='selected'";
	}
	echo "> Forestry </option>";
	echo "<option value='Lib' ";
	if ($i == 7) {
	echo "selected='selected'";
	}
	echo "> Liberal Arts </option>";
	echo "<option value='Pha' ";
	if ($i == 8) {
	echo "selected='selected'";
	}
	echo "> Pharmacy </option>";
	echo "<option value='Pub' ";
	if ($i == 9) {
	echo "selected='selected'";
	}
	echo "> Public Health and Human Services </option>";
	echo "<option value='Sci' ";
	if ($i == 10) {
	echo "selected='selected'";
	}
	echo "> Science </option>";
	echo "<option value='Vet' ";
	if ($i == 11) {
	echo "selected='selected'";
	}
	echo "> Veterinary Medicine </option>";
	echo "</select>";
	echo "</div>";
	$p = $user['campus_id'];
	echo "<div class='col-md-4'>";
	echo "<select name='campus' class='form-control'>";
		echo "<option value=''> Select Campus </option>";
		echo "<option value='1' ";
	if ($p == 1) {
	echo "selected='selected'";
	}
	echo "> Corvallis (Main)</option>";
		echo "<option value='2' ";
	if ($p == 2) {
	echo "selected='selected'";
	}
	echo "> Cascades </option>";
		echo "<option value='3' ";
	if ($p == 3) {
	echo "selected='selected'";
	}
	echo "> Online </option>";
	echo "</select>";
	echo "</div>";
      echo " </div>";
     echo "</div>";
   /*<!--<div class="container" style="width:100%">
     <div class="row">
	<div class="col-lg-12 form-inline">
	    	<p>Enter your two best times in the format DAY, TI:ME XM (i.e: R, 05:00 PM) </p>
		<p>Use R for Thursday, N for Sunday</p>
--		<input type="text" name="t1" class="form-control" style="width: 150px"> --
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
	</script> -->  */
	echo "	<p></p>";
	echo "	<div class='container'>";
	echo "	<input type='submit' style='position:relative;' class='btn btn-primary' name='filesub' value='SUBMIT'> </div>";
	//echo "	<!--<input type="submit" class="btn btn-primary" style="position:relative">-->";
	/*<!--</div>
     </div>
   </div>-->*/
	echo "</form>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			/*if ($_REQUEST["standingselect"] === "") {
					echo "<p> ERROR: Please choose class standing </p>";
			} */if ($_REQUEST["collegeselect"] === "") {
					echo "<p> ERROR: Please choose college </p>";
			} elseif($_REQUEST["campus"] === "") {
					echo "<p> ERROR: Please choose campus </p>";
			} /*elseif($_REQUEST["t1t"] === "") {
					echo "<p> ERROR: Please choose time 1 </p>";
			}elseif($_REQUEST["t1d"] === "") {
					echo "<p> ERROR: Please choose day 1 </p>";
			}elseif($_REQUEST["t2t"] === "") {
					echo "<p> ERROR: Please choose time 2 </p>";
			}elseif($_REQUEST["t2d"] === "") {
					echo "<p> ERROR: Please choose day 2 </p>";
			} */
			else {
				if (is_logged_in()) {
				//$t1 = $_REQUEST["t1d"] . $_REQUEST["t1t"];
				//$t2 = $_REQUEST["t2d"] . $_REQUEST["t2t"];
				//$db = connect_db();
				

				//RETRIEVE USERID FROM USERS SESSION - CODE HERE
				// TODO(ae): don't let non-logged-in users view this page - I just made it so that it wouldn't submit non-logged in user info (jm)
				$user_id = get_logged_in_user_id();
				$user = get_user($db, $user_id);
				$userold = get_user($db, $user_id);
				if ($_REQUEST['name'] == '') {
					$name = $user['name'];
				} else {
					$name = $_REQUEST["name"];
				}
				if ($_REQUEST['phone'] == '') {
					$phone = $user["phone"];
				} else {
					$phone = $_REQUEST['phone'];
				}
				// TODO(ae): validate standingselect range
				//$standing_id = $_REQUEST['standingselect'];
				$campus_id = $_REQUEST['campus'];
				$p = $campus_id;

				// Look up the college
				$stmt = $db->prepare("SELECT id FROM colleges WHERE abbreviation = ?");
				$stmt->execute(array($_REQUEST['collegeselect']));
				$row = $stmt->fetch();
				if ($row === false) {
					die("invalid college");
				}
				$college_id = $row[0];
				$i = $college_id;
				$stmt = $db->prepare("
					UPDATE users SET
						name = :name,
						phone = :phone,
						college_id = :college_id,
						campus_id = :campus_id
					WHERE id=:user_id");
				$stmt->bindValue("name", $name);
				$stmt->bindValue("phone", $phone);
			//	$stmt->bindValue("standing_id", $standing_id);
				$stmt->bindValue("college_id", $college_id);
			//	$stmt->bindValue("time1", $t1);
			//	$stmt->bindValue("time2", $t2);
				$stmt->bindValue("campus_id", $campus_id);
				$stmt->bindValue("user_id", $user_id);
				$stmt->execute();
				//echo 'success';
				if(($userold['campus_id'] != $campus_id) || ($userold['college_id'] != $college_id) || ($userold['name'] != $name) || ($userold['phone'] != $phone)) {
					echo "<script> location.reload() </script>";
					}
				}
				else {
					echo '<script language="javascript">';
					echo 'alert("Please Log In")';
					echo '</script>';
				}
			}
		}
	
    echo "<form action='upload.php' name='flpd' class='form-horizontal' role='form' method=post enctype=multipart/form-data>";
    
    echo "<div class='jumbotron'>";
    echo "<h2>Upload a Picture</h2>";
    echo "</div>";
    echo "<div class='container'>";

    echo "<br>";

   /* echo "<div class=col-xs-2>";
    echo "<input id='upld' class='form-control' name='finp' placeholder='Choose File' disabled='disabled'>";
    echo "</div>"; */
	 echo "<h5> Supported File Types: JPEG, JPG, GIF, PNG, less than 1 MB </h5>";
    echo "<div class='fupld btn btn-primary'>";
    echo "<span>UPLOAD</span>";
    echo "<input type='file' name='fileupload'  onchange=\"f(this.value)\" class='fupl' id='fileup'>";
    echo "</div>";

    echo "<br><br>";
    echo "<br><br>";

    echo "<div class='subbtn'>";
    echo "<input type='submit' style='position:relative;' class='btn btn-primary' name='filesub' value='SUBMIT'>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
	?>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
