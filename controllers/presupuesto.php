<?php

/**
 * Description of index
 *
 * @author JOSE LUIS
 */
class Presupuesto extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
     }

    public function index() {
        $res['view'] = $this->load->view('presupuesto_view', '', TRUE);
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $res['title'] = 'Presupuesto-Logistica';
        $this->load->view('common/templates/dashboard_lte', $res);
    }
    
    public function get_crud() {
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
//		$this->config->set_item('grocery_crud_default_per_page',10);            
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
//$crud->set_theme('twitter-bootstrap');
        $crud->set_table('plan_proyectos');
        $crud->columns('id', 'cod', 'nombre', 'area_cod', 'tipo_id', 'parent', 'presupuesto_inicial', 'presupuesto_vigente');
        $crud->display_as('area_cod', 'Area');
        $crud->display_as('parent', 'Padre');
        $crud->display_as('tipo_id', 'Tipo');
        $crud->set_relation('area_cod', 'plan_proyectos_area', 'nombre');
        $crud->set_relation('tipo_id', 'plan_proyectos_tipo', 'tipo');
        $crud->set_relation('parent', 'plan_proyectos', '{nombre} {cod}');
        $crud->unset_columns('id');
        $crud->set_subject('Cuentas');
        $crud->add_fields('cod', 'nombre', 'area_cod', 'tipo_id', 'parent', 'presupuesto_inicial', 'presupuesto_vigente', 'visible');
        $crud->edit_fields('cod', 'nombre', 'area_cod', 'tipo_id', 'parent', 'presupuesto_inicial', 'presupuesto_vigente', 'visible');

        $output = $crud->render();

//        $this->load->view('common/crud/crud_view_datatable', $output);
        $this->load->view('common/crud/crud_view_flexgrid', $output);
    }

}
