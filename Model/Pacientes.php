<?php

namespace Yahir\Compo;
use Yahir\Compo\Conexion;
use PDO;

// Incluimos el archivo de configuración para la conexión a la base de datos.

// Definimos la clase 'pacienteModulo'. Esta clase va a heredar de 'Conexion',
// lo que significa que tendrá acceso a los métodos y propiedades de la clase Conexion,
// especialmente el método para obtener la conexión a la base de datos.
// 'Modulo' en el nombre sugiere que esta clase maneja la lógica de datos para los pacientes.
class Pacientes extends Conexion{

    // Aquí declaramos las propiedades privadas de la clase.
    // Estas propiedades corresponden a las columnas de la tabla 'paciente' en la base de datos.
    // Son privadas para encapsular los datos y solo permitir su acceso y modificación
    // a través de los métodos getter y setter.
    private $pdo;
    private $id_paciente;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $fecha_nacimiento;
    private $genero;
    private $email;
    private $id_ubicacion; // Nueva propiedad para almacenar el ID de la ubicación
    private $foto;



    public function setIdUbicacion($id_ubicacion) {
        $this->id_ubicacion = $id_ubicacion;
    }

    public function getIdUbicacion() {
        return $this->id_ubicacion;
    }
    // Este es el constructor de la clase. Se ejecuta automáticamente cuando creas un nuevo objeto 'pacienteModulo'.
    public function __construct(){
        // La línea `parent::__construct();` se eliminó porque la clase `Conexion` original
        // no tiene un constructor explícito que necesite ser llamado.
        // En su lugar, nos aseguramos de que la conexión a la base de datos esté establecida.

        // Verificamos si ya existe una conexión PDO a través de la clase padre 'Conexion'.
        // Conexion::getConexion() debería devolver la instancia PDO si ya está creada, o null si no.
        if (Conexion::conectar() == null) {
            // Si no hay conexión, llamamos al método estático 'conectar()' de la clase 'Conexion'
            // para que establezca la conexión con la base de datos.
            $this->pdo = Conexion::getConexion(); 
        }
        // Una vez que estamos seguros de que la conexión existe (o se acaba de crear),
        // la asignamos a la propiedad '$this->pdo' de esta instancia de 'pacienteModulo'.
        // Esto permite que los métodos de esta clase (como listarpaciente, crearpaciente, etc.)
        // puedan usar esta conexión para interactuar con la base de datos.

        // Esta comprobación es útil para depuración, para asegurarse de que la conexión se obtuvo correctamente.
        // Si $this->pdo es falso (o null), significa que algo falló al obtener la conexión.
        // Se podría lanzar una excepción aquí para manejar el error de forma más robusta.
        // Ejemplo: if (!$this->pdo) { throw new \RuntimeException("Error: No se pudo obtener la conexión PDO en pacienteModulo."); }
    }

    // --- MÉTODOS GETTER ---
    // Los métodos "get" (o getters) se utilizan para obtener el valor de las propiedades privadas de la clase.
    // Proporcionan una forma controlada de acceder a los datos del objeto.

