<?php
$ModelLogout = require_once __DIR__ . "/models/model-logout.php";
$idSession = $_COOKIE['session'] ?? '';
$signature = $_COOKIE['signature'] ?? '';

$ModelSecurity = require_once __DIR__ . "/models/model-security.php";
$ModelLogin = require_once __DIR__ . "/models/model-login.php";
$userConnect = $ModelSecurity->securityConnect($ModelLogin);

if (!$userConnect) header("location: /");

if ($idSession && $signature) {
  $hash = hash_hmac('sha256', $idSession, "82b9cca8c89955d90458c1420d9399b16bc83c8e7c58f709b4f3022a430a0d4fd421993ef5ecc2553798044f4b5c98f23f9215b9dd84bab0fba9b332e48d7087");
  if (hash_equals($hash, $signature)) {
    $ModelLogout->deconnectSession($idSession);
    setcookie('session', '', time() - 1);
    setcookie('signature', '', time() - 1);
    header("location: /index.php");
  }
}
