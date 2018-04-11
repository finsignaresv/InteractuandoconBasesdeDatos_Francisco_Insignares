<?php
  //Clase conextor con todas la funciones para operar la base de datos
  class ConectorBD
  {
    private $host;
    private $user;
    private $password;
    private $conexion;

    function __construct($host, $user, $password)
      {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
      }
    //Funcion para conectarse a la base de datos
    function initConexion($nombre_db)
      {
        $this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db);
        if ($this->conexion->connect_error)
          {
            return "Error: " . $this->conexion->connect_error;
          }
          else
            {
              return "OK";
            }
      }

    //Funcion para crear una nueva tabla en la base de datos
    function newTable($nombre_tbl, $campos)
      {
        $sql = 'CREATE TABLE '.$nombre_tbl.' (';
        $length_array = count($campos);
        $i = 1;
        foreach ($campos as $key => $value)
          {
            $sql .= $key.' '.$value;
            if ($i!= $length_array)
              {
                $sql .= ', ';
              }
              else
                {
                  $sql .= ');';
                }
            $i++;
          }
        return $this->ejecutarQuery($sql);
      }

    //Funcion para ejecutar un script de SQL
    function ejecutarQuery($query)
      {
        return $this->conexion->query($query);
      }

    //Funcion para cerrar la conexion a la base de datos
    function cerrarConexion()
      {
        $this->conexion->close();
      }

    //Funcion para anadir una nueva restriccion a la base de datos
    function nuevaRestriccion($tabla, $restriccion)
      {
        $sql = 'ALTER TABLE '.$tabla.' '.$restriccion;
        return $this->ejecutarQuery($sql);
      }

    //Funcion para crear una nueva relacion en la base de datos
    function nuevaRelacion($from_tbl, $to_tbl, $from_field, $to_field)
      {
        $sql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_field.') REFERENCES '.$to_tbl.'('.$to_field.');';
        return $this->ejecutarQuery($sql);
      }

    //Funcion para insertar un nuevo registro en una tabla de la base de datos
    function insertData($tabla, $data)
      {
        $sql = 'INSERT INTO '.$tabla.' (';
        $i = 1;
        foreach ($data as $key => $value)
          {
            $sql .= $key;
            if ($i<count($data))
              {
                $sql .= ', ';
              }
              else $sql .= ')';
              $i++;
          }
        $sql .= ' VALUES (';
        $i = 1;
        foreach ($data as $key => $value)
          {
            $sql .= $value;
            if ($i<count($data))
              {
                $sql .= ', ';
              }
              else $sql .= ');';
              $i++;
          }
        return $this->ejecutarQuery($sql);

      }

    //Funcion para tomar la conexion
    function getConexion()
      {
        return $this->conexion;
      }

    //Funcion para actualizar un registro de una tabla de la base de datos
    function actualizarRegistro($tabla, $data, $condicion)
      {
        $sql = 'UPDATE '.$tabla.' SET ';
        $i=1;
        foreach ($data as $key => $value)
          {
            $sql .= $key.'='.$value;
            if ($i<sizeof($data))
              {
                $sql .= ', ';
              }
              else $sql .= ' WHERE '.$condicion.';';
            $i++;
          }
        return $this->ejecutarQuery($sql);
      }

    //Funcion para eliminar un registro de la base de datos
    function eliminarRegistro($tabla, $condicion)
      {
        $sql = "DELETE FROM ".$tabla." WHERE ".$condicion.";";
        return $this->ejecutarQuery($sql);
      }

    //Funcion para consultar un registro de una tabla de la base de datos a partir de una condicion
    function consultar($tablas, $campos, $condicion = "")
      {
        $sql = "SELECT ";
        $new = array_keys($campos);
        $ultima_key = end($new);
        foreach ($campos as $key => $value)
          {
            $sql .= $value;
            if ($key!=$ultima_key)
              {
                $sql.=", ";
              }
              else $sql .=" FROM ";
          }

        $new = array_keys($tablas);
        $ultima_key = end($new);
        foreach ($tablas as $key => $value)
          {
            $sql .= $value;
            if ($key!=$ultima_key)
              {
                $sql.=", ";
              }
              else $sql .= " ";
          }

        if ($condicion == "")
          {
            $sql .= ";";
          }
          else
            {
              $sql .= $condicion.";";
            }
          return $this->ejecutarQuery($sql);
      }

  }

 ?>
