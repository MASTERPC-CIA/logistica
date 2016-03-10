<?php

class presupuesto_library {

    private $ci;
    private $secuencia_orden;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->model('ordengasto_model');
        $this->secuencia_orden = $this->ci->ordengasto_model->get_last();
    }

    /* Carga la vista para generar un reporte de presupuestos */

    public function reporteView() {
        $data_search['areas_list'] = $this->ci->generic_model->get('plan_proyectos_area');
        $data_search['tipos_list'] = $this->ci->generic_model->get('plan_proyectos_tipo');
        $res['view'] = $this->ci->load->view('presupuesto/reporte_view', $data_search, TRUE);
//        $res['view'] = $this->load->view('common/crud/crud_view_datatable', $crud);
        $res['slidebar'] = $this->ci->load->view('slidebar', '', TRUE);
        $res['title'] = 'Presupuesto-Logistica';
        $this->ci->load->view('common/templates/dashboard_lte', $res);
    }

}
