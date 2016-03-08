<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ordengasto extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
        $this->load->database();
//        $this->load->helper('url');
//
//        $this->load->library('grocery_CRUD');
    }

    public function index() {
//        $res1['proyectos_list'] = $this->get_crud();
        //Tipo 1: Area funcional
        $areas_func_list = $this->generic_model->get('plan_proyectos', array('tipo_id'=>'1'), 'id, cod, nombre');
        $data_orden['areas_list'] = $areas_func_list;
        
        $res['view'] = $this->load->view('orden_gasto_view', $data_orden, TRUE);
        $res['title'] = 'Orden Gasto-Logistica';
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $this->load->view('common/templates/dashboard_lte', $res);
    }
    
    public function getChildrens($idParent, $tipo_id) {
        $list = $this->generic_model->get('plan_proyectos', 
                array('parent'=>$idParent, 'tipo_id'=>$tipo_id), 'id, nombre');

        echo json_encode($list);
    }
}