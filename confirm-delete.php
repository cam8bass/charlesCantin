<?php
$ModelArticle = require_once __DIR__ . "/models/model-Articles.php";
require_once __DIR__ . "/config/utilities.php";
$_GET = checkGet($_GET);
$id = $_GET['id'] ?? '';
// Permet de savoir si un utilisateur est connecté
$ModelSecurity = require_once __DIR__ . "/models/model-security.php";
$ModelLogin = require_once __DIR__ . "/models/model-login.php";
$userConnect = $ModelSecurity->securityConnect($ModelLogin);
// Si aucun utilisateur est connect redirection auto vers la page home
if (!$userConnect) header("location: /");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Permet de supprimer un article
  $ModelArticle->deleteArticle($id);
  header("location: /");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>
  <title>Supprimer Article</title>
</head>

<body>
  <?php require_once __DIR__ . "/includes/nav.php"; ?>
  <main class="container__delete">
    <form action="" method="POST" class="delete">
      <h1 class="delete__title">Supprimer l'article</h1>
      <p class="delete__text">Êtes-vous sûr de vouloir supprimer l'article ?</p>
      <div class="delete__btn">
        <a href="/" class="article__link">
          <button class=" btn form__btn form__btn-cancel">Annuler</button>
        </a>
        <button class=" btn form__btn form__btn-save">Confirmer</button>
      </div>
    </form>
  </main>
</body>

</html>