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
        $this->db->order_by('ord_estado', 'DESC');

        $this->db->limit(1);
        $query = $this->db->get();
        if (!empty($query->row()->ord_estado)) {
            return $query->row()->ord_estado;
        } else {
            return;
        }
    }
    function save($ord_fecha,
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
                    $ord_estado,
                    $ord_observaciones
) 
    {
        $data = array();
    }
}