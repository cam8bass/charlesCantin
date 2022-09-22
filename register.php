
<?php
require_once __DIR__ . "/config/utilities.php";
$ModelRegister = require_once __DIR__ . "/models/model-register.php";
$userConnect='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $allInput = $ModelRegister->registerCheckPost($_POST);
  $lastname = $allInput['lastname'] ?? '';
  $firstname = $allInput['firstname'] ?? '';
  $email = $allInput['email'] ?? '';
  $password = $allInput['password'] ?? '';
  $confirmPassword = $allInput['confirmPassword'] ?? '';

  $errorManagement = [
    "errorLastname" => $ModelRegister->registerCheckLastname($lastname),
    "errorFirstname" => $ModelRegister->registerCheckFirstname($firstname),
    "errorEmail" => $ModelRegister->registerCheckEmail($email),
    "errorPassword" => $ModelRegister->registerCheckPassword($password),
    "errorConfirmPassword" => $ModelRegister->registerCheckConfirmPassword($password, $confirmPassword)
  ];
  if (empty(array_filter($errorManagement, fn ($el) => $el != ''))) {
    // Permet de protéger le password
    $passwordHash = password_hash($password, PASSWORD_ARGON2I);
    // Permet d'envoyer le nouveau profil à la Bdd
    $ModelRegister->registerUser($allInput, $passwordHash);
    header("location: ./index.php");
  }
} else {
  $errorManagement = [
    "errorLastname" => "",
    "errorFirstname" => "",
    "errorEmail" => "",
    "errorPassword" => "",
    "errorConfirmPassword" => ''
  ];
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>

  <title>Inscription</title>
</head>

<body>
<?php require_once __DIR__."/includes/nav.php"; ?>
  <div class="container__form">
    <form action="" method="post" class="form" enctype="multipart/form-data">
      <h1 class="form__heading"></h1>
      <div class="form__container">

        <div class="form__block">
          <label for="lastname" class="form__label form__label-title">Nom</label>
          <input type="text" class="form__input form__input-title" name="lastname" id="lastname" value="" />
          <p class="form__warning"><?= $errorManagement['errorLastname'] ? $errorManagement['errorLastname'] : ""  ?></p>
        </div>

        <div class="form__block">
          <label for="firstname" class="form__label form__label-title">Prénom</label>
          <input type="text" class="form__input form__input-title" name="firstname" id="firstname" value="" />
          <p class="form__warning"><?= $errorManagement['errorFirstname'] ? $errorManagement['errorFirstname'] : ""  ?></p>
        </div>

        <div class="form__block">
          <label for="email" class="form__label form__label-title">Adresse email</label>
          <input type="email" class="form__input form__input-title" name="email" id="email" value="" />
          <p class="form__warning"><?= $errorManagement['errorEmail'] ? $errorManagement['errorEmail'] : ""  ?></p>
        </div>

        <div class="form__block">
          <label for="password" class="form__label form__label-title">Mot de passe</label>
          <input type="password" class="form__input form__input-title" name="password" id="password" value="" />
          <p class="form__warning"><?= $errorManagement['errorPassword'] ? $errorManagement['errorPassword'] : ""  ?></p>
        </div>

        <div class="form__block">
          <label for="confirmPassword" class="form__label form__label-title">Confirmation mot de passe</label>
          <input type="password" class="form__input form__input-title" name="confirmPassword" id="confirmPassword" value="" />
          <p class="form__warning"><?= $errorManagement['errorConfirmPassword'] ? $errorManagement['errorConfirmPassword'] : ""  ?></p>
        </div>

        <div class="form__btn">
          <a href="/index.php" class="article__link">
            <button type="button" class="btn form__btn form__btn-cancel">Annuler</button>

          </a>
          <button type="submit" class="btn form__btn form__btn-save">Valider</button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>