<?php 
require_once 'includes/all.php';
require_once __DIR__ . '/google-api-php-client/src/Google/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName("Study Group Finder");
  $client->setAuthConfigFile(__DIR__ . '/client_secret.json');
  $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
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

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Group Meeting</title>
    <?php include 'includes/_head.html';?>
  </head>
  <body>
      <?php include 'includes/_nav.php';?>
	  <?php
		if (count($results->getItems()) == 0) {
		  print "No upcoming events found.\n";
		} else {
		  print "<h3>Upcoming events:</h3></br>";
		  foreach ($results->getItems() as $event) {
			$start = $event->start->dateTime;
			if (empty($start)) {
			  $start = $event->start->date;
			}
			printf("%s (%s)\n", htmlspecialchars($event->getSummary()), $start);
		  }
		}
	  ?>
	  <?php include 'includes/_footer.php';?>
  </body>
</html>