    public function getid_paciente(){
        return $this->id_paciente;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getCedula(){
        return $this->cedula;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function getFechaNacimiento(){
        return $this->fecha_nacimiento;
    }
    public function getGenero(){
        return $this->genero;
    }
    
    public function getEmail(){
        return $this->email;
    }
    public function getFoto(){
        return $this->foto;
    }
   

    // --- MÉTODOS SETTER ---
    // Los métodos "set" (o setters) se utilizan para asignar un valor a las propiedades privadas de la clase.
    // Permiten validar o transformar los datos antes de asignarlos.

    public function setId($id_paciente){
        $this->id_paciente = $id_paciente;
    }
    public function setnombre($nombre){
        $this->nombre = $nombre;
    }
    public function setapellido($apellido){
        $this->apellido = $apellido;
    }
    public function setcedula($cedula){
        $this->cedula = $cedula;
    }
    public function settelefono($telefono){
        $this->telefono = $telefono;
    }
    public function setfecha_nacimiento($fecha_nacimiento){
        $this->fecha_nacimiento = $fecha_nacimiento;
    }
    public function setgenero($genero){
        $this->genero = $genero;
    }
    public function setdUbicacion($id_ubicacion){
        $this->id_ubicacion = $id_ubicacion;
    }
    public function setemail($email){
        $this->email = $email;
    }
    public function setFoto($foto){
        $this->foto = $foto;
    }

    // --- MÉTODOS DE INTERACCIÓN CON LA BASE DE DATOS ---

    // Este método obtiene todos los registros de la tabla 'paciente'.
   public function listarpaciente(){
        // Preparamos una consulta SQL para seleccionar todos los campos (*) de la tabla 'paciente'.
        // $this->pdo->query() es adecuado para consultas que no necesitan parámetros.
        $stmt = $this->pdo->query("SELECT p.*, u.ciudad, u.pais
        FROM paciente p
        LEFT JOIN ubicacion u ON p.id_ubicacion = u.id_ubicacion
        ");
        // fetchAll(PDO::FETCH_ASSOC) recupera todas las filas del resultado de la consulta
        // como un array asociativo, donde las claves son los nombres de las columnas.
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pacientes as &$paciente) {
            if (!empty($paciente['foto'])) {
                $paciente['foto'] = 'img/usuarios/' . $paciente['foto'];
            } else {
                $paciente['foto'] = '';
            }
        }
        unset($paciente);

        return $pacientes;
    }

    // Este método obtiene un paciente específico por su ID.
    public function obtenerpaciente($id_paciente){
        // Preparamos una consulta SQL con un marcador de posición (?) para el id_paciente.
        // Usar prepare() y execute() con marcadores de posición ayuda a prevenir inyecciones SQL.
        $stmt = $this->pdo->prepare("SELECT * FROM paciente WHERE id_paciente = ?");
        // Ejecutamos la consulta, pasando el $id_paciente en un array.
        // El valor en el array reemplazará el marcador de posición (?) en la consulta.
        $stmt->execute([$id_paciente]);
        // fetch(PDO::FETCH_ASSOC) recupera una sola fila del resultado como un array asociativo.
        // Si no se encuentra ningún paciente con ese ID, devolverá false.
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Este método inserta un nuevo paciente en la base de datos.
    // Utiliza los valores que se han establecido previamente en las propiedades del objeto ($this->nombre, $this->apellido, etc.)
    // mediante los métodos setter.
    public function crearpaciente(){
        // Preparamos la consulta SQL para insertar un nuevo registro.
        // Usamos marcadores de posición (?) para cada valor que se va a insertar.
        $stmt=$this->pdo->prepare("INSERT INTO paciente (nombre,apellido,cedula,telefono,fecha_nacimiento,genero,id_ubicacion,email,foto) VALUES (?,?,?,?,?,?,?,?,?)");
        // Ejecutamos la consulta, pasando un array con los valores de las propiedades del objeto.
        // El orden de los valores en el array debe coincidir con el orden de los marcadores de posición en la consulta SQL.
        $stmt->execute([
            $this->nombre,
            $this->apellido,
            $this->cedula,
            $this->telefono,
            $this->fecha_nacimiento,
            $this->genero,
            $this->id_ubicacion,
            $this->email,
            $this->foto
        ]);
        // No se devuelve nada explícitamente, pero se podría devolver el ID del último registro insertado
        // usando $this->pdo->lastInsertId() si fuera necesario.
    }

    // Este método actualiza los datos de un paciente existente en la base de datos.
    // Al igual que en crearpaciente(), utiliza los valores de las propiedades del objeto.
    // El $this->id_paciente debe estar establecido para saber qué paciente actualizar.
    public function actualizarpaciente(){
        // Preparamos la consulta SQL para actualizar un registro.
        // Se actualizan todos los campos listados. El último marcador de posición es para el id_paciente en la cláusula WHERE.
        $stmt = $this->pdo->prepare("UPDATE paciente SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, fecha_nacimiento = ?, genero = ?, email = ?, foto = ? WHERE id_paciente = ?");
        // Ejecutamos la consulta, pasando los valores de las propiedades del objeto.
        // $this->id_paciente se usa para la cláusula WHERE, asegurando que solo se actualice el paciente correcto.
        // Si la contraseña no se va a cambiar, el controlador debería asegurarse de que $this->password
        // contenga la contraseña original (hasheada) o implementar lógica para no actualizarla si es null.
        $stmt->execute([
            $this->nombre,
            $this->apellido, 
            $this->cedula, 
            $this->telefono, 
            $this->fecha_nacimiento, 
            $this->genero, 
            $this->email, 
            $this->foto, 
            $this->id_paciente // ID del paciente a actualizar
        ]);
        // No se devuelve nada explícitamente. Se podría devolver el número de filas afectadas si fuera necesario.
    }

    // Este método elimina un paciente de la base de datos por su ID.
    public function eliminarpaciente($id_paciente){
        // Preparamos la consulta SQL para eliminar un registro.
        $stmt = $this->pdo->prepare("DELETE FROM paciente WHERE id_paciente=?");
        // Ejecutamos la consulta, pasando el ID del paciente a eliminar.
        // El método execute() devuelve true si la consulta se ejecutó con éxito, o false en caso contrario.
        // Por lo tanto, este método devolverá true o false indicando si la eliminación fue exitosa.
        return $stmt->execute([$id_paciente]);
    }

    // Crear ubicación y devolver el id
    public function crearUbicacion($ciudad, $pais) {
        $stmt = $this->pdo->prepare("INSERT INTO ubicacion (ciudad, pais) VALUES (?, ?)");
        $stmt->execute([$ciudad, $pais]);
        return $this->pdo->lastInsertId();
    }

    // Actualizar ubicación
    public function actualizarUbicacion($id_ubicacion, $ciudad, $pais) {
        $stmt = $this->pdo->prepare("UPDATE ubicacion SET ciudad=?, pais=? WHERE id_ubicacion=?");
        return $stmt->execute([$ciudad, $pais, $id_ubicacion]);
    }

    // Obtener id_ubicacion por id_paciente
    public function obtenerIdUbicacionPorPaciente($id_paciente) {
        $stmt = $this->pdo->prepare("SELECT id_ubicacion FROM paciente WHERE id_paciente=?");
        $stmt->execute([$id_paciente]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id_ubicacion'] : null;
    }
}
?>
