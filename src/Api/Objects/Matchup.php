<?php

class Matchup
{
    // Called it matchup because match seems reserved for some reason
    private array $results;
    private array $teams;
    private MatchStatus $flag;

    public function __construct(...$teams) {
        $this->teams = $teams;
        $this->results = array();
        $this->flag = MatchStatus::Intialised;
    }

    public function getFlag(): MatchStatus
    {
        return $this->flag;
    }

    public function setFlag(MatchStatus $flag): void
    {
        $this->flag = $flag;
    }

    public function getTeams(): array
    {
        return $this->teams;
    }

    public function setTeams($teams): void
    {
        $this->teams = $teams;
    }

    public function getResults() : array
    {
        return $this->results;
    }

    public function setResults($results, $team_id): void
    {
        $this->results[$team_id] = $results;
        $this->updateFlag();
    }

    public function getMatchData() : array {
        $teams = array();

        foreach ($this->getTeams() as $team) {
            $teams[] = $team->getTeamArray();
        }

        return array (
            "Team" => $teams,
            "Result" => $this->getResults()
        );
    }

    public function matchComplete(){
        return $this->getFlag() == MatchStatus::Complete;
    }

    public function matchWinner(){
        $teamResults = array();
        $teams = $this->getTeams();

        foreach ($teams as $team){
            $teamResults[] = $team;
        }

        if($this->getFlag() == MatchStatus::Complete){
            $a_results = [];
            $b_results = [];
            foreach ($this->getResults() as $k => $v) {
                $a_results[] = $v[0];
                $b_results[] = $v[1];
            }
            if($a_results[0] > $b_results[0]){
                return $teamResults[0];
            } else {
                return $teamResults[1];
            }
        }
        return null;
//        if(sizeof($results) > 0) {
//            $i = 0;
//            foreach($teams as $team) {
//                // If result is null then it may mean the match isn't complete. Return false
//                if($results[$i] == null){
//                    return false;
//                }
//                else {
//                    $teamResults[] = array( $team, "result" => $results[$i] );
//                    $i++;
//                }
//            }
//            // Sorts by result
//            array_multisort(array_column($teamResults, 'result'), SORT_DESC, $teamResults);
//            //print_r($teamResults);
//            return $teamResults[0][0];
//        } else {
//            return false;
//        }
    }

    public function updateFlag()
    {
        //var_dump(sizeof($this->getResults()));
        if(sizeof($this->getResults()) < 2){
            return null;
        }

        $a_results = [];
        $b_results = [];

        if(sizeof($this->getResults()) == sizeof($this->getTeams())) {

            foreach ($this->getResults() as $k => $v) {
                $a_results[] = $v[0];
                $b_results[] = $v[1];
            }

            if (($a_results[0] == $a_results[1]) && ($b_results[0] == $b_results[1])) {
                $this->setFlag(MatchStatus::Complete);

            } else {
                $this->setFlag(MatchStatus::Disputed);
            }
        } else {
            $this->setFlag(MatchStatus::Ongoing);
        }
    }
}