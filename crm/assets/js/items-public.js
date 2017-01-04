$(function() {
    //#kendo developments
    var window_unity; 
    var grid_unities;
    var admin_url = "http://localhost/perfex_crm/crm/admin/";
    
    var data = JSON.parse('[{"id":"1","nombre":"Residencial La Toscana 1","logotipo":"/uploads/logo-la-toscana.png","descripcion":"Casas con amplio espacio, 3 recámaras, excelente ubicación, vialidades rápidas y espacio de estacionamiento seguro","id_tipo_desarrollo":"1","id_etapa_desarrollo":"1","total_de_unidades":"10","id_entrega":"1","id_estado":"1","id_municipio":"1","id_colonia":"1","direccion":"Calzada de Belén 806-9","codigo_postal":"32767","id_mostar_ubicacion":"0","clave_interna":null}]');
    
    
    $(document).ready(function() {
        //#kendo
         debugger;
        console.log( "ready!" );
        if(window.location.href.indexOf("/desarrollo/")){
            console.log("Load propertie!!!");
            var id = 1;
            
            /*var arrLocationHref = window.location.href.split('?');
            if(arrLocationHref[1]){
                var arrQueryString = arrLocationHref[1].split('=');
                if(arrQueryString[0] == id) 
                    id = arrQueryString[1];
            }*/

            load_development(id);
        }
        else if(window.location.href.indexOf("/admin/inventory/item") != -1){
            var id = $("[name='item_id']").val()
            if(id)
                create_grid_unities(id);
            
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
            
            _validate_form($('#invoice_item_form_1'), {
                precio: 'required'
            }, manage_invoice_item);
        }
        
    });
    
    function load_development(id){
        $.get(admin_url + 'inventory/get_development/' + id, function(response) {
            debugger;
            console.log(response);
            
            $("#nombre").text(response.nombre);
            $("#direccion").text(response.direccion);
            $("#direccion2").text(response.direccion);
            
            $("#iframeMap").attr("src", "https://maps.google.com/maps?q="+response.direccion+"&ie=UTF8&&output=embed");
            $("#descripcion").text(response.descripcion);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            $("#nombre").text(response.nombre);
            
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
                        {field: "recamaras", title: "RecÃ¡maras"},
                        {field: "banios", title: "BaÃ±os"},
                        {field: "precio", title: "Precio"},
                        {field:"id", title:"Acciones", width:"100px", 
                        template: "<a id='btn_edit_unity' _id='#:id#' class='qodef-icon-shortcode normal qodef-icon-little'><i class='qodef-icon-font-awesome fa fa-pencil-square qodef-icon-element'></i></a> <a id='btn_delete_unity' _id='#:id#' class='qodef-icon-shortcode normal qodef-icon-little' style='cursor:pointer;'> <i class='qodef-icon-font-awesome fa fa-trash qodef-icon-element'></i> </a>"}
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
    
    
});
