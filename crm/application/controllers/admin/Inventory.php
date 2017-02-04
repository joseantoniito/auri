<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
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
                'item_docs' => $this->inventory_model->get_unity_media_items($id)                
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
            echo json_encode(array(
                'item' => $item,
                'item_media_items' => $this->inventory_model->get_unity_media_items($id)                
            ));
        }
        
    }
    
    
    //manage developments
    public function index()
    {
        if (!has_permission('items','','view')) {
            access_denied('Invoice Items');
        }
        /*if ($this->input->is_ajax_request()) {
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
            redirect(admin_url('inventory'));
        }
        $response = $this->inventory_model->delete_development($id);
        /*if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_development_lowercase')));
        } else*/ 
        if ($response == true) {
            $message = _l('deleted', _l('invoice_item_development'));
        } else {
            $message = _l('problem_deleting', _l('invoice_item_development_lowercase'));
        }
        //redirect(admin_url('invoice_items'));
        //todo: cambiar recursos
        
        echo json_encode(array(
                        'success' => $response,
                        'message' => $message
                        ));
    }
    
    //development features
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
    
    //development media item
    public function add_development_media_item(){
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
                if ($this->inventory_model->add_development_media_item($data)) {
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
    
    public function delete_development_media_item($id){
        $response = $this->inventory_model->delete_development_media_item($id);
    }
    
    public function save_media_item(){
        $fileParam = "files";
        //$uploadRoot = "F:/xampp/htdocs/perfex_crm/crm/uploads/inventory/";
        $uploadRoot = "/home3/rafaq5/public_html/auri/crm/uploads/inventory/";
        $files = $_FILES[$fileParam];
        
        if (isset($files['name']))
        {
            $error = $files['error'];
            if ($error == UPLOAD_ERR_OK) {
                $targetPath = $uploadRoot . basename($files["name"]);
                $uploadedFile = $files["tmp_name"];
                if (is_uploaded_file($uploadedFile)) {
                    if (!move_uploaded_file($uploadedFile, $targetPath)) {
                        echo "Error moving uploaded file";
                    }
                }
                
                $id_type = 0;
                if (strpos($files["type"], 'image') !== false)
                    $id_type = 1;
                if (strpos($files["type"], 'video') !== false)
                    $id_type = 2;
                
                
                $inserted_id = $this->inventory_model->add_media_item([
                    'url' => "/uploads/inventory/" . $files["name"],
                    'name' => $files["name"],
                    'id_type' => $id_type
                ]);
                
                $success = true;
                echo json_encode(array(
                    'success' => $success,
                    'type' => "save",
                    'id' => $inserted_id
                    ));
                
            } else {
                // See http://php.net/manual/en/features.file-upload.errors.php
                echo "Error code " . $error;
            }
        }
    }
    
    public function remove_media_item(){
        //$uploadRoot = "F:/xampp/htdocs/perfex_crm/crm/uploads/inventory/";
        $uploadRoot = "/home3/rafaq5/public_html/auri/crm/uploads/inventory/";
        $success = true;
        $data = $this->input->post();
        $name = $data["fileNames"];
        
        if (isset($data["fileNames"]))
        {
            $targetPath = $uploadRoot . basename($data["fileNames"]);
            if (!unlink($targetPath)) {
                echo "Error removing file";
            }
            echo json_encode(array(
                'success' => $success,
                'type' => "remove",
                'data' => $data
                ));
        }
            
        
    }
    
    function delete_media_item($id){
        $response = $this->inventory_model->delete_media_item($id);
    }
    
    //development unities
    public function item($id = '')
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('items');
        }
        if($id != '')
            if(!has_permission('items', '', 'edit'))
                access_denied('items');
        
        $data['title'] = 'Propiedad';
        $data['id'] = $id;
        $data['item_features'] = json_encode(array());
        $data['item_media_items'] = json_encode(array());
        if($id != ''){
            $item = $this->inventory_model->get_development($id);
            $data['item'] = $item;
            $data['item_features'] = json_encode($this->inventory_model->get_development_features($id));
            $data['item_media_items'] = json_encode($this->inventory_model->get_development_media_items($id));
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
    
    public function add_unity_media_item(){
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
                if ($this->inventory_model->add_unity_media_item($data)) {
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
    
    public function delete_unity_media_item($id){
        $response = $this->inventory_model->delete_unity_media_item($id);
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
                        $success = true;
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
        if ($response == true) {
            $message = _l('deleted', _l('invoice_item_unity_lowercase'));
        } else {
            $message = _l('problem_deleting', _l('invoice_item_unity_lowercase'));
        }
        
        echo json_encode(array(
                        'success' => $response,
                        'message' => $message
                        ));
        
        
        //redirect(admin_url('invoice_items'));
        //todo: cambiar recursos
    }
    
    //assign assessors to developments
    public function development_assessors()
    {
        if (!has_permission('items','','view')) {
            access_denied('Invoice Items');
        }
        $data['title'] = 'Assign assessors to developments';
        //$data['items'] = json_encode($this->inventory_model->get_developments());
        $this->load->view('admin/inventory/manage_development_assessors', $data);
    }
    
    public function get_assessors()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inventory_model->get_assessors());
        }
    }
    
    public function get_developments_with_assessors()
    {
        if ($this->input->is_ajax_request()) {
            /*echo json_encode(array(
                'item' => $item,
                'item_features' => $this->inventory_model->get_development_features($id),
                'item_media_items' => $this->inventory_model->get_development_media_items($id)                
            ));*/
            $developments = $this->inventory_model->get_developments();
            
            $ids_developments = 
                array_map(function($element) { return $element['id']; }, $developments);
            
            $development_assessors = 
                $this->inventory_model->get_developments_assessors($ids_developments);
            
            /*foreach ($developments as $development) {
                $id_development = $development['id'];
                $development["assesors"] = array_filter($development_assessors, function($obj){
                    return $obj["id_development"] == $id_development;
                }); 
            }*/
            
            echo json_encode(array(
                    'developments' => $developments,
                    'development_assessors' => $development_assessors,
                    ));
            
            //echo json_encode($developments);
        }
    }
    
    public function add_development_assessor(){
        
        if ($this->input->post()) {
            $data = $this->input->post();

            $success = false;
            $message = '';
            if ($this->inventory_model->add_development_assessor($data)) {
                $success = true;
                $message = _l('added_successfuly', "asesor");
            };
            echo json_encode(array(
                'success' => $success,
                'message' => $message,
                'data' => $data
                ));
        }
    }
    
    public function delete_development_assessor(){
        
        if ($this->input->post()) {
            $data = $this->input->post();

            $success = false;
            $message = '';
            if ($this->inventory_model->delete_development_assessor($data)) {
                $success = true;
                $message = _l('deleted', "asesor");
            };
            echo json_encode(array(
                'success' => $success,
                'message' => $message,
                'data' => $data
                ));
        }
    }
    
    //reservations
    public function reservations()
    {
        if (!has_permission('items','','view')) {
            access_denied('Invoice Items');
        }
        $data['title'] = 'Reservations';
        //$data['items'] = json_encode($this->inventory_model->get_developments());
        $this->load->view('admin/inventory/manage_reservations', $data);
    }
    
    public function get_reservations()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            echo json_encode($this->inventory_model->get_reservations($data));
        }
    }
    
    public function get_reservation($id)
    {
        
        
        if ($this->input->is_ajax_request()) {
            
            
            
            if(!$id){
                $development = $this->inventory_model->get_development_of_assessor(get_staff_user_id());
                
                echo json_encode(array(
                    'item' => null,
                    'item_docs' => array(),
                    'id_development' => $development->id_development,
                    'item_unidades_desarrollo' => $this->inventory_model->get_unities($development->id_development),
                    'item_assessors' => array(),
                    'staff_id' => get_staff_user_id()
                ));
                return;
            } 
            
            $item = $this->inventory_model->get_reservation($id);
            $id_development = $item->id_development;
            
            echo json_encode(array(
                'item' => $item,
                'item_docs' => $this->inventory_model->get_reservation_docs($id),
                'id_development' => $item->id_development,
                'item_unidades_desarrollo' => $this->inventory_model->get_unities($id_development),
                'item_assessors' => $this->inventory_model->get_assessors(),
                'staff_id' => get_staff_user_id()
            ));
        }
    }
    
    public function add_reservation_media_item(){
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
                if ($this->inventory_model->add_reservation_media_item($data)) {
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
    
    public function delete_reservation_media_item($id){
        $response = $this->inventory_model->delete_reservation_media_item($id);
    }
    
    public function manage_reservation_admin()
    {
        if (has_permission('items','','view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if(!has_permission('items','','edit')){
                  header('HTTP/1.0 400 Bad error');
                  echo _l('access_denied');
                  die;
                }
                $success = $this->inventory_model->update_unity_reservation($data);
                $id = $data["id"];
                if($data["id"] == "0")
                    $data["id"] = $success;
                
                $message = '';
                if ($success) {
                    $message = _l('updated_successfuly', "Propiedad reservada");
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message,
                    'item' => $this->inventory_model->get_reservation($id)
                    ));
                
            }
        }
    }
    
    public function manage_reservation_lead()
    {
        if (has_permission('items','','view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if(!has_permission('items','','edit')){
                  header('HTTP/1.0 400 Bad error');
                  echo _l('access_denied');
                  die;
                }
                $success = $this->inventory_model->update_unity_reservation($data);
                $id = $data["id"];
                if($data["id"] == "0")
                    $data["id"] = $success;
                
                $message = '';
                if ($success) {
                    $message = _l('updated_successfuly', "Propiedad reservada");
                }
                echo json_encode(array(
                    'success' => $success,
                    'message' => $message,
                    'item' => $this->inventory_model->get_reservation($id)
                    ));
                
            }
        }
    }
}
