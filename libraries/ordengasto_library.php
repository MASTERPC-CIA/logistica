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

    public function newGastoView() {
        //Tipo 1: Area funcional

        $areas_func_list = $this->ci->generic_model->get('plan_proyectos', array('tipo_id' => '1'), 'id, cod, nombre');
        $empleados = $this->ci->generic_model->get('billing_empleado', array(), 'id, CONCAT_WS(" ", nombres, apellidos) nombres');
//        print_r($empleados);
        $data_orden['areas_list'] = $areas_func_list;
        $data_orden['empleados'] = $empleados;
        $data_orden['secuencia'] = $this->secuencia_orden;
        $data_orden['iva'] = 0;
        $data_orden['gasto_og'] = 0;
        $data_orden['total'] = 0;
        /* Enviamos los usuarios en -1 */
        $data_orden['footer_data'] = array('revisado_por' => '-1',
            'aprobado_por' => '-1',
            'relator' => '-1'
        );

        $res['view'] = $this->ci->load->view('orden_gasto/new_gasto', $data_orden, TRUE);
        $res['title'] = 'Orden Gasto-Logistica';
        $res['slidebar'] = $this->ci->load->view('slidebar', '', TRUE);
        $this->ci->load->view('common/templates/dashboard_lte', $res);
    }

    /* Carga la vista para generar un reporte de las ordenes de gasto */

    public function reporteView() {
        $data_search['lista_empleado'] = $this->ci->generic_model->get('billing_empleado', array(), 'id, CONCAT_WS(" ", nombres, apellidos) nom_empleado');
        $data_search['partidas'] = $this->ci->generic_model->get('plan_proyectos', array('tipo_id' => '7'), 'id, CONCAT_WS(" ", cod, nombre) nombre');
        $data_search['beneficiarios'] = $this->ci->generic_model->get('beneficiario_partidas', array(), 'id, ben_nombre nombre');
//        $data_search['tipos_list'] = $this->ci->generic_model->get('plan_proyectos_tipo');
        $res['view'] = $this->ci->load->view('orden_gasto/reporte_view', $data_search, TRUE);
//        $res['view'] = $this->load->view('common/crud/crud_view_datatable', $crud);
        $res['slidebar'] = $this->ci->load->view('slidebar', '', TRUE);
        $res['title'] = 'Reporte Gastos-Logistica';
        $this->ci->load->view('common/templates/dashboard_lte', $res);
    }

    /* Imprime el listado segun los parametros especificados */

    function printListado($fechaDesde, $fechaHasta, $empleadoId, $partida_id, $estado, $beneficiario) {
        $where = array();
//        $where = array('ord_estado !='=>'-1');
        $join_clause = array();

        //Enviamos los valores en vacio si son -1
        /* Programacion Funcional de un IF. (condicion ? true : false) */
        $empleadoId = ($empleadoId == -1 ? '' : $empleadoId);
        $partida_id = ($partida_id == -1 ? '' : $partida_id);
        $estado = ($estado == -2 ? '' : $estado);
        $beneficiario = ($beneficiario == -1 ? '' : $beneficiario);
        if (!empty($empleadoId)): $where['ord_user_id'] = $empleadoId;
        endif;
        if (!empty($partida_id)): $where['odet_partida_id'] = $partida_id;
        endif;
        if (!empty($estado)): $where['ord_estado'] = $estado;
        endif;
        if (!empty($beneficiario)): $where['odet_beneficiario_id'] = $beneficiario;
        endif;
        if (!empty($fechaDesde)): $where['ord_fecha >='] = $fechaDesde;
        endif;
        if (!empty($fechaHasta)): $where['ord_fecha <='] = $fechaHasta;
        endif;

        $join_clause[] = array('table' => 'billing_empleado emp_realiz', 'condition' => 'ord_user_id = emp_realiz.id');
        $join_clause[] = array('table' => 'billing_empleado emp_aprob', 'condition' => 'ord_user_aprobacion = emp_aprob.id');
        $join_clause[] = array('table' => 'orden_gasto_detalle', 'condition' => 'odet_orden_id = ord.id');

        $fields = 'ord.id, ord_numero numero, ord_fecha fecha, ord_hora hora, ord_estado estado,'
                . 'ord_total total, CONCAT_WS(" ", emp_realiz.nombres, emp_realiz.apellidos) realizado_por,'
                . 'CONCAT_WS(" ", emp_aprob.nombres, emp_aprob.apellidos) aprobado_por,'
                . 'odet_tipo_beneficiario tipo_beneficiario, odet_beneficiario_id id_beneficiario, odet_empleado_id empleado_id, odet_concepto concepto';
        $listado_ordenes = $this->ci->generic_model->get_join('orden_gasto ord', $where, $join_clause, $fields, 0, null, 'ord.id');
        //Agregamos el beneficiario correspondiente al primer registro del detalle
        foreach ($listado_ordenes as $orden) {
            $beneficiario = $orden->tipo_beneficiario == 'B' ? $orden->id_beneficiario : $orden->empleado_id;
            $orden->beneficiario = $this->getBeneficiario($orden->tipo_beneficiario, $beneficiario);
        }
        $num_registros = count($listado_ordenes);
        $listado_ordenes = json_encode($listado_ordenes);
        $res['data'] = $listado_ordenes;
        $res['num_reg'] = $num_registros;
        $res['subject'] = 'PARTIDAS';
        $this->ci->load->view('orden_gasto/result_list', $res);
    }

    /* Extrae el beneficiario segun el tipo */

    function getBeneficiario($tipo_beneficiario, $id_beneficiario) {
        $where = array();
        switch ($tipo_beneficiario) {
            case 'E':
                $where['id'] = $id_beneficiario;
                $beneficiario = $this->ci->generic_model->get_val_where('billing_empleado', $where, 'apellidos');
                break;
            case 'B':
                $where['id'] = $id_beneficiario;
                $beneficiario = $this->ci->generic_model->get_val_where('beneficiario_partidas', $where, 'ben_nombre');
                break;
            default:
                break;
        }
        return $beneficiario;
    }

    /* imprime una orden de gasto y su detalle */

    function printById($orden_id) {
        $orden_data = $res['orden_data'] = $this->ci->ordengasto_model->getData($orden_id);
        $detalle = $this->ci->ordengasto_model->getDetalle($orden_id);

        //Todas las partidas pertenecen a la misma subtarea. Extraemos el padre de la primera [0]
        //Subtarea
        $subtarea = $res['subtarea'] = $this->ci->ordengasto_model->getParent($detalle[0]->odet_partida_id);
        //tarea
        $tarea = $res['tarea'] = $this->ci->ordengasto_model->getCtaData($subtarea->parent, 'id, cod, nombre, parent');
        //subactividad
        $subactividad = $res['subactividad'] = $this->ci->ordengasto_model->getCtaData($tarea->parent, 'id, cod, nombre, parent');
        //actividad
        $actividad = $res['actividad'] = $this->ci->ordengasto_model->getCtaData($subactividad->parent, 'id, cod, nombre, parent');
        //programa
        $programa = $res['programa'] = $this->ci->ordengasto_model->getCtaData($actividad->parent, 'id, cod, nombre, parent');
        //area_funcional
        $area_funcional = $res['area_funcional'] = $this->ci->ordengasto_model->getCtaData($programa->parent, 'id, cod, nombre, parent');
        $res['detalle'] = json_encode($detalle);
        $this->ci->load->view('orden_gasto/print_orden', $res);
    }

    /* Muestra la ventana para editar una orden de gasto */

    function editView($orden_id) {
        $detalle = $this->ci->ordengasto_model->getDetalle($orden_id);
        $empleados = $this->ci->generic_model->get('billing_empleado', array(), 'id, CONCAT_WS(" ", nombres, apellidos) nombres');


        $res['orden_id'] = $orden_id;
        $fields = 'ord_iva, ord_numero, ord_gasto_og, ord_total, ord_subtarea_id,'
                . 'ord_user_revision, ord_user_aprobacion, ord_user_relator';
        $res['orden_data'] = $this->ci->generic_model->get_data('orden_gasto', array('id' => $orden_id), $fields, null, 1);
//        $res['iva'] = $this->generic_model->get_val_where('orden_gasto', array('id'=>$orden_id), 'ord_iva');
        $res['detalle'] = $detalle;
        $res['footer_data'] = array('revisado_por' => $res['orden_data']->ord_user_revision,
            'aprobado_por' => $res['orden_data']->ord_user_aprobacion,
            'relator' => $res['orden_data']->ord_user_relator,
            'empleados' => $empleados
        );
        $this->ci->load->view('orden_gasto/edit_view', $res);
    }

    /* Muestra la ventana para anular una orden de gasto */

    function anularView($orden_id) {
        $res['orden_id'] = $orden_id;
        $res['orden_data'] = $this->ci->generic_model->get_data('orden_gasto', array('id' => $orden_id), 'ord_iva, ord_numero, ord_gasto_og, ord_total, ord_subtarea_id', null, 1);
        $this->ci->load->view('orden_gasto/anular_view', $res);
    }

}
