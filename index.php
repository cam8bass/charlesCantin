<?php
$ModelArticle = require_once __DIR__ . "/models/model-Articles.php";
require_once __DIR__ . "/config/utilities.php";

// Permet de savoir si un utilisateur est connecté
$ModelSecurity = require_once __DIR__ . "/models/model-security.php";
$ModelLogin = require_once __DIR__ . "/models/model-login.php";
$userConnect = $ModelSecurity->securityConnect($ModelLogin);

$allArticles = $ModelArticle->displayAllArticles();
// Défini le nombre d'article par catégorie
$NumberArticlesPerCategory = defineCategory($allArticles);
// Permet de ranger tous les articles par catégories
$articlesPerCategory = sortArticlesPerCategory($allArticles);



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  //Récupère la catégorie dans l'url
  $_GET = checkGet($_GET);
  $cat = $_GET["cat"] ?? '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>
  <title>Mon blog</title>
</head>

<body>
  <header class="header">
    <?php require_once __DIR__ . "/includes/nav.php"; ?>
    <img src="./src/img/header-background.png" alt="photo du bureau" class="header__background" />
    <h1 class="header__heading">
      <span class="header__heading-main">Charles Cantin</span>
      <span class="header__heading-sub">Photographe</span>
    </h1>
    <div class="header__icons">
      <a href=""><img src="./src/img/icons-insta-lg.png" alt="logo instagram" class="header__icons-insta" /></a>
      <a href=""><img src="./src/img/icons-fb-lg.png" alt="logo facebook" class="header__icons-fb" /></a>
    </div>
    <div class="header__bottom">
      <span class="header__bottom-info">Scroll for more</span>
      <img src="./src/img/icon-arraow-down.png" alt="icone d'une flèche" class="header__bottom-icon" />
    </div>
  </header>
  <main class="container__index">
    <section class="section__article">
      <div class="filter">
        <ul class="filter__list">
          <li class="filter__items">
            <a href="./" class="filter__link ">Tous les articles <span class="filter__link-number">(<?= count($allArticles) ?>)</span></a>
          </li>
          <?php foreach ($NumberArticlesPerCategory as $keys => $values) : ?>
            <li class="filter__items">
              <a href="./?cat=<?= $keys ?>" class="filter__link    "><?= $keys ?><span class="filter__link-number">(<?= $values ?>)</span></a>
            </li>
          <?php endforeach ?>
        </ul>
      </div>
      <div class="overview">
        <ul class="overview__list">
          <?php if (!$cat) : ?>
            <?php foreach ($allArticles as $article) :  ?>
              <li class="overview__items">
                <a href="./view-article.php?id=<?= $article['id'] ?>" class="overview__link">
                  <div class="overview__body">
                    <img src="./dataBase/uploads/<?= $article['img'] ?>" alt="" class="overview__img" />
                  </div>
                  <h2 class="overview__title"><?= $article['title'] ?></h2>
                </a>
              </li>
            <?php endforeach ?>
          <?php else : ?>
            <?php foreach ($articlesPerCategory[$cat] as $article) : ?>
              <li class="overview__items">
                <a href="./view-article.php?id=<?= $article['id'] ?>" class="overview__link">
                  <div class="overview__body">
                    <img src="./dataBase/uploads/<?= $article['img'] ?>" alt="<?= $article['title'] ?>" class="overview__img" />
                  </div>
                  <h2 class="overview__title"><?= $article['title'] ?></h2>
                </a>
              </li>
            <?php endforeach ?>
          <?php endif ?>
        </ul>
      </div>
    </section>
  </main>
</body>

</html>