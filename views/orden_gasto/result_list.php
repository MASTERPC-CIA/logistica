<?php
/*Boton Imprimir*/
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', 
        array('id' => 'printbtn', 'data-target' => 'div_ordenes_list', 'class' => 'btn btn-default pull-left'));
/* METODO PARA EXPORTA A EXCEL */
//echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', 
//        array('href' => base_url('laboratorio/index/export_solicitudes_to_excel/'. $id_servicio. '/' . $id_tipo_form .'/'.$paciente_tipo.'/' . $fecha_emision_desde . '/' . $fecha_emision_hasta. '/' . $fecha_realiz_desde. '/' . $fecha_realiz_hasta. '/' . $id_prof_solicita. '/' . $id_paciente), 
//            'method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm'));

//print_r($data);
echo Open('div', array('class' => 'col-md-12'));
$caja_texto = '<input type="text" id="search" placeholder="Ingrese valor a buscar">';
 echo '<div class="panel panel-success">';
    echo '<div class="panel-heading">LISTADO DE '.$subject.' --- NUMERO DE REGISTROS ENCONTRADOS : N° '.$num_reg.''
            . '<font color=white>..............................................................................'
            . '...............................................................</font>'.$caja_texto.'</div>';
        echo '<div class="panel-body">';
            echo '<div id="div_ordenes_list">';
             //Div para mostrar el logo en la cabecera para imprimir
            echo Open('div',  array('id'=>'div_header', 'style'=>''));
                $this->load->view('common/hmc_head/encabezado_cuenca');
            echo Close('div');
            echo Open('table', array('id'=>'table','class' => "table table-fixed-header logistica_report"));
                echo '<thead>';
                    echo '<th class="actions" style="text-align:center">ID</th>';
                    echo '<th>NUMERO</th>';
                    echo '<th>VALOR</th>';
                    echo '<th>REALIZADO POR</th>';
                    echo '<th>APROBADO POR</th>';
                    echo '<th>FECHA</th>';
                    echo '<th>HORA</th>';
                    echo '<th class="actions" style="text-align:center">ACCIONES</th>';
                echo '</thead>';
                echo '<tbody>';
                    if(!empty($data)):
                        $data = json_decode($data);
                        $sumatoria_total = 0;
                        foreach ($data as $val) {
                            echo Open('tr');
                               echo tagcontent('td class="actions"', $val->id);
                               echo tagcontent('td', $val->numero);
                                echo tagcontent('td', '$ '. $val->total);
                                echo tagcontent('td', $val->realizado_por);
                                echo tagcontent('td', $val->aprobado_por);
                                echo tagcontent('td', $val->fecha);
                                echo tagcontent('td', $val->hora);
                                echo '<td class="actions">';
                                ?>
                                <button type="button"  title = "Imprimir Orden " data-target="ordenes_list" class="btn btn-default fa fa-print" id="ajaxpanelbtn" data-url="<?php echo base_url('logistica/ordengasto/printOrden/'.$val->id)?>"></button>
                                <button type="button"  title = "Editar Orden" data-target="ordenes_list" class="btn btn-warning fa fa-edit" id="ajaxpanelbtn" data-url="<?php echo base_url('logistica/ordengasto/editView/'.$val->id)?>"></button>
                                <button type="button"  title = "Anular Orden" data-target="ordenes_list" class="btn btn-danger fa fa-trash-o" id="ajaxpanelbtn" data-url="<?php echo base_url('logistica/ordengasto/anularView/'.$val->id)?>"></button>
                                <?php
                                echo '</td>';
                                $sumatoria_total += $val->total;
                            echo Close('tr');
                        }
                        /*SUMATORIAS*/
                        echo Open('tfoot');
                        echo Open('tr');
                               echo tagcontent('td');
                               echo tagcontent('td');
                               echo tagcontent('th', $sumatoria_total);
                               echo tagcontent('td');
                               echo tagcontent('td');
                               echo tagcontent('td');
                               echo tagcontent('td');
                        echo Close('tr');
                        echo Close('tfoot');
                    endif;
                echo '</tbody>';
            echo '</table>';
        echo '</div>';
    echo '</div>';
     echo '</div>';
echo Close('div');
?>
  
<style>

#div1 {
     overflow:scroll;
     height:400px;
     width:100%;
}
#div1 table {
    width:100%;
}
</style>
<script>
//    var $rows = $('#table tr');
//$('#search').keyup(function() {
//    
//    var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
//        reg = RegExp(val, 'i'),
//        text;
//    
//    $rows.show().filter(function() {
//        text = $(this).text().replace(/\s+/g, ' ');
//        return !reg.test(text);
//    }).hide();
//});
var $rows = $('#table tr');
$('#search').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
</script>