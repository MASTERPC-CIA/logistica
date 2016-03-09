<?php

echo tagcontent('h3', 'ORDEN DE GASTO', array('style'=>'font-weight:900;text-align:center;'));
echo tagcontent('h4', 'Para uso de las direcciones y unidades ejectutoras', array('style'=>'text-align:center;'));
echo lineBreak2(1);
echo Open('div');
    echo Open('table', array('id'=>'bloque1_ordengasto', 'class'=>'table table-striped'));
    
        echo Open('tr');
            echo tagcontent('td ROWSPAN=2  align="center"', 'FECHA',array('style'=>'font-weight:900'));
            echo tagcontent('td ROWSPAN=2', input(array('name' => "ord_fecha", 'value' => date('Y-m-d', time()), 'id' => "fecha_toma", 'type' => "text", 'class' => "form-control input-sm datepicker")));
            echo tagcontent('td ROWSPAN=2  align="center"', 'REFERENCIA',array('style'=>'font-weight:900'));
            echo tagcontent('td ROWSPAN=2', input(array('id'=>'ord_referencia','name'=>'ord_referencia', 'type'=>'text', 'value'=>'')));
            echo tagcontent('td', 'ORDEN DE GASTO N.',array('style'=>'font-weight:900'));
            echo tagcontent('td', input(array('name' => 'ord_numero', 'id' => 'ord_numero', 'readonly'=>'',
                'class' => 'form-control input-sm', 'style' => 'width:100%', 'value'=>$secuencia)));
        echo Close('tr');
            echo input(array('type'=>'hidden', 'name'=>'ord_nombre_unidad', 'value'=>get_settings("UNIDAD_OPERATIVA")));
            echo tagcontent('td', 'NOMBRE DE LA UNID.:',array('style'=>'font-weight:900'));
            echo tagcontent('td', get_settings("UNIDAD_OPERATIVA"));
        
    echo Close('table');
echo Close('div');
//Solo el combobox inicial tiene valores, los demas se llenan via ajax
 $combo_areas = combobox(
        $areas_list, 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_areas', 'class' => 'form-control', 'id'=>'combo_areas'),
        true);
 $combo_programas = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_programas', 'class' => 'form-control', 'id'=>'combo_programas'),
        false);
 $combo_actividades = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_actividades', 'class' => 'form-control', 'id'=>'combo_actividades'),
        false);
 $combo_subactividades = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_subactividades', 'class' => 'form-control', 'id'=>'combo_subactividades'),
        false);
 $combo_tareas = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_tareas', 'class' => 'form-control', 'id'=>'combo_tareas'),
        false);
 $combo_subtareas = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_subtareas', 'class' => 'form-control', 'id'=>'combo_subtareas'),
        false);
 $combo_proyectos = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_proyectos', 'class' => 'form-control', 'id'=>'combo_proyectos'),
        false);
 $combo_subproyectos = combobox(
        array(), 
        array('label' => 'nombre', 'value' => 'id'), 
        array('name' => 'combo_subproyectos', 'class' => 'form-control', 'id'=>'combo_subproyectos'),
        false);
//echo get_combo_group('Area Funcional', $combo_areas, 'col-md-4 form-group');
echo Open('div');
    echo Open('table', array('id'=>'bloque2_ordengasto', 'class'=>'table table-striped'));
        echo Open('tr');
//            echo tagcontent('td', 'AREA FUNCIONAL',array('style'=>'font-weight:900'));
            echo tagcontent('td', get_combo_group('AREA FUNCIONAL', $combo_areas, 'col-md-12 form-group'));
            echo tagcontent('td', 'UNIDAD',array('style'=>'font-weight:900'));
            echo tagcontent('td', get_settings("COD_UNIDAD"));
            echo input(array('type'=>'hidden', 'name'=>'ord_cod_unidad', 'value'=>get_settings("COD_UNIDAD")));
            echo tagcontent('td', 'UNIDAD EJECUTORA',array('style'=>'font-weight:900'));
            echo tagcontent('td', get_settings("UNIDAD_EJECUTORA"));
            echo input(array('type'=>'hidden', 'name'=>'ord_unidad_ejecutora', 'value'=>get_settings("UNIDAD_EJECUTORA")));
            
        echo Close('tr');
        echo Open('tr');
            echo tagcontent('td', get_combo_group('PROGRAMA', $combo_programas, 'col-md-12 form-group'));
