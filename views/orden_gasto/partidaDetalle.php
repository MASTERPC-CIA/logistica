<?php
/*Armamos la fila de la partida*/
//echo Open('table');
echo Open('tr', array('id'=>'tr'.$count_partidas));
//Cambiamos el input para beneficiario.
//El programa Administracion general (id 2) el beneficiario son los empleados
//El Alistamiento operacional de las Fuerzas Armadas(id 17) el beneficiario se extrae de la tabla beneficiario_partidas
switch ($programa_id) {
    case 2://Programa Administracion general
        $input_beneficiario_search = input(array('type' => 'text', 'id' => 'beneficiario_ruc'.$count_partidas, 'name' => 'beneficiario_ruc',
            'placeholder' => 'Nombre Beneficiario', 'callback' => 'load_beneficiario', 'class'=>'input_beneficiario', 'count'=>$count_partidas,
            'data-url' => base_url('common/autosuggest/get_empleado_by_name/%QUERY'), 'value'=>$beneficiario_ruc));
        //Hiden tipo empleado
        echo input(array('type'=>'hidden', 'id'=>'hidden_tipo_beneficiario'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_tipo_beneficiario]', 'value'=>'E'));
        break;
    
    case 17://Programa Alistamiento operacional de las Fuerzas Armadas(57)
        $input_beneficiario_search = input(array('type' => 'text', 'id' => 'beneficiario_ruc'.$count_partidas, 'name' => 'beneficiario_ruc',
            'placeholder' => 'Nombre Beneficiario', 'callback' => 'load_beneficiario', 'class'=>'input_beneficiario', 'count'=>$count_partidas,
            'data-url' => base_url('common/autosuggest/get_beneficiario_by_name/%QUERY'), 'value'=>$beneficiario_ruc));
        //Hiden tipo beneficiario
        echo input(array('type'=>'hidden', 'id'=>'hidden_tipo_beneficiario'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_tipo_beneficiario]', 'value'=>'B'));

        break;
}
$btn_remove = tagcontent('button', 'X', array('class'=>'btn btn-danger btn-xs','id'=>'ajaxformbtn','data-target'=>'', 'count'=>$count_partidas));
/*Hiddens a enviar*/
echo input(array('type'=>'hidden', 'id'=>'hidden_id_partida'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_partida_id]', 'value'=>$id_partida, 'count'=>$count_partidas, 'class'=>'ids_partida'));
echo input(array('type'=>'hidden', 'id'=>'hidden_beneficiario_id'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_beneficiario_id]', 'value'=>$beneficiario_id));
echo input(array('type'=>'hidden', 'id'=>'hidden_empleado_id'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_empleado_id]', 'value'=>$empleado_id));
echo input(array('type'=>'hidden', 'id'=>'hidden_programa_id', 'name'=>'partidas['.$count_partidas.'][odet_programa_id]', 'value'=>$programa_id));
echo input(array('type'=>'hidden', 'id'=>'hidden_asignacion'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_asignacion]', 'value'=>$presupuesto_inicial));
echo input(array('type'=>'hidden', 'id'=>'hidden_gasto_acumulado'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_gasto_acumulado]', 'value'=>$gasto_acumulado));
echo input(array('type'=>'hidden', 'id'=>'hidden_saldo_vigente'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][odet_saldo_vigente]', 'value'=>$saldo_vigente));

/*Hiden para acumular saldo de una partida*/
echo input(array('type'=>'hidden', 'id'=>'saldo_partida['.$id_partida.']', 'name'=>'saldo_partida['.$id_partida.']', 'value'=>$saldo_vigente));

//No permitimos eliminar partidas desde editar
if(isset($editar_orden)){
    echo input(array('type'=>'hidden', 'id'=>'valor_anterior'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][valor_anterior]', 'value'=>$gasto));
    echo input(array('type'=>'hidden', 'id'=>'detalle_id'.$count_partidas, 'name'=>'partidas['.$count_partidas.'][id]', 'value'=>$detalle_id));
    echo tagcontent('td');
}else{
    echo tagcontent('td', $btn_remove);
}
echo tagcontent('td', $cod_partida);
echo tagcontent('td', $input_beneficiario_search);
echo tagcontent('td', $beneficiario_nombre, array('id'=>'td_beneficiario_nombre'.$count_partidas, 'required'=>''));
echo tagcontent('td', input(array('id'=>'input_concepto', 'name'=>'partidas['.$count_partidas.'][odet_concepto]', 'value'=>$concepto)));
echo tagcontent('td', input(array('id'=>'input_ncomprobante', 'name'=>'partidas['.$count_partidas.'][odet_comprobante_num]', 'value'=>$comprobante_num)));
echo tagcontent('td', input(array('id'=>'input_valor', 'class'=>'inputs_valor', 'name'=>'partidas['.$count_partidas.'][odet_gasto]', 'id_partida'=>$id_partida, 'count_partidas'=>$count_partidas, 'value'=>$gasto)));
echo tagcontent('td', $presupuesto_inicial, array('id'=>'td_asignacion_codificada'.$count_partidas, 'class'=>'tds_asignacionCodificada'));
echo tagcontent('td', $gasto_acumulado, array('id'=>'td_gasto_acumulado'.$count_partidas, 'class'=>'tds_gastoAcumulado'));
echo tagcontent('td', $saldo_vigente, array('id'=>'td_saldo_vigente'.$count_partidas, 'class'=>'tds_saldoVigente'));

echo Close('tr');
//echo Close('table');
?>
<script>
    /*Tomamos el count del input autosuggest que este visualizando*/
    $('[name="beneficiario_ruc"]').on('focus','',function(){
//         alert('sadsa');
         count_partidas = $(this).attr('count');
     });
    /*Enviamos los datos extraidos por el autosuggest a sus inputs correspondientes*/
    var load_beneficiario = function (datum) {
//        console.log(count_partidas);
        setValues(datum);
       
    };
    $.autosugest_search('.input_beneficiario');
    
    function setValues(datum){
        $('#td_beneficiario_nombre'+count_partidas).text(datum.value);
        $('#beneficiario_ruc'+count_partidas).val(datum.ci);
        tipo_beneficiario = $('#hidden_tipo_beneficiario'+count_partidas).val();
        if(tipo_beneficiario == 'E'){
            $('#hidden_empleado_id'+count_partidas).val(datum.id_doc);//id_doc esta definido en el autosuggest como id
        }
        if(tipo_beneficiario == 'B'){
            $('#hidden_beneficiario_id'+count_partidas).val(datum.id_doc);//id_doc esta definido en el autosuggest como id
        }
    }
    
    //Calculamos los totales de cada fila luego de ingresar el valor
    $('.inputs_valor').keyup(function(){
        //OJO. Cada td y hidden tienen un id que concatena el id_partida al final
//        id_partida = $(this).attr('id_partida');
        count_partidas = $(this).attr('count_partidas');
        //Envio a la variable global
        count_partidas_global = count_partidas;
        valor = $(this).val();
        
        //enviamos el mismo valor a gasto acumulad

        $('#td_gasto_acumulado'+count_partidas).text(valor);
        $('#hidden_gasto_acumulado'+count_partidas).val(valor);
        
        //Calculamos el saldo vigente
        saldo_inicial = $('#hidden_asignacion'+count_partidas).val();
        saldo_nuevo = saldo_inicial - valor;
        if(saldo_nuevo < 0){
            alertaError("El valor excede el presupuesto!");
        }
        $('#td_saldo_vigente'+count_partidas).text(saldo_nuevo);
        $('#hidden_saldo_vigente'+count_partidas).val(saldo_nuevo);
        
        //Calculamos los saldos totales
        calcularTotales();
    });
    
    //Calculamos si se cambia el valor del iva
    $('#input_iva').keyup(function(){
        iva = parseFloat($(this).val());
        totalValor = parseFloat($('#td_totalGastoOg').text());
        totalValorMasIva = totalValor+(totalValor * iva)/100;
        $('#td_totalValor').text(totalValorMasIva);
        $('#input_total').val(totalValorMasIva);

    });
    
    function calcularTotales(){
        totalValor = 0;
        totalAsignacionCodificada = 0;
        totalGastoAcumulado = 0;
        totalSaldoVigente = 0;
        iva = parseFloat($('#input_iva').val());
        $(".inputs_valor").each(function() {
            totalValor += parseFloat($(this).val());
            //El valor es el mismo de gastoAcumulado
//            totalGastoAcumulado += totalValor;
        });
        $(".tds_asignacionCodificada").each(function() {
            totalAsignacionCodificada += parseFloat($(this).text());
        });
        $(".tds_gastoAcumulado").each(function() {
            totalGastoAcumulado += parseFloat($(this).text());
        });
        $(".tds_saldoVigente").each(function() {
            totalSaldoVigente += parseFloat($(this).text());
        });

        totalValorMasIva = totalValor+(totalValor * iva)/100;
        
        //Enviamos los totales a los tds y a los hiddens
        $('#td_totalGastoOg').text(totalValor);
        $('#input_gasto_og').val(totalValor);
        $('#td_totalValor').text(totalValorMasIva);
        $('#input_total').val(totalValorMasIva);
        $('#td_totalAsignacion').text(totalAsignacionCodificada);
        $('#td_totalAcumulado').text(totalGastoAcumulado);
        $('#td_totalsaldoVigente').text(totalSaldoVigente);
    }
    
    /*Boton remover. Elimina un tr*/
    $('.btn-xs').click(function(event){
        event.preventDefault;
        var count_partidas = $(this).attr('count');
        $('#tr'+count_partidas).remove();
    });
</script>