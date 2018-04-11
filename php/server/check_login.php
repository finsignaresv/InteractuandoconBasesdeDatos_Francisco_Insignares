<?php
  //Se llama al archivo para realizar la conexion a la base de datos
  require('conector.php');

  //Se guardan el username y password del cliente que viene por el post
  $user = $_POST['username'];
  $password = $_POST['password'];

  //Se hace la conexion a la base de datos
  $con = new ConectorBD('localhost', 'newuser', '12345');
  $response['conexion'] = $con->initConexion('agenda');

  if ($response['conexion']=='OK')
  {
    //Se busca el usuario en la base de datos
    $resultado_consulta = $con->consultar(['usuarios'], ['username', 'password'], `WHERE username ="$user"`);
    if ($resultado_consulta->num_rows != 0)
      {
        //Verificacion del password
        $fila = $resultado_consulta->fetch_assoc();
        if (password_verify($password, $fila['password']))
          {
            session_start();
            $response = array("msg" => "OK");
            //Se guardan los datos de la session
            $_SESSION['loggedin'] = true;
			      $_SESSION['username'] = $user;
			      $_SESSION['start'] = time();
			      $_SESSION['expire'] = $_SESSION['start'] + (20 * 60); //sesion de 20 minutos
          }
          else
            {
              $response['msg'] = 'Password incorrecto';
            }
      }
      else
        {
          $response['msg'] = 'Usuario incorrecto';
        }
  }
  else
    {
      $response['msg']= "No se pudo conectar a la base de datos";
    }

  echo json_encode($response);
  $con->cerrarConexion();

 ?>
