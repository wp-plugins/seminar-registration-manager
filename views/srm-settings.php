<?php 
    //get default settings from DB
    $plugin_settings=SrmCommon::get_plugin_settings();
    $plugin_values=array();
    foreach( $plugin_settings as $plugin_setting ):
        $plugin_values[$plugin_setting['key']]=$plugin_setting['value'];
    endforeach;
?>
<div class="srm-seminars">
    <div class="srm-header">
        <h1>Edit SRM Settings</h1>
    </div>
    <div class="srm-form">
        <?php
            if ($has_errors):
        ?>
                <div class="error"><?php echo $error_msg; ?></div>
        <?php
            endif;
        ?>
        <?php
            if ($has_message):
        ?>
                <div class="updated"><?php echo $msg; ?></div>
        <?php
            endif;
        ?>
        <form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?>" method="post" enctype="multipart/form-data" id="edit-settings-form" name="edit_settings_form" onsubmit="return srm_validate_form('edit-settings-form');">
            <fieldset>
                <legend>General SRM Settings</legend>
                <div class="form-row">
                    <div class="left">Load jQuery?:</div>
                    <div class="right">
                        <select name="SRM_LOAD_JQUERY" id="srm-SRM_LOAD_JQUERY">
                            <option value="1" <?php if ( $plugin_values['SRM_LOAD_JQUERY'] == 1 ): ?>selected="selected" <?php endif; ?>>Yes</option>
                            <option value="0" <?php if ( $plugin_values['SRM_LOAD_JQUERY'] == 0 ): ?>selected="selected" <?php endif; ?>>No</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Load jQuery UI?:</div>
                    <div class="right">
                        <select name="SRM_LOAD_JQUERYUI" id="srm-SRM_LOAD_JQUERYUI">
                            <option value="1" <?php if ( $plugin_values['SRM_LOAD_JQUERYUI'] == 1 ): ?>selected="selected" <?php endif; ?>>Yes</option>
                            <option value="0" <?php if ($plugin_values['SRM_LOAD_JQUERYUI'] == 0 ): ?>selected="selected" <?php endif; ?>>No</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Default Registration Page:</div>
                    <div class="right">
                        <input type="text" name="SRM_DEFAULT_REGISTRATION_PAGE" id="srm-SRM_DEFAULT_REGISTRATION_PAGE" value="<?php echo $plugin_values['SRM_DEFAULT_REGISTRATION_PAGE']; ?>" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Default Thank-You Page:</div>
                    <div class="right">
                        <input type="text" name="SRM_DEFAULT_THANKS_PAGE" id="srm-SRM_DEFAULT_THANKS_PAGE" value="<?php echo $plugin_values['SRM_DEFAULT_THANKS_PAGE']; ?>" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Max Image Size:</div>
                    <div class="right">
                        <select name="SRM_IMAGE_MAX_SIZE_KB" id="srm-SRM_IMAGE_MAX_SIZE_KB">
                            <option value="500" <?php if ( $plugin_values['SRM_IMAGE_MAX_SIZE_KB'] == 500 ): ?>selected="selected" <?php endif; ?>>500KB</option>
                            <option value="1000" <?php if ( $plugin_values['SRM_IMAGE_MAX_SIZE_KB'] == 1000 ): ?>selected="selected" <?php endif; ?>>1MB</option>
                            <option value="2500" <?php if ( $plugin_values['SRM_IMAGE_MAX_SIZE_KB'] == 2500 ): ?>selected="selected" <?php endif; ?>>2.5MB</option>
                            <option value="10000" <?php if ( $plugin_values['SRM_IMAGE_MAX_SIZE_KB'] == 10000 ): ?>selected="selected" <?php endif; ?>>10MB</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Seminar Image Width:</div>
                    <div class="right">
                        <select name="SRM_IMAGE_WIDTH" id="srm-SRM_IMAGE_WIDTH">
                            <option value="100" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 100 ): ?>selected="selected" <?php endif; ?>>100 pixels</option>
                            <option value="200" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 200 ): ?>selected="selected" <?php endif; ?>>200 pixels</option>
                            <option value="300" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 300 ): ?>selected="selected" <?php endif; ?>>300 pixels</option>
                            <option value="400" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 400 ): ?>selected="selected" <?php endif; ?>>400 pixels</option>
                            <option value="500" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 500 ): ?>selected="selected" <?php endif; ?>>500 pixels</option>
                            <option value="600" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 600 ): ?>selected="selected" <?php endif; ?>>600 pixels</option>
                            <option value="700" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 700 ): ?>selected="selected" <?php endif; ?>>700 pixels</option>
                            <option value="800" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 800 ): ?>selected="selected" <?php endif; ?>>800 pixels</option>
                            <option value="900" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 900 ): ?>selected="selected" <?php endif; ?>>900 pixels</option>
                            <option value="1000" <?php if ( $plugin_values['SRM_IMAGE_WIDTH'] == 1000 ): ?>selected="selected" <?php endif; ?>>1000 pixels</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Payment Gateway:</div>
                    <div class="right">
                        <select name="SRM_PAYMENT_GATEWAY" id="srm-SRM_PAYMENT_GATEWAY" onchange="toggle_payment_gateway_fields()">
                            <option value="authorize_net" <?php if ( $plugin_values['SRM_PAYMENT_GATEWAY'] == 'authorize_net' ): ?>selected="selected" <?php endif; ?>>Authorize.net</option>
                            <option value="paypal_payments_pro" <?php if ( $plugin_values['SRM_PAYMENT_GATEWAY'] == 'paypal_payments_pro' ): ?>selected="selected" <?php endif; ?>>PayPal Website Payments Pro</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Payment Gateway Mode:</div>
                    <div class="right">
                        <select name="SRM_PAYMENT_GATEWAY_MODE" id="srm-SRM_PAYMENT_GATEWAY_MODE">
                            <option value="LIVE" <?php if ( $plugin_values['SRM_PAYMENT_GATEWAY_MODE'] == 'LIVE' ): ?>selected="selected" <?php endif; ?>>LIVE</option>
                            <option value="TEST" <?php if ( $plugin_values['SRM_PAYMENT_GATEWAY_MODE'] == 'TEST' ): ?>selected="selected" <?php endif; ?>>TEST</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row srm-hide srm-payment-credentials" id="srm-authorize_net">
                    <div class="left">Authorize.net Credentials:</div>
                    <div class="right">
                        <strong>API Key</strong> <input type="text" name="SRM_AUTHORIZE_NET_APIKEY" id="srm-SRM_AUTHORIZE_NET_APIKEY" value="<?php echo $plugin_values['SRM_AUTHORIZE_NET_APIKEY']; ?>" />
                        <br />
                        <strong>Transaction ID</strong> <input type="text" name="SRM_AUTHORIZE_NET_TRANSID" id="srm-SRM_AUTHORIZE_NET_TRANSID" value="<?php echo $plugin_values['SRM_AUTHORIZE_NET_TRANSID']; ?>" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row srm-hide srm-payment-credentials" id="srm-paypal_payments_pro">
                    <div class="left">PayPal Payments Pro Credentials:</div>
                    <div class="right">
                        <strong>API Username</strong> <input type="text" name="PAYPAL_PAYMENTS_PRO_USERNAME" id="srm-PAYPAL_PAYMENTS_PRO_USERNAME" value="<?php echo $plugin_values['PAYPAL_PAYMENTS_PRO_USERNAME']; ?>" />
                        <br />
                        <strong>API Password</strong> <input type="text" name="PAYPAL_PAYMENTS_PRO_PASSWORD" id="srm-PAYPAL_PAYMENTS_PRO_PASSWORD" value="<?php echo $plugin_values['PAYPAL_PAYMENTS_PRO_PASSWORD']; ?>" />
                        <br />
                        <strong>API Signature</strong> <input type="text" name="PAYPAL_PAYMENTS_PRO_SIGNATURE" id="srm-PAYPAL_PAYMENTS_PRO_SIGNATURE" value="<?php echo $plugin_values['PAYPAL_PAYMENTS_PRO_SIGNATURE']; ?>" />
                    </div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>SRM Admin Area</legend>
                <div class="form-row">
                    <div class="left">Results Per Page:</div>
                    <div class="right">
                        <select name="SRM_PER_PAGE" id="srm-SRM_PER_PAGE">
                            <option value="5" <?php if ( $plugin_values['SRM_PER_PAGE'] == 5 ): ?>selected="selected" <?php endif; ?>>5</option>
                            <option value="10" <?php if ( $plugin_values['SRM_PER_PAGE'] == 10 ): ?>selected="selected" <?php endif; ?>>10</option>
                            <option value="15" <?php if ( $plugin_values['SRM_PER_PAGE'] == 15 ): ?>selected="selected" <?php endif; ?>>15</option>
                            <option value="25" <?php if ( $plugin_values['SRM_PER_PAGE'] == 25 ): ?>selected="selected" <?php endif; ?>>25</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Admin Email:</div>
                    <div class="right">
                        <input type="text" name="SRM_ADMIN_EMAIL" id="srm-SRM_ADMIN_EMAIL" value="<?php echo $plugin_values['SRM_ADMIN_EMAIL']; ?>" class="required email" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Email From Address:</div>
                    <div class="right">
                        <input type="text" name="SRM_MAIL_FROM" id="srm-SRM_MAIL_FROM" value="<?php echo $plugin_values['SRM_MAIL_FROM']; ?>" class="required email" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="center"><input type="submit" name="submit" value="Edit Settings" /></div>
                <input type="hidden" name="update_srm_settings" value="true" />
            </fieldset>
        </form>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                toggle_payment_gateway_fields();
            });
        </script>
    </div>
</div>