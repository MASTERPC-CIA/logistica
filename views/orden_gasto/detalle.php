<?php
$input_partida = input(array('type' => 'text', 'id' => 'partida_id', 'name' => 'partida_id',
    'placeholder' => 'CODIGO PRESUPUESTARIO', 'callback' => 'load_partida', 'class' => 'has-success',
    'data-url' => base_url('common/autosuggest/get_partidas_by_name/%QUERY')));
$input_iva = input(array('type'=>'text', 'id'=>'input_iva', 'name'=>'input_iva', 'value'=>'0'));
echo input(array('type'=>'hidden', 'id'=>'input_gasto_og', 'name'=>'input_gasto_og', 'value'=>''));
echo input(array('type'=>'hidden', 'id'=>'input_total', 'name'=>'input_total', 'value'=>''));
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2"></th>
            <th rowspan="2"><?php echo $input_partida ?></th>
            <th rowspan="2">RUC O RUP</th>
            <th rowspan="2">BENEFICIARIO</th>
            <th rowspan="2">CONCEPTO</th>
            <th rowspan="2">N. COMPROBANTE</th>
            <th rowspan="2">VALOR</th>
            <th colspan="4" style="text-align: center">INFORME PRESUPUESTARIO</th>
        </tr>
        <tr>
            <!--<th>GASTO</th>-->
            <th>ASIGNACION CODIFICADA</th>
            <th>GASTO ACUMULADO</th>
            <th>SALDO VIGENTE</th>
        </tr>
    </thead>
    <tbody id="partida_detalle">


    </tbody>
    <tfoot id="totales_partida">
        <!--TOTALES-->
        <tr>
            <td colspan="4"></td>
            <th>Gasto de O.G.</th>
            <td id="td_totalGastoOg"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <th>IVA</th>
            <td id=""><?php echo $input_iva ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <th>Suma Total</th>
            <td id="td_totalValor"></td>
            <td id="td_totalAsignacion"></td>
            <td id="td_totalAcumulado"></td>
            <td id="td_totalsaldoVigente"></td>
        </tr>
    </tfoot>
</table>
<!--<div id="partida_detalle">
    
</div>-->

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
        if(programa_id == null){
            alertaError('No ha seleccionado un programa');
        }else if(subtarea_id != datum.parent){//Validamos que la partida pertenezca a la subtarea
            alertaError('Esta partida no pertenece a la Subtarea seleccionada');
        }else{
            $.ajax({
                type: "POST",
                url: "ordengasto/loadDetalleView",
                dataType: 'html',
                data: {id_partida: id_partida, cod_partida: cod_partida, programa_id: programa_id, count_partidas: (parseInt(count_partidas)+1)},
                success: function (row) {
                    $('#partida_detalle').append(row);
                    $('#partida_id').text('');
                    count_partidas++;
//                    console.log('Contador: '+count_partidas);
                }
            });
        }
    };
    $.autosugest_search('#partida_id');
</script>
