<?php
echo tagcontent('div', '', array('id' => 'orden_out'));
date_default_timezone_set('America/Guayaquil');
$this->load->view('common/hmc_head/encabezado_cuenca'); //Encabezado para el hospital militar

echo Open('form', array('id' => 'form_examen', 'action' => base_url('logistica/ordengasto/save_new'), 'method' => 'post'));
$res['areas_list'] = $areas_list;
/*HEADER*/
$this->load->view('orden_gasto/header', $res);
echo Open('div', array('id'=>'ordengasto_view'));
//echo lineBreak2(2, array('class'=>'clear'));
//echo Open('div', array('id'=>'content_detalle_orden'));
echo Open('div', array('id'=>'content_detalle_orden', 'style'=>'display:none;'));
    echo Open('div', array('class' => 'panel panel-info', 'id' => 'respuesta_out_solic'));
    //Panel header
        echo Open('div', array('class' => 'panel panel-heading form-inline'));
            echo tagcontent('div', '<h4>Detalle de Orden de Gasto</h4>', array('class'=>'col-md-10'));
            echo tagcontent('button', '<span class=""></span>GUARDAR', array('id' => 'ajaxformbtn', 'class' => 'btn btn-success', 'data-target' => 'msj_out'));
        echo Close('div');
        //Panel content
            echo Open('div', array('class' => 'panel panel-body', 'id' => 'form_content'));
            /*DETALLE*/
                $this->load->view('orden_gasto/detalle');
    //            echo $contenido;
            echo Close('div');
    echo Close('div');
echo Close('div');//content_detalle_orden
/*FIRMAS PIE*/
echo Open('div', array('class' => 'col-md-12'));
echo Open('div', array('class' => 'input-group'));
echo tagcontent('span', 'ELABORADO POR: ', array('class' => 'input-group-addon'));
echo input(array('name' => 'elaborado_por', 'id' => 'elaborado_por', 'readonly'=>'',
    'class' => 'form-control input-sm', 'style' => 'width:100%', 'value'=>$this->user->nombres));
//echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
//        array('name' => 'elaborado_por', 'id' => 'elaborado_por', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
//        false, $this->user->id);
echo tagcontent('span', 'REVISADO POR: ', array('class' => 'input-group-addon'));
echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
        array('name' => 'revisado_por', 'id' => 'revisado_por', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
        true);
echo tagcontent('span', 'RELATOR DEL PB: ', array('class' => 'input-group-addon'));
echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
        array('name' => 'relator_pb', 'id' => 'relator_pb', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
        true);
echo tagcontent('span', 'APROBADO POR: ', array('class' => 'input-group-addon'));
echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
        array('name' => 'aprobado_por', 'id' => 'aprobado_por', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
        true);
echo Close('div');
echo Close('div');
echo Close('div');//Cierra ordengasto_view
echo Close('form');

//DIV para mensajes
echo tagcontent('div', '', array('id'=>'msj_out'));