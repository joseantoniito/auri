<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');

    }
    /* List all available items */
    public function index()
    {
        /*if (!has_permission('items','','view')) {
            access_denied('Invoice Items');
        }
        if ($this->input->is_ajax_request()) {
            $this->perfex_base->get_table_data('invoice_items');
        }
        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);*/
        $data['title'] = 'Inventory';
        
        $this->load->view('admin/inventory/manage', $data);
    }
    
    public function item($id = '')
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('items');
        }
        //if ($this->input->is_ajax_request()) {
        //    $this->perfex_base->get_table_data('mail_lists');
        //}
        $data['title'] = 'Unity';//_l('mail_lists');
        $data['id'] = $id;
        
        if($id != ''){
            $item = $this->inventory_model->get_unity($id);
            $data['item'] = $item;
        }
        
        $this->load->view('admin/inventory/item', $data);
    }
    
    
    //manage unities
    public function get_unities($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->invoice_items_model->get_unities($id));
        }
    }
    
    public function get_unity($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->invoice_items_model->get_unity($id);
            //$item->long_description = nl2br($item->long_description);
            echo json_encode($item);
        }
    }
    
    public function manage_unity()
    {
        if (has_permission('items','','view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if(!has_permission('items','','create')){
                      header('HTTP/1.0 400 Bad error');
                      echo _l('access_denied');
                      die;
                    }
                    $id = $this->invoice_items_model->add_unity($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->invoice_items_model->get_unity($id),
                        ));
                } 
                else {
                    if(!has_permission('items','','edit')){
                      header('HTTP/1.0 400 Bad error');
                      echo _l('access_denied');
                      die;
                    }
                    $success = $this->invoice_items_model->edit_unity($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->invoice_items_model->get_unity($data['id']),
                        ));
                }
            }
        }
    }

    public function delete_unity($id)
    {
        if (!has_permission('items','','delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }
        $response = $this->invoice_items_model->delete_unity($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_unity_lowercase')));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item_unity')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_unity_lowercase')));
        }
        //redirect(admin_url('invoice_items'));
        //todo: cambiar recursos
    }
    
}
