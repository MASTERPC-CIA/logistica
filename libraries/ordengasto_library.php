<?php

class ordengasto_library {

    private $ci;
    private $secuencia_orden;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->model('ordengasto_model');
        $this->secuencia_orden = $this->ci->ordengasto_model->get_last();
    }

    /* Carga la vista para generar una orden de gasto */

    public function load_view() {
        //Tipo 1: Area funcional

        $areas_func_list = $this->ci->generic_model->get('plan_proyectos', array('tipo_id' => '1'), 'id, cod, nombre');
        $empleados = $this->ci->generic_model->get('billing_empleado', array(), 'id, CONCAT_WS(" ", nombres, apellidos) nombres');
//        print_r($empleados);
        $data_orden['areas_list'] = $areas_func_list;
        $data_orden['empleados'] = $empleados;
        $data_orden['secuencia'] = $this->secuencia_orden;

        $res['view'] = $this->ci->load->view('orden_gasto_view', $data_orden, TRUE);
        $res['title'] = 'Orden Gasto-Logistica';
        $res['slidebar'] = $this->ci->load->view('slidebar', '', TRUE);
        $this->ci->load->view('common/templates/dashboard_lte', $res);
    }

    /* Guarda una nueva orden de gasto */

    function save() {
       
    }

}
