   <li data-task-id="<?php echo $task['id']; ?>">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12 task-name">
          <a href="#" onclick="init_task_modal(<?php echo $task['id']; ?>);return false;">
            <span class="inline-block full-width mtop10 mbot10"><?php echo $task['name']; ?></span>
          </a>
        </div>
        <div class="col-md-6 text-muted">
          <?php
          $assignees = $this->tasks_model->get_task_assignees($task['id']);
          foreach($assignees as $assignee){
           echo '<a href="' . admin_url('profile/' . $assignee['assigneeid']) . '">' . staff_profile_image($assignee['assigneeid'], array(
            'staff-profile-image-small mright5'
            ), 'small', array(
            'data-toggle' => 'tooltip',
            'data-title' => $assignee['firstname'] . ' ' . $assignee['lastname']
            )) . '</a>';
          } ?>
        </div>
        <div class="col-md-6 text-right text-muted">
          <small class="text-dark"><?php echo _l('task_single_start_date'); ?>: <span class="bold"><?php echo _d($task['startdate']); ?></span></small><br />
          <?php if(is_date($task['duedate'])){ ?>
          <small class="text-dark"><?php echo _l('task_single_due_date'); ?>: <span class="bold"><?php echo _d($task['duedate']); ?></span></small><br />
          <?php } ?>
          <?php if(total_rows('tbltaskchecklists') > 0){ ?>
          <span class="mright5 mtop5 inline-block text-muted" data-toggle="tooltip" data-title="<?php echo _l('task_checklist_items'); ?>">
            <i class="fa fa-check-square-o" aria-hidden="true"></i> <?php echo total_rows('tbltaskchecklists', array(
              'taskid' => $task['id'],
              'finished' => 1,
              ));; ?>
              /
              <?php echo total_rows('tbltaskchecklists', array(
              'taskid' => $task['id'],
              ));; ?>
            </span>
            <?php } ?>
            <span class="mright5 mtop5 inline-block text-muted" data-toggle="tooltip" data-title="<?php echo _l('task_comments'); ?>">
              <i class="fa fa-comments"></i> <?php echo total_rows('tblstafftaskcomments', array(
                'taskid' => $task['id'],
                ));; ?>
              </span>
              <?php $total_attachments = total_rows('tblfiles', array(
                'rel_id' => $task['id'],
                'rel_type'=>'task',
                )); ?>
                <span class="mtop5 inline-block text-muted" data-toggle="tooltip" data-title="<?php echo _l('task_view_attachments'); ?>">
                 <i class="fa fa-paperclip"></i>
                 <?php echo $total_attachments; ?>
               </span>
             </div>
           </div>
         </div>
       </li>
