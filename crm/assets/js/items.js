$(function() {
    //#kendo developments
    var window_unity; 
    var grid_unities;
    var services = [
        {id: 1, nombre: 'Circuito cerrado'},
        {id: 2, nombre: 'Gimnasio'},
        {id: 3, nombre: 'Internet/Wifi'},
        {id: 4, nombre: 'Mesa de boliche'},
        {id: 5, nombre: 'Planta de emergencia'},
        {id: 6, nombre: 'Área de juegos infantiles'},
        {id: 7, nombre: 'Seguridad contra incendios'},
        {id: 8, nombre: 'Spa'},
        {id: 9, nombre: 'Valet parking'},
        {id: 10, nombre: 'Conmutador'},
    ];
    var general_caracteristics = [
        {id: 1, nombre: 'Acabados de lujo'},
        {id: 2, nombre: 'Alberca'},
        {id: 3, nombre: 'Elevador(es)'},
        {id: 4, nombre: 'Jacuzzi'},
        {id: 5, nombre: 'Jadin(es)'},
        {id: 6, nombre: 'Seguridad'},
        {id: 7, nombre: 'Centros comerciales cercanos'},
        {id: 8, nombre: 'Escuelas cercanas'},
        {id: 9, nombre: 'Frente a parque'},
    ];
    var social_areas = [
        {id: 1, nombre: 'Lobby'},
    ];
    var outsides = [
        {id: 1, nombre: 'Árboles'},
        {id: 2, nombre: 'Cancha de golf'},
        {id: 3, nombre: 'Cancha de paddle'},
        {id: 4, nombre: 'Cancha de squash'},
        {id: 5, nombre: 'Cancha de tennis'},
        {id: 6, nombre: 'Carril de nado'},
        {id: 7, nombre: 'Estacionamiento de visitas'},
        {id: 8, nombre: 'Pista de jogging'},
        {id: 9, nombre: 'Roof garden'},
        {id: 10, nombre: 'Sky garden'},
    ];
    var amenities = [
        {id: 1, nombre: 'Bodega(s)'},
        {id: 2, nombre: 'Business Center'},
        {id: 3, nombre: 'Cine'},
        {id: 4, nombre: 'Salón de fiestas'},
        {id: 5, nombre: 'Salón de usos múltiples'},
        {id: 6, nombre: 'Sauna'},
        {id: 7, nombre: 'Vapor'},
    ];
    
    $(document).ready(function() {
        //#kendo
        console.log( "ready!" );
        if(window.location.href.indexOf("/admin/inventory/") != -1 &&
          window.location.href.indexOf("/admin/inventory/") == window.location.href.length - 17 ){
            console.log("Ready developments!!!");
            create_grid_developments();
        }
        else if(window.location.href.indexOf("/admin/inventory/item") != -1){
            var id = $("[name='item_id']").val()
            if(id)
                create_grid_unities(id);
            
            //development features
            _validate_form($('#development_features_form'), {
                precio: 'required'
            }, manage_development_features);
            var hdn_item_features = $("[name='item_features']");
            var item_features = JSON.parse(hdn_item_features.val());
            hdn_item_features.val("");
            create_checkbox_listview("list_view_services", services, "ids_services", item_features, 1);
            create_checkbox_listview("list_view_general_caracteristics", general_caracteristics, "ids_general_caracteristics", item_features, 2);
            create_checkbox_listview("list_view_social_areas", social_areas, "ids_social_areas", item_features, 3);
            create_checkbox_listview("list_view_outsides", outsides, "ids_outsides", item_features, 4);
            create_checkbox_listview("list_view_amenities", amenities, "ids_amenities", item_features, 5);
            
            //development unities
            window_unity = 
                $("#window_unity").kendoWindow({
                    width: "600px",
                    title: "Manage Unity",
                    visible: false,
                }).data("kendoWindow");
            $("#btn_add_unity").on("click", function(sender){
                 
                window_unity.center().open();
            });
            _validate_form($('#unity_form'), {
                address: 'required'
            }, manage_unity);
            
            //development item
            _validate_form($('#invoice_item_form_1'), {
                precio: 'required'
            }, manage_invoice_item); 
        }
        
         
        $("[href='" + admin_url + "invoice_items']").attr("href", admin_url + "inventory/");
    });
    
    function create_checkbox_listview(id_container, data, hdn_selected_items, features, id_type_feature){
        
        var selected_items = $.map(features, function(item){ 
            if(item.id_type == id_type_feature)
                return parseInt(item.id_feature)
        });
        $("[name='" + hdn_selected_items + "']").val(JSON.stringify(selected_items));
        
        chks_services = 
            $("#" + id_container).kendoListView({
                dataSource: data,
                template: "<div class='row'><input id='chk_item' _id='#:id#' type='checkbox' /><span id='lbl_item'>#:nombre#</span></div>"
                ,
                dataBound: function(e) {
                    $.each(e.sender.items(), function(index, item){
                        var chk_item = $(item).find("#chk_item");
                        var id = parseInt(chk_item.attr("_id"));
                        if(jQuery.inArray(id, selected_items) != -1)
                            chk_item.attr("checked", "checked");
                        
                        chk_item.on("click", { hdn: hdn_selected_items}, on_chk_item_listview);
                    });
                }
            }).data("kendoListView");
    }
    
    function on_chk_item_listview(event){
        var sender = $(event.currentTarget);
        var id = parseInt(sender.attr("_id"));
        var hdn = $("[name='" + event.data.hdn + "']");
        var selected = JSON.parse(hdn.val());

        if(sender.is(":checked"))
            selected.push(id);
        else
            selected.splice(selected.indexOf(id), 1);

        hdn.val(JSON.stringify(selected));
        console.log(JSON.stringify(selected));
    }
    
    function create_grid_developments(){
        $.get(admin_url + 'inventory/get_developments/', function(response) {
            
            console.log(response);
            grid_unities = 
                $("#grid_developments").kendoGrid({
                    dataSource: response,
                    columns: [
                        {field: "nombre", title: "nombre"},                            
                        {field: "id_tipo_desarrollo", title: "id_tipo_desarrollo"},
                        {field: "total_de_unidades", title: "total_de_unidades"},
                        {field: "direccion", title: "direccion"},
                        {field: "codigo_postal", title: "codigo_postal"},
                        {field:"id", title:"Acciones", width:"100px", 
                        template: "<a id='btn_edit_development' href='item/#:id#' class='normal'><i class='fa fa-pencil-square fa_medium'></i></a> <a id='btn_delete_development' class='normal' style='cursor:pointer;'> <i class='fa fa-trash fa_medium'></i> </a><a id='btn_edit_development' href='/desarrollo/?id=#:id#' target='_blank' class='qodef-icon-shortcode normal qodef-icon-little'><i class='fa fa-eye fa_medium'></i></a>"}
                    ],
                    dataBound: function(e) {
                        console.log("dataBound");
                         
                        $.each(e.sender.items(), function(index, item){
                            $(item).find("#btn_delete_development")
                                .on("click", delete_development);
                        });
                    }
                }).data("kendoGrid");
            }, 'json');
    }
    
    function delete_development(event){
        //todo
         
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'inventory/delete_unity/' + id, function(response) {
            console.log(response);
            
            
             
        }, 'json');
    }
    
    function manage_invoice_item(form){
        //#kendo todo
        var data = $(form).serialize();
        data = data.replace("development_id", "id");
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function manage_development_features(form){
        //#kendo todo todo
        var data = $(form).serialize();
        
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    //#kendo unities
    function create_grid_unities(id){
        var itemid = 1;
        $.get(admin_url + 'inventory/get_unities/' + id, function(response) {
            
            grid_unities = 
                $("#grid_unities").kendoGrid({
                    dataSource: response,
                    columns: [
                        {field: "status", title: "Status"},                            
                        {field: "m2_habitables", title: "m2 Habitables"},
                        //{field: "recamaras", title: "Recámaras"},
                        //{field: "banios", title: "Baños"},
                        {field: "precio", title: "Precio"},
                        {field:"id", title:"Acciones", width:"100px", 
                        template: "<a id='btn_edit_unity' _id='#:id#' class='normal'><i class='fa fa-pencil-square fa_medium'></i></a> <a id='btn_delete_unity' _id='#:id#' class='normal' style='cursor:pointer;'> <i class='fa fa-trash fa_medium'></i> </a>"}
                    ],
                    dataBound: function(e) {
                        console.log("dataBound");
                         
                        $.each(e.sender.items(), function(index, item){
                            $(item).find("#btn_edit_unity")
                                .on("click", edit_unity);
                            $(item).find("#btn_delete_unity")
                                .on("click", delete_unity);
                        });
                    }
                }).data("kendoGrid");

        }, 'json');
    }
    
    function edit_unity(event){
         
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'inventory/get_unity/' + id, function(response) {
            console.log(response);
            
            //todo
            $("[name='unity_id']").val(id);
            $("#status").val(response.status);
            $("#m2_habitables").val(response.m2_habitables);
            $("#recamaras").val(response.recamaras);
            $("#banios").val(response.banios);
            $("#precio").val(response.precio);
            
             
        }, 'json');

        window_unity.center().open();
    }
    
    function delete_unity(event){
        var id = $(event.currentTarget).attr("_id");
        $.get(admin_url + 'inventory/delete_unity/' + id, function(response) {
            console.log(response);
        }, 'json');
    }
    
    function manage_unity(form) {
        //#kendo
         
        var data = $(form).serialize();
        data = data.replace("item_id", "id_item");
        data = data.replace("unity_id", "id");
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                refresh_grid_unities(response);
                alert_float('success', response.message);
            }
            $('#sales_item_modal').modal('hide');
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function refresh_grid_unities(response){
        var data = grid_unities.dataSource.data();
        var indexItem;
        var itemInGrid = $.grep(data, function(item, index){ 
            var ok = item.id == response.item.id;
            if(ok) indexItem = index;
            return ok;
        });
        
        if(!indexItem){
            data.push(response.item);
        }
        else{
            data.splice(indexItem, 1, response.item);
        }
        grid_unities.dataSource.data(data);
        window_unity.close();
    }    
});
