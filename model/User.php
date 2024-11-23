<?php

class User
{
    private ?int $id;
    private ?string $username;
    private ?string $email;
    private ?string $password;
    private ?string $role;

    public function __construct(?int $id, string $username = '', string $email = '', string $password = '', string $role = 'user')
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getter for Id
    public function getId(): int
    {
        return $this->id;
    }

    // Setter for Id 
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter for username
    public function getUsername(): string
    {
        return $this->username;
    }

    // Setter for username
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    // Getter for email
    public function getEmail(): string
    {
        return $this->email;
    }

    // Setter for email
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Getter for password
    public function getPassword(): string
    {
        return $this->password;
    }

    // Setter for password
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Getter for role
    public function getRole(): string
    {
        return $this->role;
    }

    // Setter for role
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
?>