//            echo tagcontent('td COLSPAN=2', get_combo_group('PROYECTO', $combo_proyectos, 'col-md-12 form-group'));
            echo tagcontent('td', 'PROYECTO',array('style'=>'font-weight:900'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr');
            echo tagcontent('td', get_combo_group('ACTIVIDAD', $combo_actividades, 'col-md-12 form-group'));
//            echo tagcontent('td COLSPAN=2', get_combo_group('SUBPROYECTO', $combo_subproyectos, 'col-md-12 form-group'));
            echo tagcontent('td', 'SUBPROYECTO',array('style'=>'font-weight:900'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr');
            echo tagcontent('td', get_combo_group('SUBACTIVIDAD', $combo_subactividades, 'col-md-12 form-group'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr');
            echo tagcontent('td', get_combo_group('TAREA', $combo_tareas, 'col-md-12 form-group'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr');
            echo tagcontent('td', get_combo_group('SUBTAREA', $combo_subtareas, 'col-md-12 form-group'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
    echo Close('table');
echo Close('div');
?>
<script>
    //Funcion para actualizar el combobox hijo
    //Se envia el subject y el tipo del hijo
    $('#combo_areas').change(function(){
        //Extrae los programas: 2
        var idParent = $(this).val();
        var subjectChildren = 'programas';
        var tipo = 2;
        getChildrens(idParent, subjectChildren, tipo);
    });
    $('#combo_programas').change(function(){
        //Extrae las actividades: 3
        var idParent = $(this).val();
        var subjectChildren = 'actividades';
        var tipo = 3;
        getChildrens(idParent, subjectChildren, tipo);
    });
    $('#combo_actividades').change(function(){
        //Extrae las subactividades: 4
        var idParent = $(this).val();
        var subjectChildren = 'subactividades';
        var tipo = 4;
        getChildrens(idParent, subjectChildren, tipo);
    });
    $('#combo_subactividades').change(function(){
        //Extrae las tareas: 5
        var idParent = $(this).val();
        var subjectChildren = 'tareas';
        var tipo = 5;
        getChildrens(idParent, subjectChildren, tipo);
    });
    $('#combo_tareas').change(function(){
        //Extrae las subtareas: 6
        var idParent = $(this).val();
        var subjectChildren = 'subtareas';
        var tipo = 6;
        getChildrens(idParent, subjectChildren, tipo);
    });
    $('#combo_subtareas').change(function(){
        //Muestra el div content_detalle_orden
        $('#content_detalle_orden').show(1000);
    });
    
    //Extrae los hijos del plan de programs, el subject se utiliza como nombre del combobox
    function getChildrens(idParent, subjectChildren, $tipo){
        var url = main_path+'logistica/ordengasto/getChildrens/'+idParent+'/'+$tipo;
            $.ajax({
                type: "POST",
                url: url,
//                data: { id: estudios.id,nombre: estudios.nombre},   
                dataType: 'json',
                success: function(list){
                    $('#combo_'+subjectChildren).html(null);
                    //Obligamos a seleccionar una opcion para cargar el siguiente combobox:
                    $('#combo_'+subjectChildren).append(
                        $('<option></option>').val('-1').html('-- Seleccionar')
                    );
                    $.each(list, function(id, item){
                        $('#combo_'+subjectChildren).append(
                            $('<option></option>').val(item.id).html(item.nombre)
                        );
                    });
                },
                error: function(){
                    //alertaError("Error!! No se pudo alcanzar el archivo de proceso", "Error!!");
                }              
            });
    }
</script>