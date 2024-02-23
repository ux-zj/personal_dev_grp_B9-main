<?php

class Team
{
    private $team_id;
    private $team_name;
    private $team_members;

    public function __construct(int $team_id, string $team_name, array $assigned_participants){
        $this->team_id = $team_id;
        $this->team_name = $team_name;
        $this->team_members = $assigned_participants;
    }

    public function getTeamId()
    {
        return $this->team_id;
    }

    public function getTeamMembers()
    {
        return $this->team_members;
    }

    public function getTeamName(): string
    {
        return $this->team_name;
    }

    public function getTeamArray(): array {
        $teamMembers = array();
        foreach ($this->getTeamMembers() as $participant){
            //var_dump($participant);
            $teamMembers[] = $participant->getParticipantArray();
        }
        return array(
            "team_id" => $this->getTeamId(),
            "team_members" => $teamMembers,
            "team_name" => $this->getTeamName()
        );
    }
}