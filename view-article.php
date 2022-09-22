<?php
$ModelArticle = require_once __DIR__ . "/models/model-Articles.php";
require_once __DIR__ . "/config/utilities.php";
$ModelSecurity = require_once __DIR__ . "/models/model-security.php";
$ModelLogin = require_once __DIR__ . "/models/model-login.php";

// Permet de savoir si un utilisateur est connecté
$userConnect = $ModelSecurity->securityConnect($ModelLogin);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $_GET = checkGet($_GET);
  $id = $_GET['id'];
  $article = $ModelArticle->displayArticleContent($id);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>
  <title>Article</title>
</head>

<body>
  <?php require_once __DIR__ . "/includes/nav.php"; ?>
  <main class="container__view">
    <div class="article">
      <h1 class="article__title"> <?= $article['title'] ?> </h1>
      <img src="./dataBase/uploads/<?= $article['img'] ?>" alt="<?= $article['title'] ?>" class="article__img" />
      <p class="article__text">
        <?= $article['content'] ?>
      </p>
      <?php if ($userConnect) : ?>
        <div class="article__btn">
          <a href="/confirm-delete.php?id=<?= $article['id'] ?>" class="article__link">
            <button class="btn article__btn-delete">Supprimer</button>
          </a>
          <a href="/form-article.php?id=<?= $article['id'] ?>" class="article__link">
            <button class="btn article__btn-edit">Modifier</button>
          </a>
        </div>
      <?php endif ?>
      <a href="./" class="article__link-return">&larr;</a>
    </div>
  </main>
</body>

</html>