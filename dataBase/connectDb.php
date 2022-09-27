
<?php
// $dns = "mysql:host=localhost;dbname=charlesCantin";
// $user = "root";
// $pwd = "klouns8666";
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

return $dbh;