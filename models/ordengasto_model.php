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

    function save($array_partidas, $ord_fecha, $ord_hora, $ord_numero, $ord_referencia, $ord_cod_unidad, $ord_nombre_unidad, $ord_unidad_ejecutora, $ord_proyecto, $ord_subproyecto, $ord_subtarea_id, $ord_gasto_og, $ord_iva, $ord_total, $ord_user_id, $ord_user_revision, $ord_user_relator, $ord_user_aprobacion, $ord_estado = 1, $ord_observaciones = null) {
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
            'ord_subtarea_id' => $ord_subtarea_id,
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
        if ($id_orden == -1) {
            echo error_info_msg(' Error al guardar cabecera de la orden de gasto.');
            return $id_orden;
        }
        $id_detalle = $this->saveDetalle($array_partidas, $id_orden);
        if ($id_detalle == -1) {
            return -1;
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
            if ($id_detalle == -1) {
                echo error_info_msg(' Error al guardar detalle.');
                return -1;
            }
            //Enviamos el valor en negativo porque debe restarse del saldo
            $updateSaldo = $this->updatePresupuesto($partida['odet_partida_id'], ($partida['odet_gasto']*-1));
            //Si ocurrio no se actualizo el saldo por saldos insuficientes
            if ($updateSaldo == -1) {
                return -1;
            }
            $updateParent = $this->updateParents($partida['odet_partida_id'], ($partida['odet_gasto']*-1));
            //Si ocurrio no se actualizo el saldo por saldos insuficientes
            if ($updateParent == -1) {
                return -1;
            }
        }
    }
    
     
    /*Actualiza una orden de gasto y su detalle*/
    function update($array_partidas, $orden_id, $ord_gasto_og, $ord_iva, $ord_total, $user_aprobacion, $user_revision, $user_relator,  $observaciones = null){
        $data = array(
            'ord_gasto_og' => $ord_gasto_og,
            'ord_iva' => $ord_iva,
            'ord_total' => $ord_total,
            'ord_user_edit' => $this->user->id,
            'ord_fecha_edicion' => date('Y-m-d', time()),
            'ord_hora_edicion' => date('H:i:s', time()),
            'ord_user_aprobacion' => $user_aprobacion,
            'ord_user_revision' => $user_revision,
            'ord_user_relator' => $user_relator,
            'ord_observaciones' => $observaciones,
        );

        //Actualizamos en tabla orden_gasto
        $id_orden = $this->generic_model->update_by_id('orden_gasto', $data, $orden_id);
        //retornamos -1 para hacer las validaciones en el controlador
        if ($id_orden == -1) {
            echo error_info_msg(' Error al guardar cabecera de la orden de gasto.');
            return $id_orden;
        }
        $id_detalle = $this->updateDetalle($array_partidas, $id_orden);
        if ($id_detalle == -1) {
            return -1;
        }
        return $id_orden;
    }
    
     /* Actualiza el detalle de cada partida que pertenece a una orden de gasto */

    function updateDetalle($array_partidas) {
        //Primer foreach recorre el numero de partidas
        foreach ($array_partidas as $partida) {
            $array_detalle = array(
                'odet_empleado_id' => $partida['odet_empleado_id'],
                'odet_beneficiario_id' => $partida['odet_beneficiario_id'],
                'odet_concepto' => $partida['odet_concepto'],
                'odet_comprobante_num' => $partida['odet_comprobante_num'],
                'odet_gasto' => $partida['odet_gasto'],
                'odet_asignacion' => $partida['odet_asignacion'],
                'odet_gasto_acumulado' => $partida['odet_gasto_acumulado'],
                'odet_saldo_vigente' => $partida['odet_saldo_vigente'],
            );
//            switch ($partida['odet_tipo_beneficiario']) {
//                case 'E':
//                    $array_detalle['odet_empleado_id'] = $partida['odet_beneficiario_id'];
//                    break;
//                case 'B':
//                    $array_detalle['odet_beneficiario_id'] = $partida['odet_beneficiario_id'];
//                    break;
//                default:
//                    break;
//            }
            
            $this->generic_model->update_by_id('orden_gasto_detalle', $array_detalle, $partida['id']);
            //Calculamos la diferencia para actualizar dicho valor que puede ser positivo o negativo
            $diferencia = $partida['valor_anterior'] - $partida['odet_gasto'];
            $updateSaldo = $this->updatePresupuesto($partida['odet_partida_id'], $diferencia);
            //Si ocurrio no se actualizo el saldo por saldos insuficientes
            if ($updateSaldo == -1) {
                return -1;
            }
            $updateParent = $this->updateParents($partida['odet_partida_id'], $diferencia);
            //Si ocurrio no se actualizo el saldo por saldos insuficientes
            if ($updateParent == -1) {
                return -1;
            }
        }
    }
    
    /* Anula una orden de gasto y devuelve sus valores al plan de proyectos*/
    function anular($orden_id, $observaciones) {
        //Enviamos a anular en orden_detalle
        $data = array('ord_estado'=>'-1', 'ord_observaciones'=>$observaciones);
        $this->generic_model->update_by_id('orden_gasto', $data, $orden_id);
        
        //Extraemos los ids de las partidas de detalle
        $fields = 'odet_partida_id id, odet_gasto valor';
        $partidas = $this->generic_model->get_data('orden_gasto_detalle', array('odet_orden_id'=>$orden_id), $fields);
        
        foreach ($partidas as $partida) {
            //Actualizamos la cuenta del plan
            $updateSaldo = $this->updatePresupuesto($partida->id, $partida->valor);
             if ($updateSaldo == -1) {
                return -1;
            }
            //Actualizamos los parentes de la cuenta
            $updateParent = $this->updateParents($partida->id, $partida->valor);
            if ($updateParent == -1) {
                return -1;
            }
        }
    }

    /* Actualiza el valor del presupuesto vigente de cada partida en el plan de proyectos */

    // El parametro 'valor' en negativo determina que se va a debitar del presupuesto
    function updatePresupuesto($partida_id, $valor) {
        $presupuestoActual = $this->generic_model->get_val_where('plan_proyectos', array('id' => $partida_id), 'presupuesto_vigente');
        $nuevoPresupuesto = $presupuestoActual + $valor;

        if ($nuevoPresupuesto < 0) {
            echo error_info_msg(' No hay suficiente presupuesto en la cuenta');
            return -1;
        }
        $this->generic_model->update_by_id('plan_proyectos', array('presupuesto_vigente' => $nuevoPresupuesto), $partida_id);
    }

    /* Actualiza el presupuesto de los padres de la partida */

    function updateParents($partida_id, $valor) {
        $parent_data = $this->getParent($partida_id, 'id');

        while (!empty($parent_data)) {
            $updateParent = $this->updatePresupuesto($parent_data->id, $valor);
            if ($updateParent == -1) {
                return -1;
            }
            $parent_data = $this->getParent($parent_data->id, 'id');
        }
    }

    /* Extrae los datos correspondientes de una orden de gasto */

    function getData($orden_id) {
        $fields = 'orden_gasto.*,' //Necesitamos todos los datos
                . 'CONCAT_WS(" ", emp_realiz.nombres, emp_realiz.apellidos) realizado_por,'
                . 'CONCAT_WS(" ", emp_aprob.nombres, emp_aprob.apellidos) aprobado_por,'
                . 'CONCAT_WS(" ", emp_relat.nombres, emp_relat.apellidos) relator_pb,'
                . 'CONCAT_WS(" ", emp_revis.nombres, emp_revis.apellidos) revisado_por'
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
        $join[] = array('table' => 'billing_empleado emp', 'condition' => 'odet_empleado_id = emp.id', 'type' => 'left');
        $join[] = array('table' => 'beneficiario_partidas ben', 'condition' => 'odet_beneficiario_id = ben.id', 'type' => 'left');
        $partidasDetalle = $this->generic_model->get_join('orden_gasto_detalle', $where, $join, $fields);

        return $partidasDetalle;
    }

    /* Extrae el id, cod y nombre del padre de una cuenta del plan de proyectos */

    function getParent($cta_id, $fields = 'id, cod, nombre, parent') {
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
