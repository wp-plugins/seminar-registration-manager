<?php
    $us_states=SrmCommon::get_states();
    if ($seminar_chosen):
?>
        <div class="srm-form">
            <?php 
                if ($coupon_code_submitted):
            ?>
                    <div class="updated"><?php echo $coupon_code_msg; ?></div>
            <?php 
                endif;
            ?>
            <?php 
                if (isset($registration_success) && $registration_success!=1):
            ?>
                    <div class="warning"><?php echo $registration_msg; ?></div>
            <?php 
                endif;
            ?>
            <form action="" method="post" id="srm-registration" onsubmit="return srm_validate_form(this.id);">
                <fieldset>
                    <legend>Seminar Information</legend>
                    <div class="two-col-left">
                        <div class="form-row">
                            <div class="left">Seminar:</div>
                            <div class="right"><strong><?php echo $seminar_data['title']; ?></strong></div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Seminar Location:</div>
                            <div class="right">
                                <i><?php echo $seminar_data['location']; ?></i>
                                <br />
                                <?php echo $seminar_data['address1']; ?>
                                <?php if (!empty($seminar_data['address2'])): ?>
                                    <br />
                                    <?php echo $seminar_data['address2']; ?>
                                <?php endif; ?>
                                <br />
                                <?php echo $seminar_data['city']; ?>, <?php echo $seminar_data['state']; ?> <?php echo $seminar_data['zip']; ?>
                            </div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Phone</div>
                            <div class="right"><?php echo $seminar_data['phone']; ?></div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Date(s):</div>
                            <div class="right">
                                <?php echo date('n/j/Y', strtotime($seminar_data['start_date'])); ?>
                                <?php if ($seminar_data['start_date']!=$seminar_data['end_date']): ?>
                                    - <?php echo date('n/j/Y', strtotime($seminar_data['end_date'])); ?>
                                <?php endif; ?>
                            </div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Time:</div>
                            <div class="right"><?php echo $seminar_data['start_time'];?> - <?php echo $seminar_data['end_time']; ?></div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Registrant Price</div>
                            <div class="right">
                                <?php if ($registrant_price < $seminar_data['registrant_price']): ?>
                                    <span class="old-price">$<?php echo $seminar_data['registrant_price']; ?></span> <span class="new-price">$<?php echo $registrant_price; ?></span>
                                <?php else: ?>
                                    $<?php echo $seminar_data['registrant_price']; ?>
                                <?php endif; ?>
                            </div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Description</div>
                            <div class="right"><?php echo $seminar_data['description']; ?></div>
                            <div class="srm-clear"></div>
                        </div>
                        <div class="form-row">
                            <div class="left">Seminar Spots Left</div>
                            <div class="right"><?php echo $spots_left; ?></div>
                            <div class="srm-clear"></div>
                        </div>
                    </div>
                    <div class="two-col-right">
                        <?php if (!empty($seminar_data['image'])): ?>
                            <img src="<?php echo SRM_IMAGE_PATH_FRONTEND; ?>/seminars/<?php echo $seminar_data['image']; ?>" alt="" class="srm-featured-image" />
                        <?php endif; ?>
                    </div>
                </fieldset>
                <fieldset class="two-col-left">
                    <legend>Attendee Information</legend>
                    <div class="form-row">
                        <div class="left">Name <span class="clarification">(first, middle initial, last)</span>:</div>
                        <div class="right">
                            <input type="text" name="fname" id="srm-fname" value="<?php if (isset($_POST['fname'])): echo $_POST['fname']; endif; ?>" class="med <?php if ($registration_fields['fname']==1): ?>required<?php endif; ?>" />&nbsp;
                            <input type="text" name="mname" id="srm-mname" value="<?php if (isset($_POST['mname'])): echo $_POST['mname']; endif; ?>" class="sm <?php if ($registration_fields['mname']==1): ?>required<?php endif; ?>" />&nbsp;
                            <input type="text" name="lname" id="srm-lname" value="<?php if (isset($_POST['lname'])): echo $_POST['lname']; endif; ?>" class="med <?php if ($registration_fields['lname']==1): ?>required<?php endif; ?>" />
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Phone:</div>
                        <div class="right">
                            <input type="text" name="phone" id="srm-phone" value="<?php if (isset($_POST['phone'])): echo $_POST['phone']; endif; ?>" class="<?php if ($registration_fields['phone']==1): ?>required<?php endif; ?> phone" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Fax:</div>
                        <div class="right">
                            <input type="text" name="fax" id="srm-fax" value="<?php if (isset($_POST['fax'])): echo $_POST['fax']; endif; ?>" class="<?php if ($registration_fields['fax']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Email:</div>
                        <div class="right">
                            <input type="text" name="email" id="srm-email" value="<?php if (isset($_POST['email'])): echo $_POST['email']; endif; ?>" class="<?php if ($registration_fields['email']==1): ?>required<?php endif; ?> email" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="section-separation">
                        <h3>Additional Attendees</h3>
                        <div class="warning">Note: This area is for additional attendees only. If you have no additional attendees, you may skip this section.</div>
                        <div class="form-row">
                            <div class="left">
                                Additional Registrants:
                            </div>
                            <div class="right">
                                <select name="additional_registrants" id="srm-additional_registrants" onchange="handle_additional_registrants('registrants', this.value); srm_total(<?php echo $registrant_price; ?>, jQuery('#srm-additional_registrants').val(), <?php echo $registrant_price; ?>, jQuery('#srm-additional_registrants').val());">
                                    <option value="0">0</option>
                                    <?php for ($i=1; $i<=SRM_MAX_ADDITIONAL_REGISTRANTS; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php if (count($_POST['additional_registrants_entry'])==$i): ?>selected<?php endif; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="srm-clear"></div>
                        </div>
                        <div id="srm-additional-registrants-container">
                            <?php if (isset($_POST['additional_registrants_entry'])): ?>
                                <h4>Additional Registrants</h4>
                                <?php $i=0; $registrant_type='registrants'; ?>
                                <?php foreach($_POST['additional_registrants_entry'] as $additional_registrant): ?>
                                    <div class="srm-additional-registrant">
                                        <div class="srm-clear"><strong><?php echo $i+1; ?></strong></div>
                                        <div class="form-row">
                                            <div class="left">Name:</div>
                                            <div class="right"><input type="text" name="additional_<?php echo $registrant_type; ?>_entry[<?php echo $i; ?>][name]" value="<?php echo $additional_registrant['name']; ?>" /></div>
                                            <div class="srm-clear"></div>
                                        </div>
                                        <div class="form-row">
                                            <div class="left">Phone:</div>
                                            <div class="right"><input type="text" name="additional_<?php echo $registrant_type; ?>_entry[<?php echo $i; ?>][phone]" value="<?php echo $additional_registrant['phone']; ?>" /></div><div class="srm-clear"></div>
                                        </div>
                                        <div class="form-row">
                                            <div class="left">Fax:</div>
                                            <div class="right"><input type="text" name="additional_<?php echo $registrant_type; ?>_entry[<?php echo $i; ?>][fax]" value="<?php echo $additional_registrant['fax']; ?>" /></div><div class="srm-clear"></div>
                                        </div>
                                        <div class="form-row">
                                            <div class="left">Email:</div>
                                            <div class="right"><input type="text" name="additional_<?php echo $registrant_type; ?>_entry[<?php echo $i; ?>][email]" value="<?php echo $additional_registrant['email']; ?>" /></div><div class="srm-clear"></div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="two-col-right">
                    <legend>Billing Information</legend>
                    <div class="form-row">
                        <div class="left">Billing Name:</div>
                        <div class="right">
                            <input type="text" name="billing_name" id="srm-billing_name" value="<?php if (isset($_POST['billing_name'])): echo $_POST['billing_name']; endif; ?>" class="<?php if ($registration_fields['billing_name']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Address:</div>
                        <div class="right">
                            <input type="text" name="address1" id="srm-address1" value="<?php if (isset($_POST['address1'])): echo $_POST['address1']; endif; ?>" class="<?php if ($registration_fields['address1']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Address (line 2):</div>
                        <div class="right">
                            <input type="text" name="address2" id="srm-address2" value="<?php if (isset($_POST['address2'])): echo $_POST['address2']; endif; ?>" class="<?php if ($registration_fields['address2']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">City:</div>
                        <div class="right">
                            <input type="text" name="city" id="srm-city" value="<?php if (isset($_POST['city'])): echo $_POST['city']; endif; ?>" class="<?php if ($registration_fields['city']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">State:</div>
                        <div class="right">
                            <select name="state" id="srm-state">
                                <?php foreach($us_states as $abbrev=>$state): ?>
                                    <option value="<?php echo $abbrev; ?>" <?php if (isset($_POST['state']) && $_POST['state']==$abbrev): ?>selected<?php endif; ?>><?php echo $state; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Zip:</div>
                        <div class="right">
                            <input type="text" name="zip" id="srm-zip" value="<?php if (isset($_POST['zip'])): echo $_POST['zip']; endif; ?>" class="<?php if ($registration_fields['zip']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Card #:</div>
                        <div class="right">
                            <input type="text" name="card_num" id="srm-card_num" value="<?php if (isset($_POST['card_num'])): echo $_POST['card_num']; endif; ?>" class="<?php if ($registration_fields['card_num']==1): ?>required<?php endif; ?>" />&nbsp;
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Card Type:</div>
                        <div class="right">
                            <select name="card_type" id="srm-card_type">
                                <option value="AmEx" <?php if ($_POST['card_type']=='AmEx'): ?>selected<?php endif; ?>>American Express</option>
                                <option value="Discover" <?php if ($_POST['card_type']=='Discover'): ?>selected<?php endif; ?>>Discover</option>
                                <option value="Mastercard" <?php if ($_POST['card_type']=='Mastercard'): ?>selected<?php endif; ?>>Mastercard</option>
                                <option value="Visa" <?php if ($_POST['card_type']=='Visa'): ?>selected<?php endif; ?>>Visa</option>
                            </select>
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Exp. Date:</div>
                        <div class="right">
                            <select name="card_exp_month" id="srm-card_exp_month">
                                <?php for ($i=1; $i<=12; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if ($_POST['card_exp_month']==$i): ?>selected<?php endif; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select> / 
                            <select name="card_exp_year" id="srm-card-exp-year">
                                <?php for ($i=date('Y'); $i<=date('Y')+10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if ($_POST['card_exp_year']==$i): ?>selected<?php endif; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">Total Fee:</div>
                        <div class="right">
                            $<span id="srm-total"><?php echo $registrant_price; ?></span>
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <input type="checkbox" name="agreement" id="srm-agreement" class="required" value="1" /> I agree to the <a href="<?php bloginfo('url');?>/terms-of-registration/" target="_blank">‎terms of registration</a>.
                    <div id="srm-agreement-error" class="srm-error"></div>
                    <div class="form-row">
                        <div class="right"><input type="submit" name="register" id="srm-register" value="Register" /></div>
                    </div>
                </fieldset>
                <input type="hidden" name="coupon_code" value="<?php if (isset($_POST['coupon_code'])): echo $_POST['coupon_code']; endif; ?>" />
                <input type="hidden" name="seminar_id" value="<?php echo $seminar_data['id']; ?>" />
                <input type="hidden" name="submit_registration" value="true" />
            </form>
        </div>
<?php
    else:
?>
        <div class="srm-seminars">
            <div class="srm-register srm-form">
                <form action="" method="post">
                    <div class="form-row">
                        <div class="left">
                            Available Seminars
                        </div>
                        <div class="right">
                            <select name="seminar_id" id="srm-seminar_id">
                                <?php foreach($seminars_list as $seminar): ?>
                                    <option value="<?php echo $seminar['id']; ?>"><?php echo stripslashes($seminar['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">
                            Promo Code
                        </div>
                        <div class="right">
                            <input type="text" name="coupon_code" id="srm-coupon_code" maxlength="25" />
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                    <div class="form-row">
                        <div class="left">
                            <input type="submit" name="submit" value="Register Now" />
                        </div>
                        <div class="right">
                        </div>
                        <div class="srm-clear"></div>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                var num_doctors=jQuery('#srm-additional_doctors').val();
                var num_staff=jQuery('#srm-additional_staff').val();
                srm_total(<?php echo $doctor_price; ?>, num_doctors, <?php echo $staff_price; ?>, staff_num);
            });
        </script>
<?php
    endif;
?>