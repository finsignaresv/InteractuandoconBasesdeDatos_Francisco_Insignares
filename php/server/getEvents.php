<?php
  //Se llama al archivo conector.php encargado de realizar la conexion con la base de datos
  require('conector.php');

  //Se inicializa el array que guardara los ventos que se llamaran de la base de datos
  $campos = array();

  //Se retoma la session
  session_start();

  //Se guarda en una variable el ususario que incio la session
  $user = $_SESSION['username'];

  //Se verifica que la session siga vigente
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
            //Informacion para realizar la conexion con la base de datos
            $username = "newuser";
            $password = "12345";
            $database = "agenda";
            $server = "localhost";
            $con = new ConectorBD($server, $username, $password);
            $response['conexion'] = $con->initConexion($database);
            // checkinting connection
            if ($response['conexion']=='OK')
              {
                //Se busca en la base de datos el id del usuario que incio la session
                $sql="SELECT id,username FROM usuarios WHERE username= '$user'";
                $result=$con->ejecutarQuery($sql);
                if($result->num_rows>0)
                  {
                    //Guardamos en una variable de php el id del usuario que inicio la session
                    $row = $result->fetch_assoc();
                    $id_usuario = $row["id"];

                    //Se busca en la base de datos todos los eventos asociados el id del usuario que inicio la session
                    $sql="SELECT cod,fk_usuario,titulo,fecha_inicio,hora_inicio,fecha_fin,hora_fin,dia_completo FROM eventos WHERE fk_usuario= '$id_usuario'";
                    $result=$con->ejecutarQuery($sql);
                    if($result->num_rows>0)
                      {
                        while ($row = $result->fetch_assoc())
                          {
                            $start = $row['fecha_inicio'];
                            $hora_i = $row['hora_inicio'];
                            $end = $row['fecha_fin'];
                            $hora_f = $row['hora_fin'];

                            if(!is_null($hora_i))
                              {
                                $start = $start . 'T' . $hora_i;
				                      }

				                    if(!is_null($hora_f))
                              {
                                $end = $end . 'T' . $hora_f;
				                      }
                            //Se coloca en un arreglo los eventos del usuario que inicio la session
                            array_push($campos, ['id'=>$row['cod'],'title'=>$row['titulo'], 'start'=>$start, 'end'=>$end]);
                          }
                      }
                  }
                  else
                    {
                      echo"record 0";
                    }

                $response['msg'] = "OK";
                $response['eventos'] = $campos;
              }
              else
                {
                  die("connection failed:".$con->connect_error);
                }

            echo json_encode($response);
            $con->cerrarConexion();
          }
    }



?>
