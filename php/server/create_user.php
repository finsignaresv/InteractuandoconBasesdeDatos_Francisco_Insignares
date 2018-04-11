<?php
  //Llamo al archivo para realizar la conexion con la base de datos
  require('conector.php');

  //Se guardan en variables los passowrds de los usuarios a crear debidamente encriptados
  $password_usuario1 = password_hash('12345', PASSWORD_DEFAULT);
  $password_usuario2 = password_hash('12345', PASSWORD_DEFAULT);
  $password_usuario3 = password_hash('12345', PASSWORD_DEFAULT);

  //Se realiza la conexion a la base de datos
  $con = new ConectorBD('localhost', 'newuser', '12345');
  $response['conexion'] = $con->initConexion('agenda');

  if ($response['conexion']=='OK')
    {
      $resultado_consulta = $con->consultar(["usuarios"], ["username"], "WHERE username = 'usuario1@mail.com'");
      if ($resultado_consulta->num_rows != 0)
        {
          $response['msg']= "Los 3 usuarios ya han sido creados";
        }
        else
          {
            //Script para insertar a los tres nuevos usuarios en la base de datos
            $sql = "INSERT INTO usuarios (username, nombre_completo, password, fecha_nacimiento) VALUES ('usuario1@mail.com', 'usuario1', '$password_usuario1', '1980-01-01'), ('usuario2@mail.com', 'usuario2', '$password_usuario2', '1980-01-01'), ('usuario3@mail.com', 'usuario3', '$password_usuario3', '1980-01-01')";
            $con->ejecutarQuery($sql);
          }
    }
    else
      {
        $response['msg']= "No se pudo conectar a la base de datos";
      }

  echo json_encode($response);
  $con->cerrarConexion();

 ?>
