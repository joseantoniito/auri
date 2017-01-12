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
        console.log( "ready!", $(window).height());
        if(window.location.href.indexOf("/desarrollo/") != -1){
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
        if(window.location.href.indexOf("/listado/") != -1){
            create_developments_listview();
        }
        if(window.location.href.indexOf("/inicio/") != -1){
            create_home_developments_listviews();
            create_locations_dropdowns();
        }
    });
    
    var sample_developments_listview;
    function create_home_developments_listviews(){
        $.get(admin_url + 'inventory/get_developments/', function(response) {
            console.log(response);
            var sample_developments = response.slice(0, 3);
            var featured_developments = response.slice(0,4);//(3, 6);
            load_home_media_items(featured_developments);
            
            sample_developments_listview = 
                $("#sample_developments_listview").kendoListView({
                    dataSource: sample_developments,
                    template: kendo.template($("#sample_developments_template").html()),
                    dataBound: function(e) {
                        console.log("dataBound");
                        $.each(e.sender.items(), function(index, item){
                            item = $(item);
                            item.find("#descripcion").text(
                            item.find("#descripcion").text().substring(0,75) + ". . .");
                        });
                    }
            }).data("kendoListView");
            
            featured_developments_listview = 
                $("#featured_developments_listview").kendoListView({
                    dataSource: featured_developments,
                    template: kendo.template($("#featured_developments_template").html()),
                    dataBound: function(e) {
                        console.log("dataBound");
                        $.each(e.sender.items(), function(index, item){
                            item = $(item);
                            item.find("#descripcion").text(
                            item.find("#descripcion").text().substring(0,60) + ". . .");
                        });
                    }
            }).data("kendoListView");
        }, 'json');
    }
    
    var carousel_home_listview;
    function load_home_media_items(item_media_items, id){
        carousel_home_listview = 
                $("#carousel_home").kendoListView({
                    dataSource: item_media_items,
                    template: kendo.template($("#carousel_home_template").html()),
                    dataBound: function(e) {
                        console.log("dataBound");
                        var ol = $('#my_carousel_home .carousel-indicators');
                        $.each(e.sender.items(), function(index, item){
                            if(index == 0)
                                $(item).addClass("active");
                            var li = $("<li></li>")
                                .attr("data-target", "#myCarousel")
                                .attr("data-slide-to", index)
                                .attr("class", index == 0 ? "active" : "");
                            ol.append(li);
                            
                            item = $(item);
                            item.find("#descripcion").text(
                            item.find("#descripcion").text().substring(0,100) + ". . .");
                        });
                        $('#my_carousel_home.carousel').carousel();
                    }
            }).data("kendoListView");
        
        /*var list_box = $('#myCarousel .carousel-inner');
        $.each(item_media_items, function(index, item){
            var list_box_item = $("<div></div>")
                .attr("class", "item " + (index == 0 ? "active" : "") )
                .append($("<img />")
                    .attr("width", "585")
                    .attr("height", "585")
                    .attr("alt", item.nombre)
                    .attr("src", "/crm" + item.url_imagen_principal)
                    .attr("_id", item.id)
                );
            list_box.append(list_box_item);
        });*/
        
        
        
    }
    
    function load_development(id){
        $.get(admin_url + 'inventory/get_development/' + id, function(response) {
            console.log(response);
            var item = response.item;
            var item_features = response.item_features;
            var item_media_items = response.item_media_items;
            
            load_media_items(item_media_items, id);

            $("#nombre").text(item.nombre);
            $("#direccion").text(item.direccion_completa);
            $("#direccion2").text(item.direccion_completa);
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

            load_location(item.latitud, item.longitud);
            load_unities(id);
        }, 'json');
    }
    
    var map_locations, marker_locations;
    function load_location(latitud, longitud){
        var position = {
            lat: latitud != "0" ? parseFloat(latitud) : -34.397, 
            lng: longitud != "0" ? parseFloat(longitud) : 150.644
        };
        map_locations = new google.maps.Map(
            document.getElementById('map_locations'), {
                center: position,
                zoom: 8
            });
        marker_locations = new google.maps.Marker({
            map: map_locations,
            position: position
        });
        map_locations.setZoom(15);
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
    
    function load_media_items(item_media_items, id){
        var ol = $('#myCarousel .carousel-indicators');
        var list_box = $('#myCarousel .carousel-inner');
        
        $.each(item_media_items, function(index, item){
            var li = $("<li></li>")
                .attr("data-target", "#myCarousel")
                .attr("data-slide-to", index)
                .attr("class", index == 0 ? "active" : "");
            ol.append(li);
            
            var list_box_item = $("<div></div>")
                .attr("class", "item " + (index == 0 ? "active" : "") )
                .append($("<img />")
                    .attr("width", "585")
                    .attr("height", "585")
                    .attr("alt", item.name)
                    .attr("src", "/crm" + item.url)
                    .attr("_id", item.id)
                );
            list_box.append(list_box_item);
        });
        
        $('#myCarousel.carousel').carousel();
        
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
                template: "<div class='row'><a id='ico_item' _id='' class='qodef-icon-shortcode normal qodef-icon-little'><i class='qodef-icon-font-awesome fa fa-check qodef-icon-element'></i></a><span id='lbl_item'>#:nombre#</span></div>"
                ,
                dataBound: function(e) {
                    console.log("dataBound");
                    $.each(e.sender.items(), function(index, item){
                    });
                }
            }).data("kendoListView");
    }
    
    //list developments
    var developments_listview;
    function create_developments_listview(id_container, data){
        $.get(admin_url + 'inventory/get_developments/', function(response) {
            console.log(response);
            
            id_container = "developments_listview";
        developments_listview = 
            $("#" + id_container).kendoListView({
                dataSource: response,
                template: kendo.template($("#developments_listview_template").html()),//"div> <h1> #:nombre#</h1></div>"
                dataBound: function(e) {
                    console.log("dataBound");
                    $.each(e.sender.items(), function(index, item){
                        item = $(item);
                        
                        if(index%2 == 0)
                            item.addClass("listing-post-odd");
                        else
                            item.addClass("listing-post-even");
                        
                        item.find("#descripcion").text(
                            item.find("#descripcion").text().substring(0,75) + ". . .");
                        
                        item.find(".icon-area svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-area svg path").attr("d", 
                            "M46 16v-12c0-1.104-.896-2.001-2-2.001h-12c0-1.103-.896-1.999-2.002-1.999h-11.997c-1.105 0-2.001.896-2.001 1.999h-12c-1.104 0-2 .897-2 2.001v12c-1.104 0-2 .896-2 2v11.999c0 1.104.896 2 2 2v12.001c0 1.104.896 2 2 2h12c0 1.104.896 2 2.001 2h11.997c1.106 0 2.002-.896 2.002-2h12c1.104 0 2-.896 2-2v-12.001c1.104 0 2-.896 2-2v-11.999c0-1.104-.896-2-2-2zm-4.002 23.998c0 1.105-.895 2.002-2 2.002h-31.998c-1.105 0-2-.896-2-2.002v-31.999c0-1.104.895-1.999 2-1.999h31.998c1.105 0 2 .895 2 1.999v31.999zm-5.623-28.908c-.123-.051-.256-.078-.387-.078h-11.39c-.563 0-1.019.453-1.019 1.016 0 .562.456 1.017 1.019 1.017h8.935l-20.5 20.473v-8.926c0-.562-.455-1.017-1.018-1.017-.564 0-1.02.455-1.02 1.017v11.381c0 .562.455 1.016 1.02 1.016h11.39c.562 0 1.017-.454 1.017-1.016 0-.563-.455-1.019-1.017-1.019h-8.933l20.499-20.471v8.924c0 .563.452 1.018 1.018 1.018.561 0 1.016-.455 1.016-1.018v-11.379c0-.132-.025-.264-.076-.387-.107-.249-.304-.448-.554-.551z");
                        item.find(".icon-bed svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-bed svg path").attr("d", 
                            "M21 48.001h-19c-1.104 0-2-.896-2-2v-31c0-1.104.896-2 2-2h19c1.106 0 2 .896 2 2v31c0 1.104-.895 2-2 2zm0-37.001h-19c-1.104 0-2-.895-2-1.999v-7.001c0-1.104.896-2 2-2h19c1.106 0 2 .896 2 2v7.001c0 1.104-.895 1.999-2 1.999zm25 37.001h-19c-1.104 0-2-.896-2-2v-31c0-1.104.896-2 2-2h19c1.104 0 2 .896 2 2v31c0 1.104-.896 2-2 2zm0-37.001h-19c-1.104 0-2-.895-2-1.999v-7.001c0-1.104.896-2 2-2h19c1.104 0 2 .896 2 2v7.001c0 1.104-.896 1.999-2 1.999z");
                        item.find(".icon-bath svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-bath svg path").attr("d", 
                            "M37.003 48.016h-4v-3.002h-18v3.002h-4.001v-3.699c-4.66-1.65-8.002-6.083-8.002-11.305v-4.003h-3v-3h48.006v3h-3.001v4.003c0 5.223-3.343 9.655-8.002 11.305v3.699zm-30.002-24.008h-4.001v-17.005s0-7.003 8.001-7.003h1.004c.236 0 7.995.061 7.995 8.003l5.001 4h-14l5-4-.001.01.001-.009s.938-4.001-3.999-4.001h-1s-4 0-4 3v17.005000000000003h-.001z");
                        item.find(".icon-garage svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-garage svg path").attr("d", 
                            "M44 0h-40c-2.21 0-4 1.791-4 4v44h6v-40c0-1.106.895-2 2-2h31.999c1.106 0 2.001.895 2.001 2v40h6v-44c0-2.209-1.792-4-4-4zm-36 8.001h31.999v2.999h-31.999zm0 18h6v5.999h-2c-1.104 0-2 .896-2 2.001v6.001c0 1.103.896 1.998 2 1.998h2v2.001c0 1.104.896 2 2 2s2-.896 2-2v-2.001h11.999v2.001c0 1.104.896 2 2.001 2 1.104 0 2-.896 2-2v-2.001h2c1.104 0 2-.895 2-1.998v-6.001c0-1.105-.896-2.001-2-2.001h-2v-5.999h5.999v-3h-31.999v3zm8 12.999c-1.104 0-2-.895-2-1.999s.896-2 2-2 2 .896 2 2-.896 1.999-2 1.999zm10.5 2h-5c-.276 0-.5-.225-.5-.5 0-.273.224-.498.5-.498h5c.275 0 .5.225.5.498 0 .275-.225.5-.5.5zm1-2h-7c-.275 0-.5-.225-.5-.5s.226-.499.5-.499h7c.275 0 .5.224.5.499s-.225.5-.5.5zm-6.5-2.499c0-.276.224-.5.5-.5h5c.275 0 .5.224.5.5s-.225.5-.5.5h-5c-.277 0-.5-.224-.5-.5zm11 2.499c-1.104 0-2.001-.895-2.001-1.999s.896-2 2.001-2c1.104 0 2 .896 2 2s-.896 1.999-2 1.999zm0-12.999v5.999h-16v-5.999h16zm-24-13.001h31.999v3h-31.999zm0 5h31.999v3h-31.999z");
                        item.find(".icon-ptype svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-ptype svg path").attr("d", 
                            "M24 48.001c-13.255 0-24-10.745-24-24.001 0-13.254 10.745-24 24-24s24 10.746 24 24c0 13.256-10.745 24.001-24 24.001zm10-27.001l-10-8-10 8v11c0 1.03.888 2.001 2 2.001h3.999v-9h8.001v9h4c1.111 0 2-.839 2-2.001v-11z");
                        item.find(".icon-tag svg").attr("xmlns", "http://www.w3.org/2000/svg");
                        item.find(".icon-tag svg path").attr("d", 
                            "M47.199 24.176l-23.552-23.392c-.504-.502-1.174-.778-1.897-.778l-19.087.09c-.236.003-.469.038-.696.1l-.251.1-.166.069c-.319.152-.564.321-.766.529-.497.502-.781 1.196-.778 1.907l.092 19.124c.003.711.283 1.385.795 1.901l23.549 23.389c.221.218.482.393.779.523l.224.092c.26.092.519.145.78.155l.121.009h.012c.239-.003.476-.037.693-.098l.195-.076.2-.084c.315-.145.573-.319.791-.539l18.976-19.214c.507-.511.785-1.188.781-1.908-.003-.72-.287-1.394-.795-1.899zm-35.198-9.17c-1.657 0-3-1.345-3-3 0-1.657 1.343-3 3-3 1.656 0 2.999 1.343 2.999 3 0 1.656-1.343 3-2.999 3z");
                    });
                }
            }).data("kendoListView");
            //developments_listview.setDataSource([]);
        }, 'json');
    }
    
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
                optionLabel: "Estado (Any)",
                //value: id_estado,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_estado']").val(id);
                    $.get(admin_url + 'inventory/get_location_municipalities/' + id, function(response) {
                        dropdown_municipios.setDataSource(response);
                        //dropdown_municipios.value(id_municipio);
                        //dropdown_municipios.trigger("change");
                    }, 'json');
                }
            }).data("kendoDropDownList");
        dropdown_municipios = 
            $("#dropdown_municipios").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: [],
                optionLabel: "Municipio (Any)",
                //value: id_municipio,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_municipio']").val(id);
                    $.get(admin_url + 'inventory/get_location_colonies/' + id, function(response) {
                        dropdown_colonias.setDataSource(response);
                        //dropdown_colonias.value(id_colonia);
                        //dropdown_colonias.trigger("change");
                    }, 'json');
                }
            }).data("kendoDropDownList");
        dropdown_colonias = 
            $("#dropdown_colonias").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: [],
                optionLabel: "Colonia (Any)",
                //value: id_colonia,
                change: function(e) {
                    var id = this.value();
                    $("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        var data_10 = [];
        for(i=1; i<=10; i++)
            data_10.push({id: 1, nombre: i + ""});
        var data_money = [
            {id:1000, nombre:"$1,000"},{id:5000, nombre:"$5,000"},{id:10000, nombre:"$10,000"},{id:50000, nombre:"$50,000"},{id:100000, nombre:"$100,000"},{id:200000, nombre:"$200,000"},{id:300000, nombre:"$300,000"},{id:400000, nombre:"$400,000"},{id:500000, nombre:"$500,000"},{id:600000, nombre:"$600,000"},{id:700000, nombre:"$700,000"},{id:800000, nombre:"$800,000"},{id:900000, nombre:"$900,000"},{id:1000000, nombre:"$1,000,000"},{id:1500000, nombre:"$1,500,000"},{id:2000000, nombre:"$2,000,000"},{id:2500000, nombre:"$2,500,000"},{id:5000000, nombre:"$5,000,000"}
        ];
        
        dropdown_recamaras = 
            $("#dropdown_recamaras").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: data_10,
                optionLabel: "Recámaras (Any)",
                change: function(e) {
                    var id = this.value();
                    //$("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        dropdown_banios = 
            $("#dropdown_banios").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: data_10,
                optionLabel: "Recámaras (Any)",
                change: function(e) {
                    var id = this.value();
                    //$("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        dropdown_precio_minimo = 
            $("#dropdown_precio_minimo").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: data_money,
                optionLabel: "Precio Min. (Any)",
                change: function(e) {
                    var id = this.value();
                    //$("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        dropdown_precio_maximo = 
            $("#dropdown_precio_maximo").kendoDropDownList({
                dataTextField: "nombre",
                dataValueField: "id",
                dataSource: data_money,
                optionLabel: "Precio Max. (Any)",
                change: function(e) {
                    var id = this.value();
                    //$("[name='id_colonia']").val(id);
                }
            }).data("kendoDropDownList");
        
        
       /* geocoder_locations = new google.maps.Geocoder();
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
        });*/
        
        $.get(admin_url + 'inventory/get_location_states/', function(response) {
            dropdown_estados.setDataSource(response);
            //dropdown_estados.value(id_estado);
            //dropdown_estados.trigger("change");
        }, 'json');
    }
});
