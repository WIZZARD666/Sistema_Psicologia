<?php
// Incluye el archivo del modelo 'Tratamiento'.
// 'BASE_PATH' es una constante que define la ruta base de tu aplicación (debe estar configurada previamente).
use Yahir\Compo\Tratamiento as TratamientoModelo;
// Incluye el archivo del modelo 'Pacientes'.
use Yahir\Compo\Pacientes as PacientesModelo;

/**
 * Clase TratamientoController
 * Este es el **controlador** principal para gestionar los tratamientos.
 * Su rol es manejar la lógica de negocio y coordinar entre la vista (interfaz) y los modelos (datos).
 */
class TratamientoController {
    // Propiedades privadas para almacenar las instancias de los modelos de Tratamiento y Paciente.
    private $tratamientoModel;
    private $pacienteModel;

    /**
     * Constructor de la clase.
     * Se ejecuta automáticamente al crear un nuevo objeto TratamientoController.
     * Aquí se inicializan las instancias de los modelos para poder usarlos.
     */
    public function __construct() {
        $this->tratamientoModel = new TratamientoModelo(); // Crea una instancia del modelo Tratamiento.
        $this->pacienteModel = new PacientesModelo(); // Crea una instancia del modelo Paciente (se asume que la clase es PacienteModulo).
    }

    /**
     * Obtiene una lista de todos los tratamientos.
     * Delega la consulta directamente al modelo de Tratamiento.
     */
    public function listarTratamientos() {
        return $this->tratamientoModel->listarTratamientos();
    }

    /**
     * Busca tratamientos que coincidan con un término dado.
     * Delega la búsqueda al modelo de Tratamiento.
     * @param string $termino El texto a buscar en los tratamientos.
     */
    public function buscarTratamientos($termino) {
        return $this->tratamientoModel->buscarTratamientos($termino);
    }

    /**
     * Obtiene los detalles de un tratamiento específico por su ID.
     * Delega la obtención al modelo de Tratamiento.
     * @param int $id El ID del tratamiento.
     */
    public function obtenerTratamiento($id) {
        return $this->tratamientoModel->obtenerTratamiento($id);
    }

    /**
     * Crea un nuevo tratamiento en la base de datos.
     * Incluye **validación** de los datos antes de intentar crear.
     * @param array $data Los datos del nuevo tratamiento.
     * @return array Un array con 'success' (true/false) y mensajes/errores.
     */
    public function crearTratamiento($data) {
        // Valida los datos recibidos.
        $errors = $this->validarDatosTratamiento($data);
        if (!empty($errors)) {
            // Si hay errores de validación, retorna false y los errores.
            return ['success' => false, 'errors' => $errors];
        }
        
        // Si los datos son válidos, llama al método de creación del modelo.
        $result = $this->tratamientoModel->crearTratamiento($data);
        // Retorna el resultado de la operación (éxito con ID o error).
        return $result !== false 
            ? ['success' => true, 'id' => $result] 
            : ['success' => false, 'message' => 'Error al crear el tratamiento'];
    }

    /**
     * Actualiza un tratamiento existente.
     * Incluye **validación** de los datos antes de intentar actualizar.
     * @param int $id El ID del tratamiento a actualizar.
     * @param array $data Los nuevos datos del tratamiento.
     * @return array Un array con 'success' (true/false) y mensajes/errores.
     */
    public function actualizarTratamiento($id, $data) {
        // Valida los datos recibidos.
        $errors = $this->validarDatosTratamiento($data);
        if (!empty($errors)) {
            // Si hay errores de validación, retorna false y los errores.
            return ['success' => false, 'errors' => $errors];
        }
        
        // Si los datos son válidos, llama al método de actualización del modelo.
        $result = $this->tratamientoModel->actualizarTratamiento($id, $data);
        // Retorna el resultado de la operación.
        return $result 
            ? ['success' => true] 
            : ['success' => false, 'message' => 'Error al actualizar el tratamiento'];
    }

    /**
     * Elimina un tratamiento por su ID.
     * Delega la eliminación al modelo de Tratamiento.
     * @param int $id El ID del tratamiento a eliminar.
     * @return array Un array con 'success' (true/false) y mensaje.
     */
    public function eliminarTratamiento($id) {
        $result = $this->tratamientoModel->eliminarTratamiento($id);
        // Retorna el resultado de la operación.
        return $result 
            ? ['success' => true] 
            : ['success' => false, 'message' => 'Error al eliminar el tratamiento'];
    }

