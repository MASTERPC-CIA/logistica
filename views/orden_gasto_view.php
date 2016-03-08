<?php
echo tagcontent('div', '', array('id' => 'orden_out'));
date_default_timezone_set('America/Guayaquil');
$this->load->view('common/hmc_head/encabezado_cuenca'); //Encabezado para el hospital militar
echo Open('form', array('id' => 'form_examen', 'action' => base_url('logistica/orgen_gasto/save_new'), 'method' => 'post'));
$this->load->view('orden_gasto/header');