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
}
