<div class="srm-seminars">
    <div class="srm-header">
        <h1>Registration Info for <?php echo $registrant_data['fname'].' '.$registrant_data['lname']; ?></h1>
        <div class="srm-back-link"><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?>&q=<?php echo $_GET['q'];?>&page_num=<?php echo $_GET['page_num']; ?>">Back to Registrants List</a></div>
    </div>
    <div class="srm-form">
        <fieldset>
            <legend>Registrant Information</legend>
            <div class="form-row">
                <div class="left">Name: </div>
                <div class="right"><?php echo $registrant_data['fname'].' '.$registrant_data['mname'].' '.$registrant_data['lname'];?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Phone: </div>
                <div class="right">
                    <?php echo $registrant_data['phone']; ?>
                    <?php if (!empty($registrant_data['fax'])): ?>
                        <br />
                        <?php echo $registrant_data['fax']; ?> (fax)
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Email: </div>
                <div class="right"><?php echo $registrant_data['email']; ?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Seminar: </div>
                <div class="right">
                    <?php 
                        $seminar_data=SrmSeminars::get_seminar($registrant_data['seminar_id']);
                    ?>
                    <?php echo $seminar_data['title']; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Seminar Dates: </div>
                <div class="right">
                    <?php echo date('n/j/Y', strtotime($seminar_data['start_date']));?>
                    <?php if ($seminar_data['end_date']!=$seminar_data['start_date']): ?>
                        &mdash; <?php echo date('n/j/Y', strtotime($seminar_data['end_date'])); ?>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Billing Info</legend>
            <div class="form-row">
                <div class="left">Billing Address: </div>
                <div class="right">
                    <?php echo $registrant_data['address1']; ?>
                    <?php if (!empty($registrant_data['address2'])): ?>
                        <br />
                        <?php echo $registrant_data['address2']; ?>
                        <br />
                        <?php echo $registrant_data['city']; ?>, <?php echo $registrant_data['state']; ?> <?php echo $registrant_data['zip']; ?>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Credit Card Type: </div>
                <div class="right"><?php echo $registrant_data['card_type']; ?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Credit Card Number: </div>
                <div class="right"><?php echo substr(base64_decode($registrant_data['card_num']), -4, 4); ?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Credit Card Exp.: </div>
                <div class="right"><?php echo $registrant_data['card_exp_month'].'/'.$registrant_data['card_exp_year']; ?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Paid?: </div>
                <div class="right">
                    <?php 
                        switch($registrant_data['paid']):
                            case 0:
                                echo 'N';
                            break;
                            case 1:
                                echo 'Y';
                            break;
                        endswitch;
                    ?>
                </div>
                <div class="clear"></div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Additional Registrants Information</legend>
            <div class="form-row">
                <div class="left">Additional Registrants: </div>
                <div class="right"><?php echo $additional_registrants['num_registrants']; ?></div>
                <div class="clear"></div>
            </div>
            <div class="form-row">
                <div class="left">Names: </div>
                <div class="right">
                    <?php foreach($additional_registrants['registrants_info'] as $additional_registrant): ?>
                        <p>
                            <?php echo $additional_registrant['fname'].' '.$additional_registrant['mname'].' '.$additional_registrant['lname'].' ('.$additional_registrant['type_name'].')'; ?>
                            <br />
                            <?php echo $additional_registrant['phone']; ?>
                            <?php if (!empty($additional_registrant['fax'])): ?>
                                <br />
                                <?php echo $additional_registrant['fax']; ?> (fax)
                            <?php endif; ?>
                            <br />
                            <?php echo $additional_registrant['email']; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
                <div class="clear"></div>
            </div>
        </fieldset>
    </div>
</div>