<?php
$input_partida = input(array('type' => 'text', 'id' => 'partida_id', 'name' => 'partida_id',
    'placeholder' => 'CODIGO PRESUPUESTARIO', 'callback' => 'load_partida', 'class' => 'has-success',
    'data-url' => base_url('common/autosuggest/get_partidas_by_name/%QUERY')));
$input_iva = input(array('type' => 'text', 'id' => 'input_iva', 'name' => 'input_iva', 'value' =>$iva));

echo input(array('type'=>'hidden', 'id'=>'input_gasto_og', 'name'=>'input_gasto_og', 'value'=>$gasto_og));
echo input(array('type'=>'hidden', 'id'=>'input_total', 'name'=>'input_total', 'value'=>$total));
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2"></th>
            <?php
            //No permitimos agregar mas partidas desde vista editar
            if (isset($editar_orden)) {
                echo '<th rowspan="2">CODIGO<br>PRESUPUESTARIO</th>';
            }else{
                echo '<th rowspan="2">';
                echo $input_partida;
                echo '</th>';
            }

            ?>                    

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
        <?php
        //Si se ha enviado desde el listado de ordenes y carga la vista para editar
        //Recorremos las partidas enviando sus respectivos valores
        if (isset($editar_orden)) {
            //Enviamos los valores programa y subtarea que extrae de los combobox
            echo input(array('type' => 'hidden', 'id' => 'combo_programas', 'name' => 'combo_programas', 'value' => $detalle[0]->odet_programa_id));
            echo input(array('type' => 'hidden', 'id' => 'combo_subtareas', 'name' => 'combo_subtareas', 'value' => $subtarea_id));
            $count_partidas = 1;
            foreach ($detalle as $partida) {
                echo '<br>';
                $res['editar_orden'] = $editar_orden;
                $res['detalle_id'] = $partida->id;
                $res['id_partida'] = $partida->odet_partida_id;
                $res['cod_partida'] = $partida->cod_partida;
                $res['programa_id'] = $partida->odet_programa_id;
                $res['count_partidas'] = $count_partidas;
                $res['presupuesto_inicial'] = $partida->odet_asignacion;
                //Estos campos son diferentes segun el tipo
                if($partida->odet_tipo_beneficiario == 'E'){
                    $res['beneficiario_id'] = '';
                    $res['empleado_id'] = $partida->odet_empleado_id;
                    $res['beneficiario_ruc'] = $partida->empleado_ruc;
                    $res['beneficiario_nombre'] = $partida->empleado_nombres;
                }
                if($partida->odet_tipo_beneficiario == 'B'){
                    $res['empleado_id'] = '';
                    $res['beneficiario_id'] = $partida->odet_beneficiario_id;
                    $res['beneficiario_ruc'] = $partida->beneficiario_ruc;
                    $res['beneficiario_nombre'] = $partida->beneficiario_nombres;
                }
                $res['concepto'] = $partida->odet_concepto;
                $res['comprobante_num'] = $partida->odet_comprobante_num;
                $res['gasto'] = $partida->odet_gasto;
                $res['gasto_acumulado'] = $partida->odet_gasto_acumulado;
                $res['saldo_vigente'] = $partida->odet_saldo_vigente;
                $this->load->view('orden_gasto/partidaDetalle', $res);
                $count_partidas ++;
            }
        }
//            echo input(array('type' => 'hidden', 'id' => 'editar_orden', 'name' => 'input_total', 'value' => $editar_orden));
        ?>


    </tbody>
    <tfoot id="totales_partida">
        <!--TOTALES-->
        <tr>
            <td colspan="5"></td>
            <th>Gasto de O.G.</th>
            <td id="td_totalGastoOg"><?php echo $gasto_og ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <th>IVA</th>
            <td id=""><?php echo $input_iva ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <th>Suma Total</th>
            <td id="td_totalValor"><?php echo $total ?></td>
            <td id="td_totalAsignacion"></td>
            <td id="td_totalAcumulado"></td>
            <td id="td_totalsaldoVigente"></td>
        </tr>
    </tfoot>
</table>
<!--<div id="partida_detalle">
    
</div>-->
<script>
    
</script>

