<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contracts_model extends CRM_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * @param  integer ID (optional)
     * @return mixed
     * Get contract object based on passed id if not passed id return array of all contracts
     */
    public function get($id = '', $where = array(), $for_editor = false)
    {
        $this->db->select('*,tblcontracttypes.name as type_name,tblcontracts.id as id');
        $this->db->where($where);
        $this->db->join('tblcontracttypes', 'tblcontracttypes.id = tblcontracts.contract_type', 'left');
        $this->db->join('tblclients', 'tblclients.userid = tblcontracts.client');
        if (is_numeric($id)) {

            $this->db->where('tblcontracts.id', $id);
            $contract              = $this->db->get('tblcontracts')->row();

            $contract->attachments = $this->get_contract_attachments('', $contract->id);
            if ($for_editor == false) {
                $merge_fields = array();
                $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id));
                $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client));
                $merge_fields = array_merge($merge_fields, get_other_merge_fields());
                foreach ($merge_fields as $key => $val) {
                    if (stripos($contract->content, $key) !== false) {
                        $contract->content = str_ireplace($key, $val, $contract->content);
                    } else {
                        $contract->content = str_ireplace($key, '', $contract->content);
                    }
                }
            }
            return $contract;
        }
        $contracts = $this->db->get('tblcontracts')->result_array();
        $i         = 0;
        foreach ($contracts as $contract) {
            $contracts[$i]['attachments'] = $this->get_contract_attachments('', $contract['id']);
            $i++;
        }
        return $contracts;
    }
    public function get_contracts_years(){
        return $this->db->query('SELECT DISTINCT(YEAR(datestart)) as year FROM tblcontracts')->result_array();
    }
    /**
     * @param  integer ID
     * @return object
     * Retrieve contract attachments from database
     */
    public function get_contract_attachments($attachment_id = '', $id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);
            return $this->db->get('tblfiles')->row();
        }
        $this->db->order_by('dateadded', 'desc');
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'contract');
        return $this->db->get('tblfiles')->result_array();
    }
    /**
     * @param   array $_POST data
     * @return  integer Insert ID
     * Add new contract
     */
    public function add($data)
    {
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['addedfrom'] = get_staff_user_id();

        $data['datestart'] = to_sql_date($data['datestart']);
        unset($data['attachment']);
        if ($data['dateend'] == '') {
            unset($data['dateend']);
        } else {
            $data['dateend'] = to_sql_date($data['dateend']);
        }
        if (isset($data['trash'])) {
            $data['trash'] = 1;
        } else {
            $data['trash'] = 0;
        }
        if (isset($data['not_visible_to_client'])) {
            $data['not_visible_to_client'] = 1;
        } else {
            $data['not_visible_to_client'] = 0;
        }
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        $data = do_action('before_contract_added', $data);
        $this->db->insert('tblcontracts', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }
            do_action('after_contract_added', $insert_id);
            logActivity('New Contract Added [' . $data['subject'] . ']');
            return $insert_id;
        }
        return false;
    }
    /**
     * @param  array $_POST data
     * @param  integer Contract ID
     * @return boolean
     */
    public function update($data, $id)
    {
        $affectedRows      = 0;
        $data['datestart'] = to_sql_date($data['datestart']);
        if ($data['dateend'] == '') {
            $data['dateend'] = NULL;
        } else {
            $data['dateend'] = to_sql_date($data['dateend']);
        }
        if (isset($data['trash'])) {
            $data['trash'] = 1;
        } else {
            $data['trash'] = 0;
        }
        if (isset($data['not_visible_to_client'])) {
            $data['not_visible_to_client'] = 1;
        } else {
            $data['not_visible_to_client'] = 0;
        }
        $_data = do_action('before_contract_updated', array(
            'data' => $data,
            'id' => $id
        ));
        $data  = $_data['data'];
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        $this->db->where('id', $id);
        $this->db->update('tblcontracts', $data);
        if ($this->db->affected_rows() > 0) {
            do_action('after_contract_updated', $id);
            logActivity('Contract Updated [' . $data['subject'] . ']');
            return true;
        }
        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }
    /**
     * @param  integer ID
     * @return boolean
     * Delete contract, also attachment will be removed if any found
     */
    public function delete($id)
    {
        do_action('before_contract_deleted', $id);
        $this->db->where('id', $id);
        $this->db->delete('tblcontracts');
        if ($this->db->affected_rows() > 0) {
            // Delete the custom field values
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'contracts');
            $this->db->delete('tblcustomfieldsvalues');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'contract');
            $attachments = $this->db->get('tblfiles')->result_array();
            foreach ($attachments as $attachment) {
                $this->delete_contract_attachment($attachment['id']);
            }
            $this->db->where('contractid', $id);
            $this->db->delete('tblcontractrenewals');
            // Get related tasks
            $this->load->model('tasks_model');
            $this->db->where('rel_type', 'contract');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get('tblstafftasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }
            logActivity('Contract Deleted [' . $id . ']');
            return true;
        }
        return false;
    }
    public function send_contract_to_client($id, $attachpdf = true, $cc = '')
    {
        $this->load->model('emails_model');
        $contract = $this->get($id);

        if ($attachpdf) {
            $pdf      = contract_pdf($contract);
            $attach = $pdf->Output(slug_it($contract->subject) . '.pdf', 'S');
        }
        $sent_to = $this->input->post('sent_to');
        $send    = false;
        if (is_array($sent_to)) {
            $i = 0;
            foreach ($sent_to as $contact_id) {
                    if ($contact_id != '') {
                        if ($attachpdf) {
                            $this->emails_model->add_attachment(array(
                                'attachment' => $attach,
                                'filename' => slug_it($contract->subject) . '.pdf',
                                'type' => 'application/pdf'
                                ));
                        }
                    if ($this->input->post('email_attachments')) {
                        $_other_attachments = $this->input->post('email_attachments');
                        foreach ($_other_attachments as $attachment) {
                            $_attachment = $this->get_contract_attachments($attachment);
                            $this->emails_model->add_attachment(array(
                                'attachment' => get_upload_path_by_type('contract') . $id . '/' . $_attachment->file_name,
                                'filename' => $_attachment->file_name,
                                'type' => $_attachment->filetype,
                                'read' => true
                                ));
                        }
                    }
                    $contact      = $this->clients_model->get_contact($contact_id);
                    $merge_fields = array();
                    $merge_fields = array_merge($merge_fields, get_client_contact_merge_fields($contract->client, $contact_id));
                    $merge_fields = array_merge($merge_fields, get_contract_merge_fields($id));
                    // Send cc only for the first contact
                    if(!empty($cc) && $i > 0){
                        $cc = '';
                    }
                    if ($this->emails_model->send_email_template('send-contract', $contact->email, $merge_fields, '', $cc)) {
                        $send = true;
                    }
                }
                $i++;
            }
        } else {
            return false;
        }
        if ($send) {
            return true;
        }
        return false;
    }
    public function delete_contract_attachment($attachment_id)
    {
        $deleted    = false;
        $attachment = $this->get_contract_attachments($attachment_id);

        if($attachment){
            if(empty($attachment->external)){
                unlink(get_upload_path_by_type('contract') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if($this->db->affected_rows() > 0){
                $deleted    = true;
                logActivity('Contract Attachment Deleted [ContractID: ' . $attachment->rel_id . ']');
            }

         if(is_dir(get_upload_path_by_type('contract') . $attachment->rel_id)){
                // Check if no attachments left, so we can delete the folder also
            $other_attachments = list_files(get_upload_path_by_type('contract') . $attachment->rel_id);
            if (count($other_attachments) == 0) {
                // okey only index.html so we can delete the folder also
                delete_dir(get_upload_path_by_type('contract') . $attachment->rel_id);
            }
        }
        }


        return $deleted;

    }
    /**
     * Get contract types data for chart
     * @return array
     */
    public function get_contracts_types_chart_data()
    {
        $labels = array();
        $totals = array();
        $types  = $this->get_contract_types();
        foreach ($types as $type) {
            $total_rows_where = array(
                'contract_type' => $type['id'],
                'trash' => 0
            );
            if(is_client_logged_in()){
                $total_rows_where['client'] = get_client_user_id();
                $total_rows_where['not_visible_to_client'] = 0;
            }
            $total_rows = total_rows('tblcontracts', $total_rows_where);
            if($total_rows == 0 && is_client_logged_in()){continue;}
            array_push($labels, $type['name']);
            array_push($totals, $total_rows);
        }
        $chart = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => _l('contract_summary_by_type'),
                    'backgroundColor' => 'rgba(3,169,244,0.2)',
                    'borderColor' => "#03a9f4",
                    'borderWidth'=>1,
                    'data' => $totals
                )
            )
        );
        return $chart;
    }
    /**
     * Get contract types values for chart
     * @return array
     */
    public function get_contracts_types_values_chart_data()
    {
        $labels = array();
        $totals = array();
        $types  = $this->get_contract_types();
        foreach ($types as $type) {
            array_push($labels, $type['name']);
            $total = sum_from_table('tblcontracts', array(
                'where' => array(
                    'contract_type' => $type['id'],
                    'trash' => 0
                ),
                'field' => 'contract_value'
            ));
            if($total == null){
                $total = 0;
            }
            array_push($totals, $total);
        }
        $chart = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => _l('contract_summary_by_type_value'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor' => "#84c529",
                    'tension'=>false,
                    'borderWidth'=>1,
                    'data' => $totals
                )
            )
        );
        return $chart;
    }
    /**
     * Renew contract
     * @param  mixed $data All $_POST data
     * @return mixed
     */
    public function renew($data)
    {
        $data['new_start_date'] = to_sql_date($data['new_start_date']);
        $data['new_end_date']   = to_sql_date($data['new_end_date']);
        $data['date_renewed']   = date('Y-m-d H:i:s');
        $data['renewed_by']     = get_staff_full_name(get_staff_user_id());
        if (!is_date($data['new_end_date'])) {
            unset($data['new_end_date']);
        }
        // get the original contract so we can check if is expiry notified on delete the expiry to revert
        $_contract                         = $this->get($data['contractid']);
        $data['is_on_old_expiry_notified'] = $_contract->isexpirynotified;
        $this->db->insert('tblcontractrenewals', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $this->db->where('id', $data['contractid']);
            $_data = array(
                'datestart' => $data['new_start_date'],
                'contract_value' => $data['new_value'],
                'isexpirynotified' => 0
            );
            if (isset($data['new_end_date'])) {
                $_data['dateend'] = $data['new_end_date'];
            }
            $this->db->update('tblcontracts', $_data);
            if ($this->db->affected_rows() > 0) {
                logActivity('Contract Renewed [ID: ' . $data['contractid'] . ']');
                return true;
            } else {
                // delete the previous entry
                $this->db->where('id', $insert_id);
                $this->db->delete('tblcontractrenewals');
                return false;
            }
        }
        return false;
    }
    /**
     * Delete contract renewal
     * @param  mixed $id         renewal id
     * @param  mixed $contractid contract id
     * @return boolean
     */
    public function delete_renewal($id, $contractid)
    {
        // check if this renewal is last so we can revert back the old values, if is not last we wont do anything
        $this->db->select('id')->from('tblcontractrenewals')->where('contractid', $contractid)->order_by('id', 'desc')->limit(1);
        $query                 = $this->db->get();
        $last_contract_renewal = $query->row()->id;
        $is_last               = false;
        if ($last_contract_renewal == $id) {
            $is_last = true;
            $this->db->where('id', $id);
            $original_renewal = $this->db->get('tblcontractrenewals')->row();
        }
        $this->db->where('id', $id);
        $this->db->delete('tblcontractrenewals');
        if ($this->db->affected_rows() > 0) {
            if ($is_last == true) {
                $this->db->where('id', $contractid);
                $data = array(
                    'datestart' => $original_renewal->old_start_date,
                    'contract_value' => $original_renewal->old_value,
                    'isexpirynotified' => $original_renewal->is_on_old_expiry_notified
                );
                if ($original_renewal->old_end_date != '0000-00-00') {
                    $data['dateend'] = $original_renewal->old_end_date;
                }
                $this->db->update('tblcontracts', $data);
            }
            logActivity('Contract Renewed [RenewalID: ' . $id . ', ContractID: ' . $contractid . ']');
            return true;
        }
        return false;
    }
    /**
     * Get contract renewals
     * @param  mixed $id contract id
     * @return array
     */
    public function get_contract_renewal_history($id)
    {
        $this->db->where('contractid', $id);
        $this->db->order_by('date_renewed', 'asc');
        return $this->db->get('tblcontractrenewals')->result_array();
    }
    /**
     * Add new contract type
     * @since  Version 1.0.3
     * @param mixed $data All $_POST data
     */
    public function add_contract_type($data)
    {
        $this->db->insert('tblcontracttypes', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            logActivity('New Contract Type Added [' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }
    /**
     * Edit contract type
     * @since  Version 1.0.3
     * @param mixed $data All $_POST data
     * @param mixed $id Contract type id
     */
    public function update_contract_type($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tblcontracttypes', $data);
        if ($this->db->affected_rows() > 0) {
            logActivity('Contract Type Updated [' . $data['name'] . ', ID:' . $id . ']');
            return true;
        }
        return false;
    }
    /**
     * @since  Version 1.0.3
     * @param  integer ID (optional)
     * @return mixed
     * Get contract type object based on passed id if not passed id return array of all types
     */
    public function get_contract_types($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get('tblcontracttypes')->row();
        }
        return $this->db->get('tblcontracttypes')->result_array();
    }
    /**
     * @param  integer ID
     * @return mixed
     * Delete contract type from database, if used return array with key referenced
     */
    public function delete_contract_type($id)
    {
        if (is_reference_in_table('contract_type', 'tblcontracts', $id)) {
            return array(
                'referenced' => true
            );
        }
        $this->db->where('id', $id);
        $this->db->delete('tblcontracttypes');
        if ($this->db->affected_rows() > 0) {
            logActivity('Contract Deleted [' . $id . ']');
            return true;
        }
        return false;
    }
}
