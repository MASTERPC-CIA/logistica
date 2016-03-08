<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transferencia extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
        $this->load->library("asientocontable_cart"); //load library
        $this->ac_debito = new Asientocontable_cart("asientocontable_debito"); //cart1
        $this->ac_credito = new Asientocontable_cart("asientocontable_credito"); //cart1
    }

    //remove instance shopping cart
    public function destroy() {
        if ($this->ac_debito->destroy()) {
            $this->ac_credito->destroy();
            echo tagcontent('script', '$("#response_save_out").html("")');
            echo tagcontent('script', '$("#client_id").val("")');
            echo tagcontent('script', '$("#client_name").html("Seleccione el cliente")');
            echo tagcontent('script', '$("#suplier_id").val("")');
            echo tagcontent('script', '$("#suplier_name").html("Seleccione el Proveedor")');

            $res['cart_debito'] = $this->ac_debito->get_content();
            $res['total_cart_debito'] = $this->ac_debito->total_cart();
            $res['cart_credito'] = $this->ac_credito->get_content();
            $res['total_cart_credito'] = $this->ac_credito->total_cart();

            $res['client_id'] = $this->input->post('client_id');
            $res['suplier_id'] = $this->input->post('suplier_id');
            $this->load->view('asientocontable_cart', $res);
        }
    }
    
    //delete a product by rowid
    public function remove($tipo = 'ac_debito') {
        $rowid = $this->input->post('rowid');
        if ($this->{$tipo}->remove_item($rowid)) {
            $res = $this->get_cart_data();
            $this->load->view('asientocontable_cart', $res);
        }
    }

    private function get_cta_contable_data() {
//        $id = $this->input->post('id').rand(0, 999);//id aleatorio para poder seleccionar 2 veces la misma cta
        $id = $this->input->post('id');
        $cc_data = array(
            "id" => $id,
            "name" => $this->input->post('name'),
            "qty" => 1,
            "price" => $this->input->post('price'),
        );
        return $cc_data;
    }

    private function get_cart_data() {
        $client_id = $this->input->post('client_id');
        $suplier_id = $this->input->post('suplier_id');

        $res['cart_debito'] = $this->ac_debito->get_content();
        $res['total_cart_debito'] = $this->ac_debito->total_cart();

        $res['cart_credito'] = $this->ac_credito->get_content();
        $res['total_cart_credito'] = $this->ac_credito->total_cart();
        $res['client_id'] = $client_id;
        $res['suplier_id'] = $suplier_id;

        return $res;
    }

    public function insert($tipo = 'ac_debito') {
        $id = $this->input->post('id') . rand(100, 999); //id aleatorio para poder seleccionar 2 veces la misma cta
        $cc_data = array(
            "id" => $id,
            "name" => $this->input->post('name'),
            "qty" => 1,
            "price" => $this->input->post('price'),
        );

        $this->{$tipo}->insert($cc_data);
        $res = $this->get_cart_data();
        $this->load->view('asientocontable_cart', $res);
    }

    public function update($tipo = 'ac_debito') {
        //si cart is updated show info
        if ($this->{$tipo}->update($this->get_cta_contable_data())) {
            $res = $this->get_cart_data();
            $this->load->view('asientocontable_cart', $res);
        }
    }

}
