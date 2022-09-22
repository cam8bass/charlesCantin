<?php
$ModelLogin = require_once __DIR__ . "/models/model-login.php";
$userConnect = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $allInput = $ModelLogin->loginCheckPost($_POST);
  $email = $allInput['email'] ?? '';
  $password = $allInput['password'] ?? '';
  $errorManagement = [
    "errorEmail" => $ModelLogin->loginCheckEmail($email),
    "errorPassword" => $ModelLogin->loginCheckPassword($password),
  ];
  if (empty(array_filter($errorManagement, fn ($el) => $el != ''))) {
    // Permet de rechercher un utilisateur grace à l'email rentré
    $user = $ModelLogin->retrieveUser($email);
    if (!$user) {
      // Si l'adresse email n'est rattaché à aucun compte
      $errorManagement['errorEmail'] = "L'adresse email n'est pas reconnue";
    } elseif ($user && password_verify($password, $user['password'])) {
      // Si le compte existe et que le password est correct

      //Permet de créer un id pour la session
      $sessionId = bin2hex(random_bytes(32));
      //Permet d'envoyer l'id dans la session coté Bdd
      $ModelLogin->sendSessionContent($user['id'], $sessionId);

      // Permet de créer 2 cookies (signature et session)
      $signature = hash_hmac('sha256', $sessionId, "82b9cca8c89955d90458c1420d9399b16bc83c8e7c58f709b4f3022a430a0d4fd421993ef5ecc2553798044f4b5c98f23f9215b9dd84bab0fba9b332e48d7087");
      $session = $ModelLogin->sessionContent();
      $sessionId = $session['idsession'] ?? '';
      setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, '', '', false, true);
      setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
      header('location: /index.php');
    } else {
      // Si le mot de passe est incorrect
      $errorManagement['errorPassword'] = "Mot de passe incorrect";
    }
  }
} else {
  $errorManagement = [
    "errorEmail" => '',
    "errorPassword" => '',
  ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>
  <title>Connexion</title>
</head>

<body>
  <?php require_once __DIR__ . "/includes/nav.php"; ?>
  <div class="container__form">
    <form action="" method="post" class="form" enctype="multipart/form-data">
      <h1 class="form__heading"></h1>
      <div class="form__container">
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
        <div class="form__btn">
          <a href="/index.php" class="article__link">
            <button type="button" class="btn form__btn form__btn-cancel">Annuler</button>
          </a>
          <button type="submit" class="btn form__btn form__btn-save">Connexion</button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>