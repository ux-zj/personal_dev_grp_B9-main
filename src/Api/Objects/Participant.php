<?php

class Participant
{
    private $id;
    private $points;

    public function __construct($id) {
        $this->id = $id;
        $this->points = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function addPoints($points){
        $this->points = $this->points + $points;
    }

    public function getParticipantArray() {
        return array(
            $this->id => $this->points
        );
    }

    public function removePoints($points){
        $this->points = $this->points - $points;
    }
}