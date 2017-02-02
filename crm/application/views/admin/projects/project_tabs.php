<?php
$project_tabs = array(
    array(
        'name'=>'project_overview',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_overview'),
        'icon'=>'fa fa-th',
        'lang'=>_l('project_overview'),
        'visible'=>true,
        'order'=>1
        ),
    array(
        'name'=>'project_tasks',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_tasks'),
        'icon'=>'fa fa-check-circle',
        'lang'=>_l('tasks'),
        'visible'=>true,
        'order'=>2
        ),
    array(
        'name'=>'project_timesheets',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_timesheets'),
        'icon'=>'fa fa-clock-o',
        'lang'=>_l('project_timesheets'),
        'visible'=>true,
        'order'=>3
        ),
    array(
        'name'=>'project_milestones',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_milestones'),
        'icon'=>'fa fa-rocket',
        'lang'=>_l('project_milestones'),
        'visible'=>true,
        'order'=>4
        ),
    array(
        'name'=>'project_files',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_files'),
        'icon'=>'fa fa-files-o',
        'lang'=>_l('project_files'),
        'visible'=>true,
        'order'=>5
        ),
    array(
        'name'=>'project_discussions',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_discussions'),
        'icon'=>'fa fa-commenting',
        'lang'=>_l('project_discussions'),
        'visible'=>true,
        'order'=>6
        ),
    array(
        'name'=>'project_gantt',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_gantt'),
        'icon'=>'fa fa-line-chart',
        'lang'=>_l('project_gant'),
        'visible'=>true,
        'order'=>7
        ),
    array(
        'name'=>'project_tickets',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_tickets'),
        'icon'=>'fa fa-life-ring',
        'lang'=>_l('project_tickets'),
        'visible'=>(get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member(),
        'order'=>8
        ),
    array(
        'name'=>'project_estimates',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_estimates'),
        'icon'=>'fa fa-sun-o',
        'lang'=>_l('estimates'),
        'visible'=>has_permission('estimates','','view') || has_permission('estimates','','view_own'),
        'order'=>9
        ),
    array(
        'name'=>'project_invoices',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_invoices'),
        'icon'=>'fa fa-sun-o',
        'lang'=>_l('project_invoices'),
        'visible'=>has_permission('invoices','','view') || has_permission('invoices','','view_own'),
        'order'=>10
        ),
    array(
        'name'=>'project_expenses',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_expenses'),
        'icon'=>'fa fa-sort-amount-asc',
        'lang'=>_l('project_expenses'),
        'visible'=>has_permission('expenses','','view') || has_permission('expenses','','view_own'),
        'order'=>11
        ),
    array(
        'name'=>'project_notes',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_notes'),
        'icon'=>'fa fa-clock-o',
        'lang'=>_l('project_notes'),
        'visible'=>true,
        'order'=>12
        ),
    array(
        'name'=>'project_activity',
        'url'=>admin_url('projects/view/'.$project->id.'?group=project_activity'),
        'icon'=>'fa fa-exclamation',
        'lang'=>_l('project_activity'),
        'visible'=>has_permission('projects','','create'),
        'order'=>13
        ),
    );

    $project_tabs = do_action('project_tabs_admin',$project_tabs);
?>
<ul class="nav nav-tabs no-margin project-tabs" role="tablist">
    <?php foreach($project_tabs as $tab){
        if((isset($tab['visible']) && $tab['visible'] == true) || !isset($tab['visible'])){ ?>
        <li class="<?php if($tab['name'] == 'project_overview'){echo 'active';} ?>">
            <a data-group="<?php echo $tab['name']; ?>" href="<?php echo $tab['url']; ?>" role="tab"><i class="<?php echo $tab['icon']; ?>" aria-hidden="true"></i> <?php echo $tab['lang']; ?></a>
        </li>
        <?php } ?>
        <?php } ?>
    </ul>