    /**
     * Obtiene una lista de todos los pacientes.
     * Delega la consulta al modelo de Paciente.
     */
    public function obtenerPacientes() {
        return $this->pacienteModel->listarpaciente();
    }

    /**
     * Método privado para **validar los datos** de un tratamiento.
     * Verifica que los campos esenciales estén presentes y tengan el formato correcto.
     * @param array $data Los datos a validar.
     * @return array Un array asociativo con mensajes de error si los hay, vacío si todo es válido.
     */
    private function validarDatosTratamiento($data) {
        $errors = []; // Array para almacenar los errores de validación.
        
        // Validación: El ID de paciente es requerido.
        if (empty($data['id_paciente'])) {
            $errors['id_paciente'] = 'Seleccione un paciente';
        }
        
        // Validación: Fecha de creación es requerida y debe tener formato YYYY-MM-DD.
        if (empty($data['fecha_creacion'])) {
            $errors['fecha_creacion'] = 'La fecha de creación es requerida';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_creacion'])) {
            $errors['fecha_creacion'] = 'Formato de fecha inválido. Use YYYY-MM-DD.';
        }
        
        // Validación: Tipo de tratamiento es requerido y no debe exceder 100 caracteres.
        if (empty($data['tratamiento_tipo'])) {
            $errors['tratamiento_tipo'] = 'El tipo de tratamiento es requerido';
        } elseif (strlen($data['tratamiento_tipo']) > 100) {
            $errors['tratamiento_tipo'] = 'El tipo de tratamiento no debe exceder 100 caracteres';
        }
        
        // Validación: Estado actual es requerido y debe ser uno de los valores predefinidos.
        $estadosValidos = ['inicial', 'en_progreso', 'pausado', 'seguimiento', 'finalizado'];
        if (empty($data['estado_actual']) || !in_array($data['estado_actual'], $estadosValidos)) {
            $errors['estado_actual'] = 'Seleccione un estado válido';
        }
        
        // Validación: Descripción del diagnóstico es requerida y no debe exceder 500 caracteres.
        if (empty($data['diagnostico_descripcion'])) {
            $errors['diagnostico_descripcion'] = 'El diagnóstico es requerido';
        } elseif (strlen($data['diagnostico_descripcion']) > 500) {
            $errors['diagnostico_descripcion'] = 'El diagnóstico no debe exceder 500 caracteres';
        }
        
        // Validación: Observaciones (opcional) no deben exceder 500 caracteres si se proporcionan.
        if (!empty($data['observaciones']) && strlen($data['observaciones']) > 500) {
            $errors['observaciones'] = 'Las observaciones no deben exceder 500 caracteres';
        }
        
        return $errors; // Devuelve los errores encontrados (vacío si no hay).
    }

