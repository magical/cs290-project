<?php require_once 'includes/all.php';

$db=connect_db();

$bid=$_POST["id"];

if(!empty($bid)){
	$building="SELECT building_id,building_abbr FROM buildings WHERE cam_id=$bid ORDER BY building_abbr";
	$b=$db->query($building);

echo "<option value=''>Select Building</option>";
foreach($b as $budquery){
	$bud_id=$budquery[building_id];
	$bud_abbr=$budquery[building_abbr];
	echo "<option value='$bud_abbr'>$bud_abbr</option>";
}
}

?>