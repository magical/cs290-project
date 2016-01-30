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

	 <form action="#"> <select name="standingselect">
		<option value="0"> Select Class Standing </option>
		<option value="1"> First-Year </option>
		<option value="2"> Second-Year </option>
		<option value="3"> Third-Year </option>
		<option value="4"> Fourth-Year </option>
		<option value="5"> Fifth-Year or more </option>
	</select>
	<select name="collegeselect">
		<!--Using first three letters to indicate college, first capt'd, for consistency --!>
		<option value="0"> Select College </option>
		<option value="Agr"> Agricultural Sciences </option>
		<option value="Bus"> Business </option>
		<option value="Ear"> Earth, Ocean, and Atmospheric Sciences </option>
		<option value="Edu"> Education </option>
		<option value="Eng"> Engineering </option>
		<option value="For"> Forestry </option>
		<option value="Gra"> Graduate School </option> <!--Do we want this option?--!>
		<option value="Lib"> Liberal Arts </option>
		<option value="Pha"> Pharmacy </option>
		<option value="Pub"> Public Health and Human Services </option>
		<option value="Sci"> Science </option>
		<option value="Vet"> Veterinary Medicine </option>
	</select>
	<input type="text" name="t1"> 
	<input type="text" name="t2">
	</form>
    <?php include 'includes/_footer.php';?>
  </body>
</html>
