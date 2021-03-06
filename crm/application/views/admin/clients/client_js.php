<?php
/**
 * Included in application/views/admin/clients/client.php
 */
?>
<script>
 Dropzone.options.clientAttachmentsUpload = false;
 var customer_id = $('input[name="userid"]').val();
 if ($('#client-attachments-upload').length > 0) {
   new Dropzone('#client-attachments-upload', {
     paramName: "file",
     dictFileTooBig: file_exceds_maxfile_size_in_form,
     maxFilesize: max_php_ini_upload_size.replace(/\D/g, ''),
     addRemoveLinks: false,
     accept: function(file, done) {
       done();
     },
     acceptedFiles: allowed_files,
     error: function(file, response) {
       alert_float('danger', response);
     },
     success: function(file, response) {
      if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
        window.location.reload();
      }
    }
  });
 }
 $(function() {


     $('a[href="#contacts"],a[href="#customer_admins"]').on('click', function() {
         $('.customer-form-submiter').addClass('hide');
     });

     $('.profile-tabs a').not('a[href="#contacts"],a[href="#customer_admins"]').on('click', function() {
         $('.customer-form-submiter').removeClass('hide');
     });


     var contact_id = get_url_param('contactid');
     if (contact_id) {
         contact(customer_id, contact_id);
         $('a[href="#contacts"]').click();
     }

     // If user clicked save and add new contact
     var new_contact = get_url_param('new_contact');
     if (new_contact) {
         contact(customer_id);
         $('a[href="#contacts"]').click();
     }
     $('.customer-form-submiter').on('click', function() {
         var form = $('.client-form');
         if (form.valid()) {
             if ($(this).hasClass('save-and-add-contact')) {
                 form.find('.additional').html(hidden_input('save_and_add_contact', 'true'));
             } else {
                 form.find('.additional').html('');
             }
             form.submit();
         }
     });

     if (typeof(Dropbox) != 'undefined' && $('#dropbox-chooser').length > 0) {
         document.getElementById("dropbox-chooser").appendChild(Dropbox.createChooseButton({
             success: function(files) {
                 $.post(admin_url + 'clients/add_external_attachment', {
                     files: files,
                     clientid: customer_id,
                     external: 'dropbox'
                 }).done(function() {
                     window.location.reload();
                 });
             },
             linkType: "preview",
             extensions: allowed_files.split(','),
         }));
     }


     var tickets_not_sortable = $('.table-tickets-single').find('th').length - 1;
     initDataTable('.table-tickets-single', admin_url + 'tickets/index/""/' + customer_id, [tickets_not_sortable], [tickets_not_sortable], 'undefined', [$('table thead .ticket_created_column').index(), 'DESC'])

     var headers_contracts = $('.table-contracts-single-client').find('th');
     var headers_contracts = (headers_contracts.length - 1);

     _table_api = initDataTable('.table-contracts-single-client', admin_url + 'contracts/index/' + customer_id, [headers_contracts], [headers_contracts], 'undefined', [3, 'DESC']);

     if (_table_api) {
         _table_api.column(2).visible(false, false).columns.adjust();
     }

     var headers_contacts = $('.table-contacts').find('th');
     var not_sortable_contacts = (headers_contacts.length - 1);

     initDataTable('.table-contacts', admin_url + 'clients/contacts/' + customer_id, [not_sortable_contacts], [not_sortable_contacts]);

     _table_api = initDataTable('.table-invoices-single-client', admin_url + 'invoices/list_invoices/false/' + customer_id, 'undefined', 'undefined', 'undefined', [
         [3, 'DESC'],
         [0, 'DESC']
     ]);
     if (_table_api) {
         _table_api.column(3).visible(false, false).columns.adjust();
         _table_api.column(5).visible(false, false).columns.adjust();
     }

     _table_api = initDataTable('.table-estimates-single-client', admin_url + 'estimates/list_estimates/false/' + customer_id, 'undefined', 'undefined', 'undefined', [
         [3, 'DESC'],
         [0, 'DESC']
     ]);

     if (_table_api) {
         _table_api.column(3).visible(false, false).columns.adjust();
         _table_api.column(4).visible(false, false).columns.adjust();
     }

     _table_api = initDataTable('.table-payments-single-client', admin_url + 'payments/list_payments/' + customer_id, [7], [7], 'undefined', [6, 'DESC']);
     if (_table_api) {
         _table_api.column(4).visible(false, false).columns.adjust();
     }
     initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + customer_id + '/' + 'customer', [4], [4]);
     _table_api = initDataTable('.table-expenses-single-client', admin_url + 'expenses/list_expenses/false/' + customer_id, 'undefined', 'undefined', 'undefined', [4, 'DESC']);

     if (_table_api) {
         _table_api.column(0).visible(false, false).columns.adjust();
         _table_api.column(6).visible(false, false).columns.adjust();
     }

     initDataTable('.table-proposals-client-profile', admin_url + 'proposals/proposal_relations/' + customer_id + '/customer', 'undefined', 'undefined', 'undefined', [4, 'DESC']);

     var headers_projects = $('.table-projects-single-client').find('th');
     var headers_projects = (headers_projects.length - 1);

     _table_api = initDataTable('.table-projects-single-client', admin_url + 'projects/index/' + customer_id, [headers_projects], [headers_projects], 'undefined', [2, 'DESC']);
     if (_table_api) {
         _table_api.column(0).visible(false, false).columns.adjust();
         _table_api.column(2).visible(false, false).columns.adjust();
     }
     _validate_form($('.client-form'), {
         company: 'required',
     });

     // Save button not hidden if passed from url ?tab= we need to re-click again
     if (tab_active) {
         $('body').find('.nav-tabs [href="#' + tab_active + '"]').click();
     }

     $('.billing-same-as-customer').on('click', function(e) {
         e.preventDefault();
         $('input[name="billing_street"]').val($('input[name="address"]').val());
         $('input[name="billing_city"]').val($('input[name="city"]').val());
         $('input[name="billing_state"]').val($('input[name="state"]').val());
         $('input[name="billing_zip"]').val($('input[name="zip"]').val());
         $('select[name="billing_country"]').selectpicker('val', $('select[name="country"]').selectpicker('val'));
     });
     $('.customer-copy-billing-address').on('click', function(e) {
         e.preventDefault();
         $('input[name="shipping_street"]').val($('input[name="billing_street"]').val());
         $('input[name="shipping_city"]').val($('input[name="billing_city"]').val());
         $('input[name="shipping_state"]').val($('input[name="billing_state"]').val());
         $('input[name="shipping_zip"]').val($('input[name="billing_zip"]').val());
         $('select[name="shipping_country"]').selectpicker('val', $('select[name="billing_country"]').selectpicker('val'));
     });

     $('body').on('hidden.bs.modal', '#contact', function() {
         $('#contact_data').empty();
     });

     $('.client-form').on('submit', function() {
         $('select[name="default_currency"]').prop('disabled',false);
     });

 });

 function validate_contact_form() {
     _validate_form('#contact-form', {
         firstname: 'required',
         lastname: 'required',
         password: {
             required: {
                 depends: function(element) {
                     var sent_set_password = $('input[name="send_set_password_email"]');
                     if ($('#contact input[name="contactid"]').val() == '' && sent_set_password.prop('checked') == false) {
                         return true;
                     }
                 }
             }
         },
         email: {
             required: true,
             email: true,
             remote: {
                 url: admin_url + "misc/contact_email_exists",
                 type: 'post',
                 data: {
                     email: function() {
                         return $('#contact input[name="email"]').val();
                     },
                     userid: function() {
                         return $('body').find('input[name="contactid"]').val();
                     }
                 }
             }
         }
     }, contactFormHandler);
 }

 function contactFormHandler(form) {
     $('#contact input[name="is_primary"]').prop('disabled', false);
     form = $(form);
     var data = form.serialize();
     var serializeArray = $(form).serializeArray();

     $.post(form.attr('action'), data).done(function(response) {
         response = JSON.parse(response);
         if (response.success) {
             alert_float('success', response.message);
         }
         if ($.fn.DataTable.isDataTable('.table-contacts')) {
             $('.table-contacts').DataTable().ajax.reload();
         }
         if (response.proposal_warning && response.proposal_warning != false) {
             $('body').find('#contact_proposal_warning').removeClass('hide');
             $('body').find('#contact_update_proposals_emails').attr('data-original-email', response.original_email);
             $('#contact').animate({ scrollTop: 0 }, 800);
         } else {
             $('#contact').modal('hide');
         }

     }).fail(function(error) {
         response = JSON.parse(error.responseText);
         alert_float('danger', response.message);
     });
     return false;
 }

 function contact(client_id, contact_id) {
     if (typeof(contact_id) == 'undefined') {
         contact_id = '';
     }
     $.post(admin_url + 'clients/contact/' + client_id + '/' + contact_id).done(function(response) {
         $('#contact_data').html(response);
         $('#contact').modal({show:true,backdrop:'static'});
         $('body').on('shown.bs.modal', '#contact', function() {
             var contactid = $(this).find('input[name="contactid"]').val();
             if (contact_id == '') {
                 // Is new contact focus the field
                 $('#contact').find('input[name="firstname"]').focus();
             }
         });
         init_selectpicker();
         init_datepicker();
         validate_contact_form();
     }).fail(function(error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
 }

 function update_all_proposal_emails_linked_to_contact(contact_id) {
     var data = {};
     data.update = true;
     data.original_email = $('body').find('#contact_update_proposals_emails').data('original-email');
     $.post(admin_url + 'clients/update_all_proposal_emails_linked_to_customer/' + contact_id, data).done(function(response) {
         response = JSON.parse(response);
         if (response.success) {
             alert_float('success', response.message);
         }
         $('#contact').modal('hide');
     });
 }
</script>
