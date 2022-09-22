<?php
// a voir
$dns = "mysql:host=localhost;dbname=charlesCantin";
$user = "root";
$pwd = "klouns8666";

try {
  $dbh = new PDO($dns, $user, $pwd, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (PDOException $e) {
  $e->getMessage();
}

class ModelLogin
{
  private PDOStatement $statementUser;
  private PDOStatement $statementSession;
  private PDOStatement $statementSessionContent;
  private PDOStatement $statementSessionUser;

  function __construct($dbh)
  {
    $this->statementUser = $dbh->prepare('SELECT * FROM user WHERE email=:email');

    $this->statementSession = $dbh->prepare('INSERT INTO session VALUES(
      :idsession,
     :iduser
    )');

    $this->statementSessionContent = $dbh->prepare('SELECT * FROM session');

    $this->statementSessionUser = $dbh->prepare('SELECT * FROM session JOIN user ON user.id=session.iduser WHERE session.idsession=:id ');
  }

  public function retrieveUserConnect($id)
  {
    $this->statementSessionUser->bindValue(':id', $id);
    $this->statementSessionUser->execute();
    return $this->statementSessionUser->fetch();
  }

  public function sessionContent()
  {
    $this->statementSessionContent->execute();
    return $this->statementSessionContent->fetch();
  }

  public function sendSessionContent($id, $sessionId)
  {
    $this->statementSession->bindValue(':iduser', $id);
    $this->statementSession->bindValue(':idsession', $sessionId);
    $this->statementSession->execute();
  }

  public function retrieveUser($email)
  {
    $this->statementUser->bindValue(':email', $email);
    $this->statementUser->execute();
    return $this->statementUser->fetch();
  }

  public function loginCheckPost($allInput)
  {
    $allInput = filter_input_array(INPUT_POST, [
      "email" => FILTER_SANITIZE_EMAIL,
      "password" => FILTER_SANITIZE_SPECIAL_CHARS
    ]);
    return $allInput;
  }

  public function loginCheckEmail($email)
  {
    if (!$email) {
      return "Veuillez rentrer une adresse email";
    } else return "";
  }


  public function loginCheckPassword($password)
  {
    if (!$password) {
      return "Veuillez renseigner votre mot de passe";
    } else return "";
  }
}


return new ModelLogin($dbh);
