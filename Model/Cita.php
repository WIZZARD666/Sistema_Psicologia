<?php

namespace Yahir\Compo;
use Yahir\Compo\Conexion;
use PDO;
use Exception;


class Cita extends Conexion{

    private $id_cita;
    private $id_paciente;    
    private $title;
    private $descripcion;
    private $color;
    private $textColor;
    private $start;
    private $end;
    protected $pdo;

    public function __construct(){
        Conexion::conectar();
        $this->pdo = Conexion::getConexion();
    }
    public function getid_cita(){
        return $this->id_cita;
    }
    public function getid_paciente(){
    return $this->id_paciente;
}
    public function gettitle(){
        return $this->title;
    }
    public function getdescripcion(){
        return $this->descripcion;
    }
    public function getcolor(){
        return $this->color;
    }
    public function gettextColor(){
        return $this->textColor;
    }
    public function getstart(){
        return $this->start;
    }
    public function getend(){
        return $this->end;
    }

    public function setid_cita($id_cita){
        $this->id_cita = $id_cita;
    }
    public function setid_paciente($id_paciente){
    $this->id_paciente = $id_paciente;
}
    public function settitle($title){
        $this->title = $title;
    }
    public function setdescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function setcolor($color){
        $this->color = $color;
    }
    public function settextColor($textColor){
        $this->textColor = $textColor;
    }
    public function setstart($start){
        $this->start = $start;
    }
    public function setend($end){
        $this->end = $end;
    }

    public function listarcita(){
        $stmt = $this->pdo->query("SELECT cita.*, paciente.nombre, paciente.apellido, paciente.cedula 
                                   FROM cita 
                                   JOIN paciente ON cita.id_paciente = paciente.id_paciente");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenercita($id_cita){
    $stmt = $this->pdo->prepare("
        SELECT cita.*, paciente.nombre, paciente.apellido 
        FROM cita 
        JOIN paciente ON cita.id_paciente = paciente.id_paciente 
        WHERE id_cita = ?
    ");
    $stmt->execute([$id_cita]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    public function crearcita(){
        // Antes de insertar, validar que no exista una cita con el mismo start
        try {
            $startNorm = str_replace('T', ' ', $this->start);
            if ($this->existe($startNorm, $this->end)) {
                return ['resultado' => 'existe', 'mensaje' => 'No se pudo enviar los datos porque ya existe una cita a esa hora.'];
            }

            $stmt = $this->pdo->prepare("INSERT INTO cita (id_paciente, title, descripcion, color, textColor, start, end) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([
                $this->id_paciente,
                $this->title,
                $this->descripcion,
                $this->color,
                $this->textColor,
                $startNorm,
                $this->end
            ]);

            return ['resultado' => 'ok', 'mensaje' => 'Cita creada correctamente.'];
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => 'Error al crear la cita: ' . $e->getMessage()];
        }
    }
    public function actualizarcita(){
        // Validar usando la misma función existe() pero excluyendo el id actual
        try {
            $startNorm = str_replace('T', ' ', $this->start);

            if ($this->existe($startNorm, $this->end, $this->id_cita)) {
                return ['resultado' => 'existe', 'mensaje' => 'No se pudo enviar los datos porque ya existe una cita a esa hora.'];
            }

            $stmt = $this->pdo->prepare("UPDATE cita SET title = ?, descripcion = ?, color = ?, textColor = ?, start = ?, end = ? WHERE id_cita = ?");
            $stmt->execute([
                $this->title,
                $this->descripcion,
                $this->color,
                $this->textColor,
                $startNorm,
                $this->end,
                $this->id_cita
            ]);

            return ['resultado' => 'ok', 'mensaje' => 'Cita actualizada correctamente.'];
        } catch (Exception $e) {
            error_log("Error al actualizar cita: " . $e->getMessage());
            return ['resultado' => 'error', 'mensaje' => 'Error al actualizar la cita: ' . $e->getMessage()];
        }
    }
    public function eliminarcita($id_cita){
        $stmt=$this->pdo->prepare("DELETE FROM cita WHERE id_cita=?");
        $stmt->execute([$id_cita]);
    }

    private function existe($start, $end, $excludeId = null)
    {
        // Reutilizable: comprueba si existe otra cita con el mismo start.
        // Si $excludeId se pasa, excluye esa fila (útil para actualizar).
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $startNorm = str_replace('T', ' ', $start);
            $sql = "SELECT COUNT(*) as cnt FROM cita WHERE start = :start";
            if (!is_null($excludeId)) {
                $sql .= " AND id_cita != :excludeId";
            }
            $consulta = $q->prepare($sql);
            $consulta->bindValue(':start', $startNorm);
            if (!is_null($excludeId)) {
                $consulta->bindValue(':excludeId', $excludeId);
            }
            $consulta->execute();

            $fila = $consulta->fetch(PDO::FETCH_ASSOC);
            return (!empty($fila) && $fila['cnt'] > 0);
        } catch (Exception $e) {
            error_log('Error comprobando existencia de cita: ' . $e->getMessage());
            // Por seguridad, decir que existe para evitar inserciones/actualizaciones indeseadas
            return true;
        }
    }

}
?>