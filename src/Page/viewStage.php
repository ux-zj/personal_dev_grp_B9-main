<?php
session_start();
//header('Content-type: text/javascript');
require_once "../Api/api.php";
require_once "../builder/ComponentGenerator.php";
require_once "../Config/DatabaseManager.php";
require_once "../Api/Objects/Participant.php";
require_once "../Api/Objects/Tournament.php";
require_once "../Api/Objects/Stage.php";
require_once "../Api/Objects/Matchup.php";
require_once "../Api/Objects/Format.php";
require_once "../Api/Objects/Leaderboard.php";
require_once "../Api/Objects/Team.php";
require_once "../Builder/GenerateTeams.php";
require_once "../Builder/StageBuilder.php";
require_once "../Builder/TournamentBuilder.php";
require_once "../Api/Objects/Format.php";
require_once "../Api/Objects/MatchStatus.php";

$s_id = $_GET['s_id'];

$database = new DatabaseManager();

$stage = $database->getStageById($s_id);
$tournament = $database->getTournamentById($stage->getTournamentId());
$stage->populateStage(null, $tournament->getRegisteredParticipants());

//print_r($stage);

?>

<iframe
    id="inlineFrameExample"
    title="Inline Frame Example"
    width="100%"
    height="100%"
    src="../test.html">
</iframe>


