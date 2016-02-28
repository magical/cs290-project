<?php require_once 'includes/all.php'; ?>
<?php 
	if(!is_logged_in()) {
		header("Location: signin.php");
		exit(0);
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>
      Data Entry
    </title>
    <style>
    div.dropdown{
        width:300px;
    }
    .fupld{
        position: relative;
        overflow: hidden;
    }
    .fupld input.fupl{
        position: absolute;
        top: 0;
        right: 0;
        padding: 0;
        margin: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter:alpha(opacity=0);
    }
    </style>
    <script>
    function f(v){
        document.getElementById("upld").value=v.substring(12);
    }
    </script>
    <?php include 'includes/_head.html';?>
  </head>


  <body>
    <?php include 'includes/_nav.php';?>
    <?php

    $db=connect_db();

    $q="SELECT * FROM colleges order by name";
    $p="SELECT id, department,number FROM courses order by department";
    $s="SELECT id,name FROM standings order by id";

    /*echo "<div class='container'>";

    echo "<form action='upload.php' name='flpd' class='form-horizontal' role='form' method=post enctype=multipart/form-data>";
    
    echo "<div class='jumbotron'>";
    echo "<h2>Upload a Picture</h2>";
    echo "</div>";

    echo "<br>";

    echo "<div class=col-xs-2>";
    echo "<input id='upld' class='form-control' name='finp' placeholder='Choose File' disabled='disabled'>";
    echo "</div>";

    echo "<div class='fupld btn btn-primary'>";
    echo "<span>UPLOAD</span>";
    echo "<input type='file' name='fileupload'  onchange=\"f(this.value)\" class='fupl' id='fileup'>";
    echo "</div>";

    echo "<br><br>";
    echo "<br><br>";

    echo "<div class='subbtn'>";
    echo "<input type='submit' style='position:relative;' class='btn btn-info' name='filesub' value='SUBMIT'>";
    echo "</div>";
    echo "</form>";
    echo "</div>";
	*/

    echo "<div class='container'>";

   /* echo "<form action='' class='form-horizontal' role='form' method='post' name='dentry'>";

    echo "<div class='jumbotron'>";
    echo "<h2>Personal Profile</h2>";
    echo "</div>";

    echo "<div class='form-group'>";

    echo "<div class ='col-xs-2'>";
    echo "<label for='name'>First Name:</label>";
    echo "<input type='text' class='form-control' name='fnm' id='fnm'>";
    echo "</div>";

    echo "<div class ='col-xs-2'>";
    echo "<label for='name'>Last Name:</label>";
    echo "<input type='text' class='form-control' name='lnm' id='lnm'>";
    echo "</div>";

    echo "<div class ='col-xs-2'>";
    echo "<label for='phone'>Phone Number:</label>";
    echo "<input type='text' class='form-control' name='phn' id='phn'>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<BR><BR>";
    echo "<div class='col-sm-3'>";
    echo "<h5>Please select Your College:</h5>";
    echo "<select name='colnm' id='col' class='form-control'>";
    echo "<option class='colleg' value=''>Select College</option>";
    foreach ($db->query($q) as $colquery) {
      $colname=$colquery[name];
      $colid=$colquery[id];
      echo  "<option value='$colid'>$colname</option>";
    }
    echo "</select>";
    echo "</div>";
	 echo "<input type='submit' class='btn btn-info' value='SUBMIT'></form>"; */
	 
	 echo "<div class='jumbotron'>";
    echo "<h2>Study Profile</h2>";
    echo "</div>";

	echo "<form action='' class='form-horizontal' role='form' method='post' name='dentry'>";
	 echo "<table width='100%' border='0px'>";
	 echo "<tr> <td>";
    //echo "<div class='col-sm-3'>";
    echo "<h5>Select Your Standing:</h5>";
	 echo "</td> <td>";
	 //echo "<h5>Please Select Course:</h5>";
	 echo "</td> </tr>";
	 echo "<tr> <td>";
	 echo "<select name='sta' id='sta' class='form-control'>";
    echo "<option class='sopt' value=''>Select Standing</option>";
    foreach ($db->query($s) as $squery) {
      $sname=$squery[name];
      $sid=$squery[id];
      echo  "<option value='$sid'>$sname</option>";
    }
    echo "</select>";
	 echo "</td>";
    //echo "</div>";
    //echo "</div>";

   // echo "<div class='dropdown'>";
   // echo "<br><br>";
	 echo "<td>";
   /* echo "<select name='cdept' id='cdept' class='form-control'>";
    echo "<option class='cdt' value=''>Select Course</option>";
    foreach($db->query($p) as $couquery){
      $coud=$couquery[department];
      $counum=$couquery[number];
      $cid=$couquery[id];
      echo "<option value='$cid'>$coud $counum</option>";
 
    }
    echo "</select>";*/
    //echo "</div>";    
	 echo "</td>";
	 echo "</tr> <tr> <td>";
	 
    //echo "<div class='row'>";
    //echo "<br><br>";
    //echo "<div class='dropdown'>";
    echo "<h5>Please Select the Day:</h5>";
    echo "</td> <td>";
	 echo "<h5>Please Select the Time:</h5>";	 
	 echo "</td> </tr>";
	 echo "<tr> <td>";
	 echo "<select name='week1' id='week1' class='form-control'>";
    echo "<option class='ww' value=''>Select Day</option>";
    $week=array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday'); 
    foreach ($week as $value) {
      echo '<option>'.$value.'</option>';
    }
    echo "</select>";
    //echo "</div>";
	 echo "</td> <td>";
    //echo "<div class='dropdown'>";
    echo "<select name='selt1' id='selt1' class='form-control'>";
    echo "<option value=''>Select Time</option>";
    for($i=1;$i<=24;$i++){
      echo "<option value='$i'>$i:00</option>";
    }
    echo "</select>";
    //echo "</div>";

    //echo "</div>";
	 echo "</td></tr>";
	 echo "<tr> <td>";
	 echo "<select name='week2' id='week2' class='form-control'>";
    echo "<option class='ww' value=''>Select Day</option>";
    $week=array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday');
    foreach ($week as $value) {
      echo '<option>'.$value.'</option>';
    }
    echo "</select>";
    //echo "</div>";
	 echo "</td> <td>";
    //echo "<div class='dropdown'>";
    echo "<select name='selt2' id='selt2' class='form-control'>";
    echo "<option value=''>Select Time</option>";
    for($i=1;$i<=24;$i++){
      echo "<option value='$i'>$i:00</option>";
    }
    echo "</select>";
    //echo "</div>";

    //echo "</div>";
	 echo "</td></tr></table>";

    echo "<br><br>";


    echo "<input type='submit' class='btn btn-primary' value='SUBMIT'>";
	 echo "<input type='hidden' name='times' value='times'> </form>";

    echo "</div>";
	

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('times', $_POST)) {

	$db = connect_db();

	$user_id=get_logged_in_user_id();
	$sid=$_REQUEST["sta"];//standing id
	$couid=$_REQUEST['cdept'];//course id
	$day1=$_REQUEST['week1'];//day
	$time1=$_REQUEST['selt1'];//time
	$day2=$_REQUEST["week2"];
	$time2=$_REQUEST['selt2'];
	$t1="$day1 at $time1:00";
	$t2="$day2 at $time2:00";
	if($sid!='' && $couid!='' && $t1 != '' && $t2 !=''){
				$stmt = $db->prepare("
					UPDATE users SET
						standing_id = :standing_id,
						time1 = :time1,
						time2 = :time2
					WHERE id=:user_id");
				$stmt->bindValue("standing_id", $sid);
				$stmt->bindValue("time1", $t1);
				$stmt->bindValue("time2", $t2);
				$stmt->bindValue("user_id", $user_id);
				$stmt->execute();
	$stmt->execute();

	
}elseif($sid==='') {
		echo '<script language="javascript">';
		echo 'alert("Please Select Standing")';
		echo '</script>';
		header("Location: dataentry.php");
		}
 elseif($couid===''){
 		echo '<script language="javascript">';
		echo 'alert("Please Select Course")';
		echo '</script>';
		header("Location: dataentry.php");
 }
 elseif($day1==='' || $day2 === ''){
 		echo '<script language="javascript">';
		echo 'alert("Please Select Day")';
		echo '</script>';
		header("Location: dataentry.php");
 }
 elseif($time1 ==='' || $time2 === ''){
 		echo '<script language="javascript">';
		echo 'alert("Please Select Time")';
		echo '</script>';
		header("Location: dataentry.php");
 }
 
	
$db = null;
}


$db = connect_db();
$stmt = $db->prepare('SELECT * FROM courses');
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('course_id', $_POST)) {
  echo 'trolololol';
  $stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:user_id, :course_id)");
  $stmt->bindValue(":user_id", get_logged_in_user_id());
  $stmt->bindValue(":course_id", $_POST['course_id']);
  $stmt->execute();
  //header("Location: dataentry.php");
  //exit(0);
}

$user_courses = get_user_courses($db, get_logged_in_user_id()); 


?>

    <h2>Your Courses</h2>

    <table class="table">
      <thead>
        <tr><th>Course<th>Title
      <tbody>
        <?php
          foreach ($user_courses as $course) {
            echo '<tr>';
            echo '<td>';
            echo htmlspecialchars($course['department']. ' ' . $course['number']);
            echo '<td>'.htmlspecialchars($course['title']).'</td>';
          }
        ?>
    </table>

    <form action="" method="POST">
      <select name="course_id">
        <?php
          foreach ($courses as $course) {
            echo '<option value="'.htmlspecialchars($course['id']).'">';
            echo htmlspecialchars($course['department']. ' ' . $course['number'] . ' ' . $course['title']);
            echo "</option>\n";
          }
        ?>
      </select>
      <button class='btn btn-primary'>Add</button>
	
    </form>


    
    <?php include 'includes/_footer.php';?>
  </body>
</html>
