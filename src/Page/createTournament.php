<?php
session_start();
require_once "../builder/ComponentGenerator.php";
require_once "../Api/api.php";

$generator = new ComponentGenerator();
$u_id = $_SESSION['userID'] ?? null;

if($u_id == null){
    header("location: login.php?logg=in");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
    $generator->head("Create Tournament");
    $generator->navbar();

    if(isset($_POST['title']) && isset($_POST['date'])){
        $api = new api();
        $result =  $api->createTournament(array(
                'owner' => $_POST['u_id'],
                'title' => $_POST['title'],
                'private' => "0",
                'date' => $_POST['date']
        ));

        if($result != "-1"){
            header("location: viewTournament.php?t_id=$result");
            exit();
        } else {
            echo $result;
        }
    }
?>
<body>
    <div class="container py-4">

        <div class="row g-0 border rounded bg-light overflow-hidden flex-md-row mb-4 shadow-sm h-md-250" style="border-radius: 15px !important;">
            <div class="col p-4 d-flex flex-column position-static">
                <form action="createTournament.php" method="post">
                    <div class="form-group">
                        <label for="Title">Title</label>
                        <input name="title" type="text" class="form-control" id="Title" placeholder="Tournament Title">
                    </div>
                    <div class="form-group mt-4">
                        <label for="dateScheduled">Date Scheduled</label>
                        <input name="date" type="Date" class="form-control" id="dateScheduled" placeholder="Date scheduled">
                    </div>
                    <div class="form-group mt-4">
                        <label for="avatarUpload">Upload Tournament Avatar</label>
                        <input name="upload" type="file" class="form-control" id="avatarUpload" placeholder="avatar">
                    </div>
                    <input type="hidden" id="u_id" name="u_id" value="<?php echo $u_id;?>">
                    <button type="submit" class="btn btn-primary mt-4">Create Tournament</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>