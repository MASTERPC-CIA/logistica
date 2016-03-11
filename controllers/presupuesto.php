<?php

/**
 * Description of index
 *
 * @author JOSE LUIS Q
 * Desarrollado a inicios del mes de marzo/2016
 */
class Presupuesto extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
        $this->load->library('presupuesto_library');
    }

    public function index() {
        $this->presupuesto_library->reporteView();
    }

    /* Filtra segun los parametros de busqueda para generar el listado */

    function search() {
        $area = $this->input->post('select_area');
        $tipo = $this->input->post('select_tipo');

        $this->presupuesto_library->printListado($area, $tipo);
    }

}
