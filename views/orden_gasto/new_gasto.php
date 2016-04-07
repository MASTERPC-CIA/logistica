<?php
date_default_timezone_set('America/Guayaquil');
$this->load->view('common/hmc_head/encabezado_cuenca'); //Encabezado para el hospital militar

echo Open('div', array('id' => 'orden_out'));
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
                $res['iva'] = $iva;
                $res['gasto_og'] = $gasto_og;
                $res['total'] = $total;
                $this->load->view('orden_gasto/detalle');
    //            echo $contenido;
            echo Close('div');
    echo Close('div');
echo Close('div');//content_detalle_orden
/*FIRMAS PIE*/
echo LineBreak();
$this->load->view('orden_gasto/footer', $footer_data);
echo Close('div');
echo Close('div');//Cierra ordengasto_view
echo Close('form');
echo Close('div');//Div orden_out

//DIV para mensajes
echo tagcontent('div', '', array('id'=>'msj_out'));
?>
<script>
    var count_partidas = 0;
    /*Cargamos los datos de la partida digitada*/
    var load_partida = function (datum) {
        //        console.log(datum);
        id_partida = datum.id;
        cod_partida = datum.ci;
        programa_id = $('#combo_programas').val();
        subtarea_id = $('#combo_subtareas').val();
        //Validamos que haya seleccionado los combobox para que script no de error
        if (programa_id == null) {
            alertaError('No ha seleccionado un programa');
        } else if (subtarea_id != datum.parent) {//Validamos que la partida pertenezca a la subtarea
            alertaError('Esta partida no pertenece a la Subtarea seleccionada');
        } else {
            //Verificamos si ya existe una misma partida para restar el presupuesto
            presupuesto = null;
            $('.ids_partida').each(function(index, value){
                id_partida_listada = $(this).val();
                if(id_partida == id_partida_listada){
                    //Tomamos el ultimo valor del saldo vigente como presupuesto
                    count = $(this).attr('count');
                    saldo_vigente_anterior = $('#hidden_saldo_vigente'+count).val();
                    //Enviamos este valor como nuevo presupuesto
                    presupuesto = saldo_vigente_anterior;
                }
            });
            $.ajax({
                type: "POST",
                url: "ordengasto/loadDetalleView",
                dataType: 'html',
                data: {id_partida: id_partida, cod_partida: cod_partida, 
                    programa_id: programa_id, count_partidas: (parseInt(count_partidas) + 1),
                    presupuesto: presupuesto},
                success: function (row) {
                    $('#partida_detalle').append(row);
                    $('#partida_id').text('');
                    count_partidas++;
                }
            });
        }
    };
    $.autosugest_search('#partida_id');
</script>