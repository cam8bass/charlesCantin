<?php

class ModelSecurity
{

  function securityConnect($ModelLogin)
  {

    $idSession = $_COOKIE['session'] ?? '';
    $signature = $_COOKIE['signature'] ?? '';

    if ($idSession && $signature) {
      $hash = hash_hmac('sha256', $idSession, "82b9cca8c89955d90458c1420d9399b16bc83c8e7c58f709b4f3022a430a0d4fd421993ef5ecc2553798044f4b5c98f23f9215b9dd84bab0fba9b332e48d7087");
      if (hash_equals($hash, $signature)) {
        $userConnect = $ModelLogin->retrieveUserConnect($idSession);
      }
    } 
    return $userConnect ?? false;
  }
}

return new ModelSecurity();
