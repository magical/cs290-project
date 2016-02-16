<?php require_once 'includes/all.php'; ?>
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

    echo "<div class='container'>";

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


    echo "<div class='container'>";

    echo "<form action='entrycheck.php' class='form-horizontal' role='form' method='post' name='dentry'>";

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

    echo "<div class='col-sm-3'>";
    echo "<h5>Please select Your Standings:</h5>";
    echo "<select name='sta' id='standing' class='form-control'>";
    echo "<option class='sopt' value=''>Select Standing</option>";
    foreach ($db->query($s) as $squery) {
      $sname=$squery[name];
      $sid=$squery[id];
      echo  "<option value='$sid'>$sname</option>";
    }
    echo "</select>";
    echo "</div>";
    echo "</div>";

    echo "<div class='dropdown'>";
    echo "<br><br>";
    echo "<h5>Please Select Course:</h5>";
    echo "<select name='coudept' id='cdept' class='form-control'>";
    echo "<option class='cdt' value=''>Select Course</option>";
    foreach($db->query($p) as $couquery){
      $coud=$couquery[department];
      $counum=$couquery[number];
      $cid=$couquery[id];
      echo "<option value='$cid'>$coud $counum</option>";
 
    }
    echo "</select>";
    echo "</div>";    

    echo "<div class='row'>";
    echo "<br><br>";
    echo "<div class='col-sm-3'>";
    echo "<h5>Please Select the Day:</h5>";
    echo "<select name='week' class='form-control'>";
    echo "<option class='ww' value=''>Select Day</option>";
    $week=array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday');
    foreach ($week as $value) {
      echo '<option>'.$value.'</option>';
    }
    echo "</select>";
    echo "</div>";

    echo "<div class='col-sm-3'>";
    echo "<h5>Please Select the Time:</h5>";
    echo "<select name='selt' class='form-control'>";
    echo "<option value=''>Select Time</option>";
    for($i=1;$i<=24;$i++){
      echo "<option value='$i'>$i:00</option>";
    }
    echo "</select>";
    echo "</div>";

    echo "</div>";


    echo "<br><br>";


    echo "<input type='submit' class='btn btn-info' value='SUBMIT'></form>";

    echo "</div>";

    ?>


    
    <?php include 'includes/_footer.php';?>
  </body>
</html>
