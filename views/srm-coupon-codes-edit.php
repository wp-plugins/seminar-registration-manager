<div class="srm-seminars">
    <div class="srm-header">
        <?php if (!$is_edit_mode): ?>
            <h1>Add a Coupon Code</h1>
        <?php else: ?>
            <h1>Edit Coupon Code: <i><?php echo $coupon_code_data['coupon_name']; ?></i></h1>
        <?php endif; ?>
        <div class="srm-back-link"><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=srm-coupon-codes&q=<?php echo rawurlencode($q); ?>&page_num=<?php echo $page_num; ?>">Back to All Coupon Codes</a></div>
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
        <form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?><?php if ($is_edit_mode): ?>&id=<?php echo $_GET['id']; ?><?php endif; ?>" method="post" enctype="multipart/form-data" id="add-edit-coupon-form" name="add-edit-coupon-form" onsubmit="return srm_validate_form('add-edit-coupon-form');">
            <fieldset>
                <legend>Coupon Code Information</legend>
                <div class="form-row">
                    <div class="left">Coupon Name:</div>
                    <div class="right"><input type="text" name="coupon_name" id="srm-coupon_name" value="<?php echo $coupon_code_data['coupon_name']; ?>" class="<?php if ($coupon_code_fields['coupon_name']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Code:</div>
                    <div class="right"><input type="text" name="coupon_code" id="srm-coupon_code" value="<?php echo $coupon_code_data['coupon_code']; ?>" class="<?php if ($coupon_code_fields['coupon_code']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Registrant Price:</div>
                    <div class="right">$<input type="text" name="registrant_price" id="srm-registrant_price" value="<?php echo $coupon_code_data['registrant_price']; ?>" class="<?php if ($coupon_code_fields['registrant_price']==1): ?>required<?php endif; ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Active:</div>
                    <div class="right">
                        <select name="active" id="srm-active">
                            <option value="1" <?php if ($coupon_code_data['active']==1): ?>selected="selected"<?php endif; ?>>Yes</option>
                            <option value="0" <?php if ($coupon_code_data['active']==0): ?>selected="selected"<?php endif; ?>>No</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                    if ($is_edit_mode):
                ?>
                        <div class="center"><input type="submit" name="submit" value="Edit Coupon Code" /></div>
                <?php
                    else:
                ?>
                        <div class="center"><input type="submit" name="submit" value="Create Coupon Code" /></div>
                <?php
                    endif;
                ?>
            </fieldset>
            <input type="hidden" name="add_edit_coupon_code" value="1" />
            <?php 
                if ($is_edit_mode):
            ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <?php 
                endif;
            ?>
        </form>
    </div>
</div>