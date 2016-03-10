<?php

$week_names = array('Monday','Tuesday','Wednesday','Thursday','Friday', 'Saturday', 'Sunday');
$time_names = array('Midnight', '1 AM', '2 AM', '3 AM', '4 AM', '5 AM', '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', 'Noon', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM', '10 PM', '11 PM');

function is_valid_day($s) {
  global $week_names;
  return in_array($s, $week_names, true);
}

function is_valid_time($t) {
  return is_numeric($t) && (0 <= (int)$t && (int)$t <= 23);
}

function format_time($d, $t) {
  global $time_names;
  if ($d === null || $t === null) {
    return "";
  } elseif (is_valid_day($d) && is_valid_time($t)) {
    return "$d at ${time_names[$t]}";
  } else {
    return '<invalid time>';
  }
}

// Returns the date of the next Monday (or whatever).
// $weekday should be a string containing Monday - Sunday
function next_weekday($weekday) {
  global $week_names;
  $date = new DateTime();
  $i = (int)$date->format("w"); // 0=Sunday
  $j = array_search($weekday, $week_names); // 0=Monday
  $diff = (($j+1) - $i + 7); // days til next $weekday [0..6]
  $diff = ($diff+6)%7+1; // days til next $weekday [1..7]
  $date->modify("+$diff days");
  return $date;
}
