<?php

/**
 * Description of index
 *
 * @author JOSE LUIS
 */
class Index extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
    }

    public function index() {
         //Tipo 1: Area funcional
        $areas_func_list = $this->generic_model->get('plan_proyectos', array('tipo_id'=>'1'), 'id, cod, nombre');
        $data_orden['areas_list'] = $areas_func_list;
        $res['view'] = $this->load->view('orden_gasto_view', $data_orden, TRUE);
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $res['title'] = 'Orden Gasto-Logistica';
        $this->load->view('common/templates/dashboard_lte', $res);
    }

}
