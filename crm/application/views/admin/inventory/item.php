<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); ?>
            <div class="col-md-5" id="survey-add-edit-wrapper">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h4 class="customer-heading-profile bold">Add or Edit Property</h4>
                        </div>
                        
                        <div class="panel-body border-t-grey">
                            <?php echo form_open('admin/inventory/manage_development',array('id'=>'invoice_item_form_1')); ?>
                                
                                <?php echo form_hidden('development_id',$id); ?>
                                <?php $value=( isset($item) ? $item->nombre : ''); ?>
                                <?php echo render_input( 'nombre', 'nombre',$value); ?>
                                <?php $value=( isset($item) ? $item->logotipo : ''); ?>
                                <?php echo render_input( 'logotipo', 'logotipo',$value); ?>
                                <?php $value=( isset($item) ? $item->descripcion : ''); ?>
                                <?php echo render_textarea( 'descripcion', 'descripcion',$value); ?>
                                <?php $value=( isset($item) ? $item->id_tipo_desarrollo : ''); ?>
                                <?php echo render_select('id_tipo_desarrollo',$items_tipo_desarrollo,array('id','nombre'),'Tipo de desarrollo',$value); ?>
                                <?php $value=( isset($item) ? $item->id_tipo_desarrollo : ''); ?>
                                <?php echo render_select('id_etapa_desarrollo',$items_etapa_desarrollo,array('id','nombre'),'Etapa de desarrollo',$value); ?>
                                <?php $value=( isset($item) ? $item->total_de_unidades : ''); ?>
                                <?php echo render_input( 'total_de_unidades', 'total de unidades',$value); ?>
                                <?php $value=( isset($item) ? $item->id_entrega : ''); ?>
                                <?php echo render_select('id_entrega',$items_tipos_entrega,array('id','nombre'),'Entrega',$value); ?>
                            
                                <?php $value=( isset($item) ? $item->id_estado : ''); ?>
                                <?php echo render_select('id_estado',$items_estados,array('id','nombre'),'Estado',$value); ?>
                                <?php $value=( isset($item) ? $item->id_municipio : ''); ?>
                                <?php echo render_select('id_municipio',$items_municipios,array('id','nombre'),'Municipio',$value); ?>
                                <?php $value=( isset($item) ? $item->id_colonia : ''); ?>
                                <?php echo render_select('id_colonia',$items_colonias,array('id','nombre'),'Colonia',$value); ?>
                            
                                <?php $value=( isset($item) ? $item->direccion : ''); ?>
                                <?php echo render_input( 'direccion', 'Dirección',$value); ?>
                                <?php $value=( isset($item) ? $item->codigo_postal : ''); ?>
                                <?php echo render_input( 'codigo_postal', 'Código postal',$value); ?>
    
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                        
                            
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <!--<?php echo _l('survey_questions_string'); ?>-->
                            
                            <h4 class="customer-heading-profile bold margin-b-grey">Unities</h4>
                        </div>
                        
                        <div class="panel-body _buttons ">
                            <span class="btn btn-info pull-left" 
                               id="btn_add_unity"><?php echo _l('new_invoice_item'); ?></span>
                        </div>
                        
                        <div id="grid_unities"></div>
                        
                        <div id="window_unity">
                            <?php echo form_open('admin/invoice_items/manage_unity',array('id'=>'unity_form')); ?>
                            <?php echo form_hidden( 'item_id',$id); ?>
                            <?php echo form_hidden( 'unity_id', ''); ?>
                            <div class="panel_s" style="min-height:200px;">
                                <?php echo render_input('status','status'); ?>
                                <?php echo render_input('m2_habitables','m2_habitables'); ?>
                                <?php echo render_input('recamaras','recamaras'); ?>
                                <?php echo render_input('banios','banios'); ?>
                                <?php echo render_input('precio','precio'); ?>

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
