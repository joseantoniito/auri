<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php include_once(APPPATH . 'views/admin/includes/alerts.php'); ?>
            <div class="col-md-7" id="survey-add-edit-wrapper">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h4 class="customer-heading-profile bold">Agregar o Editar Desarrollo</h4>
                        </div>
                        
                        <div class="panel-body border-t-grey">
                            <?php echo form_open('admin/inventory/manage_development',array('id'=>'invoice_item_form_1')); ?>
                                
                            <?php echo form_hidden('development_id',$id); ?>
                            <?php $value=( isset($item) ? $item->nombre : ''); ?>
                            <?php echo render_input( 'nombre', 'Nombre',$value); ?>
                            <!--<?php $value=( isset($item) ? $item->logotipo : ''); ?>
                            <?php echo render_input( 'logotipo', 'Logotipo',$value); ?>-->
                            <?php $value=( isset($item) ? $item->descripcion : ''); ?>
                            <?php echo render_textarea( 'descripcion', 'Descripción',$value); ?>
                            <div class="row padTop32">
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->id_tipo_desarrollo : ''); ?>
                                    <?php echo render_select('id_tipo_desarrollo',$items_tipo_desarrollo,array('id','nombre'),'Tipo de desarrollo',$value); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->id_tipo_desarrollo : ''); ?>
                                    <?php echo render_select('id_etapa_desarrollo',$items_etapa_desarrollo,array('id','nombre'),'Etapa de desarrollo',$value); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php $value=( isset($item) ? $item->precio_desde : ''); ?>
                                    <?php echo render_input( 'precio_desde', 'Precio Desde',$value); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php $value=( isset($item) ? $item->total_de_unidades : ''); ?>
                                    <?php echo render_input( 'total_de_unidades', 'Total de Unidades',$value); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php $value=( isset($item) ? $item->entrega : ''); ?>
                                    <label for="date_picker_entrega" class="control-label">Entrega</label>
                                    <input name="entrega" id="date_picker_entrega" value="<?php echo $value ?>" />
                                    <!--<?php echo render_select('id_entrega',$items_tipos_entrega,array('id','nombre'),'Entrega',$value); ?>-->
                                </div>
                            </div>
                            <div id="container_locations" class="padTop32">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dropdown_estados" class="control-label">Estados</label>
                                            <?php $value=( isset($item) ? $item->id_estado : ''); ?>
                                            <?php echo form_hidden('id_estado',$value); ?>
                                            <input id="dropdown_estados" value='<?php echo $item->id_estado ?>' />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dropdown_municipios" class="control-label">Municipios</label>
                                            <?php $value=( isset($item) ? $item->id_municipio : ''); ?>
                                            <?php echo form_hidden('id_municipio',$value); ?>
                                            <input id="dropdown_municipios" value='<?php echo $item->id_municipio ?>' />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dropdown_colonias" class="control-label">Colonias</label>
                                            <?php $value=( isset($item) ? $item->id_colonia : ''); ?>
                                            <?php echo form_hidden('id_colonia',$value); ?>
                                            <input id="dropdown_colonias" value='<?php echo $item->id_colonia ?>' />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php $value=( isset($item) ? $item->direccion : ''); ?>
                                        <?php echo render_input( 'direccion', 'Dirección',$value); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php $value=( isset($item) ? $item->codigo_postal : ''); ?>
                                        <?php echo render_input( 'codigo_postal', 'Código Postal',$value); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <div style="height:25px;"></div>
                                        <span id="btn_get_position" class="btn btn-info"> OBTENER </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php $value=( isset($item) ? $item->latitud : ''); ?>
                                        <?php echo form_hidden('latitud',$value); ?>
                                        <?php $value=( isset($item) ? $item->longitud : ''); ?>
                                        <?php echo form_hidden('longitud',$value); ?>
                                        <?php $value=( isset($item) ? $item->direccion_completa : ''); ?>
                                        <?php echo form_hidden('direccion_completa',$value); ?>
                                        <div id="map_locations" style="height: 260px;"></div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="row padTop32">
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->superficie_terreno_minima : ''); ?>
                                    <?php echo render_input( 'superficie_terreno_minima', 'Superficie de Terreno Mínima',$value); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->superficie_terreno_maxima : ''); ?>
                                    <?php echo render_input( 'superficie_terreno_maxima', 'Superficie de Terreno Máxima',$value); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->superficie_contruida_minima : ''); ?>
                                    <?php echo render_input( 'superficie_contruida_minima', 'Superficie Construida Mínima',$value); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $value=( isset($item) ? $item->superficie_contruida_maxima : ''); ?>
                                    <?php echo render_input( 'superficie_contruida_maxima', 'Superficie Construida Máxima',$value); ?>
                                </div>
                            </div>
                            <div class="row padTop32">
                                <div class="col-md-3">
                                    <?php $value=( isset($item) ? $item->recamaras_total : ''); ?>
                                    <?php echo render_input( 'recamaras_total', 'Total de Recámaras',$value); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php $value=( isset($item) ? $item->banios_maximo : ''); ?>
                                    <?php echo render_input( 'banios_maximo', 'Máximo de Baños',$value); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php $value=( isset($item) ? $item->medios_banios_maximo : ''); ?>
                                    <?php echo render_input( 'medios_banios_maximo', 'Máximo de Medio-Baños',$value); ?>
                                </div>
                                <div class="col-md-3">
                                    <?php $value=( isset($item) ? $item->estacionamientos_maximo : ''); ?>
                                    <?php echo render_input( 'estacionamientos_maximo', 'Máximo de Estacionamientos',$value); ?>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        
                            
                    </div>
                </div>
            </div>
            <div id="additional_features" class="col-md-5">
                <div id="annoucement" class="panel_s">
                    <div class="panel-body">
                        <h4 class="bold no-margin font-medium">
                         Unidades, Características y Multimedia</h4>
                        <hr>
                        <p class="no-margin">Es necesario crear el desarrollo, entonces usted será capaz de insertar las características.</p>
                    </div>
                </div>
                
                <div id="unitites" class="panel_s" style="display:none;">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h4 class="customer-heading-profile bold margin-b-grey">Unidades</h4>
                        </div>
                        
                        <div class="panel-body _buttons ">
                            <span class="btn btn-info pull-left" 
                               id="btn_add_unity">Nueva Unidad</span>
                        </div>
                        
                        <div id="grid_unities"></div>
                        
                        <div id="window_unity">
                            <?php echo form_open('admin/inventory/manage_unity',array('id'=>'unity_form')); ?>
                            <?php echo form_hidden( 'item_id',$id); ?>
                            <?php echo form_hidden( 'unity_id', ''); ?>
                            <div class="panel_s" style="min-height:300px;">
                                <div id="details" class="row padTop32">
                                    <div class="col-md-6">
                                        <?php echo render_input('precio','Precio'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo render_input('unidad','Unidad'); ?>
                                    </div>
                                </div>
                                <div id="details2" class="row padTop32">
                                    <div class="col-md-6">
                                        <?php echo render_input('m2_habitables','m2 Habitables'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo render_input('m2_totales','m2 Totales'); ?>
                                    </div>
                                </div>
                                <div id="features" class="row padTop32">
                                    <div class="col-md-4">
                                        <label for="balcon" class="control-label">Balcon</label>
                                        <input type="checkbox" name="balcon" id="balcon" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="terraza" class="control-label">Terraza</label>
                                        <input type="checkbox" name="terraza" id="terraza" />
                                    </div>
                                    <div class="col-md-4">
                                        <label for="roofgarden" class="control-label">Roof Garden</label>
                                        <input type="checkbox" name="roofgarden" id="roofgarden" />
                                    </div>
                                </div>
                                <div id="features_2" class="row padTop32">
                                    <div class="col-md-4">
                                        <?php echo render_input('recamaras','Recámaras'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo render_input('banios','Baños'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo render_input('estacionamientos','Estacionam.'); ?>
                                    </div>
                                </div>
                                <div id="media_items_planos" class="row padTop32">
                                    <div class="col-md-12">
                                        <input id="hdn_unity_media_items" type="hidden" />
                                       <input name="files" id="upload_planos" type="file" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn_close_window_unity" type="button" class="btn btn-default" ><?php echo _l('close'); ?></button>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        
                    </div>
                </div>
                
                <div id="features" class="panel_s" style="display:none;">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h4 class="customer-heading-profile bold margin-b-grey">Características</h4>
                        </div>
                        <div>
                        <?php echo form_open('admin/inventory/manage_development_features',array('id'=>'development_features_form')); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo form_hidden('id_development',$id); ?>
                                    <?php echo form_hidden('item_features', $item_features); ?>
                                    
                                    <h4>Servicios</h4>
                                    <?php echo form_hidden('ids_services','[1,3]'); ?>
                                    <div id="list_view_services"></div>
                                    <div class="height_20"></div>
                                    
                                    <h4>Características Generales</h4>
                                    <?php echo form_hidden('ids_general_caracteristics','[1,3]'); ?>
                                    <div id="list_view_general_caracteristics"></div>
                                    <div class="height_20"></div>                                    
                                </div>
                                <div class="col-md-6">
                                    <h4>Áreas Sociales</h4>
                                    <?php echo form_hidden('ids_social_areas','[1,3]'); ?>
                                    <div id="list_view_social_areas"></div>
                                    <div class="height_20"></div>
                                    
                                    <h4>Exteriores</h4>
                                    <?php echo form_hidden('ids_outsides','[1,3]'); ?>
                                    <div id="list_view_outsides"></div>
                                    <div class="height_20"></div>
                                    
                                    <h4>Amenidades</h4>
                                    <?php echo form_hidden('ids_amenities','[1,3]'); ?>
                                    <div id="list_view_amenities"></div>
                                    <div class="height_20"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                </div>
                        <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                
                <div id="multimedia" class="panel_s" style="display:none;">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h4 class="customer-heading-profile bold margin-b-grey">Multimedia</h4>
                        </div>
                        <div>
                        <?php echo form_open('admin/inventory/manage_development_photos',array('id'=>'development_photos_form')); ?>
                            <div id="upload_photos_container" class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                    <?php echo form_hidden('id_development',$id); ?>
                                    <?php echo form_hidden('item_media_items', $item_media_items); ?>
                                    
                                    <?php echo form_hidden('ids_multimedia','[1,3]'); ?>
                                    <input name="files" id="upload_photos" type="file" />
                                    </div>
                                    
                                    <div class="row">
                                        <div class="height_32"></div>
                                        <div class="panel-heading">
                                        <h4 class="customer-heading-profile bold margin-b-grey">Video</h4>
                                        </div>
                                        <input name="files" id="upload_video" type="file" />
                                        <div class="height_20"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- multimedia -->
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
