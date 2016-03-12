<?php
echo tagcontent('h3', 'Editando detalle de orden N. '.$orden_data->ord_numero);
echo Open('div', array('id'=>'edit_orden'));
echo Open('form', array('id' => 'form_examen', 'action' => base_url('logistica/ordengasto/editOrden'), 'method' => 'post'));
    echo input(array('type'=>'hidden', 'id'=>'orden_id', 'name'=>'orden_id', 'value'=>$orden_id));
    /*Cargamos la misma vista del detalle de partidas, 
     * enviamos la peticion de editar*/
    $res['editar_orden'] = 1;
    $res['detalle'] = $detalle;
    $res['iva'] = $orden_data->ord_iva;
    $res['gasto_og'] = $orden_data->ord_gasto_og;
    $res['total'] = $orden_data->ord_total;
    $res['subtarea_id'] = $orden_data->ord_subtarea_id;
    $this->load->view('orden_gasto/detalle', $res);
    echo tagcontent('button', 'Guardar Modificacion', array('class'=>'btn btn-primary','id'=>'ajaxformbtn','data-target'=>'edit_orden'));
echo Close('form');
echo Close('div');
?>