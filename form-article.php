<?php
$ModelArticle = require_once __DIR__ . "/models/model-Articles.php";
require_once __DIR__ . "/config/utilities.php";
$ModelSecurity = require_once __DIR__ . "/models/model-security.php";
$ModelLogin = require_once __DIR__ . "/models/model-login.php";
// Permet de savoir si un utilisateur est connecté
$userConnect = $ModelSecurity->securityConnect($ModelLogin);
if (!$userConnect) header("location: /");
$_GET = checkGet($_GET);
$id = $_GET['id'] ?? '';


// === Ecrire un nouvelle article ===

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$id) {
  //recupere les inputs pour les mettre dans un array article
  $article = checkPostArticle($_POST);

  $errorManagement = [
    "errorTitle" => checkTitle($article['title'] ?? ''),
    "errorContent" => checkContent($article['content'] ?? ''),
    "errorImg" => checkImgFile($_FILES) ?? ''
  ];


  // Vérifie si il n'y a pas d'erreur
  if (empty(array_filter($errorManagement, fn ($el) => $el != ''))) {

    // Permet de sauvegarder l'image dans le dossier uploads
    $imgName = saveImgFile($_FILES);
    // Permet de sauvegarder l'article
    $newArticle = [
      "title" => $article['title'],
      "content" => $article['content'],
      "category" => $article['category'],
      "img" => $imgName,
      'id' => time()
    ];
    $ModelArticle->createNewArticle($newArticle);
    header("location: /");
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $errorManagement = [
    "errorTitle" => "",
    "errorContent" => "",
    "errorImg" => ""
  ];
}


// === Modification d'un article ===

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
  $article = checkPostArticle($_POST);
  $errorManagement = [
    "errorTitle" => checkTitle($article['title'] ?? ''),
    "errorContent" => checkContent($article['content'] ?? ''),
    "errorImg" => checkImgFile($_FILES) ?? ''
  ];

  $OldArticle = $ModelArticle->displayArticleContent($id);


  $newImg = saveImgFile($_FILES);

  deleteImg($OldArticle['img']);


  $OldArticle['title'] = $article['title'];
  $OldArticle['content'] = $article['content'];
  $OldArticle['category'] = $article['category'];

  $ModelArticle->modifyArticle($OldArticle, $newImg);
  header("location: ./");
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $id) {
  // Permet d'afficher le contenu de l'article à modifier
  $article = $ModelArticle->displayArticleContent($id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once "./includes/head.php" ?>

  <title><?= $id ? "Modifier article" : "Nouvelle aticle" ?></title>
</head>

<body>
  <?php require_once __DIR__ . "/includes/nav.php"; ?>
  <div class="container__form">
    <form action="" method="post" class="form" enctype="multipart/form-data">
      <h1 class="form__heading"></h1>
      <div class="form__container">
        <div class="form__block">
          <label for="title" class="form__label form__label-title">Titre de l'article</label>
          <input type="text" class="form__input form__input-title" name="title" id="title" value="<?= ($id ? $article['title'] : '') || $errorManagement['errorTitle'] ? $article['title'] : "" ?>" />
          <p class="form__warning"><?= $errorManagement['errorTitle'] ? $errorManagement['errorTitle'] : ""  ?></p>
        </div>
        <div class="form__block">
          <label for="img" class="form__label form__label-img">Téléchargement de l'image</label>
          <input type="file" class="form__input form__input-img" name="img" />
          <p class="form__warning"><?= $errorManagement['errorImg'] ? $errorManagement['errorImg'] : '' ?></p>
        </div>
        <div class="form__block">
          <label for="categories" class="form__label ">Sélectionner une categorie</label>
          <select name="category" id="category">
            <option value="news">News</option>
            <option value="mariage">Mariage</option>
            <option value="grossesse">Grossesse</option>
            <option value="bébé">Bébé</option>
            <option value="famille">Famille</option>
            <option value="baptème">Baptème</option>
            <option value="couple">Couple</option>
          </select>
        </div>
        <div class="form__block">
          <label for="content" class="form__label form__label-content">Le contenu</label>
          <textarea name="content" id="id" class="form__content"><?= ($id ? $article['content'] : "") || $errorManagement['errorContent'] ? $article['content'] : "" ?></textarea>
          <p class="form__warning"><?= $errorManagement['errorContent'] ? $errorManagement['errorContent'] : '' ?></p>
        </div>
        <div class="form__btn">
          <a href="/index.php" class="article__link">
            <button type="button" class="btn form__btn form__btn-cancel">Annuler</button>
          </a>
          <button type="submit" class="btn form__btn form__btn-save"><?= $id ? 'Modifier' : 'Sauvegarder' ?></button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>