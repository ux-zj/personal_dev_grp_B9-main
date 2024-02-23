<?php

class Stage
{
    private int $id;
    private int $team_size;
    private string $format;
    private int $activity_id;
    private int $tournament_id;
    //private bool $isComplete;
    private array $matches = array();
//    private array $leaderboard = array();

    public function __construct(int $id, string $format, int $activity_id, int $tournament_id, int $team_size){
        $this->id = $id;
        $this->format = $format;
        $this->activity_id = $activity_id;
        $this->tournament_id = $tournament_id;
        $this->team_size = $team_size;
    }

    public function getId() : int {
        return $this->id;
    }
    public function getTeamSize() : int {
        return $this->team_size;
    }
    public function getFormat() : string {
        return $this->format;
    }
    public function getActivityId() : int {
        return $this->activity_id;
    }
    public function getTournamentId() : int {
        return $this->tournament_id;
    }
    public function setTeamSize(int $team_size): void
    {
        $this->team_size = $team_size;
    }
    public function isComplete(): bool
    {
        $matches = $this->getMatches();
        $isComplete = true;
        foreach($matches as $matchup){
            if($matchup->getFlag() != MatchStatus::Complete){
                $isComplete = false;
            }
        }
        return $isComplete;
    }

    public function getStageData() : array {
        return array(
            "id" => $this->getId(),
            "team_size" => $this->getTeamSize(),
            "format" => $this->getFormat(),
            "activity_id" => $this->getActivityId(),
            "tournament_id" => $this->getTournamentId(),
            "matches" => $this->getMatches()
        );
    }
    public function getMatches(): array
    {
        return $this->matches;
    }
    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

//    public function updateIsComplete() : void{
//        $matches = $this->getMatches();
//        $isComplete = true;
//        foreach($matches as $matchup){
//            if($matchup->getFlag() != MatchStatus::Complete){
//                $isComplete = false;
//            }
//        }
//        $this->setIsComplete($isComplete);
//    }
    // $i is index of match
    function setMatchResult($i, array $results, $team_id){
        $matches = $this->getMatches();
        $size = sizeof($matches);

        if($size  < 2) {
            return null;
        } else
        {
            $matches[$i]->setResults($results, $team_id);
            if($matches[$i]->getFlag() == MatchStatus::Complete){
                $winner = $matches[$i]->matchWinner() ?? null;
                if($winner != null){
                    if($i + 1 < $size - 1){
                        $teams = $matches[$i + 1]->getTeams();
                        $teams[1] = $winner;
                        $matches[$i + 1]->setTeams($teams);
                    }
                }
        }
        }
        return $matches;
    }

    function getAllResults(){
        $matches = $this->getMatches();
        $size = sizeof($matches);
        $seeding = array();

        if ($size > 1){
            foreach ($matches as $match){
                $higher_seed = $match->matchWinner();
                if($higher_seed == null){
                    return null;
                }
                foreach ($match->getTeams() as $team){
                    if($team != $higher_seed){
                        $lower_seed = $team;
                    }
                }
                $seeding[] = $lower_seed;
            }
            $seeding[] = $higher_seed;
        }
        return $seeding; // Highest at the bottom
    }

    function setInternalPoints(){
        $seeding = $this->getAllResults();
        $size = sizeof($seeding);

        $points = 0;
        foreach($seeding as $team){
            foreach ($team->getTeamMembers() as $participant){
                $participant->addPoints($points);
            }
            $points++;
        }
    }

    public function populateStage(?Stage $previous_stage, $participants)
    {
        if($previous_stage != null) {
//            echo "PREVIOUS" . PHP_EOL;
//            print_r($previous_stage);
//            echo "PREVIOUS" . PHP_EOL;

            if($previous_stage->getMatches() != null && $previous_stage->isComplete()) {
                new StageBuilder($this, $participants);
            }
        } else {
            new StageBuilder($this, $participants); // If previous stage is null, means that the index is at 0.
        }
    }

//    public function setInternalLeaderboard($internal_leaderboard): void
//    {
//        foreach(json_decode($internal_leaderboard) as $k => $v) {
//            $this->internal_leaderboard[] = new Participant($v);
//        }
//        //$this->internal_leaderboard = $internal_leaderboard;
//    }
//
//    public function getInternalLeaderboard()
//    {
//        return $this->internal_leaderboard;
//    }
//
//    public function removeKnockedOut(){
//        $leaderboard = $this->getInternalLeaderboard();
//        $i=0;
//        foreach($leaderboard as $p){
//
//            if($p->points <= -1){
//                unset($this->internal_leaderboard[$i]);
//            }
//            $i++;
//        }
//    }
//
//    public function sortParticipants(){
//        usort($this->internal_leaderboard, function($a, $b) { return $b->points - $a->points; });
//    }
//
//    public function setInternalPoints()
//    {
//    }

}