jQuery(document).ready(
   function(){
       jQuery('[data-toggle="tooltip"]').tooltip();

       jQuery('#lp-is_adult').change(
           function(){
               if (this.checked) {
                    jQuery('#exit_url_section').show();
               }else {
                   jQuery('#exit_url_section').hide();
               }
           }
       );

   }
);
