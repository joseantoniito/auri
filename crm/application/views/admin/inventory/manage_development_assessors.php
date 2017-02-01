<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
        <div id="assessors_container" class="col-md-4">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <input id="hdn_has_permission_edit" type="hidden" 
                           value="<?php echo has_permission('items','','edit') ?>"  />
                    <div id="list_view_assessors" class="list_view_assessors"></div>

                    <!----><script type="text/x-kendo-template" id="assessors_template">
                    <div class="row" draggable="true" _id="#:staffid#">
                        <div class="col-md-8">
                            <span class="draganddrop"></span>
                            <img  _src='#: profile_image #' src="#:'/crm/uploads/staff_profile_images/' + staffid + '/small_' + profile_image #" class="img_radius" >
                            <h4 id="assessor_name">#:firstname#</h4>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    </script>
                </div>
            </div>
        </div>
        
      <div id="developments_container" class="col-md-8">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <input id="hdn_has_permission_edit" type="hidden" 
                           value="<?php echo has_permission('items','','edit') ?>"  />
                    
                    <div id="list_view_developments"></div>
                    
                    <!----><script type="text/x-kendo-template" id="developments_template">
                    <div class="row" _id="#:id#">
                        <div class="drop_container"></div>
                        <div class="col-md-2">
                            <img src="/perfex_crm/crm/#: logo #" alt="#:nombre#" />
                        </div>
                        <div class="col-md-10">
                            <div _id_dev="#:id#" id="list_view_development_assessors" class="list_view_assessors"></div>
                        </div>
                    </div>
                    </script>
                    
                    
                    <!--<div id="div1" ></div>
                    <br>
                    <img id="drag1" src="http://www.w3schools.com/html/img_logo.gif" draggable="true"  width="336" height="69">
                    
                    <script>
                    
                    </script>
                    <style>
                    #div1 {
                        width: 350px;
                        height: 70px;
                        padding: 10px;
                        border: 1px solid #aaaaaa;
                    }
                    </style>-->
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
  
<?php init_tail(); ?>
</body>
</html>
