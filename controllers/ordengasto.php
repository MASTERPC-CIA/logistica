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

    /* Extrae todos los hijos de una cuenta del plan de proyectos, llamada desde vista */

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
        //Campos vacios para reutilizar la misma vista para editar
        $res['beneficiario_id'] = '';
        $res['beneficiario_ruc'] = '';
        $res['beneficiario_nombre'] = '';
        $res['concepto'] = '';
        $res['comprobante_num'] = '';
        $res['gasto'] = 0;
        $res['gasto_acumulado'] = 0;
        $res['saldo_vigente'] = 0;

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
        $ord_subtarea_id = $this->input->post('combo_subtareas');
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
                $array_partidas, $ord_fecha, $ord_hora, $ord_numero, $ord_referencia, $ord_cod_unidad, $ord_nombre_unidad, $ord_unidad_ejecutora, $ord_proyecto, $ord_subproyecto, $ord_subtarea_id, $ord_gasto_og, $ord_iva, $ord_total, $ord_user_id, $ord_user_revision, $ord_user_relator, $ord_user_aprobacion);
        
        if($id_orden == -1){#Si ha ocurrido un error en la transaccion
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>Orden de Gasto NO Guardada');
            echo tagcontent('script', 'alertaError("Ha ocurrido un error")');
            echo $this->res_msj;
            die();
        }
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
            echo tagcontent('script', '$("#orden_out").hide(500);');
        }
    }

    /* Filtra segun los parametros de busqueda para generar el listado de ordenes de gasto*/

    function search() {
        $fechaDesde = $this->input->post('fechaIn');
        $fechaHasta = $this->input->post('fechaFin');
        $empleadoId = $this->input->post('empleado_id');

        $this->ordengasto_library->printListado($fechaDesde, $fechaHasta, $empleadoId);
    }

    /* Muestra una orden de gasto para imprimirla */
    function printOrden($orden_id) {
        $this->ordengasto_library->printById($orden_id);
    }
    
    /*Muestra las opciones para editar el detalle de una orden*/
    function editView($orden_id) {
        $this->ordengasto_library->editView($orden_id);

    }
    
    /*Edita el detalle de una orden de gasto*/
    function editOrden(){
        $orden_id = $this->input->post('orden_id');
        $ord_gasto_og = $this->input->post('input_gasto_og');
        $ord_iva = $this->input->post('input_iva');
        $ord_total = $this->input->post('input_total');
        $array_partidas = $this->input->post('partidas');


        $this->db->trans_begin();

        $id_orden = $this->ordengasto_model->update(
                $array_partidas, $orden_id, $ord_gasto_og, $ord_iva, $ord_total);
        
        if($id_orden == -1){#Si ha ocurrido un error en la transaccion
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>Orden de Gasto NO Actualizada');
            echo tagcontent('script', 'alertaError("Ha ocurrido un error")');
            echo $this->res_msj;
            die();
        }
//Verificación de transacción:
// verifico que todo elproceso en si este bien ejecutado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>Ha ocurrido un error al actualizar la orden.');
            echo $this->res_msj;
        } else {
            $this->res_msj .= success_msg('. Orden de Gasto Actualizada');
            echo $this->res_msj;
            $this->db->trans_commit();
//            echo tagcontent('script', '$("#orden_out").hide(500);');
        }
    }
    /*Muestra las opciones para editar el detalle de una orden*/
    function anularView($orden_id) {
        $this->ordengasto_library->anularView($orden_id);

    }
    
    /*Edita el detalle de una orden de gasto*/
    function anularOrden(){
        $orden_id = $this->input->post('id_orden');
        $observaciones = $this->input->post('observaciones');

        $this->db->trans_begin();
        $id_orden = $this->ordengasto_model->anular($orden_id, $observaciones);

        
        if($id_orden == -1){#Si ha ocurrido un error en la transaccion
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>NO se ha podido anular la orden');
            echo tagcontent('script', 'alertaError("Ha ocurrido un error")');
            echo $this->res_msj;
            die();
        }
//Verificación de transacción:
// verifico que todo elproceso en si este bien ejecutado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->res_msj .= error_msg('<br>Ha ocurrido un error al anular la orden.');
            echo $this->res_msj;
        } else {
            $this->res_msj .= success_msg('. Orden de Gasto Anulada');
            echo $this->res_msj;
            $this->db->trans_commit();
//            echo tagcontent('script', '$("#orden_out").hide(500);');
        }
    }

}
