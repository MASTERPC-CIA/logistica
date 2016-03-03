<?php

/**
 * Description of index
 *
 * @author JOSE LUIS
 */
class Index extends MX_Controller {

//    private $form_his = 10;
//    private $form_imagen = 9; //id del tipo_formulario tabla tipo_formulario
//    private $res_msj = '';
//    private $form_laborat = 8;

    public function __construct() {
        parent::__construct();
        $this->load->model('solicitud_model');
        $this->load->model('informe_model');
        $this->load->library('solicitud_laboratorio');
    }

    public function index() {
        $this->new_solicitud();
    }
    
    

}
