<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
            <?php if(has_permission('items','','create')){ ?>
            <div class="panel_s">
              <div class="panel-body _buttons">
                <a href="inventory/item" class="btn btn-info pull-left" ><?php echo _l('new_invoice_item'); ?></a>
              </div>

            </div>
            <?php } ?>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div id="grid_developments"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
  
<?php init_tail(); ?>
</body>
</html>
