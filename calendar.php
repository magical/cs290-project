<?php 
require_once __DIR__ . '/google-api-php-client/src/Google/autoload.php';
require_once 'includes/all.php';
/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName("Study Group Finder");
  $client->setAuthConfigFile(__DIR__ . '/client_secret.json');
  $client->addScope(Google_Service_Calendar::CALENDAR);
  $client->setRedirectUri(current_url());
  $client->setAccessType('offline');
  if(isset($_SESSION["googleauth"])){
	  $client->setAccessToken($_SESSION["googleauth"]);
	  $_SESSION["googleauth"] = NULL;
	  return $client;
  }
  if (!isset($_GET['code'])) {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    header("Location: $authUrl");
    exit(0);
  }

  $authCode = $_GET['code'];

  // Exchange authorization code for an access token.
  $accessToken = $client->authenticate($authCode);
  $_SESSION["googleauth"] = $accessToken;
  header("Location: calendar.php");
  exit(0);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);
$event = new Google_Service_Calendar_Event();
 
 $event->setSummary($_SESSION['event']['Summary']);
 $event->setDescription($_SESSION['event']['Description']);
 $event->setLocation($_SESSION['event']['Location']);
 $start = new Google_Service_Calendar_EventDateTime();
 $start->setDateTime('2015-04-16T10:00:00.000-07:00');
 $event->setStart($start);
 $end = new Google_Service_Calendar_EventDateTime();
 $end->setDateTime('2015-04-16T10:25:00.000-07:00');
 $event->setEnd($end);
 $event->attendees = $_SESSION['event']['GrMem'];

$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);


$groupID = $_SESSION["event"]["gID"];
$_SESSION["event"] = 'Event created: '.'<a href = "'.$event->htmlLink.'">'.$_SESSION["event"]["Summary"].'</a>';

header("Location: group.php?id=".urlencode($groupID));
?>

