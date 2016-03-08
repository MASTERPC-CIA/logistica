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
        $res['view'] = $this->load->view('orden_gasto_view', '', TRUE);
        $res['slidebar'] = $this->load->view('slidebar', '', TRUE);
        $res['title'] = 'Orden Gasto-Logistica';
        $this->load->view('common/templates/dashboard_lte', $res);
    }

}
