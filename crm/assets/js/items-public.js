$(function() {
    //#kendo developments
    var window_unity; 
    var grid_unities;
    var admin_url = "/crm/admin/";
    
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
    
    var items_tipo_desarrollo = [
        {id: 1, nombre: 'Desarrollo horizontal'},
        {id: 2, nombre: 'Desarrollo vertical'},
    ];
    var items_etapa_desarrollo = [
        {id: 1, nombre: 'Pre-Venta'},
        {id: 2, nombre: 'Venta'},
    ];
    var items_tipos_entrega = [
        {id: 1, nombre: 'Inmediata'},
        {id: 2, nombre: '3 meses después'},
    ];

    $(document).ready(function() {
        //#kendo
        console.log( "ready!" );
        if(window.location.href.indexOf("/desarrollo/")){
            console.log("Load propertie!!!");
            var id = null;
            
            var arrLocationHref = window.location.href.split('?');
            if(arrLocationHref[1]){
                var arrQueryString = arrLocationHref[1].split('=');
                if(arrQueryString[0] == 'id') 
                    id = arrQueryString[1];
            }

            if(id)
                load_development(id);
        }        
    });
    
    function load_development(id){
        $.get(admin_url + 'inventory/get_development/' + id, function(response) {
            console.log(response);
            var item = response.item;
            var item_features = response.item_features;
            
            $("#nombre").text(item.nombre);
            $("#direccion").text(item.direccion);
            $("#direccion2").text(item.direccion);
            $("#iframeMap").attr("src", "https://maps.google.com/maps?q="+item.direccion+"&ie=UTF8&&output=embed");
            $("#descripcion").text(item.descripcion);
            var tipo_desarrollo = $.grep(items_tipo_desarrollo, function(item_cat){ return item_cat.id == item.id_tipo_desarrollo; })[0].nombre;
            $("#tipo_desarrollo").text(tipo_desarrollo);
            $("#tipo_desarrollo2").text(tipo_desarrollo);
            $("#etapa_desarrollo").text($.grep(items_etapa_desarrollo, function(item_cat){ return item_cat.id == item.id_etapa_desarrollo; })[0].nombre);
            $("#total_de_unidades").text(item.total_de_unidades);
            $("#tipo_entrega").text($.grep(items_tipos_entrega, function(item_cat){ return item_cat.id == item.id_entrega; })[0].nombre);
            
            var precio_desde = "MN " + item.precio_desde;
            $("#precio_desde").text(precio_desde);
            $("#precio_desde2").text(precio_desde);
            $("#superficie_terreno_minima").text(item.superficie_terreno_minima);
            $("#superficie_terreno_maxima").text(item.superficie_terreno_maxima);
            $("#superficie_contruida_minima").text(item.superficie_contruida_minima);
            $("#superficie_contruida_maxima").text(item.superficie_contruida_maxima);
            $("#recamaras_total").text(item.recamaras_total);
            $("#banios_maximo").text(item.banios_maximo);
            $("#medios_banios_maximo").text(item.medios_banios_maximo);
            $("#estacionamientos_maximo").text(item.estacionamientos_maximo);

            create_check_listview("list_view_services", services, item_features, 1);
            create_check_listview("list_view_general_caracteristics", general_caracteristics, item_features, 2);
            create_check_listview("list_view_social_areas", social_areas, item_features, 3);
            create_check_listview("list_view_outsides", outsides, item_features, 4);
            create_check_listview("list_view_amenities", amenities, item_features, 5);

            load_unities(id);
        }, 'json');
    }
    
    function load_unities(id){
        $.get(admin_url + 'inventory/get_unities/' + id, function(response) {
            console.log(response);
            grid_unities = 
                $("#grid_unities").kendoGrid({
                    dataSource: response,
                    columns: [
                        {field: "status", title: "Status"},                            
                        {field: "m2_habitables", title: "m2 Habitables"},
                        {field: "recamaras", title: "Recamaras"},
                        {field: "banios", title: "Banios"},
                        {field: "precio", title: "Precio"},
                        {field:"id", title:"Acciones", width:"100px", 
                        template: "<a id='btn_view_unity' href='' class='qodef-icon-shortcode normal qodef-icon-little'><i class='qodef-icon-font-awesome fa fa-eye qodef-icon-element'></i></a>"}
                    ],
                    dataBound: function(e) {
                        console.log("dataBound");
                        
                    }
                }).data("kendoGrid");

        }, 'json');
    }
    
    function create_check_listview(id_container, data, features, id_type_feature){
        var selected_items = $.map(features, function(item){ 
            if(item.id_type == id_type_feature)
                return parseInt(item.id_feature);
        });
        data = $.grep(data, function(item){
            return jQuery.inArray(item.id, selected_items) != -1;
        });
        
        chks_services = 
            $("#" + id_container).kendoListView({
                dataSource: data,
                template: "<div class='row'><a id='ico_item' _id='#:id#' class='qodef-icon-shortcode normal qodef-icon-little'><i class='qodef-icon-font-awesome fa fa-check qodef-icon-element'></i></a><span id='lbl_item'>#:nombre#</span></div>"
                ,
                dataBound: function(e) {
                    console.log("dataBound");
                    $.each(e.sender.items(), function(index, item){
                    });
                }
            }).data("kendoListView");
    }
});
