<?php
    echo '<h3 class="page-header"><small><font class="text-success"><span class="glyphicon glyphicon-share"></span> BUSCAR PRESUPUESTO POR TIPO Y AREA</font></small></h3>';

    echo Open('form', array('action' => base_url('logistica/presupuesto/search'), 'method' => 'post'));
    //Combobox area
    $areas_combo = combobox($areas_list, array('label' => 'nombre', 'value' => 'cod'), array('name' => 'select_area', 'id' => 'select_area', 'class' => 'combobox form-control input-sm'), true);
    echo Open('div', array('class' => 'col-md-5 form-group'));
        echo Open('div', array('class' => 'input-group has-success'));
            echo tagcontent('span', '<span class="glyphicon glyphicon-th-large"></span> Area:', array('class' => 'input-group-addon'));
            echo $areas_combo; 
        echo Close('div');
    echo Close('div');
        
    //Combobox tipo
    $tipos_combo = combobox($tipos_list, array('label' => 'tipo', 'value' => 'id'), array('name' => 'select_tipo', 'id' => 'select_tipo', 'class' => 'combobox form-control input-sm'), true);
    echo Open('div', array('class' => 'col-md-5 form-group'));
        echo Open('div', array('class' => 'input-group has-success'));
            echo tagcontent('span', '<span class="glyphicon glyphicon-list-alt"></span>&nbsp Tipo:', array('class' => 'input-group-addon'));
            echo $tipos_combo; 
        echo Close('div');
    echo Close('div');
        
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('name' => 'btnreportes', 'class' => 'btn btn-success btn-sm  col-md-1', 'id' => 'ajaxformbtn', 'type' => 'submit', 'data-target' => 'proyectos_list'));
    
    echo Close('form');