<?php
session_start();
$id =  $_GET['t_id'];
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

$database = new DatabaseManager();

$tournamentFromDB = $database->getTournamentById($id);
$stagesFromDB = $database->getStageByTournamentId($database->getTournamentById($id)->getId())
    ?? array(new Stage(-1, "Add Stage", -1, -1, -1));

$tournamentBuilder = new TournamentBuilder($tournamentFromDB, $stagesFromDB);

$generator = new ComponentGenerator();
$generator->setTournament($tournamentBuilder->getTournament());

?>

<!DOCTYPE html>
<html lang="en">

<?php
    $generator->head("View Tournament");
    $generator->navbar();
?>

<body>
    <div class="container-fluid">
        <?php
            $generator->manageTournament();
            $generator->getQR();
        ?>

        <div class="row gy-1 d-flex align-items-center mt-3">
            <div class="col-md-auto">
                <button onclick="window.location.href='viewLeaderboard.php?t_id='<?php echo $id ?>" class="btn btn-dark leaderboard-btn">Leaderboard</button>
            </div>
            <div class="col">
                <div class=" card scroller pill-container" style="width: 100%;">
                    <div class="text overflow-hidden p-2" style="white-space: nowrap;">Some kind of scrolling text goes here -> Tournament description / notification / anything the owner....</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="tournament-wrapper">
            <?php
            $i=0;
            $stages = $tournamentBuilder->getStages();
            if($stages != null) {
                foreach($stages as $stage){
                    $i++;
                    $generator->setStage($stage);
                    $generator->stage_card($i);
                }
            }

            if(isset($_SESSION['userID'])){
                if(($i == sizeof($stages) && $generator->getTournament()->getOwner() == $_SESSION['userID'])){
                    $i++;
                    $generator->setStage(null);
                    $generator->stage_card($i);
                }
            }

            ?>
        </div>
    </div>

</body>
</html>
