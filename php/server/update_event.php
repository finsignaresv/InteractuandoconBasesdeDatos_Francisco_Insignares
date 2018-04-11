<?php
  //Se llama al archivo conector.php encargado de realizar la conexion con la base de datos
  require('conector.php');

  //Se retoma la session inciada
  session_start();

  //Se inicializa en array datos donde se guardara el evento a actualizar
  $datos = [];
  //Se guarda en el array los datos del evento a actualizar traidos con el post
  $datos['id'] = $_POST['id'];
  $datos['start_date'] = $_POST['start_date'];
  $datos['start_hour'] = $_POST['start_hour'];
  $datos['end_date'] = $_POST['end_date'];
  $datos['end_hour'] = $_POST['end_hour'];

  //Informacion para realizar la conexion con la base de datos
  $username = "newuser";
  $password = "12345";
  $database = "agenda";
  $server = "localhost";

  //Se verifica que la session aun este vigente
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
            //Se realiza la conexion con la base de datos
            $con = new ConectorBD($server, $username, $password);
            $response['conexion'] = $con->initConexion($database);
            if ($response['conexion']=='OK')
              {
                //Se pregunta si se va a actualizar la fecha de incio o la fecha de fin del evento
                if( !DateTime::createFromFormat('Y-m-d', $datos['end_date']))
                  {
                    $sql = "UPDATE eventos SET fecha_inicio = '{$datos['start_date']}' WHERE cod = {$datos['id']}";
                    $result=$con->ejecutarQuery($sql);
                    $response['msg'] = 'OK';
                  }
                  else
                    {
                      $sql = "UPDATE eventos SET fecha_inicio = '{$datos['start_date']}', fecha_fin = '{$datos['end_date']}' WHERE cod = {$datos['id']}";
                      $result=$con->ejecutarQuery($sql);
                      $response['msg'] = 'OK';
                    }

                }
                else
                {
                  $response = array('msg' => 'Error');
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
