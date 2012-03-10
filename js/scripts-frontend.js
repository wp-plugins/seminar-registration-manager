jQuery(document).ready(function(){
    
});

/*GENERAL*/    
    //form validation
    function srm_validate_form(form_id){
        var form_valid=true;
        var invalid_class='invalid';
        
        //check that all required fields are populated
        jQuery('#'+form_id).find('.required').each(function(){
            if (jQuery(this).val().length==''){
                jQuery(this).addClass(invalid_class);
                form_valid=false;
            } else {
                jQuery(this).removeClass(invalid_class);
                
                //required checkboxes
                if (jQuery(this).attr('type')=='checkbox' && !jQuery(this).is('input:checked')){
                    jQuery(this).addClass(invalid_class);
                    form_valid=false;
                    if (jQuery(this).attr('id')=='srm-agreement'){
                        jQuery('#srm-agreement-error').html('You must agree to the terms of registration');
                    }
                } else {
                    jQuery(this).removeClass(invalid_class);
                }
                
                //phone
                if (jQuery(this).hasClass('phone')){
                    var phone_pattern = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
                    if (!phone_pattern.test(jQuery(this).val())){
                        jQuery(this).addClass(invalid_class);
                        form_valid=false;
                    } else {
                        jQuery(this).removeClass(invalid_class);
                    } 
                } 
                
                //email
                if (jQuery(this).hasClass('email')){
                    var email_pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; 
                    if (!email_pattern.test(jQuery(this).val())){
                        jQuery(this).addClass(invalid_class);
                        form_valid=false;
                    } else {
                        jQuery(this).removeClass(invalid_class);
                    }  
                } 
                
                //number
                if (jQuery(this).hasClass('number')){
                    if (isNaN(jQuery(this).val())){
                        jQuery(this).addClass(invalid_class);
                        form_valid=false;
                    } else {
                        jQuery(this).removeClass(invalid_class);
                    }
                } 
            }
        });
        
        if (form_valid){
            return true;
        } else {
            return false;
        }
    }
/**********/

/*registrants*/
    function handle_additional_registrants(registrant_type, num){
        var registrant_container='srm-additional-'+registrant_type+'-container';
        if (num > 0){
            jQuery('#'+registrant_container).html('<h4>Additional '+registrant_type.charAt(0).toUpperCase() + registrant_type.slice(1)+'</h4>');
            for (i=0; i<num; i++){
                var this_container_info='<div class="srm-additional-registrant"><div class="srm-clear"><strong>'+eval(i+1)+'.</strong></div><div class="form-row"><div class="left">Name:</div><div class="right"><input type="text" name="additional_'+registrant_type+'_entry['+i+'][name]" /></div><div class="srm-clear"></div></div><div class="form-row"><div class="left">Phone:</div><div class="right"><input type="text" name="additional_'+registrant_type+'_entry['+i+'][phone]" /></div><div class="srm-clear"></div></div><div class="form-row"><div class="left">Fax:</div><div class="right"><input type="text" name="additional_'+registrant_type+'_entry['+i+'][fax]" /></div><div class="srm-clear"></div></div><div class="form-row"><div class="left">Email:</div><div class="right"><input type="text" name="additional_'+registrant_type+'_entry['+i+'][email]" /></div><div class="srm-clear"></div></div></div>';
                jQuery('#'+registrant_container).append(this_container_info);
            }
        } else {
            jQuery('#'+registrant_container).html('');
        }
    }
    
    
    function srm_total(doctors_price, doctors_num, staff_price, staff_num){
        var initial_price=doctors_price;
        initial_price+=eval(doctors_price*doctors_num);
        initial_price+=eval(staff_price*staff_num);
        jQuery('#srm-total').html(initial_price.toFixed(2));
    }
/************************/