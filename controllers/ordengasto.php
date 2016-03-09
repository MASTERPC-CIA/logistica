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
        $this->load->library('ordengasto_library');
        $this->load->model('ordengasto_model');
    }

    public function index() {
        $this->ordengasto_library->load_view();
    }

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
        $this->ordengasto_model->save();
        $ord_fecha = $this->input->post('ord_fecha');
        $ord_hora = date('H:i:s', time());
        $ord_numero = $this->input->post('ord_numero');
        $ord_referencia= $this->input->post('ord_referencia');
                    $ord_cod_unidad= $this->input->post('ord_cod_unidad');
                    $ord_nombre_unidad= $this->input->post('ord_nombre_unidad');
                    $ord_unidad_ejecutora= $this->input->post('ord_unidad_ejecutora');
                    $ord_proyecto = null;
                    $ord_subproyecto = null;
//                    $ord_gasto_og,
//                    $ord_iva,
//                    $ord_total,
//                    $ord_user_id,
//                    $ord_user_revision,
//                    $ord_user_relator,
//                    $ord_user_aprobacion,
//                    $ord_estado,
//                    $ord_observaciones
    }

}
