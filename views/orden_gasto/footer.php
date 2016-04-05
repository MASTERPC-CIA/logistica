<?php
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
        true, $revisado_por);
echo tagcontent('span', 'RELATOR DEL PB: ', array('class' => 'input-group-addon'));
echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
        array('name' => 'relator_pb', 'id' => 'relator_pb', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
        true, $relator);
echo tagcontent('span', 'APROBADO POR: ', array('class' => 'input-group-addon'));
echo combobox($empleados, array('label' => 'nombres', 'value' => 'id'), 
        array('name' => 'aprobado_por', 'id' => 'aprobado_por', 'class' => 'form-control input-sm', 'style' => 'width:100%'), 
        true, $aprobado_por);
echo Close('div');
echo Close('div');