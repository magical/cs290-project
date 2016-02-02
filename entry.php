<?php session_start();?>
<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Data Entry</title>
  </head>
   <?php include 'includes/_head.html';?>
  <body>
      <?php include 'includes/_nav.php';?>
      <div class="jumbotron">	 
	 <h1> Your Profile </h1>
	 <h4> Please fill out this information </h4>
      </div>
    <div class="container" style="width:100%">
      <div class="row"> 
        <div class="col-md-4">
	<form action="#"> <select name="standingselect">
		<option value="0"> Select Class Standing </option>
		<option value="1"> First-Year </option>
		<option value="2"> Second-Year </option>
		<option value="3"> Third-Year </option>
		<option value="4"> Fourth-Year </option>
		<option value="5"> Fifth-Year or more </option>
	</select>

	</div>
	<div class="col-md-4">
	<select name="collegeselect">
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
	</div>
	<div class="col-md-4">
	<select name="campus">
		<option value="0"> Select Campus </option>
		<option value="Cor"> Corvallis (Main)</option>
		<option value="Cas"> Cascades </option>
		<option value="Onl"> Online </option>
	</select>
	</div>
       </div>
     </div>
   <div class="container" style="width:100%">
     <div class="row">
	<div class="col-lg-12">
	    	<h4>Enter your two best times in the format DAY, TI:ME XM (i.e: R, 05:00 PM) </h4>
		<h5>Use R for Thursday, N for Sunday</h5>
		<input type="text" name="t1"> 
		<input type="text" name="t2">
		<input type="submit">
	</div>
     </div>
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

		if ($_REQUEST["standingselect"] == "0") {
				echo "<p> ERROR: Please choose class standing </p>";
		}
		elseif ($_REQUEST["collegeselect"] == "0") {
				echo "<p> ERROR: Please choose college </p>";
		}
		elseif($_REQUEST["campus"] == "0") {
				echo "<p> ERROR: Please choose campus </p>";
		}
		else {
		
		

			//$db = connect_db(); //not working for me, probably because i need a version on my own machine, but i cant find it
			
			

			//RETRIEVE USERID FROM USERS SESSION - CODE HERE
			
			//UP THERE SEE THAT NOTE PLEASE :)
			//This is a temporary user ID
		//	$sql = UPDATE 'users'
			//		 SET 'standing_id'
			
			//$mysqli->query("update users set standing_id=$_REQUEST["standingselect"] major_id=$_REQUEST["collegeselect"] time1_id=$_REQUEST["t1"] time2_id=$_REQUEST["t2"] campus_id=$_REQUEST["campus"] where id=2");
			//$db = null;
		}
			
/*				$dbhost = 'odu';
			$dbname = 'mb';
			$dbuser = 'mb';
			$dbpass = 'HA NICE TRY';

			$mysql_handle = mysql_connect($dbhost, $dbuser, $dbpass)
	   	 or die("Error connecting to database server");

			mysql_select_db($dbname, $mysql_handle)
		    or die("Error selecting database: $dbname");

			echo 'Successfully connected to database!';
			
			mysql_close($mysql_handle);
			
*/
			//insert this into table 

		?>
		    <?php include 'includes/_footer.php';?>
  </body>
  
</html>