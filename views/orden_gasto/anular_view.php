<?php
echo LineBreak(2);
echo warning_msg(' Est&aacute; a punto de anular la Orden de Gasto');
echo Open('div', array('id'=>'anular_orden'));

    echo Open('form', array('action' => base_url('logistica/ordengasto/anularOrden'), 'method' => 'post'));
        echo input(array('type' => 'hidden', 'value' => $orden_id, 'name' => 'id_orden'));
        //echo $planilla_id;
        echo tagcontent('textarea', '', array('name'=>'observaciones','class'=>'form-control','maxlength'=>'500','placeholder'=>'Detalle de la Anulaci&oacute;n'));
        echo LineBreak(2);
        echo tagcontent('button', '<span class="glyphicon glyphicon-trash"></span> Confirmar Anulacion', 
                array('title' => 'Anular Orden', 'name' => 'btnreportes', 'class' => 'btn btn-danger pull-left  btn-sm', 
                    'id' => 'ajaxformbtn', 'type' => 'submit', 'data-target' => 'anular_orden'));
        echo lineBreak2(1, array('style'=>'clear:both'));    
    echo Close('form');
echo Close('div');
