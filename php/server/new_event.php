<?php
  //Se llama al archivo conector.php encargado de realizar la conexion con la base de datos
  require('conector.php');

  //Se retoma la session iniciada
  session_start();

  //Se guarda en una variable de php el usuario que incio la session
  $user = $_SESSION['username'];

  //Se inicializa el array para guardar el nuevo evento
  $datos = array();

  //Se guardan en variable de php los datos del nuevo eventos traidos del post
  $title = $_POST['title'];
  $start_date = $_POST['start_date'];
  $start_hour = $_POST['start_hour'];
  $end_date = $_POST['end_date'];
  $end_hour = $_POST['end_hour'];
  $allDay = $_POST['allDay'];

  //Informacion para realizar la conexion a la base de datos
  $username = "newuser";
  $password = "12345";
  $database = "agenda";
  $server = "localhost";

  //Se realiza la conexion con la base de datos
  $con = new ConectorBD($server, $username, $password);
  $response['conexion'] = $con->initConexion($database);
  //Se chequea el resultado de la conexion
  if ($response['conexion']=='OK')
    {
      //Script de SQL para traer de la base de datos el id del usuario que inicio la session
      $sql="SELECT id,username FROM usuarios WHERE username= '$user'";
      $result=$con->ejecutarQuery($sql);
      if($result->num_rows>0)
        {
          //Se almacena en una variable de php el id del usuario que inicio la session
          $row = $result->fetch_assoc();
          $id_usuario = $row["id"];

          //Script de SQL para insertar en la base de datos el nuevo evento
          $sql="INSERT INTO eventos (fk_usuario, titulo, fecha_inicio, hora_inicio, fecha_fin, hora_fin, dia_completo)
            VALUES ('$id_usuario', '$title', '$start_date', '$start_hour', '$end_date', '$end_hour', '$allDay')";
          //Se corre el script de php y se envia la respuesta php al js
          $result=$con->ejecutarQuery($sql);
          $response['msg'] = "OK";
          $response['username'] = $id_usuario;
        }
        else
          {
            $response['msg'] = "No Insercion";
          }
    }
    else
      {
        $response['msg']= "No se pudo conectar a la base de datos";
      }

  echo json_encode($response);
  $con->cerrarConexion();

?>
