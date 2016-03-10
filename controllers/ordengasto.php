<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ordengasto extends MX_Controller {

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
    }

    public function index() {
        $this->ordengasto_library->newGastoView();
    }
    
    public function reporteView() {
        $this->ordengasto_library->reporteView();
    }

    /*Extrae todos los hijos de una cuenta del plan de proyectos, llamada desde vista*/
    public function getChildrens($idParent, $tipo_id) {
        $list = $this->generic_model->get('plan_proyectos', array('parent' => $idParent, 'tipo_id' => $tipo_id), 'id, nombre');
        echo json_encode($list);
    }

    //Carga un elemento del detalle de la orden gasto via ajax
    public function loadDetalleView() {
        $id_partida = $this->input->post('id_partida');
        $res['id_partida'] = $id_partida;
        $res['cod_partida'] = $this->input->post('cod_partida');
        $res['programa_id'] = $this->input->post('programa_id');
        $res['count_partidas'] = $this->input->post('count_partidas');
        $res['presupuesto_inicial'] = $this->generic_model->get_val_where('plan_proyectos', array('id' => $id_partida), 'presupuesto_inicial');
        $this->load->view('orden_gasto/partidaDetalle', $res);
    }

    /* Guarda una orden de gasto */

    public function save_new() {
        $ord_fecha = $this->input->post('ord_fecha');
        $ord_hora = date('H:i:s', time());
        $ord_numero = $this->input->post('ord_numero');
        $ord_referencia = $this->input->post('ord_referencia');
        $ord_cod_unidad = $this->input->post('ord_cod_unidad');
        $ord_nombre_unidad = $this->input->post('ord_nombre_unidad');
        $ord_unidad_ejecutora = $this->input->post('ord_unidad_ejecutora');
        $ord_proyecto = null;
        $ord_subproyecto = null;
        $ord_gasto_og = $this->input->post('input_gasto_og');
        $ord_iva = $this->input->post('input_iva');
        $ord_total = $this->input->post('input_total');
        $ord_user_id = $this->user->id;
        $ord_user_revision = $this->input->post('revisado_por');
        $ord_user_relator = $this->input->post('relator_pb');
        $ord_user_aprobacion = $this->input->post('aprobado_por');
        $array_partidas = $this->input->post('partidas');

//        $this->form_validation->set_rules('revisado_por', 'REVISADO POR', 'required');
//        $this->form_validation->set_rules('relator_pb', 'RELATOR DEL PB', 'required');
//        $this->form_validation->set_rules('aprobado_por', 'APROBADO POR', 'required');
        //Validamos que se selecione un revisor, relator y aprobador
        if ($ord_user_revision == '-1' || $ord_user_relator == '-1' || $ord_user_aprobacion == '-1') {
            echo info_msg('Debe seleccionar revisor, relator y aprobador');
            echo tagcontent('script', 'alertaError("Hay campos requeridos")');
            die();
        }

        $this->db->trans_begin();

        $id_orden = $this->ordengasto_model->save(
                $array_partidas, $ord_fecha, $ord_hora, $ord_numero, $ord_referencia, $ord_cod_unidad, $ord_nombre_unidad, $ord_unidad_ejecutora, $ord_proyecto, $ord_subproyecto, $ord_gasto_og, $ord_iva, $ord_total, $ord_user_id, $ord_user_revision, $ord_user_relator, $ord_user_aprobacion);

//Verificación de transacción:
// verifico que todo elproceso en si este bien ejecutado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>Ha ocurrido un error al guardar la orden.');
            echo $this->res_msj;
        } else {
            $this->res_msj .= success_msg('. Orden de Gasto registrada');
            echo $this->res_msj;
            $this->db->trans_commit();
            echo tagcontent('script', '$("#ordengasto_view").hide(500);');
        }
    }
    
     /* Filtra segun los parametros de busqueda para generar el listado */
    function search() {
        $fDesde = $this->input->post('fechaIn');
        $fHasta = $this->input->post('fechaFin');
        $empleadoId = $this->input->post('empleado_id');

        $where = array();
        $join_clause = array();

        //Enviamos los valores en vacio si son -1
        /* Programacion Funcional de un IF. (condicion ? true : false) */
        $empleadoId = ($empleadoId == -1 ? '' : $empleadoId);

        if (!empty($empleadoId)): $where['ord_user_id'] = $empleadoId;
        endif;
        if (!empty($fDesde)): $where['ord_fecha >='] = $fDesde;
        endif;
        if (!empty($fHasta)): $where['ord_fecha <='] = $fHasta;
        endif;

        $join_clause[] = array('table' => 'billing_empleado emp_realiz', 'condition' => 'ord_user_id = emp_realiz.id');
        $join_clause[] = array('table' => 'billing_empleado emp_aprob', 'condition' => 'ord_user_aprobacion = emp_aprob.id');

        $json_res = $this->generic_model->get_join('orden_gasto ord', $where, 
                $join_clause, 'ord.id, ord_numero numero, ord_fecha fecha, ord_hora hora, '
                . 'ord_total total, CONCAT_WS(" ", emp_realiz.nombres, emp_realiz.apellidos) realizado_por,'
                . 'CONCAT_WS(" ", emp_aprob.nombres, emp_aprob.apellidos) aprobado_por', '');
        $resultado = count($json_res);
        $json_res = json_encode($json_res);
        $res['data'] = $json_res;
        $res['num_reg'] = $resultado;
        $res['subject'] = 'PARTIDAS';
        $this->load->view('orden_gasto/result_list', $res);
    }
}
