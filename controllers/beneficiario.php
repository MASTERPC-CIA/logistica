<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Beneficiario extends MX_Controller {

    private $res_msj = '';

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
        $this->load->database();
//        $this->load->helper('url');
//
        $this->load->library('ordengasto_library');
        $this->load->model('ordengasto_model');
        $this->load->library('form_validation');
        $this->load->library('grocery_CRUD');
    }

    public function index() {
        $res['view'] = $this->load->view('beneficiario_crud', array(), true);
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $this->load->view('common/templates/dashboard_lte', $res);
    }

    public function get_crud() {
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
//		$this->config->set_item('grocery_crud_default_per_page',10);            
        $crud = new grocery_CRUD();
//        $crud->set_theme('flexigrid');
//$crud->set_theme('twitter-bootstrap');
        $crud->set_table('beneficiario_partidas');
        $crud->columns('id', 'ben_ruc', 'ben_nombre');
        $crud->display_as('ben_ruc', 'Ruc');
        $crud->display_as('ben_nombre', 'Nombre');
        $crud->set_relation('ben_plan_proyectos_id', 'plan_proyectos', '{nombre} {cod}');
//        $crud->unset_columns('id');
        $crud->set_subject('Beneficiarios');
        $crud->add_fields('ben_ruc', 'ben_nombre', 'ben_plan_proyectos_id');
        $crud->edit_fields('ben_ruc', 'ben_nombre', 'ben_plan_proyectos_id');

        $output = $crud->render();

        $this->load->view('common/crud/crud_view_datatable', $output);
//        $this->load->view('common/crud/crud_view_flexgrid', $output);
    }

}
