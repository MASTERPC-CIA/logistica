<?php
class Ordengasto_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

//Permite obtener el último registro de la base de datos
    function get_last() {
        $this->db->select('ord_numero');
        $this->db->from('orden_gasto');
        $this->db->where('ord_estado !=', '-1');
        $this->db->order_by('ord_numero', 'DESC');

        $this->db->limit(1);
        $query = $this->db->get();
        if (!empty($query->row()->ord_numero)) {
            return $query->row()->ord_numero;
        } else {
            return;
        }
    }
    /*Guarda una nueva orden de gasto y su detalle*/
    function save($array_partidas, $ord_fecha,
                    $ord_hora,
                    $ord_numero,
                    $ord_referencia,
                    $ord_cod_unidad,
                    $ord_nombre_unidad,
                    $ord_unidad_ejecutora,
                    $ord_proyecto,
                    $ord_subproyecto,
                    $ord_gasto_og,
                    $ord_iva,
                    $ord_total,
                    $ord_user_id,
                    $ord_user_revision,
                    $ord_user_relator,
                    $ord_user_aprobacion,
                    $ord_estado = 1,
                    $ord_observaciones = null) 
    {
        $data = array(
            'ord_fecha' => $ord_fecha,
            'ord_hora'=>$ord_hora,
            'ord_numero'=>$ord_numero,
            'ord_referencia'=>$ord_referencia,
            'ord_cod_unidad'=>$ord_cod_unidad,
            'ord_nombre_unidad'=>$ord_nombre_unidad,
            'ord_unidad_ejecutora'=>$ord_unidad_ejecutora,
            'ord_proyecto'=>$ord_proyecto,
            'ord_subproyecto'=>$ord_subproyecto,
            'ord_gasto_og'=>$ord_gasto_og,
            'ord_iva'=>$ord_iva,
            'ord_total'=>$ord_total,
            'ord_user_id'=>$ord_user_id,
            'ord_user_revision'=>$ord_user_revision,
            'ord_user_relator'=>$ord_user_relator,
            'ord_user_aprobacion'=>$ord_user_aprobacion,
            'ord_estado'=>$ord_estado,
            'ord_observaciones'=>$ord_observaciones,
        );
        
        //Guardamos en tabla orden_gasto
        $id_orden = $this->generic_model->save($data, 'orden_gasto');
        //retornamos -1 para hacer las validaciones en el controlador
        if($id_orden == '-1'){
            return $id_orden;
        }
        $id_detalle =$this->saveDetalle($array_partidas, $id_orden);
        if($id_detalle == '-1'){
            echo error_msg('<br>Error al guardar cabecera de orden.');
            return $id_detalle;
        }
        return $id_orden;
    }
    
    /*Guarda el detalle de cada partida que pertenece a una orden de gasto*/
    function saveDetalle($array_partidas, $id_orden){
        $array_detalle = array();
        //Primer foreach recorre el numero de partidas
        foreach ($array_partidas as $partida) {
            $partida['odet_orden_id'] = $id_orden;
//            print_r($partida);
            //Segundo foreach recorre la data de cada partida
            foreach ($partida as $key => $value) {
                $array_detalle[$key] = $value;
            }
            $id_detalle = $this->generic_model->save($array_detalle, 'orden_gasto_detalle');
            if($id_detalle == '-1'){
                echo error_msg('<br>Error al guardar detalle.');
                return $id_detalle;
            }
        }
    }
}