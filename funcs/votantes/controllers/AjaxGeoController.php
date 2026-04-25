<?php
require_once 'models/AjaxGeoModel.php';

class AjaxGeoController {
    private $model;

    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        $this->model = new AjaxGeoModel();
    }

    public function getDepartamentos() {
        $deptos = $this->model->getDepartamentos();
        $selected_dpto = (isset($_POST['current']) && $_POST['current'] !== "") ? $_POST['current'] : 'VALLE';
        
        echo '<option value="" disabled '. (empty($selected_dpto) ? 'selected' : '') .'>Seleccione Departamento</option>';
        foreach($deptos as $d) {
            $sel = (strtoupper($selected_dpto) == strtoupper($d['dpto_zona'])) ? 'selected' : '';
            echo '<option value="'.$d['dpto_zona'].'" '.$sel.'>'.$d['dpto_zona'].'</option>';
        }
    }

    public function getMunicipios() {
        $dpto = isset($_POST['departamento']) ? $_POST['departamento'] : '';
        $munis = $this->model->getMunicipios($dpto);
        $selected_muni = isset($_POST['current']) ? $_POST['current'] : '';

        echo '<option value="" disabled '. (empty($selected_muni) ? 'selected' : '') .'>Seleccione Municipio</option>';
        foreach($munis as $m) {
            $sel = ($selected_muni == $m['mun_zona']) ? 'selected' : '';
            echo '<option value="'.$m['mun_zona'].'" '.$sel.'>'.$m['mun_zona'].'</option>';
        }
    }

    public function getZonas() {
        $dpto = isset($_POST['departamento']) ? $_POST['departamento'] : '';
        $muni = isset($_POST['municipio']) ? $_POST['municipio'] : '';
        $zonas = $this->model->getZonas($dpto, $muni);
        $selected_zona = isset($_POST['current']) ? $_POST['current'] : '';

        echo '<option value="" disabled '. (empty($selected_zona) ? 'selected' : '') .'>Seleccione Zona</option>';
        foreach($zonas as $z) {
            $sel = ($selected_zona == $z['num_zona']) ? 'selected' : '';
            echo '<option value="'.$z['num_zona'].'" '.$sel.'>'.$z['num_zona'].'</option>';
        }
    }

    public function getPuestos() {
        $dpto = isset($_POST['departamento']) ? $_POST['departamento'] : '';
        $muni = isset($_POST['municipio']) ? $_POST['municipio'] : '';
        $zona = isset($_POST['zona']) ? $_POST['zona'] : '';
        $puestos = $this->model->getPuestos($dpto, $muni, $zona);
        $selected_puesto = isset($_POST['current']) ? $_POST['current'] : '';

        echo '<option value="" disabled '. (empty($selected_puesto) ? 'selected' : '') .'>Seleccione Puesto</option>';
        foreach($puestos as $p) {
            $sel = ($selected_puesto == $p['pues_zona']) ? 'selected' : '';
            echo '<option value="'.$p['pues_zona'].'" '.$sel.'>'.$p['pues_zona'].'</option>';
        }
    }

    public function getPuestosByMuni() {
        $dpto = isset($_POST['departamento']) ? $_POST['departamento'] : '';
        $muni = isset($_POST['municipio']) ? $_POST['municipio'] : '';
        $puestos = $this->model->getPuestosByMuni($dpto, $muni);
        $selected_puesto = isset($_POST['current']) ? $_POST['current'] : '';

        echo '<option value="" '. (empty($selected_puesto) ? 'selected' : '') .'>-- Todos --</option>';
        foreach($puestos as $p) {
            $val = $p['num_zona']."-".$p['pues_zona'];
            $nom = $p['nom_puesto'] . " (Zona: " . $p['num_zona'] . ")";
            $sel = ($selected_puesto == $val) ? 'selected' : '';
            echo '<option value="'.$val.'" '.$sel.'>'.$nom.'</option>';
        }
    }

    public function getPuestoInfo() {
        $dpto = isset($_POST['departamento']) ? $_POST['departamento'] : '';
        $muni = isset($_POST['municipio']) ? $_POST['municipio'] : '';
        $zona = isset($_POST['zona']) ? $_POST['zona'] : '';
        $puesto = isset($_POST['puesto']) ? $_POST['puesto'] : '';
        
        $info = $this->model->getPuestoInfo($dpto, $muni, $zona, $puesto);
        if ($info) {
            echo json_encode(array('nom_puesto' => $info['nom_puesto'], 'dir_puesto' => $info['dir_zona']));
        } else {
            echo json_encode(array('nom_puesto' => '', 'dir_puesto' => ''));
        }
    }

    public function getReverseGeo() {
        $zona = isset($_POST['zona']) ? $_POST['zona'] : '';
        $puesto = isset($_POST['puesto']) ? $_POST['puesto'] : '';
        $info = $this->model->getReverseGeo($zona, $puesto);
        if ($info) {
            echo json_encode(array('status' => 'success', 'dpto' => $info['dpto_zona'], 'muni' => $info['mun_zona']));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }
}
?>
