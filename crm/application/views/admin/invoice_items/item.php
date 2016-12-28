<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('invoice_item_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('invoice_item_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/invoice_items/manage',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <?php echo render_input('description','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('rate','invoice_item_add_edit_rate','','number'); ?>
                        <?php echo render_textarea('long_description','invoice_item_long_description'); ?>
                        <div class="form-group">
                            <label class="control-label" for="tax"><?php echo _l('invoice_item_add_edit_tax'); ?> !!! </label>
                            <select class="selectpicker display-block" data-width="100%" name="tax" title='<?php echo _l('invoice_item_add_edit_tax_select'); ?>' data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <?php foreach($taxes as $tax){ ?>
                                    <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php echo render_input('address','invoice_item_add_edit_address'); ?>
                        </div>
                    
                    <div class="col-md-7">
                        <div class="panel_s">
                            <div class="panel-body _buttons">
                                <span class="btn btn-info pull-left" 
                                   id="btn_add_unity"><?php echo _l('new_invoice_item'); ?></span>
                            </div>
                        </div>
                        
                        <div id="grid_unities"></div>
                        
                        
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                    <?php echo form_close(); ?>
                </div>
            
            
                <div id="window_unity">
                    <?php echo form_open('admin/invoice_items/manage_unity',array('id'=>'unity_form')); ?>
                    <?php echo form_hidden('itemid'); ?>
                    <div class="panel_s" style="min-height:200px;">
                        <?php echo render_input('status','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('m2_habitables','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('recamaras','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('banios','invoice_item_add_edit_description'); ?>
                        <?php echo render_input('precio','invoice_item_add_edit_description'); ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" ><?php echo _l('close'); ?></button>
                        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
