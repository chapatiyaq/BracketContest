<?php 
include ('global.php');
include ('Connection.php');
include ('Controller.php');
include ('Contest.php');
include ('ContestTable.php');
include ('Participant.php');
include ('ParticipantTable.php');
?>

<html>
<head>
<title>LP BCT</title>

</head>
<body>


<?php 

$controller = new Controller();

echo 'getContests()';
echo '<pre>';
print_r($controller->getContests());
echo '</pre>';

echo 'getContest(28)';
echo '<pre>';
print_r($controller->getContest(28));
echo '</pre>';

echo 'getContestByGame(\'Dota 2\'))';
echo '<pre>';
print_r($controller->getContestsByGame('Dota 2'));
echo '</pre>';

echo 'getParticipants(28)';
echo '<pre>';
print_r($controller->getParticipants(28));
echo '</pre>';

?>

</body>
</html>