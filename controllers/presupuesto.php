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
    }

    public function index() {
        $data_search['areas_list'] = $this->generic_model->get('plan_proyectos_area');
        $data_search['tipos_list'] = $this->generic_model->get('plan_proyectos_tipo');
        $res['view'] = $this->load->view('presupuesto_view', $data_search, TRUE);
//        $res['view'] = $this->load->view('common/crud/crud_view_datatable', $crud);
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $res['title'] = 'Presupuesto-Logistica';
        $this->load->view('common/templates/dashboard_lte', $res);
    }

    /* Filtra segun los parametros de busqueda para generar el listado */

    function search() {
        $area = $this->input->post('select_area');
        $tipo = $this->input->post('select_tipo');

        $where = array();
        $join_clause = array();

        //Enviamos los valores en vacio si son -1
        /* Programacion Funcional de un IF. (condicion ? true : false) */
        $area = ($area == -1 ? '' : $area);
        $tipo = ($tipo == -1 ? '' : $tipo);

        if (!empty($area)): $where['area_cod'] = $area;
        endif;
        if (!empty($tipo)): $where['tipo_id'] = $tipo;
        endif;

        $join_clause[] = array('table' => 'plan_proyectos_area area', 'condition' => 'area_cod = area.cod');
        $join_clause[] = array('table' => 'plan_proyectos_tipo tipo', 'condition' => 'tipo_id = tipo.id');

        $json_res = $this->generic_model->get_join('plan_proyectos plan', $where, $join_clause, 'plan.cod, plan.nombre, '
                . 'presupuesto_inicial, presupuesto_vigente, area.nombre area, tipo.tipo', '');
        $resultado = count($json_res);
        $json_res = json_encode($json_res);
        $res['data'] = $json_res;
        $res['num_reg'] = $resultado;
        $res['subject'] = 'PARTIDAS';
        $this->load->view('presupuesto/result_list', $res);
    }

}
