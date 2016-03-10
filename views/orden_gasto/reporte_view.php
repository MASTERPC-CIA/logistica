<?php
echo Open('div', array('id'=>'ordenes_search_form','align' => 'center', 'class' => 'col-md-12'));
    $this->load->view('orden_gasto/search_form');
echo Close('div');//Close presupuesto search form
echo tagcontent('div','', array('id'=>'ordenes_list'));
?>
<script>
</script>