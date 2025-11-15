<?php

//clase para manejar la conexion con la base de datos
class Conexion
{
    //maneja la conexion con la base de datos
    private static $pdo;

    //Conecta con la base de datos
    static function conectar()
    {
        try
        {
            //crea una nueva instancia PDO en localhost con la BDD asignada
            Conexion::$pdo = new PDO("mysql:host=localhost; dbname=sistema_psicologia", "root", "");
            //asigna el mensaje de error a mostrar en caso de problemas al conectar
            Conexion::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //establece la codificación de caracteres a UTF-8, para aceptar caracteres especiales
            Conexion::$pdo->exec("SET CHARACTER SET UTF8");
        }
        catch (PDOException $e)
        { $e->getMessage(); }
    }

    static function getConexion()
    { return Conexion::$pdo; }
}

?>