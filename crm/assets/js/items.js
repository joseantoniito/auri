$(function() {
    //#kendo unities
    var window_unity; 
    var grid_unities;
    $(document).ready(function() {
        //#kendo
         debugger;
        console.log( "ready!" );
        if(window.location.href.indexOf("/admin/invoice_items/invoice_items") != -1){
            
        }
        else if(window.location.href.indexOf("/admin/invoice_items/item") != -1){
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
    
    function create_grid_unities(id){
        var itemid = 1;
        $.get(admin_url + 'invoice_items/get_unities/' + id, function(response) {
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
    
    function manage_invoice_item(form){
        //#kendo todo
         
        var data = $(form).serialize();
        data += "&tax=''";
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                if ($('body').find('.ei-template').length > 0) {
                    $('#item_select').find('option').eq(0).after('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">' + response.item.description + '</option>');
                    $('body').find('#item_select').selectpicker('refresh');
                    add_item_to_preview(response.item.itemid);
                } else {
                    // Is general items view
                    //$('.table-invoice-items').DataTable().ajax.reload();
                }
                alert_float('success', response.message);
            }
            //$('#sales_item_modal').modal('hide');
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function edit_unity(event){
         
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'invoice_items/get_unity/' + id, function(response) {
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
        //todo
         
        var id = $(event.currentTarget).attr("_id");

        $.get(admin_url + 'invoice_items/delete_unity/' + id, function(response) {
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
                
                /*if ($('body').find('.ei-template').length > 0) {
                    $('#item_select').find('option').eq(0).after('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">' + response.item.description + '</option>');
                    $('body').find('#item_select').selectpicker('refresh');
                    add_item_to_preview(response.item.itemid);
                } else {
                    // Is general items view
                    $('.table-invoice-items').DataTable().ajax.reload();
                }
                alert_float('success', response.message);*/
            }
            $('#sales_item_modal').modal('hide');
        }).fail(function(data) {
            alert_float('danger', data.responseText);
        });
        return false;
    }
    
    function refresh_grid_unities(response){
        debugger;
        console.log(grid_unities);
        
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
