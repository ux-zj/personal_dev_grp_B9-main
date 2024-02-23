<?php

class StageBuilder
{
    private Stage $stage;
    private array $participants;
    private array $teams;

    public function __construct(Stage $stage, $participants){
        $this->stage = $stage;
        $this->participants = $participants;
//        $assigned_teams = new GenerateTeams($stage->getTeamSize(), $participants);
//        $teams = $assigned_teams->generateTeams();
//        $format = Format::tryFrom($stage->getFormat()) ?? Format::Gauntlet;
//        $matches = $format->generateFromFormat($teams);
//        $stage->setMatches($matches);
//        if(sizeof($this->getStage()->getMatches()) > 1) {
            $this->setMatches();
//        }
    }

    public function setTeams(array $teams): void
    {
        $this->teams = $teams;
    }
    public function getParticipants(): array {
        return $this->participants;
    }
    public function getStage(): Stage
    {
        return $this->stage;
    }
    public function getFormat(): Format
    {
        return Format::tryFrom($this->getStage()->getFormat()) ?? Format::Gauntlet;
    }

    public function getMatches(): array
    {
        return $this->getStage()->getMatches();
    }
    public function setMatches(): void
    {
        $this->getStage()->setMatches($this->getFormat()->generateFromFormat($this->generateTeams()));
    }

    public function generateTeams(): array
    {
        return $this->teams = (new GenerateTeams($this->getStage()->getTeamSize(), $this->getParticipants()))->generateTeams();
    }

}