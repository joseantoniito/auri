<div class="row">
    <div class="col-md-6 project-overview-column">
        <div class="panel-heading project-info-bg no-radius"><?php echo _l('project_overview'); ?></div>
        <div class="panel-body no-radius">
            <div class="row">
            <div class="col-md-12">
                          <?php if(has_permission('projects','','create')){ ?>
            <p class="pull-left mleft10"><a href="<?php echo admin_url('projects/export_project_data/'.$project->id); ?>" target="_blank"><i class="fa fa-file-pdf-o"></i> <?php echo _l('export_project_data'); ?></a>&nbsp;<span class="text-muted">|</span>&nbsp;</p>
        <?php } ?>
      <?php
                if(has_permission('projects','','create') || has_permission('projects','','edit') || has_permission('customers','','view')){ ?>
            <a href="<?php echo admin_url('projects/view_project_as_client/'.$project->id .'/'.$project->clientid); ?>" target="_blank"><?php echo _l('project_view_as_client'); ?></a>
            <?php } ?>
            </div>
                <div class="col-md-7">
                    <table class="table table-borded no-margin">
                        <tbody>
                        <?php if(has_permission('customers','','view') || is_customer_admin($project->clientid) || has_permission('projects','','create') || has_permission('projects','','edit')){ ?>
                            <tr>
                                <td class="bold"><?php echo _l('project_customer'); ?></td>
                                <td><a href="<?php echo admin_url(); ?>clients/client/<?php echo $project->clientid; ?>"><?php echo $project->client_data->company; ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                            <?php if(has_permission('projects','','create') || has_permission('projects','','edit')){ ?>
                            <tr>
                                <td class="bold"><?php echo _l('project_billing_type'); ?></td>
                                <td>
                                    <?php
                                        if($project->billing_type == 1){
                                          $type_name = 'project_billing_type_fixed_cost';
                                        } else if($project->billing_type == 2){
                                          $type_name = 'project_billing_type_project_hours';
                                        } else {
                                          $type_name = 'project_billing_type_project_task_hours';
                                        }
                                        echo _l($type_name);
                                        ?>
                                </td>
                                <?php if($project->billing_type == 1 || $project->billing_type == 2){
                                    echo '<tr>';
                                    if($project->billing_type == 1){
                                      echo '<td class="bold">'._l('project_total_cost').'</td>';
                                      echo '<td>'.format_money($project->project_cost,$currency->symbol).'</td>';
                                    } else {
                                      echo '<td class="bold">'._l('project_rate_per_hour').'</td>';
                                      echo '<td>'.format_money($project->project_rate_per_hour,$currency->symbol).'</td>';
                                    }
                                    echo '<tr>';
                                    }
                                    }
                                    ?>
                            <tr>
                                <td class="bold"><?php echo _l('project_status'); ?></td>
                                <td><?php echo _l('project_status_'.$project->status); ?></td>
                            </tr>
                            <tr>
                                <td class="bold"><?php echo _l('project_datecreated'); ?></td>
                                <td><?php echo _d($project->project_created); ?></td>
                            </tr>
                            <tr>
                                <td class="bold"><?php echo _l('project_start_date'); ?></td>
                                <td><?php echo _d($project->start_date); ?></td>
                            </tr>
                            <tr>
                                <td class="bold"><?php echo _l('project_deadline'); ?></td>
                                <td><?php echo _d($project->deadline); ?></td>
                            </tr>
                            <?php if($project->billing_type == 1 && (has_permission('projects','','create'))){ ?>
                            <tr>
                                <td class="bold"><?php echo _l('project_overview_total_logged_hours'); ?></td>
                                <td><?php echo seconds_to_time_format($this->projects_model->total_logged_time($project->id)); ?></td>
                            </tr>
                            <?php } ?>
                            <?php $custom_fields = get_custom_fields('projects');
                                if(count($custom_fields) > 0){ ?>
                            <?php foreach($custom_fields as $field){ ?>
                            <?php $value = get_custom_field_value($project->id,$field['id'],'projects');
                                if($value == ''){continue;} ?>
                            <tr>
                                <td class="bold"><?php echo ucfirst($field['name']); ?></td>
                                <td><?php echo $value; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5 text-center project-percent-col">
                    <p class="bold text-muted"><?php echo _l('project'). ' ' . _l('project_progress'); ?></p>
                    <div class="project-progress relative mtop15" data-value="<?php echo $percent_circle; ?>" data-size="170" data-thickness="22" data-reverse="true">
                        <strong class="project-percent"></strong>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6 project-overview-column">
        <div class="row project-progress-bars">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-9">
                      <p class="text-uppercase bold text-dark font-medium">
                         <?php echo $project_days_left; ?> / <?php echo $project_total_days; ?> <?php echo _l('project_days_left'); ?>
                     </p>
                     <p class="text-muted bold"><?php echo round($project_time_left_percent,2); ?>%</p>

                 </div>
                 <div class="col-md-3 text-right">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                </div>
                <div class="col-md-12 mtop5">
                 <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo round($project_time_left_percent,2); ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo round($project_time_left_percent,2); ?>">
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="row">
        <div class="col-md-9">
          <p class="text-uppercase bold text-dark font-medium">
             <?php echo $tasks_not_completed; ?> / <?php echo $total_tasks; ?> <?php echo _l('project_open_tasks'); ?>
         </p>
         <p class="text-muted bold"><?php echo round($tasks_not_completed_progress,2); ?>%</p>

     </div>
     <div class="col-md-3 text-right">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
   </div>
   <div class="col-md-12 mtop5">
     <div class="progress no-margin progress-bar-mini">
      <div class="progress-bar light-green-bg no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo round($tasks_not_completed_progress,2); ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo round($tasks_not_completed_progress,2); ?>">
      </div>
  </div>
</div>
</div>
</div>
</div>
        <hr />
        <?php if(has_permission('projects','','create')) { ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                if($project->billing_type == 3 || $project->billing_type == 2){ ?>
                <div class="row">
                    <div class="col-md-3">
                        <?php
                            $data = $this->projects_model->total_logged_time_by_billing_type($project->id);
                         ?>
                        <p class="text-uppercase text-muted"><?php echo _l('project_overview_logged_hours'); ?> <span class="bold"><?php echo $data['logged_time']; ?></span></p>
                        <p class="bold font-medium"><?php echo format_money($data['total_money'],$currency->symbol); ?></p>
                    </div>
                    <div class="col-md-3">
                        <?php
                            $data = $this->projects_model->data_billable_time($project->id);
                        ?>
                        <p class="text-uppercase text-info"><?php echo _l('project_overview_billable_hours'); ?> <span class="bold"><?php echo $data['logged_time'] ?></span></p>
                        <p class="bold font-medium"><?php echo format_money($data['total_money'],$currency->symbol); ?></p>
                    </div>
                    <div class="col-md-3">
                        <?php
                            $data = $this->projects_model->data_billed_time($project->id);
                         ?>
                        <p class="text-uppercase text-success"><?php echo _l('project_overview_billed_hours'); ?> <span class="bold"><?php echo $data['logged_time']; ?></span></p>
                        <p class="bold font-medium"><?php echo format_money($data['total_money'],$currency->symbol); ?></p>
                    </div>
                    <div class="col-md-3">
                        <?php
                            $data = $this->projects_model->data_unbilled_time($project->id);
                         ?>
                        <p class="text-uppercase text-danger"><?php echo _l('project_overview_unbilled_hours'); ?> <span class="bold"><?php echo $data['logged_time']; ?></span></p>
                        <p class="bold font-medium"><?php echo format_money($data['total_money'],$currency->symbol); ?></p>
                    </div>
                </div>
                 <hr />
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
              <p class="text-uppercase text-muted"><?php echo _l('project_overview_expenses'); ?></p>
              <p class="bold font-medium"><?php echo format_money(sum_from_table('tblexpenses',array('where'=>array('project_id'=>$project->id),'field'=>'amount')),$currency->symbol); ?></p>
            </div>
            <div class="col-md-3">
              <p class="text-uppercase text-info"><?php echo _l('project_overview_expenses_billable'); ?></p>
              <p class="bold font-medium"><?php echo format_money(sum_from_table('tblexpenses',array('where'=>array('project_id'=>$project->id,'billable'=>1),'field'=>'amount')),$currency->symbol); ?></p>
            </div>
            <div class="col-md-3">
              <p class="text-uppercase text-success"><?php echo _l('project_overview_expenses_billed'); ?></p>
              <p class="bold font-medium"><?php echo format_money(sum_from_table('tblexpenses',array('where'=>array('project_id'=>$project->id,'invoiceid !='=>'NULL','billable'=>1),'field'=>'amount')),$currency->symbol); ?></p>
            </div>
            <div class="col-md-3">
              <p class="text-uppercase text-danger"><?php echo _l('project_overview_expenses_unbilled'); ?></p>
              <p class="bold font-medium"><?php echo format_money(sum_from_table('tblexpenses',array('where'=>array('project_id'=>$project->id,'invoiceid IS NULL','billable'=>1),'field'=>'amount')),$currency->symbol); ?></p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="panel-heading project-info-bg no-radius"><?php echo _l('project_description'); ?></div>
        <div class="panel-body no-radius tc-content">
            <?php echo check_for_links($project->description); ?>
        </div>
    </div>
    <div class="col-md-6 team-members">
        <div class="panel-heading project-info-bg no-radius"><?php echo _l('project_members'); ?>
            <?php if(has_permission('projects','','edit') || has_permission('projects','','create')){ ?>
            <a href="#" class="btn btn-info pull-right btn-xs" data-toggle="modal" data-target="#add-edit-members"><?php echo _l('add_edit_members'); ?></a>
            <?php } ?>
        </div>
        <div class="panel-body no-radius">
            <?php
                foreach($members as $member){
                  $member_tasks_assigned = $this->tasks_model->get_tasks_by_staff_id($member['staff_id'],array('rel_id'=>$project->id,'rel_type'=>'project'));

                  $seconds = 0;
                  foreach($member_tasks_assigned as $member_task){
                    $seconds += $this->tasks_model->calc_task_total_time($member_task['id'],' AND staff_id='.$member['staff_id']);
                  }

                  ?>
            <div class="media">
                <div class="media-left">
                    <a href="<?php echo admin_url('profile/'.$member["staff_id"]); ?>">
                    <?php echo staff_profile_image($member['staff_id'],array('staff-profile-image-small','media-object')); ?>
                    </a>
                </div>
                <div class="media-body">
                    <?php if(has_permission('projects','','edit') || has_permission('projects','','create')){ ?>
                    <a href="<?php echo admin_url('projects/remove_team_member/'.$project->id.'/'.$member['staff_id']); ?>" class="pull-right text-danger _delete"><i class="fa fa fa-times"></i></a>
                    <?php } ?>

                    <h5 class="media-heading mtop5"><a href="<?php echo admin_url('profile/'.$member["staff_id"]); ?>"><?php echo get_staff_full_name($member['staff_id']); ?></a>
                    <?php if(has_permission('projects','','create') || $member['staff_id'] == get_staff_user_id()){ ?>
                    <br /><small class="text-muted"><?php echo _l('total_logged_hours_by_staff') .': '.seconds_to_time_format($seconds); ?></small>
                    <?php } ?>
                    </h5>

                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal fade" id="add-edit-members" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('projects/add_edit_members/'.$project->id)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('project_members'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    $selected = array();
                    foreach($members as $member){
                      array_push($selected,$member['staff_id']);
                    }
                    echo render_select('project_members[]',$staff,array('staffid',array('firstname','lastname')),'project_members',$selected,array('multiple'=>true));
                    ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
