<?php

class Leaderboard
{
    private $leaderboard = array();

    public function __construct(array $registeredParticipants){
        echo "\n\nInitialising Leaderboard\n\n";
        foreach ($registeredParticipants as $participant_id){
            $this->leaderboard[] = new Participant($participant_id);
        }
    }


    public function getLeaderboard(): array
    {
        return $this->leaderboard;
    }

    public function sort_leaderboard(){
        usort($this->leaderboard, function($a, $b) { return $b->points - $a->points; });
    }

}