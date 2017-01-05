<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('inventory_model');

    }
    
    //manage developments
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
        $data['items_groups'] = $this->inventory_model->get_groups();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/manage', $data);*/
        $data['title'] = 'Inventory';
        
        //$data['items'] = json_encode($this->inventory_model->get_developments());
        
        $this->load->view('admin/inventory/manage', $data);
    }
    
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
                'item_features' => $this->inventory_model->get_development_features($id)));
        }
    }
    
    public function manage_development()
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
                    $id = $this->inventory_model->add_development($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->inventory_model->get_development($id),
                        ));
                } 
                else {
                    if(!has_permission('items','','edit')){
                      header('HTTP/1.0 400 Bad error');
                      echo _l('access_denied');
                      die;
                    }
                    $success = $this->inventory_model->edit_development($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfuly', _l('invoice_item'));
                    }
                    echo json_encode(array(
                        'success' => $success,
                        'message' => $message,
                        'item'=>$this->inventory_model->get_development($data['id']),
                        ));
                }
            }
        }
    }

    public function delete_development($id)
    {
        if (!has_permission('items','','delete')) {
            access_denied('Invoice Items');
        }

        if (!$id) {
            redirect(admin_url('invoice_items'));
        }
        $response = $this->inventory_model->delete_development($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_development_lowercase')));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item_development')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_development_lowercase')));
        }
        //redirect(admin_url('invoice_items'));
        //todo: cambiar recursos
    }
    
    public function manage_development_features(){
        if (has_permission('items','','view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                if(!has_permission('items','','create')){
                  header('HTTP/1.0 400 Bad error');
                  echo _l('access_denied');
                  die;
                }
                $success = false;
                $message = '';
                if ($this->inventory_model->manage_development_features($data)) {
                    $success = true;
                    $message = _l('added_successfuly', _l('invoice_item'));
                };
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message,
                    'data' => $data
                    ));
                
            }
        }
    }
    
    public function get_development_features($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_development_features($id));
        }
    }
    
    //manage unities
    public function item($id = '')
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('items');
        }
        //if ($this->input->is_ajax_request()) {
        //    $this->perfex_base->get_table_data('mail_lists');
        //}
        $data['title'] = 'Propiedad';//_l('mail_lists');
        $data['id'] = $id;
        
        if($id != ''){
            $item = $this->inventory_model->get_development($id);
            $data['item'] = $item;
            $data['item_features'] = json_encode($this->inventory_model->get_development_features($id));
        }
        
        $items_tipo_desarrollo = array();
        $items_tipo_desarrollo[0] = ['id' => 1,'nombre' => 'Desarrollo horizontal',];            
        $items_tipo_desarrollo[1] = ['id' => 2,'nombre' => 'Desarrollo vertical',];
        $data['items_tipo_desarrollo'] = $items_tipo_desarrollo;
        
        $items_etapa_desarrollo = array();
        $items_etapa_desarrollo[0] = ['id' => 1,'nombre' => 'Pre-Venta',];            
        $items_etapa_desarrollo[1] = ['id' => 2,'nombre' => 'Venta',];
        $data['items_etapa_desarrollo'] = $items_etapa_desarrollo;
        
        $items_tipos_entrega = array();
        $items_tipos_entrega[0] = ['id' => 1,'nombre' => 'Imediata',];            
        $items_tipos_entrega[1] = ['id' => 2,'nombre' => '3 meses después',];
        $data['items_tipos_entrega'] = $items_tipos_entrega;
        
        $items_estados = array();
        $items_estados[0] = ['id' => 1,'nombre' => 'Querétaro',];            
        $items_estados[1] = ['id' => 2,'nombre' => 'Estado de México',];
        $data['items_estados'] = $items_estados;
        
        $items_municipios = array();
        $items_municipios[0] = ['id' => 1,'nombre' => 'Querétaro',];            
        $items_municipios[1] = ['id' => 2,'nombre' => 'Corregidora',];
        $data['items_municipios'] = $items_municipios;
        
        $items_colonias = array();
        $items_colonias[0] = ['id' => 1,'nombre' => 'Lomas de San Pedrito Peñuelas',];            
        $items_colonias[1] = ['id' => 2,'nombre' => 'Álamos',];
        $data['items_colonias'] = $items_colonias;
        
        $this->load->view('admin/inventory/item', $data);
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
        $response = $this->inventory_model->delete_unity($id);
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
