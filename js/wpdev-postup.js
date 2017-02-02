jQuery(document).ready(function() {
  jQuery('input#wpdevemailaddress').prop('required', true);
  jQuery("#wpdev-postup-form").submit(function() {
      var email_add = jQuery('input#wpdevemailaddress').val();
      var list_id = jQuery('form#wpdev-postup-form').data('list-id');
      jQuery('div#wpdev-postup-form-fields').hide();
      jQuery('div#wpdev-postup-load').show();
      jQuery.ajax({
        url: wpdevpostup.ajax_url,
        type: 'post',
        data : {
          action  : 'wpdev_postup_add',
          email   : email_add,
          listid  : list_id,
          security: wpdevpostup.security
        },
        success   : function(response) {
          jQuery('div#wpdev-postup-load').html(response);
        }
      });
      event.preventDefault();
  });
});