    /**
     * Maneja las **solicitudes AJAX** entrantes.
     * Este método centraliza las peticiones asíncronas desde el frontend.
     */
    public function handleAjaxRequest() {
        // Verifica si la solicitud es de tipo POST y si contiene el parámetro 'ajax_action'.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
            // Establece el encabezado para indicar que la respuesta será en formato JSON.
            header('Content-Type: application/json');
            
            try {
                // Un 'switch' para ejecutar la acción del controlador según el valor de 'ajax_action'.
                switch ($_POST['ajax_action']) {
                    case 'listar_tratamientos':
                        // Llama a listarTratamientos() y devuelve el resultado en JSON.
                        echo json_encode(['success' => true, 'data' => $this->listarTratamientos()]);
                        break;
                    case 'obtener_tratamiento':
                        // Obtiene el ID del POST (0 si no está) y llama a obtenerTratamiento().
                        $id = $_POST['id'] ?? 0; 
                        echo json_encode(['success' => true, 'data' => $this->obtenerTratamiento($id)]);
                        break;
                    case 'crear_tratamiento':
                        // Llama a crearTratamiento() con todos los datos del POST.
                        $result = $this->crearTratamiento($_POST);
                        echo json_encode($result); // Devuelve el resultado (éxito/errores).
                        break;
                    case 'actualizar_tratamiento':
                        // Obtiene el ID del POST (0 si no está) y llama a actualizarTratamiento().
                        $id = $_POST['id_tratamiento'] ?? 0;
                        $result = $this->actualizarTratamiento($id, $_POST);
                        echo json_encode($result); // Devuelve el resultado.
                        break;
                    case 'eliminar_tratamiento':
                        // Obtiene el ID del POST (0 si no está) y llama a eliminarTratamiento().
                        $id = $_POST['id'] ?? 0;
                        $result = $this->eliminarTratamiento($id);
                        echo json_encode($result); // Devuelve el resultado.
                        break;
                    case 'buscar_tratamientos':
                        // Obtiene el término de búsqueda y llama a buscarTratamientos().
                        $termino = $_POST['termino'] ?? '';
                        echo json_encode(['success' => true, 'data' => $this->buscarTratamientos($termino)]);
                        break;
                    default:
                        // Si la acción no es reconocida, devuelve un error.
                        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                }
            } catch (Exception $e) {
                // Captura cualquier excepción y devuelve un mensaje de error.
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            
            // IMPORTANTE: Termina la ejecución del script aquí.
            // Esto evita que se renderice la página HTML completa para una solicitud AJAX.
            exit();
        }
    }
}

// --- Lógica Principal del Script ---
// Esta sección se ejecuta cuando el archivo es accedido, ya sea por AJAX o una carga de página normal.

// Crea una instancia del controlador.
$controller = new TratamientoController();

// Intenta manejar cualquier solicitud AJAX.
// Si handleAjaxRequest() detecta una petición AJAX, la procesará y usará 'exit()' para detener el script.
$controller->handleAjaxRequest();

// Si el script llega hasta aquí, significa que NO fue una petición AJAX.
// Ahora, obtenemos los datos iniciales necesarios para la vista (si es una carga de página normal).
$tratamientos = $controller->listarTratamientos();
$pacientes = $controller->obtenerPacientes();

// Maneja las acciones de envío de formularios **tradicionales** (POST).
// Esta parte se activa si la página se recarga por un envío de formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se envió el formulario para 'guardar_tratamiento'.
    if (isset($_POST['guardar_tratamiento'])) {
        $result = $controller->crearTratamiento($_POST); // Intenta crear.
        if ($result['success']) {
            // Si es exitoso, guarda un mensaje en la sesión y redirige para evitar reenvío.
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Tratamiento creado correctamente'];
            header('Location: ?pagina=tratamiento'); // Redirección HTTP.
            exit(); // Detiene el script después de la redirección.
        } else {
            // Si falla, guarda un mensaje de error en la sesión.
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => $result['message'] ?? 'Error al crear el tratamiento'];
        }
    } elseif (isset($_POST['actualizar_tratamiento'])) {
        // Si se envió el formulario para 'actualizar_tratamiento'.
        $id = $_POST['id_tratamiento'] ?? 0;
        $result = $controller->actualizarTratamiento($id, $_POST); // Intenta actualizar.
        if ($result['success']) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Tratamiento actualizado correctamente'];
            header('Location: ?pagina=tratamiento');
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => $result['message'] ?? 'Error al actualizar el tratamiento'];
        }
    } elseif (isset($_POST['eliminar_tratamiento'])) {
        // Si se envió el formulario para 'eliminar_tratamiento'.
        $id = $_POST['id_tratamiento'] ?? 0;
        $result = $controller->eliminarTratamiento($id); // Intenta eliminar.
        if ($result['success']) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Tratamiento eliminado correctamente'];
            header('Location: ?pagina=tratamiento');
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => $result['message'] ?? 'Error al eliminar el tratamiento'];
        }
    } elseif (isset($_POST['buscar'])) {
        // Si se envió el formulario de 'buscar'.
        $termino = $_POST['termino'] ?? '';
        $tratamientos = $controller->buscarTratamientos($termino); // Actualiza la lista de tratamientos con los resultados de la búsqueda.
    }
}

// Finalmente, se incluye el archivo de la **vista**.
// Las variables como $tratamientos y $pacientes estarán disponibles en 'tratamiento.php' para ser mostradas.
require_once BASE_PATH . 'View/tratamiento.php';
?>