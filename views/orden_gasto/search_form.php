<?php
    echo '<h3 class="page-header"><small><font class="text-success"><span class="glyphicon glyphicon-share"></span> BUSCAR ORDENES DE GASTO</font></small></h3>';

    echo Open('form', array('action' => base_url('logistica/ordengasto/search'), 'method' => 'post'));
    //Rango Fechas
    echo Open('div',array('class'=>'col-md-5 form-group'));
        echo Open('div',array('class'=>'input-group has-success'));
            echo tagcontent('span', '<span class="glyphicon glyphicon-calendar"></span> Fecha Creacion: ', array('class'=>'input-group-addon'));
            echo input(array('name'=>"fechaIn",'id'=>"fechaIn", 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
            echo input(array('name'=>"fechaFin",'id'=>"fechaFin", 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div'); 
        
    //Realizado por
    $lista_empleado_combo = combobox($lista_empleado, array('label' => 'nom_empleado', 'value' => 'id'), array('name' => 'profesional_id', 'id' => 'profesional_id', 'class' => 'combobox form-control input-sm'), true);
    echo Open('div', array('class' => 'col-md-5 form-group'));
        echo Open('div', array('class' => 'input-group has-success'));
            echo tagcontent('span', '<span class="glyphicon glyphicon-user"></span> Realizado por:', array('class' => 'input-group-addon'));
            echo $lista_empleado_combo; 
        echo Close('div');
    echo Close('div');
        
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('name' => 'btnreportes', 'class' => 'btn btn-success btn-sm  col-md-1', 'id' => 'ajaxformbtn', 'type' => 'submit', 'data-target' => 'ordenes_list'));
    
    echo Close('form');