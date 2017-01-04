<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    //manage developments
    public function get_developments()
    {
        $this->db->select('id, nombre, logotipo, descripcion, id_tipo_desarrollo, id_etapa_desarrollo, total_de_unidades, id_entrega, id_estado, id_municipio, id_colonia, direccion, codigo_postal, id_mostar_ubicacion, clave_interna');
        $this->db->from('tbldevelopments');
        return $this->db->get()->result_array();
    }
    
    public function get_development($id)
    {
        $this->db->select('id, nombre, logotipo, descripcion, id_tipo_desarrollo, id_etapa_desarrollo, total_de_unidades, id_entrega, id_estado, id_municipio, id_colonia, direccion, codigo_postal, id_mostar_ubicacion, clave_interna');
        $this->db->from('tbldevelopments');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
    
    public function add_development($data)
    {
        unset($data['id']);
        $this->db->insert('tbldevelopments', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Invoice Item development Added [ID:' . $insert_id . ', ' . $data['status'] . ']');
            return $insert_id;
        }
        return false;
    }
    
    public function edit_development($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        $this->db->update('tbldevelopments', $data);
        
        $ids_services = $data['ids_services'];
        unset($data['ids_services']);
        
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item development Updated [ID: ' . $id . ', ' . $data['status'] . ']');
            return true;
        }
        return false;
    }
    
    public function delete_development($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbldevelopments');
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item development Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
    
    public function manage_development_features($data)
    {
        $this->db->where('id_development', $data['id_development']);
        $this->db->delete('tbldevelopmentservices');
        
        $ids_services = json_decode($data['ids_services']);
        foreach ($ids_services as $id_service) {
            $this->db->insert('tbldevelopmentservices', ['id_development' => $data['id_development'],'id_service' => $id_service]);
        }
        return true;
    }
    public function get_development_features($id)
    {
        $this->db->select('id_development, id_service');
        $this->db->from('tbldevelopmentservices');
        $this->db->where('id_development', $id);
        return $this->db->get()->result_array();
    }
    
    
    //manage unities
    public function get_unities($id)
    {
        $this->db->select('tblunities.id, id_item, status, unidad, m2_habitables, balcon, terraza, roofgarden, m2_totales, recamaras, banios, precio, enganche_total, reservacion, contrato, num_mensualidades, saldo_de_enganche, mensualidades, credito');
        $this->db->from('tblunities');
        $this->db->join('tbldevelopments', 'tblunities.id_item = tbldevelopments.id', 'inner');
        $this->db->where('id_item', $id);
        return $this->db->get()->result_array();
    }
    
    public function get_unity($id)
    {
        $this->db->select('id, id_item, status, unidad, m2_habitables, balcon, terraza, roofgarden, m2_totales, recamaras, banios, precio, enganche_total, reservacion, contrato, num_mensualidades, saldo_de_enganche, mensualidades, credito');
        $this->db->from('tblunities');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }
    
    public function add_unity($data)
    {
        unset($data['id']);
        $this->db->insert('tblunities', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Invoice Item Unity Added [ID:' . $insert_id . ', ' . $data['status'] . ']');
            return $insert_id;
        }
        return false;
    }
    
    public function edit_unity($data)
    {
       $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        $this->db->update('tblunities', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Unity Updated [ID: ' . $id . ', ' . $data['status'] . ']');
            return true;
        }
        return false;
    }
    
    public function delete_unity($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblunities');
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Unity Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
}
