<?php

include(__DIR__ . '/../config.php');
include(__DIR__ . '/../Model/User.php');

class UserController
{
  public function getUsers()
  {
    $sql = "SELECT * FROM users";
    $db = config::getConnexion();
    try {
      $liste = $db->query($sql);
      return $liste;
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

  public function getUser($id)
  {
    $sql = "SELECT * from users where id = $id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute();

      $user = $query->fetch();
      return $user;
    } catch (Exception $e) {
      die('Error: ' . $e->getMessage());
    }
  }

  public function deleteUser($id)
  {
    $sql = "DELETE FROM users WHERE id = :id";
    $db = config::getConnexion();
    $req = $db->prepare($sql);
    $req->bindValue(':id', $id);

    try {
      $req->execute();
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

  public function addUser($user)
  {
    $sql = "INSERT INTO users VALUES (NULL, :username, :email, :password, :role)";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        ':username' => $user->getUsername(),
        ':email' => $user->getEmail(),
        ':password' => $user->getPassword(),
        ':role' => $user->getRole(),
      ]);
    } catch (Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  function updateUser($user, $id)
  {
   
    try {
      $db = config::getConnexion();

      $query = $db->prepare(
        'UPDATE users SET 
                  username = :username,
                  email = :email,
                  password =  :password,
                  role = :role
              WHERE id = :id'
      );

      $query->execute([
        'id' => $id,
        'username' => $user->getUsername(),
        'email' => $user->getEmail(),
        'password' => $user->getPassword(),
        'role' => $user->getRole(),
      ]);

      //echo $query->rowCount() . " records UPDATED successfully <br>";
      
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}