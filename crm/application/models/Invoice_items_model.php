<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Invoice_items_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        $this->db->select('tblitems.id as itemid,rate,taxrate,tbltaxes.id as taxid,tbltaxes.name as taxname,description,long_description, tblitems.name, tblitems.address');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes', 'tbltaxes.id = tblitems.tax', 'left');
        $this->db->order_by('description','asc');
        if (is_numeric($id)) {
            $this->db->where('tblitems.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        if ($data['tax'] == '') {
            unset($data['tax']);
        }
        $this->db->insert('tblitems', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');
            return $insert_id;
        }
        return false;
    }
    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);
        $this->db->where('id', $itemid);
        $this->db->update('tblitems', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
            return true;
        }
        return false;
    }
    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tblitems');
        if ($this->db->affected_rows() > 0) {
            logActivity('Invoice Item Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
    /**
     * Get invoice items - ajax call for autocomplete when adding invoicei tems
     * @param  mixed $data query
     * @return array
     */
    public function get_all_items_ajax()
    {
        $this->db->select('tblitems.id as itemid,rate,taxrate,tbltaxes.id as taxid,tbltaxes.name as taxname,description as label,long_description, tblitems.name, tblitems.address');
        $this->db->from('tblitems');
        $this->db->join('tbltaxes', 'tbltaxes.id = tblitems.tax', 'left');
        $this->db->order_by('description','asc');
        return $this->db->get()->result_array();
    }
    
    //manage unities
    public function get_unities($id)
    {
        $this->db->select('tblunities.id, id_item, status, unidad, m2_habitables, balcon, terraza, roofgarden, m2_totales, recamaras, banios, precio, enganche_total, reservacion, contrato, num_mensualidades, saldo_de_enganche, mensualidades, credito');
        $this->db->from('tblunities');
        $this->db->join('tblitems', 'tblunities.id_item = tblitems.id', 'inner');
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
