<?php $_announcements = get_announcements_for_user();
if(sizeof($_announcements) > 0 && isset($is_home) && is_staff_member()){ ;?>
<div class="col-lg-12">
	<div class="panel_s">
		<?php foreach($_announcements as $__announcement){ ?>
		<div class="panel-body announcement mbot15 tc-content">
			<div class="text-info alert-dismissible" role="alert">
				<a href="<?php echo admin_url('misc/dismiss_announcement/'.$__announcement['announcementid']); ?>" class="close"><span aria-hidden="true">&times;</span></a>
				<?php if(is_admin()){ ?>
				<a href="<?php echo admin_url('announcements/announcement/'.$__announcement['announcementid']); ?>"><i class="fa fa-pencil-square-o pull-right"></i></a>
				<?php } ?>
				<h4 class="bold no-margin font-medium"><?php echo _l('announcement'); ?>! <?php if($__announcement['showname'] == 1){ echo '<br /><small class="font-medium-xs">'._l('announcement_from').' '. $__announcement['userid']; } ?></small><br /><small>Added: <?php echo _dt($__announcement['dateadded']); ?></small></h4>
			</div>
			<hr />
			<h4 class="bold font-medium"><?php echo $__announcement['name']; ?></h4>
			<?php echo check_for_links($__announcement['message']); ?>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
<?php do_action('before_start_render_content'); ?>
