<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); ?>
            <div class="col-md-5" id="survey-add-edit-wrapper">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo render_input('status','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('m2_habitables','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('recamaras','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('banios','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('precio','invoice_item_add_edit_description'); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-7" id="survey_questions_wrapper">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <?php echo _l('survey_questions_string'); ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url(); ?>assets/js/surveys.js"></script>
<script>
    init_editor('.tinymce-email-description');
    init_editor('.tinymce-view-description');
</script>
</body>
</html>
