<?php
echo Open('div', array('id'=>'presupuesto_search_form','align' => 'center', 'class' => 'col-md-12'));
    $this->load->view('presupuesto/search_form');
echo Close('div');//Close presupuesto search form
echo tagcontent('div','', array('id'=>'proyectos_list'));
?>
<script>
</script>