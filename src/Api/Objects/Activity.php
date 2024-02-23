<?php

class Activity
{
    private int $id;
    private string $name;
    private $dateAdded;
    private $img;

    public function __construct(int $id, string $name, $dateAdded, ?string $img)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateAdded = $dateAdded;
        $this->img = $img;
    }

    public function getId() : int{
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

}