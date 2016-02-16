<?php

require_once "includes/all.php";

$db = connect_db();

$fname=$_POST['fnm'];//first name
$lname=$_POST['lnm'];//last name
$phone=$_POST['phn'];//phone num
$col=$_POST['colnm'];//college_id
$sid=$_POST['sta'];//standing id
$couid=$_POST['coudept'];//course id
$day=$_POST['week'];//day
$time=$_POST['selt'];//time

if($fname!='' && $lname!='' && $phone!='' && $col!='' && $sid!='' && $couid!=''){
	$stmt=$db->prepare("INSERT INTO users (name, phone/*,standing_id, college_id */) VALUES (:name, :phone/*,:standing_id, :college_id */)");//I have a problem on insert standing_id and college_id into users table
	$stmt->bindValue("name", "$fname $lname");
	$stmt->bindValue("phone", "$phone");
	$stmt->execute();

	header("Location: display.php");
}else{
	header("Location: dataentry.php");
}


$db = null;


?>