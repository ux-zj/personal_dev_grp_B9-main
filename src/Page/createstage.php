<?php
session_start();
// TODO: Rename type to team_size
require_once "../Config/DatabaseManager.php";
require_once "../Api/Objects/Activity.php";
require_once "../Api/Objects/Stage.php";
require_once "../Api/api.php";
require_once "../builder/ComponentGenerator.php";
$generator = new ComponentGenerator();

if(!isset($_GET['t_id'])){
    header("Location: home.php?alert=failed"); // Change to other page when ready
}
else {

    $tournamentId = $_GET['t_id'];
    $t_id = null;
    $activityObject = null;

    if( isset($_POST['t_id']) && $_POST['t_id'] != null
        && isset($_POST['activitypost']) && $_POST['activitypost'] != null
        && isset($_POST['formatpost']) && $_POST['formatpost'] != null
        && isset($_POST['teamSizepost']) && $_POST['teamSizepost'] != null) {

        $database = new DatabaseManager();

        if ($database->getActivityByName($_POST['activitypost']) == null) {
            $database->createActivity($_POST['activitypost'], date("Y-m-d"));
        }
        $activityObject = $database->getActivityByName($_POST['activitypost']);

        if($database->createStage($_POST['teamSizepost'], $_POST['formatpost'], $activityObject->getId(), $_POST['t_id'])) {
            header("Location: viewtournament.php?t_id=".$tournamentId); // TODO: Change to other page when it is ready
            exit;
        } else {
            header("Location: createStage.php?t_id=".$tournamentId."&alert=Error");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php
$generator->head("View Tournament");
$generator->navbar();
?>

<body>

<div class="container py-4">

    <div class="row g-1 overflow-hidden flex-md-row mb-4 h-md-250 position-relative">
        <div class="col mt-4 ml-4 d-flex flex-column">
            <div class="">
                <button class="btn btn-dark btn-lg disabled">Stage 2</button>
            </div>
        </div>
    </div>

    <div class="row g-0 border rounded bg-light overflow-hidden flex-md-row mb-4 shadow-sm h-md-250" style="border-radius: 15px !important;">

        <div class="col p-4 d-flex flex-column position-static">
            <form action="createstage.php?<?php echo "t_id=" . $tournamentId;?>" method="post">
                <div class="form-group">
                    <label for="activity">Activity</label>
                    <input type="text" class="form-control" id="activitypost" name="activitypost" placeholder="Activity">
                </div>
                <div class="form-group mt-4">
                    <label for="format">Format</label>
                    <input type="text" class="form-control" id="formatpost" name="formatpost" placeholder="Format">
                </div>
                <div class="form-group mt-4">
                    <label for="teamSize">Team Size</label>
                    <input type="text" class="form-control" id="teamSizepost" name="teamSizepost" placeholder="Team Size">
                </div>
                <input type="hidden" id="t_id" name="t_id" value="<?php echo $tournamentId;?>">
                <button type="submit" class="btn btn-primary mt-4">Add Stage</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>