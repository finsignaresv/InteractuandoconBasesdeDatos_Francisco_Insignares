<?php
  //Se incluye al archivo php encargado de realizar la conexion
  require('conector.php');

  //Se retoma la session iniciada
  session_start();

  //Se guarda en una variable php el id del evento a borrar
  $id=$_POST['id'];

  //Informacion para realizar la conexion con la base de datos
  $username = "newuser";
  $password = "12345";
  $database = "agenda";
  $server = "localhost";

  //Se verifica que la session siga estando activa
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
    {
      if(time() > $_SESSION['expire'])
        {
          session_destroy();
          echo "Esta pagina es solo para usuarios registrados.<br>";
          die("Su session ha concluido...");
        }
        else
          {
            //Conexion con la base de datos
            $con = new ConectorBD($server, $username, $password);
            $response['conexion'] = $con->initConexion($database);
            if ($response['conexion']=='OK')
              {
                //Script SQL para borrar el evento
                $sql="DELETE FROM eventos WHERE cod=$id";
                $result=$con->ejecutarQuery($sql);
                $response['msg'] = 'OK';
              }
              else
                {
                  $response['error'] = "Error";
		             }
          }
    }
    else
      {
        header("Location: ../client/index.html");
        exit;
      }
  echo json_encode($response);
  $con->cerrarConexion();
?>
