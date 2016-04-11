<?php

class presupuesto_library {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
    }

    /* Carga la vista para generar un reporte de presupuestos */

    public function reporteView() {
        //Filtramos las partidas segun el rol del usuario
        $esAdministrativoPersonal = $this->ci->user->check_permission(array('logistica_personal', 'compras'), $this->ci->user->id);
        $esAdministrativoSanidad = $this->ci->user->check_permission(array('logistica_sanidad', 'compras'), $this->ci->user->id);

//        echo '<br>Personal: '.$esAdministrativoPersonal;
//        echo '<br>Sanidad: '.$esAdministrativoSanidad;
        $where = array ();
        //Filtramos sus permisos a la base de datos
        if ($esAdministrativoPersonal && $esAdministrativoSanidad): 
        elseif ($esAdministrativoPersonal): $where['cod'] ='A';
        elseif  ($esAdministrativoSanidad): $where['cod'] ='S';
        endif;
        $data_search['areas_list'] = $this->ci->generic_model->get('plan_proyectos_area', $where);
        $data_search['tipos_list'] = $this->ci->generic_model->get('plan_proyectos_tipo');
        $res['view'] = $this->ci->load->view('presupuesto/reporte_view', $data_search, TRUE);
//        $res['view'] = $this->load->view('common/crud/crud_view_datatable', $crud);
        $res['slidebar'] = $this->ci->load->view('slidebar', '', TRUE);
        $res['title'] = 'Presupuesto-Logistica';
        $this->ci->load->view('common/templates/dashboard_lte', $res);
    }

    /* Imprime el listado segun los parametros especificados */

    function printListado($area, $tipo) {
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

        $json_res = $this->ci->generic_model->get_join('plan_proyectos plan', $where, $join_clause, 'plan.cod, plan.nombre, '
                . 'presupuesto_inicial, presupuesto_vigente, area.nombre area, tipo.tipo', '');
        $resultado = count($json_res);
        $json_res = json_encode($json_res);
        $res['data'] = $json_res;
        $res['num_reg'] = $resultado;
        $res['subject'] = 'PARTIDAS';
        $this->ci->load->view('presupuesto/result_list', $res);
    }
    
    

}
