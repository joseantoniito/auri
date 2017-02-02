<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$aColumns      = array(
    'staff_id',
    'task_id',
    'start_time',
    'end_time',
    'end_time - start_time',
    'end_time - start_time',
    );
$sIndexColumn  = "id";
$sTable        = 'tbltaskstimers';

$aColumns = do_action('projects_timesheets_table_sql_columns',$aColumns);

$join          = array('LEFT JOIN tblstafftasks ON tblstafftasks.id = tbltaskstimers.task_id');

$join = do_action('projects_timesheets_table_sql_join',$join);

$where = array('WHERE task_id IN (SELECT id FROM tblstafftasks WHERE rel_id="'.$project_id.'" AND rel_type="project")');

if(!has_permission('projects','','create')){
    array_push($where,'AND staff_id='.get_staff_user_id());
}

$staff_ids = $this->_instance->projects_model->get_distinct_tasks_timesheets_staff($project_id);

$_staff_ids = array();

foreach($staff_ids as $s){
    if($this->_instance->input->post('staff_id_'.$s['staff_id'])){
        array_push($_staff_ids,$s['staff_id']);
    }
}

if(count($_staff_ids) > 0){
    array_push($where,'AND staff_id IN ('.implode(', ',$_staff_ids).')');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(
    'tbltaskstimers.id',
    'tblstafftasks.name',
    'billed',
    'billable',
    'status',
    ));
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = array();
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'staff_id') {
            $_data = '<a href="' . admin_url('staff/profile/' . $aRow['staff_id']) . '"> ' . staff_profile_image($aRow['staff_id'], array(
                'staff-profile-image-small mright5'
                )) . '</a>';
            if(has_permission('staff','','edit')){
                $_data .= ' <a href="' . admin_url('staff/member/' . $aRow['staff_id']) . '"> ' . get_staff_full_name($aRow['staff_id']) . '</a>';
            } else {
                $_data .= get_staff_full_name($aRow['staff_id']);
            }

        } else if ($aColumns[$i] == 'task_id') {
            $_data = '<a href="#" onclick="init_task_modal(' . $aRow['task_id'] . '); return false;">' .$aRow['name'] . '</a>';

            if($aRow['billed'] == 1){
                    // hidden is for export
                $_data .=  '<span class="hidden"> - </span><span class="label label-success inline-block pull-right">'._l('task_billed_yes').'</span>';
            } else if($aRow['billable'] == 1 && $aRow['billed'] == 0){
                $_data .=  '<span class="hidden"> - </span> <span class="label label-warning inline-block pull-right">'._l('task_billed_no').'</span>';
            }

            $_data .= '<span class="hidden"> - </span><span class="inline-block pull-right mright5 label label-'.get_status_label($aRow['status']).'" task-status-table="'.$aRow['status'].'">' . format_task_status($aRow['status'],false,true).'</span>';

        } else if ($aColumns[$i] == 'start_time' || $aColumns[$i] == 'end_time') {
            if ($aColumns[$i] == 'end_time' && $_data == NULL) {
                $_data = '';
            } else {
                $_data = strftime(get_current_date_format().' %H:%M', $_data);
            }
        } else {
            if($i == 4){
                if ($_data == NULL) {
                   $_data = seconds_to_time_format(time() - $aRow['start_time']);
               } else {
                $_data = seconds_to_time_format($_data);
            }
        } else {
            if ($_data == NULL) {
               $_data = sec2qty(time() - $aRow['start_time']);
           } else {
            $_data = sec2qty($_data);
        }
    }

}
$row[] = $_data;
}
$task_is_billed = $this->_instance->tasks_model->is_task_billed($aRow['task_id']);
$options = '';
if(($aRow['staff_id'] == get_staff_user_id() || has_permission('projects','','edit'))){

    if(($aRow['staff_id'] == get_staff_user_id() || has_permission('projects','','edit'))){
        if($aRow['end_time'] !== NULL){

            $attrs = array(
                'onclick' => 'edit_timesheet(this,' . $aRow['id'] . ');return false',
                'data-start_time'=>strftime(get_current_date_format() . ' %H:%M',$aRow['start_time']),
                'data-timesheet_task_id'=>$aRow['task_id'],
                'data-timesheet_staff_id'=>$aRow['staff_id'],
                );

            $btn_icon_class = 'btn-default';
            if($aRow['status'] == 5){
                $attrs['disabled'] = true;
                $btn_icon_class .= ' disabled';
            }

            $attrs['data-end_time'] = strftime(get_current_date_format() . ' %H:%M',$aRow['end_time']);
            $icon_btn = icon_btn('#', 'pencil-square-o', $btn_icon_class, $attrs);
            if($aRow['status'] == 5){
                $icon_btn = '<span data-toggle="tooltip" data-title="'._l('task_edit_delte_timesheet_notice',array(($task_is_billed ? _l('task_billed') : _l('task_status_5')),_l('edit'))).'">'.$icon_btn.'</span>';
            }
            $options .= $icon_btn;
        }
    }

    if(!$task_is_billed){
        if ($aRow['end_time'] == NULL && $aRow['staff_id'] == get_staff_user_id()) {
            $options .= icon_btn('#', 'clock-o', 'btn-danger', array(
                'onclick' => 'timer_action(this,' . $aRow['task_id'] . ',' . $aRow['id'] . ');return false',
                'data-toggle' => 'tooltip',
                'data-title' => _l('timesheet_stop_timer')
                ));
        }
    }


    if(has_permission('projects','','delete') || has_permission('tasks','','delete')){
        $btn_icon_class = 'btn-danger _delete';
        $attrs = array();

        if($task_is_billed){
            $btn_icon_class .= ' disabled';
            $attrs['disabled'] = true;
        }

        $icon_btn = icon_btn('tasks/delete_timesheet/'.$aRow['id'], 'remove', $btn_icon_class,$attrs);
        if($task_is_billed){
             $icon_btn = '<span data-toggle="tooltip" data-title="'._l('task_edit_delte_timesheet_notice',array(
                _l('task_billed'),
                _l('delete'))).'">'.$icon_btn.'</span>';
        }

        $options .= $icon_btn;
    }
}

$row[]              = $options;
$output['aaData'][] = $row;
}
