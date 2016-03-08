<?php
/*Armamos la fila de la partida*/
//echo Open('table');
echo Open('tr');
//Cambiamos el input para beneficiario.
//El programa Administracion general (id 2) el beneficiario son los empleados
//El Alistamiento operacional de las Fuerzas Armadas(id 17) el beneficiario se extrae de la tabla beneficiario_partidas
switch ($programa_id) {
    case 2://Programa Administracion general
        $input_beneficiario_search = input(array('type' => 'text', 'id' => 'beneficiario_ruc', 'name' => 'beneficiario_ruc',
            'placeholder' => 'Nombre Beneficiario', 'callback' => 'load_beneficiario',
            'data-url' => base_url('common/autosuggest/get_empleado_by_name/%QUERY')));
        break;
    
    case 17://Programa Alistamiento operacional de las Fuerzas Armadas(57)
        $input_beneficiario_search = input(array('type' => 'text', 'id' => 'beneficiario_ruc', 'name' => 'beneficiario_ruc',
            'placeholder' => 'Nombre Beneficiario', 'callback' => 'load_beneficiario',
            'data-url' => base_url('common/autosuggest/get_beneficiario_by_name/%QUERY')));
        break;
}
/*Hiddens a enviar*/
//echo input(array('type'=>'hidden', 'id'=>'hidden_id_partida', 'name'=>'id_partida', 'value'=>$id_partida));
echo input(array('type'=>'hidden', 'id'=>'hidden_beneficiario_id', 'name'=>'partidas['.$id_partida.']beneficiario_id', 'value'=>''));
echo input(array('type'=>'hidden', 'id'=>'hidden_programa_id', 'name'=>'partidas['.$id_partida.']programa_id', 'value'=>$programa_id));
echo input(array('type'=>'hidden', 'id'=>'hidden_asignacion'.$count_partidas, 'name'=>'partidas['.$id_partida.']asignacion', 'value'=>$presupuesto_inicial));
echo input(array('type'=>'hidden', 'id'=>'hidden_gasto_acumulado'.$count_partidas, 'name'=>'partidas['.$id_partida.']gasto_acumulado', 'value'=>''));
echo input(array('type'=>'hidden', 'id'=>'hidden_saldo_vigente'.$count_partidas, 'name'=>'partidas['.$id_partida.']saldo_vigente', 'value'=>''));


echo tagcontent('td', $cod_partida);
echo tagcontent('td', $input_beneficiario_search);
echo tagcontent('td', '', array('id'=>'td_beneficiario_nombre', 'required'=>''));
echo tagcontent('td', input(array('id'=>'input_concepto', 'name'=>'partidas['.$id_partida.']concepto')));
echo tagcontent('td', input(array('id'=>'input_ncomprobante', 'name'=>'partidas['.$id_partida.']ncomprobante')));
echo tagcontent('td', input(array('id'=>'input_valor', 'class'=>'inputs_valor', 'name'=>'partidas['.$id_partida.']valor', 'id_partida'=>$id_partida, 'count_partidas'=>$count_partidas)));
echo tagcontent('td', $presupuesto_inicial, array('id'=>'td_asignacion_codificada'.$count_partidas, 'class'=>'tds_asignacionCodificada'));
echo tagcontent('td', '', array('id'=>'td_gasto_acumulado'.$count_partidas, 'class'=>'tds_gastoAcumulado'));
echo tagcontent('td', '', array('id'=>'td_saldo_vigente'.$count_partidas, 'class'=>'tds_saldoVigente'));

echo Close('tr');
//echo Close('table');
?>
<script>
    /*Enviamos los datos extraidos por el autosuggest a sus inputs correspondientes*/
    var load_beneficiario = function (datum) {
        $('#td_beneficiario_nombre').text(datum.value);
        $('#hidden_beneficiario_id').val(datum.id_doc);//id_doc esta definido en el autosuggest como id
        $('#beneficiario_ruc').val(datum.ci);
    };
    $.autosugest_search('#beneficiario_ruc');
    
    //Calculamos los totales de cada fila luego de ingresar el valor
    $('.inputs_valor').keyup(function(){
        //OJO. Cada td y hidden tienen un id que concatena el id_partida al final
//        id_partida = $(this).attr('id_partida');
        count_partidas = $(this).attr('count_partidas');
        valor = $(this).val();
        
        //enviamos el mismo valor a gasto acumulad

        $('#td_gasto_acumulado'+count_partidas).text(valor);
        $('#hidden_gasto_acumulado'+count_partidas).val(valor);
        
        //Calculamos el saldo vigente
        saldo_inicial = $('#hidden_asignacion'+count_partidas).val();
        saldo_nuevo = saldo_inicial - valor;
        $('#td_saldo_vigente'+count_partidas).text(saldo_nuevo);
        $('#hidden_saldo_vigente'+count_partidas).val(saldo_nuevo);
        
        //Calculamos los saldos totales
        calcularTotales();
    });
    
    function calcularTotales(){
        totalValor = 0;
        totalAsignacionCodificada = 0;
        totalGastoAcumulado = 0;
        totalSaldoVigente = 0;
        $(".inputs_valor").each(function() {
            totalValor += parseFloat($(this).val());
            //El valor es el mismo de gastoAcumulado
            totalGastoAcumulado += totalValor;
        });
        $(".tds_asignacionCodificada").each(function() {
            totalAsignacionCodificada += parseFloat($(this).text());
        });
//        $(".tds_gastoAcumulado").each(function() {
//            totalGastoAcumulado += $(this).text();
//        });
        $(".tds_saldoVigente").each(function() {
            totalSaldoVigente += parseFloat($(this).text());
        });
        
        //Enviamos los totales
        $('#td_totalValor').text(totalValor);
        $('#td_totalAsignacion').text(totalAsignacionCodificada);
        $('#td_totalAcumulado').text(totalGastoAcumulado);
        $('#td_totalsaldoVigente').text(totalSaldoVigente);
    }
</script>