<?php

/**
 * Description of index
 *
 * @author JOSE LUIS
 */
class Index extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
    }

    public function index() {
        //Tipo 1: Area funcional
        $this->load->library('ordengasto_library');

        $this->ordengasto_library->load_view();
    }

}
