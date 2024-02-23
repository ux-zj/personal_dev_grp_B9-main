<?php

require_once "Database.php";

class DatabaseManager
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    //Users
    function getUsers()
    {
        $query = "SELECT * FROM " . $this->db->USER_TABLE;
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getUser($password, $username): ?User
    {
        $query= "SELECT * FROM ". $this->db->USER_TABLE ." 
            WHERE password = ? 
            AND username = ?";

        $stmt = $this->db->prepared_query($query, [$password, $username]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if($row != null) {
            $userId = $row[0]['id'];
            $username = $row[0]['username'];
            $password = $row[0]['password'];
            $email = $row[0]['email'];

            return new User($userId, $username, $password, $email);
        }

        return null;
    }

    function getUserById(int $userId): ?User
    {
        $query = "SELECT * FROM " . $this->db->USER_TABLE . " WHERE id = ?";

        $stmt = $this->db->prepared_query($query, [$userId]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($row != null) {
            $username = $row[0]['username'];
            $password = $row[0]['password'];
            $email = $row[0]['email'];

            return new User($userId, $username, $password, $email);
        }

        return null;
    }

    function createUser($username, $password, $email): bool
    {
        $query = "INSERT INTO " . $this->db->USER_TABLE . "(username, password, email) VALUES (?, ?, ?)";

        if (($username == "" || $username == NULL) || ($password == "" || $password == NULL)) {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$username, $password, $email]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    function updateUser(User $user): bool
    {
        $query = "UPDATE users u
            SET
                u.username=?,
                u.password=?,
                u.email=?
            WHERE
                u.id=?";

        $id = $user->getId();

        if ((!is_numeric($id)) && ($id != null)) {
            return false;
        }

        $username = $user->getUsername();
        $password = $user->getpassword();
        $email = $user->getEmail();

        if (($username == "" || $username == NULL) || ($password == "" || $password == NULL)) {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$username, $password, $email, $id]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    function deleteUser($id): bool
    {
        $query = "DELETE FROM " . $this->db->USER_TABLE . " WHERE u_id = ?";

        $id = $this->db->stripString($id);

        $stmt = $this->db->prepared_query($query, [$id]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    // Tournaments
    function getTournaments()
    {
        $query = "SELECT * FROM " . $this->db->TOURNAMENT_TABLE;
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $owner, $title, $private, $date, $participants, $ended);

        $tournaments_array = array();

        while($stmt->fetch()){
            //$tournament = new Tournament($id, $owner, $title, $private, $date);
            $tournament = array(
                "id" => $id,
                "owner" => $owner,
                "title" => $title,
                "private" => $private,
                "date" => $date,
                "participants" => $participants,
                "ended" => $ended
            );
            $tournaments_array[] = $tournament;
        }
        $stmt->free_result();
        $stmt->close();

        return $tournaments_array;
    }

    function getTournamentById(int $id): Tournament
    {
        $query = "SELECT * FROM " . $this->db->TOURNAMENT_TABLE . " WHERE id = ?";

        $stmt = $this->db->prepared_query($query, [$id]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $title = $row[0]['title'];
        $owner = $row[0]['owner'];
        $date = $row[0]['date'];
        $private = $row[0]['private'];
        $ended = $row[0]['ended'];
        $participants = $row[0]['participants'];
        $tournament = new Tournament($id, $owner, $title, $private, $date, $ended);
        $registered_participants = array();
        foreach (json_decode($participants) as $p){
            $registered_participants[] = new Participant($p);
        }
        $tournament->setRegisteredParticipants($registered_participants);
        return $tournament;
    }

    function createTournament($owner, $title, $private, $date): int
    {
        $query = "INSERT INTO " . $this->db->TOURNAMENT_TABLE . "(owner, title, private, date) VALUES (?, ?, ?, ?)";

        if ($title == NULL || $title == "") {
            return -1;
        }

        $stmt = $this->db->prepared_query($query, [$owner, $title, $private, $date]);
        $count = $stmt->affected_rows;

        if($count == 1) {
            return $stmt->insert_id;
        }
        else {
            return -1;
        }
    }

    function updateTournament(Tournament $tournament): bool
    {
        $query = "UPDATE tournaments t
            SET
                t.title=?,
                t.private=?,
                t.date=?
                t.ended=?
            WHERE
                t.id=?";

        $id = $tournament->getId();

        if ((!is_numeric($id)) && ($id != null)) {
            return false;
        }

        $title = $tournament->getTitle();
        $private = $tournament->getPrivate();
        $date = $tournament->getDate();
        $ended = $tournament->getEnded();

        if ($title == null || $title == "") {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$title, $private, $date, $ended, $id]);
        $count = $stmt->affected_rows;
        return $count == 1;
    }

    function deleteTournament($id): bool
    {
        $query = "DELETE FROM " . $this->db->TOURNAMENT_TABLE . " WHERE t_id = ?";

        $id = $this->db->stripString($id);

        $stmt = $this->db->prepared_query($query, [$id]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    // Stages
    function getStages(): array
    {
        $query = "SELECT * FROM " . $this->db->STAGE_TABLE;
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $activity_id, $format, $tournament_id, $matches, $team_size);

        $stages_array = array();

        while($stmt->fetch()){
            $stage = array(
                "id" => $id,
                "format" => $format,
                "activity_id" => $activity_id,
                "tournament_id" => $tournament_id,
                "team_size" => $team_size,
                "matches" => json_decode($matches) ?? null
            );
            $stages_array[] = $stage;
        }

        $stmt->free_result();
        $stmt->close();

        return $stages_array;
    }

    function getStageById(int $id): ?Stage
    {
        $query = "SELECT * FROM " . $this->db->STAGE_TABLE . " WHERE id = ?";

        $stmt = $this->db->prepared_query($query, [$id]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($row == null) {
            return null;
        }
        $team_size = $row[0]['team_size'] ?? 1;
        $format = $row[0]['format'];
        $act_id = $row[0]['activity_id'];
        $t_id = $row[0]['tournament_id'];
        $matches = $row[0]['matches'] ?? null;
        $stage =  new Stage($id, $format, $act_id, $t_id, $team_size);

        if($matches != null){
            $stage->setMatches(json_decode($matches));
        }

        return $stage;
    }

    function createStage($team_size, $format, $act_id, $t_id): bool
    {
        $query = "INSERT INTO " . $this->db->STAGE_TABLE . "(activity_id, format, tournament_id, team_size) VALUES (?, ?, ?, ?)";

        if (($act_id == NULL || $act_id == "")
            || ($format == NULL || $format == "")
            || ($team_size == NULL || $team_size == "")
            || ($t_id == NULL || $t_id == "")
        ) {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$act_id, $format, $t_id, $team_size]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    function updateStage(Stage $stage): bool
    {
        $query = "UPDATE stages
            SET
                format=?,
                activity_id=?,
                tournament_id=?,
                teams_size=?
            WHERE
                id=?";

        $id = $stage->getId();

        if ((!is_numeric($id)) && ($id != null)) {
            return false;
        }

        $team_size = $stage->getTeamSize();
        $format = $stage->getFormat();
        $act_id = $stage->getActivityId();
        $t_id = $stage->getTournamentId();

        if ($act_id == null || $act_id == "") {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$format, $act_id, $t_id, $team_size, $id]);

        $count = $stmt->affected_rows;

        return $count == 1;
    }

    function deleteStage($id): bool
    {
        $query = "DELETE FROM " . $this->db->STAGE_TABLE . " WHERE id = ?";

        $id = $this->db->stripString($id);

        $stmt = $this->db->prepared_query($query, [$id]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    function getStageMatches(int $id): array {
        $query = "SELECT * FROM " . $this->db->STAGE_TABLE . " WHERE id = ?";

        $stmt = $this->db->prepared_query($query, [$id]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        //print_r($row);
         $matches = json_decode($row[0]['matches']) ?? null;
        if($matches == null) {
            $matchList[] = null;
        } else {
            $matchList = [];
            foreach($matches as $match) {
                $matchList[] = new Matchup($match->id, $match->results);
            }
        }
        return $matchList;
    }

    // Activity
    function getActivityByName(string $activityName): ?Activity
    {
        $query = "SELECT * FROM " . $this->db->ACTIVITY_TABLE . " WHERE title = ?";

        $stmt = $this->db->prepared_query($query, [$activityName]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if($row != null) {

            $dateAdded = $row[0]['date_added'];
            $id = $row[0]['id'];
            $img = $row[0]['img'] ?? null;

            return new Activity($id, $activityName, $dateAdded, $img);
        }
        return null;
    }

    function getActivityById(int $id)
    {
        $query = "SELECT * FROM " . $this->db->ACTIVITY_TABLE . " WHERE id = ?";

        $stmt = $this->db->prepared_query($query, [$id]);

        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if($row != null) {

            return array(
                "id" => $id,
                "dateAdded" => $row[0]['date_added'],
                "img" => $row[0]['img'] ?? null,
                "name" => $row[0]['title']
            );
        }
        return null;
    }

    function createActivity($activityName, $dateAdded): bool
    {
        $query = "INSERT INTO " . $this->db->ACTIVITY_TABLE . "(title, date_added) VALUES (?, ?)";

        if (($activityName == "" || $activityName == NULL) || ($dateAdded == "" || $dateAdded == NULL)) {
            return false;
        }

        $stmt = $this->db->prepared_query($query, [$activityName, $dateAdded]);
        $count = $stmt->affected_rows;

        return $count == 1;
    }

    public function getStageByTournamentId(int $id)
    {
        $query = "SELECT * FROM " . $this->db->STAGE_TABLE . " WHERE tournament_id = ?";
        $stmt = $this->db->prepared_query($query, [$id]);
        $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if($row == null){
            return null;
        }
        $stageArray = array();
        foreach($row as $stage){
            $stageArray[] = new Stage($stage['id'], $stage['format'], $stage['activity_id'], $stage['tournament_id'], $stage['team_size'] ?? 1);
        }
        return ($stageArray);
    }

    // $participant needs to be encoded (json_encode()) for the input to be accepted into the database
    // prepare_query converts everything to a string as well, need to change that
    public function updateParticipants(int $id, String $participants){
        $query = "UPDATE tournaments t
            SET
                t.participants=?
            WHERE
                t.id=?";

        $stmt = $this->db->prepared_query($query, [$participants, $id]);
        $count = $stmt->affected_rows;
        return $count == 1;
    }

}
