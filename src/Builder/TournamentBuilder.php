<?php

class TournamentBuilder
{
    private Tournament $tournament;
    public function __construct(Tournament $tournament, array $stages){
        $this->tournament = $tournament;
        $this->getTournament()->setStages($stages ?? array(new Stage(-1, "Add Stage", -1, -1, -1)));
    }

    public function getStages(): array
    {
        return $this->getTournament()->getStages();
    }

    public function setStages($stages): void
    {
        $this->getTournament()->setStages($stages);
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

//    public function updateStages(): void
//    {
//        $participants = $this->getTournament()->getRegisteredParticipants();
//        $s[] = new StageBuilder($stage, $participants);
//        $generateStage = array();
//        $currentIndex = 0;
//
//        //var_dump($this->getStages());
//
//        foreach($this->getStages() as $stage){
//
//            $alreadyGenerated = false;
//
//            if($currentIndex == 0 && !$stage->isComplete()){
//                $participants = $this->getTournament()->getRegisteredParticipants();
//            }
//            else{
//
//                $previousStage = $s[$i-1]->getStage();
//                //$previousStage->updateIsComplete();
//
//                if($previousStage->isComplete()){
//                    $participants = $this->getTournament()->getRegisteredParticipants(); //$s[$i-1]->getStage();
//                }
//                else {
//                    $participants = array(new Participant(-1));
//                }
//            }
//            $s[] = new StageBuilder($stage, $participants);
//            $i++;
//        }
//    }


}