<?php

$dns = "mysql:host=54.37.31.19;dbname=u223495013_blog";
$user = "u223495013_root";
$pwd = "Klouns;8666";

try {
  $dbh = new PDO($dns, $user, $pwd, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (PDOException $e) {
  $e->getMessage();
}



class ModelRegister
{
  private PDOStatement $statementRegisterUser;

  function __construct($dbh)
  {
    $this->statementRegisterUser = $dbh->prepare('INSERT INTO user VALUES(
            DEFAULT,
            :lastname,
            :firstname,
            :email,
            :password
    )');
  }


  public function registerUser($allInput, $passwordHash)
  {
    $this->statementRegisterUser->bindValue(":lastname", $allInput['lastname']);
    $this->statementRegisterUser->bindValue(":firstname", $allInput['firstname']);
    $this->statementRegisterUser->bindValue(":email", $allInput['email']);
    $this->statementRegisterUser->bindValue(":password", $passwordHash);
    $this->statementRegisterUser->execute();
    return $this->statementRegisterUser->fetch();
  }

  public function registerCheckPost($allInput)
  {
    $allInput = filter_input_array(INPUT_POST, [
      "lastname" => FILTER_SANITIZE_SPECIAL_CHARS,
      "firstname" => FILTER_SANITIZE_SPECIAL_CHARS,
      "email" => FILTER_SANITIZE_EMAIL,
      "password" => FILTER_SANITIZE_SPECIAL_CHARS,
      "confirmPassword" => FILTER_SANITIZE_SPECIAL_CHARS

    ]);

    return $allInput;
  }

  public function registerCheckLastname($lastname)
  {
    if (!$lastname || mb_strlen($lastname) < 2) {
      return "Veuillez rentrer un prénom supérieur à 2 caractères";
    } else return "";
  }

  public function registerCheckFirstname($firstname)
  {
    if (!$firstname || mb_strlen($firstname) < 2) {
      return "Veuillez rentrer un nom supérieur à 2 caractères";
    } else return "";
  }


  public function registerCheckEmail($email)
  {
    if (!$email) {
      return "Veuilllez rentrer une adresse email";
    } else return "";
  }


  public function registerCheckPassword($password)
  {
    if (!$password || mb_strlen($password) < 8) {
      return "Veuillez rentrer un mot de passe contenant 8 caractères";
    } else return "";
  }

  public function registerCheckConfirmPassword($password, $confirmPassword)
  {
    if ($password !== $confirmPassword || !$confirmPassword) {
      return "Les mots de passe ne sont pas identiques";
    } else return "";
  }
}


return new ModelRegister($dbh);
