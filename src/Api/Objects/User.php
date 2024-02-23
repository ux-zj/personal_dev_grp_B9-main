<?php
class User
{

    private int $id;
    private string $username;
    private string $password;
    private string $email;

    public function __construct(int $id, string $username, string $password, string $email){
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getUserData() : array {
        return array(
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "password" => $this->getPassword(),
            "email" => $this->getEmail()
        );
    }

}