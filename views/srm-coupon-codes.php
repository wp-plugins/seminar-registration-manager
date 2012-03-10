<div class="srm-seminars">
    <div class="srm-header">
        <h1>Manage Coupon Codes</h1>
        <?php echo SrmCommon::render_filter(true, false); ?>
        <div class="srm-back-link"><a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=srm-coupon-codes-add-edit">Create New Coupon Code</a></div>
    </div>
    <?php
        if ($coupon_codes_count==0):
    ?>
            <div class="error">There are no coupons for the given view. Perhaps changing the filter options above will yield more results.</div>
    <?php     
        else:
    ?>
            <table class="srm-grid">
                <tr>
                    <th>Coupon Name</th>
                    <th>Code</th>
                    <th>Registrant Price</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $i=0; 
                    foreach($coupon_codes_results as $coupon_codes_result):
                        $is_even_row=false;
                        if ($i%2==0):
                            $is_even_row=true;
                        else:
                            $is_even_row=false;
                        endif;
                        
                        //stripslashes
                        foreach($coupon_codes_result as $key=>$value):
                            $coupon_codes_result[$key]=stripslashes($value);
                        endforeach;
                ?>
                        <tr>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo $coupon_codes_result['coupon_name']; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo $coupon_codes_result['coupon_code']; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo $coupon_codes_result['registrant_price']; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <input type="checkbox" name="active" id="active" value="1" onclick="var this_value=1; if (!this.checked){ this_value=0; };update_coupon_active(<?php echo $coupon_codes_result['id'];?>, this_value, '<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?>&q=<?php echo rawurlencode($q); ?>&page_num=<?php echo $page_num; ?>')" <?php if ($coupon_codes_result['active']==1): ?>checked<?php endif; ?> />
                            </td>
                            <td class="actions <?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=srm-coupon-codes-add-edit&id=<?php echo $coupon_codes_result['id'];?>&q=<?php echo rawurlencode($q); ?>&page_num=<?php echo $page_num; ?>" title="Edit <?php echo $coupon_codes_result['coupon_name']; ?>"><img src="<?php echo SRM_FRONT_END_PATH; ?>/images/edit.gif" alt="Edit <?php echo $coupon_codes_result['coupon_name']; ?>" /></a>
                                <a href="#" title="Delete <?php echo $coupon_codes_result['coupon_name']; ?>" onclick="delete_coupon_code('<?php echo get_bloginfo('url').'/wp-admin/admin.php?page='.$_GET['page'].'&q='.rawurlencode($q).'&page_num='.$page_num.'&delete_coupon='.$coupon_codes_result['id']; ?>')"><img src="<?php echo SRM_FRONT_END_PATH; ?>/images/delete.gif" alt="Delete <?php echo $seminar_result['title']; ?>" /></a>
                            </td>
                        </tr>
                <?php
                        $i++; 
                    endforeach;
                ?>
            </table>
            <?php 
                echo SrmCommon::render_pagination($coupon_codes_count, $page_num);
            ?>
    <?php 
        endif;
    ?>
</div>