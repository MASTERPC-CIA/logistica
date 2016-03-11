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

    /* Guarda una nueva orden de gasto y su detalle */

    function save($array_partidas, $ord_fecha, $ord_hora, $ord_numero, $ord_referencia, $ord_cod_unidad, $ord_nombre_unidad, $ord_unidad_ejecutora, $ord_proyecto, $ord_subproyecto, $ord_gasto_og, $ord_iva, $ord_total, $ord_user_id, $ord_user_revision, $ord_user_relator, $ord_user_aprobacion, $ord_estado = 1, $ord_observaciones = null) {
        $data = array(
            'ord_fecha' => $ord_fecha,
            'ord_hora' => $ord_hora,
            'ord_numero' => $ord_numero,
            'ord_referencia' => $ord_referencia,
            'ord_cod_unidad' => $ord_cod_unidad,
            'ord_nombre_unidad' => $ord_nombre_unidad,
            'ord_unidad_ejecutora' => $ord_unidad_ejecutora,
            'ord_proyecto' => $ord_proyecto,
            'ord_subproyecto' => $ord_subproyecto,
            'ord_gasto_og' => $ord_gasto_og,
            'ord_iva' => $ord_iva,
            'ord_total' => $ord_total,
            'ord_user_id' => $ord_user_id,
            'ord_user_revision' => $ord_user_revision,
            'ord_user_relator' => $ord_user_relator,
            'ord_user_aprobacion' => $ord_user_aprobacion,
            'ord_estado' => $ord_estado,
            'ord_observaciones' => $ord_observaciones,
        );

        //Guardamos en tabla orden_gasto
        $id_orden = $this->generic_model->save($data, 'orden_gasto');
        //retornamos -1 para hacer las validaciones en el controlador
        if ($id_orden == '-1') {
            return $id_orden;
        }
        $id_detalle = $this->saveDetalle($array_partidas, $id_orden);
        if ($id_detalle == '-1') {
            echo error_msg('<br>Error al guardar cabecera de orden.');
            return $id_detalle;
        }
        return $id_orden;
    }

    /* Guarda el detalle de cada partida que pertenece a una orden de gasto */

    function saveDetalle($array_partidas, $id_orden) {
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
            if ($id_detalle == '-1') {
                echo error_msg('<br>Error al guardar detalle.');
                return $id_detalle;
            }
        }
    }

    /* Extrae los datos correspondientes de una orden de gasto */

    function getData($orden_id) {
        $fields = 'orden_gasto.*,' //Necesitamos todos los datos
                .'CONCAT_WS(" ", emp_realiz.nombres, emp_realiz.apellidos) realizado_por,'
                .'CONCAT_WS(" ", emp_aprob.nombres, emp_aprob.apellidos) aprobado_por,'
                .'CONCAT_WS(" ", emp_relat.nombres, emp_relat.apellidos) relator_pb,'
                .'CONCAT_WS(" ", emp_revis.nombres, emp_revis.apellidos) revisado_por'
                ;
        $where = array('orden_gasto.id' => $orden_id);
        $join_clause = array();
        
        $join_clause[] = array('table' => 'billing_empleado emp_realiz', 'condition' => 'ord_user_id = emp_realiz.id');
        $join_clause[] = array('table' => 'billing_empleado emp_aprob', 'condition' => 'ord_user_aprobacion = emp_aprob.id');
        $join_clause[] = array('table' => 'billing_empleado emp_revis', 'condition' => 'ord_user_revision = emp_revis.id');
        $join_clause[] = array('table' => 'billing_empleado emp_relat', 'condition' => 'ord_user_relator = emp_relat.id');
        
        $orden_data = $this->generic_model->get_join('orden_gasto', $where, $join_clause, $fields, 1);

        return $orden_data;
    }

    /* Extrae las partidas del detalle de una orden de gasto */

    function getDetalle($orden_id) {
        $fields = 'orden_gasto_detalle.*, '
                . 'partida.cod cod_partida,'
                . 'CONCAT_WS(" ", emp.nombres, emp.apellidos) empleado_nombres,'
                . 'emp.PersonaComercio_cedulaRuc empleado_ruc,'
                . 'ben_nombre beneficiario_nombres,'
                . 'ben_ruc beneficiario_ruc,'
                ;
        $where = array('odet_orden_id' => $orden_id);
        $join = array();
        
        $join[] = array('table' => 'plan_proyectos partida', 'condition' => 'odet_partida_id = partida.id');
        $join[] = array('table' => 'billing_empleado emp', 'condition' => 'odet_empleado_id = emp.id', 'type'=>'left');
        $join[] = array('table' => 'beneficiario_partidas ben', 'condition' => 'odet_beneficiario_id = ben.id', 'type'=>'left');
        $partidasDetalle = $this->generic_model->get_join('orden_gasto_detalle', 
                $where, $join, $fields);

        return $partidasDetalle;
    }

    /* Extrae el id, cod y nombre del padre de una cuenta del plan de proyectos */

    function getParent($cta_id) {
        $fields = 'id, cod, nombre, parent';
        $parent_id = $this->generic_model->get_val_where('plan_proyectos', array('id' => $cta_id), 'parent');
        $parent = $this->getCtaData($parent_id, $fields);

        return $parent;
    }

    /* Extrae los datos de una cuenta del plan de proyectos a partir de su id */

    function getCtaData($cta_id, $fields = '') {

        $cta_data = $this->generic_model->get_data('plan_proyectos', array('id' => $cta_id), $fields, null, 1);

        return $cta_data;
    }

}
