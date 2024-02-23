<?php

class Tournament
{

    private int $id;
    private int $owner;
    private string $title;
    private int $private;
    private string $date;
    private array $registered_participants; // Stored as a string to be messed with elsewhere
    private bool $ended;
    private array $stages;

    public function __construct(int $id, int $owner, string $title, int $private, string $date, bool $ended)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->title = $title;
        $this->private = $private;
        $this->date = $date;
        $this->ended = $ended;
    }

    public function getId() : int {
        return $this->id;
    }
    public function getOwner() : int {
        return $this->owner;
    }
    public function getTitle() : string {
        return $this->title;
    }
    public function getPrivate() : int {
        return $this->private;
    }
    public function getDate() : string {
        return $this->date;
    }
    public function getEnded(){
        return $this->ended;
    }
    public function setEnded(bool $ended){
        $this->ended = $ended;
    }
    public function getTournamentData() : array {
        return array(
            "id" => $this->getId(),
            "owner" => $this->getOwner(),
            "title" => $this->getTitle(),
            "private" => $this->getPrivate(),
            "date" => $this->getDate(),
            "participants" => $this->getParticipants(),
            "ended" => $this->getEnded()
        );
    }

    public function setRegisteredParticipants(array $registered_participants): void
    {
        $this->registered_participants = $registered_participants;
    }

    public function setStages(array $stages): void
    {
        $this->stages = $stages;
    }

    public function getRegisteredParticipants(): array
    {
        return $this->registered_participants;
    }

    public function getStages(): array
    {
        return $this->stages;
    }

    public function getStageByIndex($i) : ?Stage {
        return $this->getStages()[$i] ?? null;
    }

}

//    public function prepareParticipants(){
//        $participants = $this->getParticipants();
//        $participantIdArray = array();
//
//        foreach ($participants as $p){
//            $participantIdArray[] = $p->getId();
//        }
//        return json_encode($participantIdArray);
//    }
//    public function getParticipantById($id){
//        $participants = $this->getParticipants();
////        var_dump($participants);
//        if($participants != null){
//            foreach ($participants as $p){
//                if($p->getId() == $id) return($p);
//            }
//        }
//        return null;
//    }
//    public function addParticipant(Participant $participant): void
//    {
////        var_dump($participant);
//        if(($this->getParticipantById($participant->getId())) == null){
//            $this->participants[] = $participant;
//        }
//    }