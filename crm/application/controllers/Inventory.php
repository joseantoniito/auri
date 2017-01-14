<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->db->reconnect();
        $this->load->model('inventory_model');

    }
    
    //manage locations
    public function get_location_states(){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_location_states());
        }
    }
    public function get_location_municipalities($id){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_location_municipalities($id));
        }
    }
    public function get_location_colonies($id){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_location_colonies($id));
        }
    }
    //development item
    public function get_developments()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_developments());
        }
    }

    public function get_development($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->inventory_model->get_development($id);
            echo json_encode(array(
                'item' => $item,
                'item_features' => $this->inventory_model->get_development_features($id),
                'item_media_items' => $this->inventory_model->get_development_media_items($id)                
            ));
        }
    }
    
    public function get_development_features($id){
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_development_features($id));
        }
    }
    public function get_unities($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_unities($id));
        }
    }
    
    public function get_unity($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->inventory_model->get_unity($id);
            //$item->long_description = nl2br($item->long_description);
            echo json_encode($item);
        }
    }
    
    //manage leads
    public function manage_leads()
    {
         echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->inventory_model->get_unity(1),
                        ));
        
        /*if (has_permission('items','','view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if(!has_permission('items','','create')){
                      header('HTTP/1.0 400 Bad error');
                      echo _l('access_denied');
                      die;
                    }
                    $id = $this->inventory_model->add_unity($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->inventory_model->get_unity($id),
                        ));
                } 
                else {
                    if(!has_permission('items','','edit')){
                      header('HTTP/1.0 400 Bad error');
                      echo _l('access_denied');
                      die;
                    }
                    $success = $this->inventory_model->edit_unity($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->inventory_model->get_unity($data['id']),
                        ));
                }
            }*/
        }
    public function add_lead(){
        
        if ($this->input->post()) {
            $data = $this->input->post();

            $success = false;
            $message = '';
            if ($this->inventory_model->add_lead($data)) {
                $success = true;
                $message = _l('added_successfuly', "multimedia item");
            };
            echo json_encode(array(
                'success' => $success,
                'message' => $message,
                'data' => $data
                ));
        }
    }
}
