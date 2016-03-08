<?php
echo tagcontent('div', '', array('id' => 'orden_out'));
date_default_timezone_set('America/Guayaquil');
$this->load->view('common/hmc_head/encabezado_cuenca'); //Encabezado para el hospital militar

echo Open('form', array('id' => 'form_examen', 'action' => base_url('logistica/orgen_gasto/save_new'), 'method' => 'post'));
$res['areas_list'] = $areas_list;
/*HEADER*/
$this->load->view('orden_gasto/header', $res);
//echo lineBreak2(2, array('class'=>'clear'));
echo Open('div', array('id'=>'content_detalle_orden'));
//echo Open('div', array('id'=>'content_detalle_orden', 'style'=>'display:none;'));
    echo Open('div', array('class' => 'panel panel-info', 'id' => 'respuesta_out_solic'));
        echo Open('div', array('class' => 'panel panel-heading form-inline'));
            echo '<h4>Detalle de Orden de Gasto</h4>';
        echo Close('div');
            echo Open('div', array('class' => 'panel panel-body', 'id' => 'form_content'));
            /*DETALLE*/
                $this->load->view('orden_gasto/detalle');
    //            echo $contenido;
            echo Close('div');
    echo Close('div');
echo Close('div');//content_detalle_orden
echo Close('form');