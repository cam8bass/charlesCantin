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

class ModelArticles
{
  private PDOStatement $statementCreateNewArticle;
  private PDOStatement $statementDisplayAllArticles;
  private PDOStatement $statementDisplayArticlesContent;
  private PDOStatement $statementModifyArticle;
  private PDOStatement $statementDeleteArticle;

  function __construct(private PDO $dbh)
  {
    $this->statementCreateNewArticle = $dbh->prepare("INSERT INTO articles (
            id,
            title,
            content,
            img,
            category
    )VALUES(
            :id,
            :title,
            :content,
            :img,
            :category
    )");

    $this->statementDisplayAllArticles = $dbh->prepare("SELECT * FROM articles");

    $this->statementDisplayArticlesContent = $dbh->prepare("SELECT * FROM articles WHERE id=:id");

    $this->statementModifyArticle = $dbh->prepare("UPDATE articles
    SET
           
            title=:title,
            content=:content,
            img=:img,
            category=:category

    WHERE id=:id
    ");

    $this->statementDeleteArticle = $dbh->prepare("DELETE FROM articles WHERE id=:id");
  }

  public function createNewArticle($article)
  {
    $this->statementCreateNewArticle->bindValue(":id", $article['id']);
    $this->statementCreateNewArticle->bindValue(":title", $article['title']);
    $this->statementCreateNewArticle->bindValue(":content", $article['content']);
    $this->statementCreateNewArticle->bindValue(":img", $article['img']);
    $this->statementCreateNewArticle->bindValue(":category", $article['category']);
    $this->statementCreateNewArticle->execute();
    return $article['id'];
  }

  public function displayAllArticles()
  {
    $this->statementDisplayAllArticles->execute();
    return $this->statementDisplayAllArticles->fetchAll();
  }

  public function displayArticleContent($id)
  {
    $this->statementDisplayArticlesContent->bindValue(":id", $id);
    $this->statementDisplayArticlesContent->execute();
    return $this->statementDisplayArticlesContent->fetch();
  }

  public function modifyArticle($article, $img)
  {
    $this->statementModifyArticle->bindValue(":title", $article['title']);
    $this->statementModifyArticle->bindValue(":content", $article['content']);
    $this->statementModifyArticle->bindValue(":img", $img);
    $this->statementModifyArticle->bindValue(":category", $article['category']);
    $this->statementModifyArticle->bindValue(":id", $article['id']);
    $this->statementModifyArticle->execute();
    return $article;
  }

  public function deleteArticle($id)
  {
    $this->statementDeleteArticle->bindValue(":id", $id);
    $this->statementDeleteArticle->execute();
    return $id;
  }
}

return new ModelArticles($dbh);
