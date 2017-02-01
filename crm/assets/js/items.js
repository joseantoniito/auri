$(function() {
    //#kendo developments
    var window_unity; 
    var grid_unities;
    var upload_photos;
    var services = [
        {id: 1, nombre: 'Circuito cerrado'},
        {id: 2, nombre: 'Gimnasio'},
        {id: 3, nombre: 'Internet/Wifi'},
        //{id: 4, nombre: 'Mesa de boliche'},
        {id: 5, nombre: 'Planta de emergencia'},
        {id: 6, nombre: 'Área de juegos infantiles'},
        {id: 7, nombre: 'Seguridad contra incendios'},
        {id: 8, nombre: 'Spa'},
        {id: 9, nombre: 'Valet parking'},
        //{id: 10, nombre: 'Conmutador'},
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
        //{id: 2, nombre: 'Cancha de golf'},
        //{id: 3, nombre: 'Cancha de paddle'},
        //{id: 4, nombre: 'Cancha de squash'},
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
        //{id: 3, nombre: 'Cine'},
        {id: 4, nombre: 'Salón de fiestas'},
        {id: 5, nombre: 'Salón de usos múltiples'},
        {id: 6, nombre: 'Sauna'},
        {id: 7, nombre: 'Vapor'},
    ];
    
    $(document).ready(function() {
        hide_unnecesary_controls();
        if(window.location.href.indexOf("/admin/inventory/") != -1 &&
          window.location.href.indexOf("/admin/inventory/") == window.location.href.length - 17 ){
            create_grid_developments();
        }
        else if(window.location.href.indexOf("/admin/inventory/item") != -1){
            load_development_info();
        }
        
        if(window.location.href.indexOf("/admin/inventory/development_assessors") != -1){
            load_development_assessors();
        }
        
        if(window.location.href.indexOf("/admin/inventory/reservations") != -1){
            load_reservations_admin();
        }
        if(window.location.href.indexOf("/admin/leads") != -1){
            pre_load_reservation_assessor();
        }
    });
    
    function hide_unnecesary_controls(){
        $(".finance-summary").hide();
        $("[href='#home_tab_tickets']").hide();
        
        parent = $("[href='" + admin_url + "invoice_items']").parent().parent();
        parent.append(
            '<li><a href="'+admin_url+'inventory/reservations">Propiedades de Interés</a></li>');
        parent.append(
            '<li><a href="'+admin_url+'inventory/development_assessors">Asignar Asesores</a></li>');
            
        $("[href='" + admin_url + "invoice_items']").attr("href", admin_url + "inventory/");
        $("[href='" + admin_url + "projects']").hide();
        $("[href='" + admin_url + "invoices/list_invoices']").hide();
        $("[href='#home_my_projects']").hide();
        $(".home-summary .col-md-6").hide();
        $(".home-summary .col-md-12").hide();
        $($(".top_stats_wrapper .fa-cubes")).parent().parent().parent().hide();
        $($(".top_stats_wrapper .fa-balance-scale")).parent().parent().parent().hide();
        $("#invoices_total").hide();
        $(".panel_s.projects-activity").hide();
        $("#projects_status_stats")
            .parent().parent().parent().parent().hide();
        $("#projects_status_stats")
            .parent().parent().parent().parent().siblings()
            .attr("class", "col-md-12 col-sm-12");
        
        if(window.location.href.indexOf("http://auri.mastering-marketing.org/crm/admin") != -1){
            $(".panel-body.home-activity").prepend("<h4 style='color: #008ece;'>Reservaciones</h4><div id='grid_reservations' style='margin-bottom: 32px;'></div>");
            
            var data = "status=3&id_lead=&top=5";
            create_grid_reservations_home(data);
            
        }
        
    }
    
    
    //development item
    function load_development_info(){
        var id = $("[name='item_id']").val()
        show_additional_features(id);
        create_locations_dropdowns();
        _validate_form($('#invoice_item_form_1'), {
            precio: 'required'
        }, manage_invoice_item); 
        $("#date_picker_entrega").kendoDatePicker({});
        
        load_development_unities(id);
        load_development_features();
        create_uploads_media_items();
    }
    
    function show_additional_features(id){
        if(id != ''){
            $("#annoucement").hide();
            $("#unitites").show();
            $("#features").show();
            $("#multimedia").show();
        }
    }
    
    function create_uploads_media_items(){
        //development photos
        var hdn_item_media_items = $("[name='item_media_items']");
        var item_media_items = JSON.parse(hdn_item_media_items.val());
        hdn_item_media_items.val("");

        $.each(item_media_items, function(index, item){
            item.extension = "." + item.name.split('.')[1];
        })

        var initial_files = [
            { name: "file1.doc", size: 525, extension: ".doc" },
            { name: "file2.jpg", size: 600, extension: ".jpg" },
            { name: "file3.xls", size: 720, extension: ".xls" },
        ];
        
        var image_items = $.grep(item_media_items, function(item){
            return item.id_type == 1;
        });
        create_upload_media_item(
            "upload_photos",
            image_items, 
            "<div class='upload_photos_item'><img src='/crm/uploads/inventory/#:name#' /><span id='lbl_item'>#:name#</span><button type='button' class='k-button k-button-bare k-upload-action'><span class='k-icon k-i-close k-delete' title='Remove'></span></button></div>",
            [".jpg", ".png"],
            function(e){
                console.log(e);
                if(this.getFiles().length = 0){
                    //e.preventDefault();
                }
            });
        
        var video_items = $.grep(item_media_items, function(item){
            return item.id_type == 2;
        });
        create_upload_media_item(
            "upload_video",
            video_items, 
            null,
            [".mp4"],
            function(e){
                console.log(e);
                if(this.getFiles().length > 0){
                    alert("Solo puedes cargar un video.");
                    e.preventDefault();
                }
            });
        
    }
    
    function create_upload_media_item(id_upload, item_media_items, template, allowed_extensions, onSelect){
        
        $("#"+id_upload).kendoUpload({
            async: {
                saveUrl: admin_url + "inventory/save_media_item",
                removeUrl: admin_url + "inventory/remove_media_item",
                autoUpload: true
            },
            files: item_media_items,
            template: template,
            validation: {
                allowedExtensions: allowed_extensions,
            },
            success: function(e){
                debugger;
                console.log(e);
                if(e.response.type == "save"){
                    var id_media_item = e.response.id;
                    if(id_media_item)
                        add_development_media_item(id_media_item);
                }
                else if(e.response.type == "remove"){
                    var id_media_item = e.files[0].id;
                    delete_development_media_item(id_media_item);
                }
            },
            error: function(e){
                debugger;
                console.log(e);
            },
            remove: function(e){
                console.log("on_remove_files", e);
                //e.preventDefault();
            },
            select: onSelect
        }).data("kendoUpload");
    
    }

  
    //development locations
    var dropdown_estados, dropdown_municipios, dropdown_colonias, map_locations, geocoder_locations, marker_locations;;
    function create_locations_dropdowns(){
        var id_estado = $("[name='id_estado']").val();
        var id_municipio = $("[name='id_municipio']").val();
        var id_colonia = $("[name='id_colonia']").val();
        var latitud = $("[name='latitud']").val();
        var longitud = $("[name='longitud']").val();
        
        dropdown_estados = 
            $("#dropdown_estados").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: [],
                value: id_estado,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_estado']").val(id);
                    $.get(admin_url + 'inventory/get_location_municipalities/' + id, function(response) {
                        dropdown_municipios.setDataSource(response);
                        dropdown_municipios.value(id_municipio);
                        dropdown_municipios.trigger("change");
                    }, 'json');
                }
            }).data("kendoDropDownList");
        dropdown_municipios = 
            $("#dropdown_municipios").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: [],
                value: id_municipio,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_municipio']").val(id);
                    $.get(admin_url + 'inventory/get_location_colonies/' + id, function(response) {
                        dropdown_colonias.setDataSource(response);
                        dropdown_colonias.value(id_colonia);
                        dropdown_colonias.trigger("change");
                    }, 'json');
                }
            }).data("kendoDropDownList");
        dropdown_colonias = 
            $("#dropdown_colonias").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: [],
                value: id_colonia,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        
        geocoder_locations = new google.maps.Geocoder();
        var position = {
            lat: latitud != "0" ? parseFloat(latitud) : -34.397, 
            lng: longitud != "0" ? parseFloat(longitud) : 150.644
        };
        map_locations = new google.maps.Map(document.getElementById('map_locations'), {
            center: position,
            zoom: 8
        });
        marker_locations = new google.maps.Marker({
            map: map_locations,
            position: position
        });
        map_locations.setZoom(15);
        
        $("#btn_get_position").on("click", function(){
            var address = 
                dropdown_estados.text() + " " +
                dropdown_municipios.text() + " " +
                dropdown_colonias.text() + " " +
                $("#direccion").val();
            $("[name='direccion_completa']").val(address);
            
            geocoder_locations.geocode( { 'address': address}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                var location = results[0].geometry.location;
                map_locations.setCenter(location);
                map_locations.setZoom(15);
                marker_locations.setPosition(location);
                $("[name='latitud']").val(location.lat());
                $("[name='longitud']").val(location.lng());
              } else {
                alert_float('danger', "Geocode was not successful for the following reason: " + status);
              }
            });
        });
        
        $.get(admin_url + 'inventory/get_location_states/', function(response) {
            dropdown_estados.setDataSource(response);
            dropdown_estados.value(id_estado);
            dropdown_estados.trigger("change");
        }, 'json');
    }


    //development media items
    function add_development_media_item(id_media_item){
        var id_development = parseInt($("[name='item_id']").val());
        var data = "id_development=" + id_development + "&id_media_item=" +id_media_item;
        
        $.post(admin_url + 'inventory/add_development_media_item', data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
    }

    function delete_development_media_item(id_media_item){
        $.get(admin_url + 'inventory/delete_media_item/' + id_media_item, function(response) {
            console.log(response); 
            $.get(admin_url + 'inventory/delete_development_media_item/' + id_media_item, function(response) {
                console.log(response); 
                alert_float('success', "Item multimedia eliminado correctamente");
                //var uploadf=$("#upload_photos").data("kendoUpload");
                //uploadf.options.files[0].uid
                //uploadf.wrapper.find("img")
                
            }, 'json');
            
        }, 'json');
        
        
        /*$.post(admin_url + 'inventory/remove_development_media_item', data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });*/
    }


    //development features
    function load_development_features(){
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
    }

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
    

    //development unities
    function load_development_unities(id){
        create_grid_unities(id);
        
        window_unity = 
            $("#window_unity").kendoWindow({
                width: "600px",
                title: "Manage Unity",
                visible: false,
                close: function(){
                    $("[name='unity_id']").val("");
                    $("#status").val("");
                    $("#m2_habitables").val("");
                    $("#recamaras").val("");
                    $("#banios").val("");
                    $("#precio").val("");
                    $("#unidad").val("");

                    $("#balcon").prop('checked', false);
                    $("#terraza").prop('checked', false);
                    $("#roofgarden").prop('checked', false);
                    
                    if($("#upload_planos").data("kendoUpload")){
                         $("#upload_planos").data("kendoUpload").clearAllFiles();
                         $("#upload_planos").data("kendoUpload").destroy();
                    }
                    $(".k-dropzone em").remove();
                }
            }).data("kendoWindow");
        
        $("#btn_add_unity").on("click", function(sender){
            window_unity.center().open();
        });
        
        $("#btn_close_window_unity").on("click", function(){
             window_unity.close();
        });
        
        _validate_form($('#unity_form'), {
            address: 'required'
        }, manage_unity);
        
        $("[name='unity_id']").bind('add_unity_media_item',function (evt,ret) { 
            id_media_item = ret.id_media_item;
            var id_unity = $("[name='unity_id']").val();
            var data = "id_unity=" + id_unity + "&id_media_item=" +id_media_item;
            
            $.post(admin_url + 'inventory/add_unity_media_item', data).done(function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float('success', response.message);
                }
            }).fail(function(data) {
                alert_float('danger', data.responseText);
            });
            
        });
    }

    function create_grid_unities(id){
        
        grid_unities = 
                $("#grid_unities").kendoGrid({
                    dataSource: [],
                    columns: [
                        {field: "unidad", title: "Unidad"},                            
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
        
        if(id)
            $.get(admin_url + 'inventory/get_unities/' + id, function(response) {
                grid_unities.dataSource.data(response);
            }, 'json');
    }
    
    function edit_unity(event){
        $("#hdn_unity_media_items").val("");
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'inventory/get_unity/' + id, function(response) {
            console.log(response);
            var item_planos = response.item_media_items;
            response = response.item;
            //todo
            $("[name='unity_id']").val(id);
            $("#m2_habitables").val(response.m2_habitables);
            $("#recamaras").val(response.recamaras);
            $("#banios").val(response.banios);
            $("#precio").val(response.precio);
            
            $("#balcon").prop('checked', response.balcon == "1");
            $("#terraza").prop('checked', response.terraza == "1");
            $("#roofgarden").prop('checked', response.roofgarden == "1");
            
            $("#unidad").val(response.unidad);
            $("#m2_totales").val(response.m2_totales);
            $("#estacionamientos").val(response.estacionamientos);
            
            $.each(item_planos, function(index, item){
                item.extension = "." + item.name.split('.')[1];
            });
            if($("#upload_planos").data("kendoUpload")){
                 $("#upload_planos").data("kendoUpload").clearAllFiles();
                 $("#upload_planos").data("kendoUpload").destroy();
            }
               
            $("#upload_planos").kendoUpload({
                async: {
                    saveUrl: admin_url + "inventory/save_media_item",
                    removeUrl: admin_url + "inventory/remove_media_item",
                    autoUpload: true
                },
                files: item_planos,
                validation: {
                    allowedExtensions: [".jpg", ".png"],
                },
                success: function(e){
                    debugger;
                    console.log(e);
                    if(e.response.type == "save"){
                        var id_media_item = e.response.id;
                        if(id_media_item)
                            add_unity_media_item(id_media_item);
                    }
                    else if(e.response.type == "remove"){
                        var id_media_item = e.files[0].id;
                        delete_unity_media_item(id_media_item);
                    }
                },
                error: function(e){
                    debugger;
                    console.log(e);
                }
            }).data("kendoUpload");
        }, 'json');

        window_unity.center().open();
    }
    
    function add_unity_media_item(id_media_item){
        
        if( $("[name='unity_id']").val() ){
            var ret = { id_media_item: id_media_item };
            $("[name='unity_id']").trigger('add_unity_media_item', [ret]);
        }
        else
            $("#hdn_unity_media_items").val( 
                 $("#hdn_unity_media_items").val() + id_media_item + "," );
    }

    function delete_unity_media_item(id_media_item){
        $.get(admin_url + 'inventory/delete_unity_media_item/' + id_media_item, function(response) {
            console.log(response); 
            $.get(admin_url + 'inventory/delete_media_item/' + id_media_item, function(response) {
                console.log(response); 
                alert_float('success', "Item multimedia eliminado correctamente");  
            }, 'json');
            
        }, 'json');
    }
    
    function delete_unity(event){
        var id = $(event.currentTarget).attr("_id");
        $.get(admin_url + 'inventory/delete_unity/' + id, function(response) {
            console.log(response);
            if (response.success == true) {
                refresh_grid_unities({ item: {id: id }}, true);
                alert_float('success', response.message);
            }
            else
                alert_float('danger', response.message);
        }, 'json');
    }
    
    function manage_unity(form) {
        //#kendo
         
        var data = $(form).serialize();
        data = data.replace("item_id", "id_item");
        data = data.replace("unity_id", "id");
        data = data.replace("balcon=on", "balcon=1");
        data = data.replace("terraza=on", "terraza=1");
        data = data.replace("roofgarden=on", "roofgarden=1");
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                
                if($("#hdn_unity_media_items").val() != ""){
                    var unity_media_items = $("#hdn_unity_media_items").val().split(',');
                    $.each(unity_media_items, function(index, id_media_item){
                        var ret = { id_media_item: id_media_item };
                        $("[name='unity_id']").trigger('add_unity_media_item', [ret]);
                    })
                }
                
                refresh_grid_unities(response);
                alert_float('success', response.message);
            }
            $('#sales_item_modal').modal('hide');
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function refresh_grid_unities(response, delete_item){
        var data = grid_unities.dataSource.data();
        var indexItem = null;
        var itemInGrid = $.grep(data, function(item, index){ 
            var ok = item.id == response.item.id;
            if(ok) indexItem = index;
            return ok;
        });
        
        if(indexItem == null){
            data.push(response.item);
        }
        else{
            if(!delete_item)
                data.splice(indexItem, 1, response.item);
            else
                data.splice(indexItem, 1);
        }
        grid_unities.dataSource.data(data);
        window_unity.close();
    }    
     

    //development list items
    function create_grid_developments(){
        $.get(admin_url + 'inventory/get_developments/', function(response) {
            
            console.log(response);
            //grid_unities = 
                $("#grid_developments").kendoGrid({
                    dataSource: response,
                    columns: [
                        {field: "nombre", title: "nombre"},                            
                        {field: "precio_desde", title: "precio desde"},
                        {field: "total_de_unidades", title: "total de unidades"},
                        {field: "direccion", title: "dirección"},
                        {field: "codigo_postal", title: "código postal"},
                        {field:"id", title:"Acciones", width:"100px", 
                        template: "<a id='btn_edit_development' href='item/#:id#' class='normal'><i class='fa fa-pencil-square fa_medium'></i></a> <a _id='#:id#' id='btn_delete_development' class='normal' style='cursor:pointer;'> <i class='fa fa-trash fa_medium'></i> </a><a id='btn_view_development' href='/desarrollo/?id=#:id#' target='_blank' class='qodef-icon-shortcode normal qodef-icon-little'><i class='fa fa-eye fa_medium'></i></a>"}
                    ],
                    dataBound: function(e) {
                        console.log("dataBound");
                        var has_permission_edit = $("#hdn_has_permission_edit").val() == "1";
                        
                        $.each(e.sender.items(), function(index, item){
                            $(item).find("#btn_delete_development")
                                .on("click", delete_development)
                                .css("display", has_permission_edit ? "" : "none");
                            
                            $(item).find("#btn_edit_development")
                                .css("display", has_permission_edit ? "" : "none");
                        });
                    }
                }).data("kendoGrid");
            }, 'json');
    }
    
    function delete_development(event){
        //todo
         
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'inventory/delete_development/' + id, function(response) {
            console.log(response); 
            if (response.success == true) {
                refresh_grid_developments({ item: {id: id }});
                alert_float('success', response.message);
            }
            else
                alert_float('danger', response.message);
        }, 'json');
    }
    
    function manage_invoice_item(form){
        //#kendo todo
        var data = $(form).serialize();
        data = data.replace("development_id", "id");
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            show_additional_features(response.item.id);
            $("[name='development_id']").val(response.item.id);
            $("[name='id_development']").val(response.item.id);
            $("[name='item_id']").val(response.item.id);
            
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }

    function refresh_grid_developments(response){
        grid = $("#grid_developments").data("kendoGrid");
        var data = grid.dataSource.data();
        var indexItem = null;
        var itemInGrid = $.grep(data, function(item, index){ 
            var ok = item.id == response.item.id;
            if(ok) indexItem = index;
            return ok;
        });
        
        if(indexItem == null){
            data.push(response.item);
        }
        else{
            data.splice(indexItem, 1);
        }
        grid.dataSource.data(data);
        //window_unity.close();
    } 


    //development assessors
    function load_development_assessors(){
        
        $.get(admin_url + 'inventory/get_assessors/', function(response) {
            $("#list_view_assessors").kendoListView({
                dataSource: response,
                template: kendo.template($("#assessors_template").html()),
                dataBound: function(e) {
                    $.each(e.sender.items(), function(index, item){
                        item = $(item);
                        item[0].addEventListener('dragstart', on_drag_start_assessor, false);
                        item.find("#descripcion").text();
                        
                        
                    });
                }
            });
            
            create_listview_developments_with_assesors();
            
        }, 'json');
        
        /*
        $("#drag1").on('dragstart', function(ev) {
            console.log(ev);
            ev = ev.originalEvent;
            evt.dataTransfer.data("id","123");
        });
        $("#drag1")[0].addEventListener('dragstart', on_drag_start, false);
        
        $("#div1")[0].addEventListener('drop', on_drop, false);
        
        $("#div1")[0].addEventListener('dragover', on_drag_over, false);*/
    }
    
    function create_listview_developments_with_assesors(){
        $.get(admin_url + 'inventory/get_developments_with_assessors/', function(response) {
            var idsStaff = $.map(response.development_assessors, function(item){
                return item.id_staff;
            });
            var list_view_staff = $("#list_view_assessors").data("kendoListView");
            var data_staff = list_view_staff.dataSource.data();
            data_staff = $.grep(data_staff, function(item){
                return jQuery.inArray( item.staffid, idsStaff ) == -1;
            });
            list_view_staff.dataSource.data(data_staff);
            
            $("#list_view_developments").kendoListView({
                dataSource: response.developments,
                template: kendo.template($("#developments_template").html()),
                dataBound: function(e) {
                    $.each(e.sender.items(), function(index, item){
                        item = $(item);
                        item[0].addEventListener('drop', on_drop_assessor, false);
                        item[0].addEventListener('dragover', on_drag_over_assessor, false);
                        item.find("#descripcion").text();
                        
                        var id_development = item.attr("_id");
                        var assessors = $.grep(response.development_assessors, function(itemA){
                            return itemA.id_development == id_development;
                        });
                        
                        
                        item.find("#list_view_development_assessors").kendoListView({
                            dataSource: assessors,
                            template: 
                            "<div _id='#:id_staff#' draggable='true' class='col-md-6'> \
                            <span class='draganddrop'></span><img _src='#: profile_image #' src='#:'/crm/uploads/staff_profile_images/' + staffid + '/small_' + profile_image #' class='img_radius' >\
                            <h4 id='assessor_name'>#:firstname#</h4></div>",
                            dataBound: function(e) {
                                 $.each(e.sender.items(), function(index, item){
                                    item = $(item);
                                    item[0].addEventListener('dragstart', on_drag_start_assessor_1, false);
                                });
                            }
                        });
                    });
                }
            });
        }, 'json');
        
    }

    function on_drag_over_assessor(ev) {
        ev.preventDefault();
    }

    function on_drag_start_assessor(ev) {
        if($(ev.target).attr("draggable") != "true") return false;
        ev.dataTransfer.setData("id_assessor", $(ev.target).attr("_id"));//ev.target.id
        ev.dataTransfer.setData("name", $(ev.target).find("#assessor_name").text());
		ev.dataTransfer.setData("profile_image", $(ev.target).find("img").attr("_src"));
        ev.dataTransfer.setData("type", "add_assessor");
    }

    function on_drag_start_assessor_1(ev) {
        if($(ev.target).attr("draggable") != "true") return false;
        ev.dataTransfer.setData("id_assessor", $(ev.target).attr("_id"));//ev.target.id
        ev.dataTransfer.setData("name", $(ev.target).find("#assessor_name").text());
        ev.dataTransfer.setData("profile_image", $(ev.target).find("img").attr("_src"));
        ev.dataTransfer.setData("type", "change_assessor");        
        ev.dataTransfer.setData("id_development", $(ev.target).parent().attr("_id_dev"));
    }

    function on_drop_assessor(ev) {
        ev.preventDefault();
        var id_assessor = ev.dataTransfer.getData("id_assessor");
        var name = ev.dataTransfer.getData("name");
        var type = ev.dataTransfer.getData("type");
        
        var sender = $(ev.target).parent();
        var id_development = sender.attr("_id");
		var profile_image = ev.dataTransfer.getData("profile_image");
       
        list_view_developments = sender.find("#list_view_development_assessors").data("kendoListView");
        data_list_view_developments = list_view_developments.dataSource.data();
        data_list_view_developments.push({
			id_development: id_development, 
			firstname: name, 
			id_staff: id_assessor, 
			staffid: id_assessor,
			profile_image: profile_image
		});
        //add in db
         var data = "id_development=" + id_development + "&id_staff=" +id_assessor;
        $.post(admin_url + 'inventory/add_development_assessor', data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        
        if(type == "add_assessor"){
            list_view_assessors = $("#list_view_assessors").data("kendoListView");
            data_list_view_assessors = list_view_assessors.dataSource.data();
            indexItem = $.map(data_list_view_assessors, function(obj, index) {
                if(obj.staffid == id_assessor) {return index;}
            })[0];
            data_list_view_assessors.splice(indexItem, 1);
        }
        else if(type == "change_assessor"){
            var id_development_from = ev.dataTransfer.getData("id_development");
            data_list_view_assessors =
                $("#list_view_developments")
                    .find("[_id_dev='"+id_development_from+"']")
                    .data("kendoListView").dataSource.data();
            
            indexItem = $.map(data_list_view_assessors, function(obj, index) {
                if(obj.id_staff == id_assessor) {return index;}
            })[0];
            data_list_view_assessors.splice(indexItem, 1);
            var data = "id_development=" + id_development_from + "&id_staff=" +id_assessor;
            //delete in db
            $.post(admin_url + 'inventory/delete_development_assessor', data).done(function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    //alert_float('success', response.message);
                }
            }).fail(function(data) {
                //alert_float('danger', data.responseText);
            });
        }
        //ev.target.appendChild(document.getElementById(data));
    }


    //desarrollos de interes -reservaciones-
    //reservaciones admins
    function load_reservations_admin(){
        var data = "status=&id_lead=&top=";
        create_grid_reservations(data, data_bound_reservations_admin);
        
        _validate_form($('#reservation_form'), {
            address: 'required'
        }, manage_reservation);
        
        var status_items = [
            {id: 1, nombre: "Disponible"},
            {id: 2, nombre: "En validación"},
            {id: 3, nombre: "Reservado"},
            {id: 4, nombre: "Vendido"}
        ];
        $("#dropdown_status").kendoDropDownList({
            dataTextField: "nombre",
            dataValueField: "id",
            dataSource: status_items,
            value: null,
            optionLabel: "Seleccione.. ",
            change: function(e) {
                var id = this.value();
                $("[name='id_status']").val(id);
                var data = "status="+id+"&id_lead=&top=";
                create_grid_reservations(data, data_bound_reservations_admin);
            }
        }).data("kendoDropDownList");
    }
    
    function data_bound_reservations_admin(e) {
        console.log("dataBound");
        var has_permission_edit = $("#hdn_has_permission_edit").val() == "1";

        $.each(e.sender.items(), function(index, item){
            $(item).find("#btn_manage")
                .on("click", { index:index }, edit_reservation_admin);

        });
    }

    function edit_reservation_admin(){
        var sender = $(event.currentTarget);
        var id = sender.attr("_id");
        $.get(admin_url + 'inventory/get_reservation/' + id, function(response) {
            var item = response.item;
            var item_docs = response.item_docs;
            var item_assessors = response.item_assessors;
            
            $("[name='id']").val(item.id);
            $("[name='id_development']").val(item.id_development);
            $("[name='id_unity']").val(item.id_unity);
            $("[name='id_lead']").val(item.id_lead);
            $("[name='id_assessor']").val(item.id_assessor);
            $("#development_name").text(item.nombre);
            var next_status = null;
            $(".reservation_item").hide();
            if(item.status == "1"){
                next_status = "1";
                $("#reservation_avaiable").show();
                $("#dropdown_assessors").kendoDropDownList({
                    dataTextField: "firstname",
                    dataValueField: "staffid",
                    dataSource: item_assessors,
                    optionLabel: "Seleccione.. ",
                    value: item.id_assessor,
                    change: function(e) {
                        var id = this.value();
                        $("[name='id_assessor']").val(id);
                    }
                }).data("kendoDropDownList");
            }
            if(item.status == "2"){
                next_status = "3";
                $("#reservation_in_validation").show();
                $("#list_view_reservation_docs").kendoListView({
                    dataSource: item_docs,
                    template: kendo.template($("#reservation_docs_template").html()),
                    dataBound: function(e) {
                        $.each(e.sender.items(), function(index, item){
                            item = $(item);
                            item.find("#descripcion").text();
                        });
                    }
                });
            }
            if(item.status == "3"){
                next_status = "4";
            }
            
            $("[name='status']").val(next_status); 
            $("#window_reservation").data("kendoWindow").center().open();
        }, 'json');
    }

    //reservaciones asesores
    function pre_load_reservation_assessor(){
        $(".dataTable").bind('example',function (evt,ret) { 
            console.log($(".dataTable [href='#']"));
            //$("[href='#lead_reservations']")
            $(".dataTable [href='#']")
                .on("click", function(event){
                    var id_lead = $("[name='leadid']").val()
                    str_on_click = $(event.currentTarget).attr("onclick")
                    id_lead = 
                        str_on_click.substring(
                            str_on_click.indexOf('(')+1,
                            str_on_click.indexOf(')'));
                    //grid
                    load_reservation_assessor(id_lead);
                }) 
            //ret.val = false;
        });
        setTimeout(function() {
            //alert("enter");
            console.log("call function datatable");
            var ret = {ret:true};
            $(".dataTable").trigger('example',[ret]);
        }, 7000);
        
        
    }
    
    function load_reservation_assessor(id_lead){
        setTimeout(function() {
            var data = "status=&id_lead="+id_lead+"&top=";
            create_grid_reservations(data, data_bound_reservations_assessor);
            
            _validate_form($('#reservation_form'), {
                address: 'required'
            }, manage_reservation);
        }, 3000);
    }
    
    function data_bound_reservations_assessor(e) {
        console.log("dataBound");
        var has_permission_edit = $("#hdn_has_permission_edit").val() == "1";

        $.each(e.sender.items(), function(index, item){
            
            $(item).find("#btn_manage")
                .on("click", { index:index }, edit_reservation_assessor);

        });
    }
    
    function edit_reservation_assessor(event){
        var sender = $(event.currentTarget);
        var id = sender.attr("_id");
        //var index = event.data.index;
        //var item = $("#grid_reservations").data("kendoGrid").dataSource.data()[index];
        
        $.get(admin_url + 'inventory/get_reservation/' + id, function(response) {
            var item = response.item;
            var item_docs = response.item_docs;
            var item_unidades_desarrollo = response.item_unidades_desarrollo;
            
            $("[name='id']").val(item.id);
            $("[name='id_development']").val(item.id_development);
            $("[name='id_unity']").val(item.id_unity);
            $("[name='id_lead']").val(item.id_lead);
            $("[name='status']").val(item.status);
            $("[name='id_assessor']").val(response.staff_id);
            $("#development_name").text(item.nombre);
            $("#unity_name").text(item.unidad);
            
            $(".reservation_item").hide();
            $("#window_reservation [type='submit']").hide();
            if(item.status == "1"){
                $("[name='status']").val("2");
                $("#reservation_avaiable").show();
                $("#window_reservation [type='submit']").show();
                $("#dropdown_unities").kendoDropDownList({
                    dataTextField: "unidad",
                    dataValueField: "id",
                    dataSource: item_unidades_desarrollo,
                    optionLabel: "Seleccione.. ",
                    value: item.id_unity,
                    change: function(e) {
                        var id = this.value();
                        $("[name='id_unity']").val(id);
                    }
                }).data("kendoDropDownList");

                $.each(item_docs, function(index, item){
                    item.extension = "." + item.name.split('.')[1];
                });
                if($("#upload_docs").data("kendoUpload")){
                    $("#upload_docs").data("kendoUpload").clearAllFiles();
                    $("#upload_docs").data("kendoUpload").destroy();
                }
                    
                $("#upload_docs").kendoUpload({
                    async: {
                        saveUrl: admin_url + "inventory/save_media_item",
                        removeUrl: admin_url + "inventory/remove_media_item",
                        autoUpload: true
                    },
                    files: item_docs,
                    validation: {
                        allowedExtensions: [".doc", ".docx", ".xls", ".xlslx", "pdf"],
                    },
                    success: function(e){
                        debugger;
                        console.log(e);
                        if(e.response.type == "save"){
                            var id_media_item = e.response.id;
                            if(id_media_item)
                                add_reservation_media_item(id_media_item);
                        }
                        else if(e.response.type == "remove"){
                            var id_media_item = e.files[0].id;
                            delete_reservation_media_item(id_media_item);
                        }
                    },
                    error: function(e){
                        debugger;
                        console.log(e);
                    }
                }).data("kendoUpload");
            }
            $("#window_reservation").data("kendoWindow").center().open();
            
        }, 'json');
    }
    
    function add_reservation_media_item(id_media_item){
        var id_reservation = $("[name='id']").val();
        var data = "id_reservation=" + id_reservation + "&id_media_item=" +id_media_item;
        
        $.post(admin_url + 'inventory/add_reservation_media_item', data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
    }

    function delete_reservation_media_item(id_media_item){
        $.get(admin_url + 'inventory/delete_reservation_media_item/' + id_media_item, function(response) {
            console.log(response); 
            $.get(admin_url + 'inventory/delete_media_item/' + id_media_item, function(response) {
                console.log(response); 
                alert_float('success', "Item multimedia eliminado correctamente");  
            }, 'json');
            
        }, 'json');
    }

    
    
    //utilerías desarrollos de interés y otros
    function create_grid_reservations(data, fn_data_bound){
        
        
        $.post(admin_url + 'inventory/get_reservations',data).done(function(response) {
            response = JSON.parse(response);
            console.log(response);
            $.each(response, function(index, item){
               item.nombre_status = get_nombre_status(item.status); 
            });
            console.log($("#grid_reservations"));
            $("#grid_reservations").kendoGrid({
                dataSource: response,
                columns: [
                    {field: "unidad", title: "Unidad"},                            
                    {field: "nombre", title: "Nombre"},
                    {field: "precio", title: "precio"},
                    {field: "firstname", title: "Asesor"},
                    {field: "nombre_status", title: "Status"},
                    {field:"id", title:"Acciones", width:"100px", 
                    template: "<span _id='#:id#' id='btn_manage' href='' class='qodef-icon-shortcode normal qodef-icon-little'><i class='qodef-icon-font-awesome fa fa-cog qodef-icon-element'></i></span>"}
                ],
                dataBound: fn_data_bound
            }).data("kendoGrid");   
            
            $("#window_reservation").kendoWindow({
                width: "600px",
                title: "Reservación",
                visible: false,
                modal: true,
                resizable: false,
                scrollable: false,
            }).data("kendoWindow");

            $("#btn_close_window_reservation").on("click", function(){
                 $("#window_reservation").data("kendoWindow").close();
            });
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        }); 

    }

    function create_grid_reservations_home(data){        
        $.post(admin_url + 'inventory/get_reservations',data).done(function(response) {
            response = JSON.parse(response);
            //response.splice(0,5);
            console.log(response);
            $.each(response, function(index, item){
               item.nombre_status = get_nombre_status(item.status); 
            });
            console.log($("#grid_reservations"));
            $("#grid_reservations").kendoGrid({
                dataSource: response,
                columns: [
                    {field: "unidad", title: "Unidad"},                            
                    {field: "nombre", title: "Nombre"},
                    {field: "precio", title: "precio"},
                    {field: "firstname", title: "Asesor"},
                    {field: "nombre_status", title: "Status"}
                ]
            }).data("kendoGrid");   
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        }); 

    }
    
    function manage_reservation(form) {
        var data = $(form).serialize();
        //data = data.replace("item_id", "id_item");
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                refresh_grid_reservations(response);
                alert_float('success', response.message);
            }
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function refresh_grid_reservations(response, delete_item){
        grid = $("#grid_reservations").data("kendoGrid");
        var data = grid.dataSource.data();
        var indexItem = null;
        var itemInGrid = $.grep(data, function(item, index){ 
            var ok = item.id == response.item.id;
            if(ok) indexItem = index;
            return ok;
        });
        
        if(indexItem == null){
            data.push(response.item);
        }
        else{
            if(!delete_item)
                data.splice(indexItem, 1, response.item);
            else
                data.splice(indexItem, 1);
        }
        grid.dataSource.data(data);
        $("#window_reservation").data("kendoWindow").close();
    }    
    
    function get_nombre_status(status){
        var str = "";
        if(status == "1")
            str = "Disponible";
        else if(status == "2")
            str = "En Validacion";
        else if(status == "3")
            str = "Reservado";
        else if(status == "4")
            str = "Vendido";
        return str;
    }
});
