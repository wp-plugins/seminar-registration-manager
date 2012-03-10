/*SEMINARS*/
    function delete_seminar(location){
        var c=confirm("Are you SURE you want to delete this seminar? Once deleted, it is gone forever.");
        if (c){
            window.location=location;
        }
    }
    
    jQuery(document).ready(function() {
        jQuery( ".srm-date" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
        
        if (jQuery('#add-edit-coupon-form').length){
            document.forms['add-edit-coupon-form'].reset();
        }
        
    });
/**********/    

/*REGISTRANTS*/
    function delete_registrant(location){
        var c=confirm("Are you SURE you want to delete this registrant? Once deleted, he/she will be gone forever.");
        if (c){
            window.location=location;
        }
    }
    
    function update_paid_status(registrant_id, value, post_url){
        jQuery.ajax({
            url: post_url,
            dataType: 'html',
            data: 'registrant_id='+registrant_id+'&update_registrant_paid_status=true&paid_status='+value,
            type: 'POST',
            success: function(data){
                if (data==1){
                    alert('Paid status updated for this registrant');
                } else {
                    alert('There was a problem updating this registrant\'s paid status: '+data);
                }
            }
                
        });
    }
/*************/

/*COUPON CODES*/
    function delete_coupon_code(location){
        var c=confirm("Are you SURE you want to delete this coupon code? Once deleted, it will be gone forever.");
        if (c){
            window.location=location;
        }
    }

    function update_coupon_active(coupon_id, value, post_url){
        jQuery.ajax({
            url: post_url,
            dataType: 'html',
            data: 'coupon_id='+coupon_id+'&update_coupon_active=true&coupon_active='+value,
            type: 'POST',
            success: function(data){
                if (data==1){
                    alert('Active status updated for this coupon');
                } else {
                    alert('There was a problem updating this coupon\'s active status: '+data);
                }
            }
                
        });
    }
/**************/

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
                
                //phone
        
                //email
                
                //number
                
            }
        });
        
        if (form_valid){
            return true;
        } else {
            return false;
        }
    }
    
    //payment gateway extra fields
    function toggle_payment_gateway_fields(){
        var selected_gateway=jQuery('#srm-SRM_PAYMENT_GATEWAY').val();
        jQuery('.srm-payment-credentials').each(function(){
            if ( jQuery( this ).attr( 'id' ) == 'srm-'+selected_gateway){
                jQuery(this).removeClass('srm-hide');
            } else {
                jQuery(this).addClass('srm-hide');
            }
        });
    }
/**********/