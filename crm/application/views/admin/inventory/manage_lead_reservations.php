
<div id="">
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
                    <div id="grid_reservations"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div id="window_reservation">
    <form action="/perfex_crm/crm/admin/inventory/manage_reservation_lead" id="reservation_form" method="post" accept-charset="utf-8" novalidate="novalidate">
         <input type="hidden" name="id" />
        <input type="hidden" name="id_development" />
        <input type="hidden" name="id_unity" />
        <input type="hidden" name="id_lead" />
        <input type="hidden" name="id_assessor" />
        <input type="hidden" name="status" value="2" />
        <div class="panel_s" style="min-height:300px;">
            <div id="details" class="row padTop32">
                <div class="col-md-12">
                    <label for="dropdown_unities" class="control-label">Desarrollo</label>
                    <span id="development_name"></span>
                </div>
            </div>
            <div id="reservation_avaiable" class="row reservation_item">
                <div class="row">
                    <div class="col-md-12">
                        <label for="dropdown_unities" class="control-label">Unidad</label>
                        <!--<input type="hidden" name="id_unity" />-->
                        <input id="dropdown_unities" value='' />
                    </div>
                </div>
                <div class="row padTop32">
                    <div class="panel-heading">
                        <h4 class="customer-heading-profile bold margin-b-grey">Docs</h4>
                    </div>
                    <input name="files" id="upload_docs" type="file" />
                    <div class="height_20"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="btn_close_window_reservation" type="button" class="btn btn-default" ><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
    </form>
</div>
  


