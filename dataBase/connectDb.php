
<?php
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

return $dbh;