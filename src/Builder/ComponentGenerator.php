<?php
require_once "../Api/api.php";
require_once "../Config/DatabaseManager.php";
require_once "../Api/Objects/Activity.php";



// Not a good way to do this... but it'll do...
class ComponentGenerator
{
    private $tournament;
    private $user;
    private $stage;
    private api $api;
    private $database;

    public function __construct(){
        $this->api = new api();
        $this->database = new DatabaseManager();
    }

    public function getDatabase(): DatabaseManager
    {
        return $this->database;
    }
    public function tournament_card(): void
    {
        $title = $this->tournament->title;
        $id = $this->tournament->id;
        $owner = $this->api->getUsername($this->tournament->owner);
        $status = $this->date_check($this->tournament->date);

        echo
        <<<_END
         <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
                <strong class="d-inline-block mb-2 text-success">$status</strong>
                <h3 class="mb-0">$title</h3>
                <div class="mb-1 text-muted">Hosted by $owner</div>
                <a href="viewTournament.php?t_id=$id" class="stretched-link">View More</a>
            </div>
            
            <div class="col-auto d-none d-lg-block">
                <img src="" width="200" height="250" role="img" aria-label="Placeholder: Thumbnail"
                     preserveAspectRatio="xMidYMid slice" focusable="false"/>
            </div>
        </div>
        _END;
    }

    public function setTournament($tournament): void
    {
        $this->tournament=$tournament;
    }

    public function date_check($date): string
    {
        $current_date = Date("D, d M Y");

        if($current_date > $date) {
            $status = "LIVE";
        } else {
            $status = "SOON";
        }

        return $status;
    }

    public function manageTournament(): void
    {
        $tournament = $this->getTournament();
        $title = $tournament->getTitle();
        $id = $tournament->getId();
        $owner = $this->api->getUsername($tournament->getOwner());
        $status = $this->date_check($tournament->getDate());
        $date = $tournament->getDate();

        echo
        <<<_END
        <div class="row d-flex align-items-center mt-2">
            <div class="col-md-auto">
                <div class="avatar_container">
                    <div class="avatar">
                        <img src="../img/img.png">
                    </div>
                    <div class="pill">
                        <p class="pill_text">$status</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row gy-1">
                    <div class="col-sm-5">
                        <div class="tournament-title mx-auto">
                            <h6>Hosted by $owner</h6>
                            <h2>$title</h2>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class=" card pill-container">
                            <div class="card-body">
                                <h6>Timezone: GMT</h6>
                                <h4>$date</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        _END;

    }

    public function getQR(): void
    {
    }

    public function stage_card($stageNo): void
    {
        //$act_id = null;
        $stage = $this->stage;

        if($stage != null){
            if($stage->getActivityId() !== null){
                $act_id = $this->getDatabase()->getActivityByID((int) $stage->getActivityId());
                $act_id = $act_id['name'] ?? null;
            }
        }
        else {
            $act_id = null;
        }

        $format = $stage->getFormat() ?? "Add Stage";
        $meta_data = $stage->meta_data ?? null;
        $stageId = $stage->getId() ?? null;
        $date = $stage->date ?? null;
        $status = $stage->status ?? null;
        $winner = $stage->winner ?? null;
        $img = $stage->img ?? "placeholder.png";
        $t_id = $this->tournament->getId();

        $btn = null;

        if($meta_data != null) {
            $btn = '<div class="tournament-indicator-container">
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm 
                                rounded-pill tournament-type-indicator">'.$meta_data.'</button>
                    </div>';
        }

        if($stage != null) {
            $link = "viewStage.php?s_id=".$stageId;
        }else{
            $link = "createStage.php?t_id=".$t_id;
        }
        echo
        <<<_END
        <div class="card bg-dark text-white rounded m-3 tournament-container">               
                <img class="card_image" src="../img/$img" class="card-img" alt="...">
               
                <div class="card-img-overlay">
                    
                    $btn
                        
                    <div class="tournament-activity-title">
                        <h2>$act_id</h2>
                        <h4>$format</h4>
                    </div>
        
                    <div class="tournament-winner">
                        <h5 class="d-flex justify-content-center">$winner</h5>
                        <h2 class="d-flex justify-content-center">$winner</h2>
                    </div>
        
                    <div class="tournament-card-info mt-auto">
                        <h4>$status</h4>
                        <div class="d-flex justify-content-between">
                            <h6 style="text-align: left; font-weight: 800;">Stage $stageNo</h6>
                            <h6 style="text-align: right; font-style: italic;
                            font-weight: 600;">$date</h6>
                        </div>
                    </div>
        
                </div>
                <a href="$link" class="stretched-link"></a>
            </div>
        _END;

    }

    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    public function getTournament(){
        return $this->tournament;
    }

    public function head($title): void
    {
        echo <<<_END
            <head>
                <meta charset="UTF-8">
                <title>$title</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <link href="../CSS/style.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                <script src="https://kit.fontawesome.com/a5e8e2f662.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            </head>
        _END;
    }

    public function navbar(): void
    {
        require_once "../Page/Components/navbar.php";
    }
}