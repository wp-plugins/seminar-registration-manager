<div class="srm-seminars">
    <div class="srm-header">
        <h1>Manage Registrants</h1>
        <?php echo SrmCommon::render_filter(true, false); ?>
    </div>
    <?php
        if ($registrants_count==0):
    ?>
            <div class="error">There are no registrants for the given view. Perhaps changing the filter options above will yield more results.</div>
    <?php     
        else:
    ?>
            <table class="srm-grid">
                <tr>
                    <th>Registrant Name</th>
                    <th>Seminar</th>
                    <th>Address</th>
                    <th>Paid?</th>
                    <th>Other Registrants</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $i=0; 
                    foreach($registrants_results as $registrant_result):
                        $is_even_row=false;
                        if ($i%2==0):
                            $is_even_row=true;
                        else:
                            $is_even_row=false;
                        endif;
                        
                        //stripslashes
                        foreach($registrant_result as $key=>$value):
                            $registrant_result[$key]=stripslashes($value);
                        endforeach;
                ?>
                        <tr>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page'];?>&q=<?php echo $_GET['q']; ?>&seminar_id=<?php echo $_GET['seminar_id']; ?>&registrant_id=<?php echo $registrant_result['id']; ?>&page_num=<?php echo $_GET['page_num']; ?>"><?php echo $registrant_result['fname'].' '.$registrant_result['mname'].' '.$registrant_result['lname']; ?></a>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php 
                                    $seminar=SrmSeminars::get_seminar($registrant_result['seminar_id']);
                                    echo $seminar['title'];
                                ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo $registrant_result['billing_address1']; ?>
                                <?php if (!empty($registrant_result['billing_address2'])): ?>
                                    <br />
                                    <?php echo $registrant_result['billing_address2']; ?>
                                <?php endif; ?>
                                <br />
                                <?php echo $registrant_result['billing_city']; ?>, <?php echo $registrant_result['billing_state']; ?> <?php echo $registrant_result['billing_zip']; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <input type="checkbox" name="paid" id="paid" value="1" onclick="var this_value=1; if (!this.checked){ this_value=0; };update_paid_status(<?php echo $registrant_result['id'];?>, this_value, '<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?>&q=<?php echo $_GET['q']; ?>&page_num=<?php echo $_GET['page_num']; ?>')" <?php if ($registrant_result['paid']==1): ?>checked<?php endif; ?> />
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php $additional_registrants_info=SrmRegistrants::get_additional_registrants($registrant_result['id']); ?>
                                Registrants: <?php echo $additional_registrants_info['num_registrants']; ?>
                                <br />
                                <?php 
                                    foreach($additional_registrants_info['registrants_info'] as $registrant):
                                ?>
                                        <?php echo $registrant['fname']; ?> <?php echo $registrant['mname']; ?> <?php echo $registrant['lname']; ?> (<?php echo $registrant['type_name']; ?>) 
                                        <br /> 
                                <?php 
                                    endforeach;
                                ?>
                            </td>
                            <td class="actions <?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <a href="#" title="Delete <?php echo $registrant_result['fname']; ?>" onclick="delete_registrant('<?php echo get_bloginfo('url').'/wp-admin/admin.php?page='.$_GET['page'].'&q='.$q.'&page_num='.$page_num.'&delete_registrant='.$registrant_result['id']; ?>')"><img src="<?php echo SRM_FRONT_END_PATH; ?>/images/delete.gif" alt="Delete <?php echo $seminar_result['title']; ?>" /></a>
                            </td>
                        </tr>
                <?php
                        $i++; 
                    endforeach;
                ?>
            </table>
            <?php 
                echo SrmCommon::render_pagination($registrants_count, $page_num);
            ?>
    <?php 
        endif;
    ?>
</div>