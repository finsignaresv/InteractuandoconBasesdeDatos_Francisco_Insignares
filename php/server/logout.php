<?php
  //Se retoma la session iniciada
  session_start();
  //Se destruye la session
  session_destroy();
  //Se envia al usuario a la pagina de login
  header("Location: ../client/index.html");
  exit;
 ?>
