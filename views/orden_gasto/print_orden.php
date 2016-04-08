<?php
/*
 * @author JOSE LUIS QUICHIMBO
 * 10/03/2016 
 */
date_default_timezone_set('America/Guayaquil');

echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id' => 'printbtn', 'data-target' => 'print_orden', 'class' => 'btn btn-default pull-left'));
echo Open('div', array('id' => 'print_orden'));
$this->load->view('common/hmc_head/encabezado_cuenca'); //Encabezado para el hospital militar
?>
<!--TABLA 1 HEADER-->
<table class="table table_ordengasto" style=" border: 1px solid black;">
    <thead>
        <tr>
            <th colspan="2" style="text-align: center">EJERCITO ECUATORIANO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>DIRECCION DE DESARROLLO INSTITUCIONAL</td>
            <td>DIRECCION DE FINANZAS</td>
        </tr>
        <tr style="text-align: center">
            <td colspan="2">ORDEN GASTO</td>
        </tr>
        <tr style="text-align: center">
            <td colspan="2">PARA USO DE LAS DIRECCIONES Y UNIDADES EJECUTORAS</td>
        </tr>
    </tbody>
</table>

<!--TABLA 2 ENCABEZADO ORDEN-->
<table class="table table_ordengasto" style=" border: 1px solid black;">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle">FECHA</th>
            <td rowspan="2" style="vertical-align: middle"><?php echo $orden_data->ord_fecha ?></td>
            <th rowspan="2" style="vertical-align: middle">REFERENCIA</th>
            <td rowspan="2" style="vertical-align: middle"><?php echo $orden_data->ord_referencia ?></td>
            <th>ORDEN GASTO N.</th>
            <td colspan="2"><?php echo $orden_data->ord_numero ?></td>
        </tr>
        <tr>
            <th>NOMBRE DE LA UNID.:</th>
            <td colspan="2"><?php echo $orden_data->ord_nombre_unidad ?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>AREA FUNCIONAL</td>
            <td><?php echo $area_funcional->cod ?></td>
            <td><?php echo $area_funcional->nombre ?></td>
            <td>UNIDAD</td>
            <td><?php echo $orden_data->ord_cod_unidad ?></td>
            <td>UNIDAD EJECUTORA</td>
            <td><?php echo $orden_data->ord_unidad_ejecutora ?></td>
        </tr>
        <tr>
            <td>PROGRAMA</td>
            <td><?php echo $programa->cod ?></td>
            <td><?php echo $programa->nombre ?></td>
            <td>PROYECTO</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>ACTIVIDAD</td>
            <td><?php echo $actividad->cod ?></td>
            <td><?php echo $actividad->nombre ?></td>
            <td>SUBPROYECTO</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>SUBACTIVIDAD</td>
            <td><?php echo $subactividad->cod ?></td>
            <td><?php echo $subactividad->nombre ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>TAREA</td>
            <td><?php echo $tarea->cod ?></td>
            <td><?php echo $tarea->nombre ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>SUBTAREA</td>
            <td><?php echo $subtarea->cod ?></td>
            <td><?php echo $subtarea->nombre ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: center">solicito agradecere a ud señor jefe de logistica/ jefe financiero que con carga de la (s) partidas presupuestarias (s)<br>
            se cancelen los valores que acontinuacion se detallan </td>
        </tr>
    </tfoot>
</table>

<!--TABLA 3: DETALLE DE PARTIDAS-->
<table class="table table_ordengasto" style=" border: 1px solid black;">
    <thead>
        <tr>
            <th rowspan="2">CODIGO<br>PRESUPUEST.</th>
            <th rowspan="2">RUC O RUP</th>
            <th rowspan="2">BENEFICIARIO</th>
            <th rowspan="2">CONCEPTO</th>
            <th colspan="4" style="text-align: center">INFORME PRESUPUESTARIO</th>
        </tr>
        <tr>
            <th>GASTO</th>
            <th>ASIGN.<br>CODIFICADA</th>
            <th>GASTO<br>ACUMULADO</th>
            <th>SALDO VIGENTE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //recorremos el array detalle partidas
        if(!empty($detalle)){
            $sumatoria_inicial = 0;
            $sumatoria_acumulado = 0;
            $sumatoria_vigente = 0;
            $detalle = json_decode($detalle);
            foreach ($detalle as $partida) {
                echo Open('tr');
                    echo tagcontent('td', $partida->cod_partida);
                    if($partida->odet_tipo_beneficiario == 'E'){//Beneficiario Empleado
                        echo tagcontent('td', $partida->empleado_ruc);
                        echo tagcontent('td', $partida->empleado_nombres);
                    }
                    if($partida->odet_tipo_beneficiario == 'B'){//Beneficiario Beneficiario
                        echo tagcontent('td', $partida->beneficiario_ruc);
                        echo tagcontent('td', $partida->beneficiario_nombres);
                    }
                    echo tagcontent('td', $partida->odet_concepto.'<br>'.$partida->odet_comprobante_num);
                    echo tagcontent('td', $partida->odet_gasto);
                    echo tagcontent('td', $partida->odet_asignacion);
                    echo tagcontent('td', $partida->odet_gasto_acumulado);
                    echo tagcontent('td', $partida->odet_saldo_vigente);
                    $sumatoria_inicial += $partida->odet_asignacion;
                    $sumatoria_acumulado += $partida->odet_gasto_acumulado;
                    $sumatoria_vigente += $partida->odet_saldo_vigente;
                echo Close('tr');
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <th>Gasto de O.G.</th>
            <td><?php echo $orden_data->ord_gasto_og?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <th>IVA</th>
            <td><?php echo $orden_data->ord_iva?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <th>Suma Total</th>
            <td><?php echo $orden_data->ord_total?></td>
            <!--Estas sumatorias no son guardadas, se calculan-->
            <td><?php echo $sumatoria_inicial ?></td>
            <td><?php echo $sumatoria_acumulado ?></td>
            <td><?php echo $sumatoria_vigente ?></td>
        </tr>
    </tfoot>
</table>

<!--TABLA 4: PIES Y FIRMAS-->
<table class="table table_ordengasto"style=" border: 1px solid black;">
    <tbody>
        <tr>
            <td>ELABORADO POR</td>
            <td>REVISADO POR</td>
            <td>RELATOR DEL PB</td>
            <td>APROBADO POR</td>
        </tr>
        <tr style="height: 60px">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>_____________________________</td>
            <td>_____________________________</td>
            <td>_____________________________</td>
            <td>_____________________________</td>
        </tr>
        <tr>
            <td>AMANUENCE DE LOGISTICA</td>
            <td>OFICIAL DE LOGISTICA</td>
            <td>RELATOR DEL PB.</td>
            <td>DIRECTOR DEL HG-III-DE</td>
        </tr>
        <tr>
            <td><?php echo $orden_data->realizado_por ?></td>
            <td><?php echo $orden_data->revisado_por ?></td>
            <td><?php echo $orden_data->relator_pb ?></td>
            <td><?php echo $orden_data->aprobado_por ?></td>
        </tr>
    </tbody>
</table>
<?php
echo Close('div'); //print_orden

