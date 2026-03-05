<?php
class RegistrovotoController {
    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        if(!isset($_SESSION['id_usuario'])) {
            header("Location: index.php");
            exit;
        }

        require_once 'funcs/permisos_helper.php';
        $id_tipouser = $_SESSION['tipo_usuario'];

        // Acceso base: si no tiene permiso para ver ninguno de los dos módulos principales, fuera
        $puedeRegistro = tienePermiso($id_tipouser, 'Registro v.', 'ver');
        $puedeResultados = tienePermiso($id_tipouser, 'Resultados', 'ver');

        if (!$puedeRegistro && !$puedeResultados) {
            header("Location: index.php?url=dashboard/index");
            exit;
        }
    }

    public function index() {
        $id_tipouser = $_SESSION['tipo_usuario'];
        $id_usuario = $_SESSION['id_usuario'];
        
        // Si es Coordinador de Puesto, necesitamos sus datos de asignación
        $asignacion = null;
        if ($id_tipouser == 4) {
            require_once 'models/UsuarioModel.php';
            $userModel = new UsuarioModel();
            $asignacion = $userModel->getUsuarioById($id_usuario);
        }

        require 'views/registrovoto/index.php';
    }

    public function zonas() {
        $dpto = isset($_GET['dpto']) ? $_GET['dpto'] : '';
        $muni = isset($_GET['muni']) ? $_GET['muni'] : '';
        $aspirante = isset($_GET['aspirante']) ? $_GET['aspirante'] : '';

        if(empty($dpto) || empty($muni) || empty($aspirante)) {
            header("Location: index.php?url=registrovoto/index");
            exit;
        }

        require_once 'models/AjaxGeoModel.php';
        $geoModel = new AjaxGeoModel();
        
        // Pido la lista de zonas, por ahora usando el mismo de AjaxGeo sino creamos uno nuevo
        // La funcion del modelo esta diseñada para agrupar o retornar todo. Modifiquemosla si es necesario 
        // o llamamos directo.
        
        // En AjaxGeoModel hay una función getConfigZonas que podemos crear.
        $zonas = $geoModel->getZonasCompleto($dpto, $muni);

        require 'views/registrovoto/zonas.php';
    }

    public function votar() {
        $dpto = isset($_GET['dpto']) ? $_GET['dpto'] : '';
        $muni = isset($_GET['muni']) ? $_GET['muni'] : '';
        $aspirante = isset($_GET['aspirante']) ? $_GET['aspirante'] : '';
        $zona = isset($_GET['zona']) ? $_GET['zona'] : '';
        $puesto = isset($_GET['puesto']) ? $_GET['puesto'] : '';

        if(empty($dpto) || empty($muni) || empty($aspirante) || empty($zona) || empty($puesto)) {
            header("Location: index.php?url=registrovoto/index");
            exit;
        }

        require_once 'models/AjaxGeoModel.php';
        $geoModel = new AjaxGeoModel();
        
        $infoPuesto = $geoModel->getPuestoInfo($dpto, $muni, $zona, $puesto);
        $candidatos = $geoModel->getCandidatosByAspirante($aspirante);

        // Fetch the specific zona to know the number of mesas
        $zonasDelPuesto = $geoModel->getZonasCompleto($dpto, $muni);
        $total_mesa = 0;
        foreach($zonasDelPuesto as $z) {
            if ($z['num_zona'] == $zona && $z['pues_zona'] == $puesto) {
                // We use total_mesa if available
                $total_mesa = isset($z['total_mesa']) ? intval($z['total_mesa']) : 0;
                break;
            }
        }

        require 'views/registrovoto/votar.php';
    }

    public function store() {
        require_once 'models/RegistroVotoModel.php';
        $votoModel = new RegistroVotoModel();
        
        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
        
        if (isset($_POST['mesas']) && !empty($_POST['mesas'])) {
            if ($votoModel->guardarVotos($_POST, $id_usuario)) {
                echo json_encode(array('status' => 'success', 'message' => 'Votos registrados correctamente en la base de datos.'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Hubo un error al guardar los votos en la base de datos.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No se enviaron datos de mesas para guardar.'));
        }
    }

    public function resultados() {
        require 'views/registrovoto/resultados.php';
    }

    public function detalle() {
        $id_candidato = isset($_GET['id_candidato']) ? $_GET['id_candidato'] : '';
        $aspirante    = isset($_GET['aspirante'])    ? $_GET['aspirante']    : '';
        $dpto         = isset($_GET['dpto'])         ? $_GET['dpto']         : '';
        $muni         = isset($_GET['muni'])         ? $_GET['muni']         : '';

        if(empty($id_candidato) || empty($aspirante)) {
            header("Location: index.php?url=registrovoto/resultados");
            exit;
        }

        require_once 'models/RegistroVotoModel.php';
        $votoModel = new RegistroVotoModel();
        $detalles = $votoModel->detallePorZonaPuesto($id_candidato, $aspirante, $dpto, $muni);

        // Info candidato para el header
        require_once 'models/AjaxGeoModel.php';
        $geoModelDet = new AjaxGeoModel();
        $candidatos = $geoModelDet->getCandidatosByAspirante($aspirante);
        $nomCandidato = $id_candidato;
        foreach($candidatos as $c) {
            if ($c['id_candidato'] == $id_candidato) {
                $nomCandidato = $c['nom_candidato'] . ' (' . $c['nom_partido'] . ')';
                break;
            }
        }
        if ($id_candidato === 'EN_BLANCO')   $nomCandidato = 'Votos en Blanco';
        if ($id_candidato === 'NULOS')        $nomCandidato = 'Votos Nulos';
        if ($id_candidato === 'NO_MARCADOS')  $nomCandidato = 'Tarjetones No Marcados';

        require 'views/registrovoto/detalle.php';
    }

    public function ajaxResultados() {
        $dpto = isset($_POST['dpto']) ? $_POST['dpto'] : '';
        $muni = isset($_POST['muni']) ? $_POST['muni'] : '';
        $aspirante = isset($_POST['aspirante']) ? $_POST['aspirante'] : 'ALCALDIA';
        $puesto = isset($_POST['puesto']) ? $_POST['puesto'] : ''; // Ej: "3-2" (zona-puesto)

        // Separar zona y puesto si vienen como "zona-puesto"
        $zona_filter = '';
        $puesto_filter = '';
        if (!empty($puesto) && strpos($puesto, '-') !== false) {
            $parts = explode('-', $puesto, 2);
            $zona_filter = $parts[0];
            $puesto_filter = $parts[1];
        }

        require_once 'models/RegistroVotoModel.php';
        $votoModel = new RegistroVotoModel();

        $datos = $votoModel->resultadosGenerales($aspirante, $dpto, $muni, $zona_filter, $puesto_filter);

        $totalValidos = 0;
        $totalGeneral = 0;
        $totalNulosBlancos = 0;

        foreach($datos as $d) {
            $votos = intval($d['total_votos']);
            $totalGeneral += $votos;
            if (in_array($d['id_candidato'], ['EN_BLANCO', 'NULOS', 'NO_MARCADOS'])) {
                $totalNulosBlancos += $votos;
                if ($d['id_candidato'] == 'EN_BLANCO') {
                    $totalValidos += $votos; // Voto en blanco es válido
                }
            } else {
                $totalValidos += $votos;
            }
        }

        echo json_encode(array(
            'status' => 'success',
            'data' => $datos,
            'stats' => array(
                'total_general' => $totalGeneral,
                'total_validos'  => $totalValidos
            )
        ));
        exit;
    }
}
?>
