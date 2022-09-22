<?php

// === For Articles === 


function checkPostArticle(array $request): array
{
  $request = filter_input_array(INPUT_POST, [
    "title" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    "category" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    "content" => FILTER_SANITIZE_FULL_SPECIAL_CHARS
  ]);

  return $request;
}

function checkGet(array $request)
{
  $request = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  return $request;
}

function checkTitle(string $inputTitle): string
{
  if (!$inputTitle || mb_strlen($inputTitle) < 5) {
    return "Veuillez rentrer un titre comportant au minimum 5 caractères";
  } else return "";
}

function checkContent(string $inputContent): string
{
  if (!$inputContent || mb_strlen($inputContent) < 50) {
    return "Le contenu de l'article doit comporter au minimum 50 caractères";
  } else return "";
}

function checkImgFile($requestFile)
{
  $valideImgExtension = ["jpeg", "JPEG", "png", "PNG", "jpg", "JPG"];

  if (isset($requestFile['img'])) {

    if ($requestFile['img']['error'] === UPLOAD_ERR_INI_SIZE) {
      return "Le fichier est trop volumineux";
    } elseif ($requestFile['img']['error'] === UPLOAD_ERR_PARTIAL) {
      return "Problème lors du téléchargement du fichier";
    } elseif ($requestFile['img']['error'] === UPLOAD_ERR_NO_FILE) {
      return "Aucun fichier téléchargé";
    } elseif ($requestFile['img']['size'] > 3145728) {
      return "La taille du fichier ne doit pas dépasser 3 mo";
    } elseif (!array_search(pathinfo($requestFile['img']['name'], PATHINFO_EXTENSION), $valideImgExtension)) {
      return "Veuillez télécharger un fichier de types ( jpeg, png, jpg )";
    } else return "";
  }
}

function saveImgFile($requestFile)
{
  $idFileName = uniqid();
  $extension = pathinfo($requestFile['img']['name'], PATHINFO_EXTENSION);
  $imgName = explode(".", $requestFile['img']['name']);
  $newImgName = $imgName[0] . '-' . $idFileName . '.' . $extension;

  move_uploaded_file($requestFile['img']['tmp_name'], "./dataBase/uploads/$newImgName");
  return $newImgName;
}

function defineCategory($allArticles)
{
  // Définit le type de catégorie présente
  $availableCategory = array_map(fn ($el) => $el['category'], $allArticles);

  // Défini le nombre d'article par catégorie
  $NumberArticlesPerCategory = array_reduce($availableCategory, function ($acc, $curr) {
    if (isset($acc[$curr])) {
      $acc[$curr]++;
    } else {
      $acc[$curr] = 1;
    }

    return $acc;
  }, []);

  return $NumberArticlesPerCategory;
}


function sortArticlesPerCategory($allArticles)
{
  $articlesPerCategory = array_reduce($allArticles, function ($acc, $curr) {

    if (isset($acc[$curr['category']])) {
      $acc[$curr['category']] = [...$acc[$curr['category']], $curr];
    } else {
      $acc[$curr['category']] = [$curr];
    }
    return $acc;
  }, []);

  return $articlesPerCategory;
}

function deleteImg($oldImg)
{
  unlink("./dataBase/uploads/" . $oldImg);
}




// === For register ===
