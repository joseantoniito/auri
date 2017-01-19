<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inventory_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    //manage locations
    public function get_location_states()
    {
        $activo = 1;
        $this->db->select('id, nombre');
        $this->db->from('estados');
        $this->db->where('activo', $activo);
        return $this->db->get()->result_array();
    }
    public function get_location_municipalities($id)
    {
        $this->db->select('id, nombre');
        $this->db->from('municipios');
        $this->db->where('estado_id', $id);
        return $this->db->get()->result_array();
    }
    public function get_location_colonies($id)
    {
        $this->db->select('id, nombre');
        $this->db->from('localidades');
        $this->db->where('municipio_id', $id);
        return $this->db->get()->result_array();
    }
    
    //manage developments
    public function search_get_developments($data)
    {
        //{"id_estado":"9","id_municipio":"","id_colonia":"","recamaras":"","banios":"","precio_minimo":"","precio_maximo":""}
                
        $this->db->select('id, nombre, logotipo, url_imagen_principal, descripcion, id_tipo_desarrollo, id_etapa_desarrollo, total_de_unidades, id_entrega, entrega, id_estado, id_municipio, id_colonia, direccion, direccion_completa, latitud, longitud, codigo_postal, id_mostar_ubicacion, clave_interna, precio_desde, superficie_terreno_minima, superficie_terreno_maxima, superficie_contruida_minima, superficie_contruida_maxima, recamaras_total, banios_maximo, medios_banios_maximo, estacionamientos_maximo');
        $this->db->from('tbldevelopments');
        
        $key = "id_estado";
        $value = $data[$key];
        if($value != '')
            $this->db->where($key, $value);
         
        $key = "id_municipio";
        $value = $data[$key];
        if($value != '')
            $this->db->where($key, $value);
        
        $key = "id_colonia";
        $value = $data[$key];
        if($value != '')
            $this->db->where($key, $value);
        
        $key = "recamaras";
        $value = $data[$key];
        if($value != '')
            $this->db->where("recamaras_total", $value);
        
        $key = "banios";
        $value = $data[$key];
        if($value != '')
            $this->db->where('banios_maximo', $value);
        
        $key = "precio_minimo";
        $value = $data[$key];
        if($value != '')
            $this->db->where("precio_desde >", $value);
        
        $key = "precio_maximo";
        $value = $data[$key];
        if($value != '')
            $this->db->where("precio_desde <", $value);
        
        return $this->db->get()->result_array();
    }
    
    public function get_developments()
    {
        $this->db->select('id, nombre, logotipo, url_imagen_principal, descripcion, id_tipo_desarrollo, id_etapa_desarrollo, total_de_unidades, id_entrega, entrega, id_estado, id_municipio, id_colonia, direccion, direccion_completa, latitud, longitud, codigo_postal, id_mostar_ubicacion, clave_interna, precio_desde, superficie_terreno_minima, superficie_terreno_maxima, superficie_contruida_minima, superficie_contruida_maxima, recamaras_total, banios_maximo, medios_banios_maximo, estacionamientos_maximo');
        $this->db->from('tbldevelopments');
        return $this->db->get()->result_array();
    }
    
    public function get_development($id)
    {
        $this->db->select('id, nombre, logotipo, url_imagen_principal, descripcion, id_tipo_desarrollo, id_etapa_desarrollo, total_de_unidades, id_entrega, entrega, id_estado, id_municipio, id_colonia, direccion, direccion_completa, latitud, longitud, codigo_postal, id_mostar_ubicacion, clave_interna, precio_desde, superficie_terreno_minima, superficie_terreno_maxima, superficie_contruida_minima, superficie_contruida_maxima, recamaras_total, banios_maximo, medios_banios_maximo, estacionamientos_maximo');
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
        
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item development Updated [ID: ' . $id . ', ' . $data['nombre'] . ']');
            return true;
        }
        return false;
    }
    
    public function delete_development($id)
    {
        $this->db->where('id_item', $id);
        $this->db->delete('tblunities');
        
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
        $id_development = $data['id_development'];
        $this->db->where('id_development', $data['id_development']);
        $this->db->delete('tbldevelopmentfeatures');
        
        $id_type = 1;
        foreach (json_decode($data['ids_services']) as $id_feature) {
            $this->db->insert('tbldevelopmentfeatures', [
                'id_development' => $id_development,
                'id_feature' => $id_feature,
                'id_type' => $id_type
            ]);
        }
            
        $id_type = 2;
        foreach (json_decode($data['ids_general_caracteristics']) as $id_feature) {
            $this->db->insert('tbldevelopmentfeatures', [
                'id_development' => $id_development,
                'id_feature' => $id_feature,
                'id_type' => $id_type
            ]);
        }
            
        $id_type = 3;
        foreach (json_decode($data['ids_social_areas']) as $id_feature) {
            $this->db->insert('tbldevelopmentfeatures', [
                'id_development' => $id_development,
                'id_feature' => $id_feature,
                'id_type' => $id_type
            ]);
        }
            
        $id_type = 4;
        foreach (json_decode($data['ids_outsides']) as $id_feature) {
            $this->db->insert('tbldevelopmentfeatures', [
                'id_development' => $id_development,
                'id_feature' => $id_feature,
                'id_type' => $id_type
            ]);
        }
            
        $id_type = 5;
        foreach (json_decode($data['ids_amenities']) as $id_feature) {
            $this->db->insert('tbldevelopmentfeatures', [
                'id_development' => $id_development,
                'id_feature' => $id_feature,
                'id_type' => $id_type
            ]);
        }
        
        return true;
    }
    
    public function get_development_features($id)
    {
        $this->db->select('id_development, id_feature, id_type');
        $this->db->from('tbldevelopmentfeatures');
        $this->db->where('id_development', $id);
        return $this->db->get()->result_array();
    }
    
    public function add_media_item($data){
        $this->db->insert('tblmediaitems', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) 
            return $insert_id;
        return false;
    }
    
    public function delete_media_item($id){
        $this->db->where('id', $id);
        $this->db->delete('tblmediaitems');
        if ($this->db->affected_rows() > 0) {
            return $id;
        }
        return 0;
    }
        
    public function add_development_media_item($data){
        $this->db->insert('tbldevelopmentmediaitems', $data);
        
        $this->db->select('id, url, name, id_type');
        $this->db->from('tblmediaitems');
        $this->db->join('tbldevelopmentmediaitems', 'tblmediaitems.id = tbldevelopmentmediaitems.id_media_item', 'inner');
        $this->db->where('id_development', $data["id_development"]);
        $media_items = $this->db->get()->result_array();
        if(count($media_items) == 1){
            $this->db->where('id', $data["id_development"]);
            $this->db->update('tbldevelopments', ['url_imagen_principal'=> $media_items[0]["url"]]);
        }
    }
    
    public function delete_development_media_item($id){
        $this->db->where('id', $id);
        $this->db->delete('tbldevelopmentmediaitems');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    
    public function get_development_media_items($id)
    {
        //#media
        $this->db->select('id, url, name, id_type');
        $this->db->from('tblmediaitems');
        $this->db->join('tbldevelopmentmediaitems', 'tblmediaitems.id = tbldevelopmentmediaitems.id_media_item', 'inner');
        $this->db->where('id_development', $id);
        return $this->db->get()->result_array();
    }
    
    
    //manage unities
    public function get_unities($id)
    {
        $this->db->select('tblunities.id, id_item, status, unidad, m2_habitables, balcon, terraza, roofgarden, m2_totales, recamaras, banios, estacionamientos, precio, enganche_total, reservacion, contrato, num_mensualidades, saldo_de_enganche, mensualidades, credito');
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
    
    //manage leads
    public function add_lead($data)
    {
        $id_development = $data['id_development'];
        unset($data['id_development']);
        unset($data['id']);
        $this->db->insert('tblleads', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }
}
