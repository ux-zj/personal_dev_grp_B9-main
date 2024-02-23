<?php

/*
 * Generates teams based on participant array (given as string) and declared team sizes.
 */

class GenerateTeams
{

    private int $team_size;
    private array $participants;

    public function __construct(int $team_sizes, array $participants){
        $this->team_size = $team_sizes;
        $this->participants = $participants;
    }

    public function getTeamSize(): int
    {
        return $this->team_size;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function generateTeams(){
        $teams = array();
        $participants = $this->getParticipants(); // Change this
        $team_size = $this->getTeamSize();
        $size = sizeof($participants);
        $total_teams = $size / $team_size;

        for($i=0; $i < $total_teams; $i++){
            $team_members = array();
            $team_name = "Team ". $i+1;
            for ($j = 0; $j < $team_size; $j++) {
                $p = $participants[0] ?? new Participant(-1);
                $team_members[] = $p;
                array_shift($participants);
            }
//            $teams[] = array(
//                $team_name => json_encode($team_members, JSON_NUMERIC_CHECK)
//            );
            $teams[] = new Team($i+1, $team_name, $team_members);
        }

//        if($size % ($team_size*2) != 0){
//            $remainder = $size % ($team_size*2);
//            for($i = 0; $i < $remainder; $i++){
//                $participants[] = -1; // Adds Dummy
//            }
//        }
        return $teams;
    }
}