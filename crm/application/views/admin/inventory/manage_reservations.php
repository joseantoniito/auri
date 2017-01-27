<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
            <?php if(has_permission('items','','create')){ ?>
            <div class="panel_s" style="display:none;">
              <div class="panel-body _buttons">
                <a href="item" class="btn btn-info pull-left" >Nueva Reservación</a>
              </div>

            </div>
            <?php } ?>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <input id="hdn_has_permission_edit" type="hidden" 
                           value="<?php echo has_permission('items','','edit') ?>"  />
                    
                    <div class="row">
                        <label for="dropdown_status" class="control-label">Status</label>
                        <input id="dropdown_status" value='' />
                    </div>
                    <div class="row padTop32">
                        <div id="grid_reservations"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div id="window_reservation">
    <form action="/perfex_crm/crm/admin/inventory/manage_reservation_admin" id="reservation_form" method="post" accept-charset="utf-8" novalidate="novalidate">
        <input type="hidden" name="id" />
        <input type="hidden" name="id_development" />
        <input type="hidden" name="id_unity" />
        <input type="hidden" name="id_lead" />
        <input type="hidden" name="id_assessor" />
        <input type="hidden" name="status" />
        <div class="panel_s" style="min-height:100px;">
            <div id="" class="row padTop32">
                <div class="col-md-12">
                    <label for="dropdown_unities" class="control-label">Desarrollo</label>
                    <span id="development_name"></span>
                </div>
            </div>
            <div id="" class="row">
                <div class="col-md-12">
                    <label for="development_name" class="control-label">Unidad</label>
                    <span id="unity_name"></span>
                </div>
            </div>
            <div id="reservation_in_validation" class="row reservation_item" style="display:none;">
                <div class="col-md-12">
                    <div id="list_view_reservation_docs"></div>
                    <script type="text/x-kendo-template"  id="reservation_docs_template">
                        <div class="col-md-3">
                            <a href="/perfex_crm/crm/#:url#" target="_blank">
                                <span>#:name#</span> 
                            </a>
                            <span>#:name.split('.')[name.split('.').length - 1]#</span> 
                        </div> 
                        <!--fin template-->
                    </script>
                </div>
            </div>
            <div id="reservation_avaiable" class="row reservation_item" style="display:none;">
                <div class="col-md-12">
                    <label for="dropdown_assessors" class="control-label">Asesor</label>
                    <input id="dropdown_assessors" value='' />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="btn_close_window_reservation" type="button" class="btn btn-default" ><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
    </form>
</div>
  
  
<?php init_tail(); ?>
</body>
</html>
