<div class="srm-seminars">
    <div class="srm-header">
        <h1>Manage Seminars</h1>
        <?php echo SrmCommon::render_filter(true, true); ?>
        <div class="srm-back-link"><a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=srm-seminars-add-edit">Create New Seminar</a></div>
    </div>
    <?php
        if ($seminars_count==0):
    ?>
            <div class="error">There are no seminars for the given view. Perhaps changing the filter options above will yield more results.</div>
    <?php     
        else:
    ?>
            <table class="srm-grid">
                <tr>
                    <th>Seminar</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Attendees</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $i=0; 
                    foreach($seminars_results as $seminar_result):
                        $is_even_row=false;
                        if ($i%2==0):
                            $is_even_row=true;
                        else:
                            $is_even_row=false;
                        endif;
                        
                        //stripslashes
                        foreach($seminar_result as $key=>$value):
                            $seminar_result[$key]=stripslashes($value);
                        endforeach;
                ?>
                        <tr>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo $seminar_result['title']; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <?php echo date('n/j/Y', strtotime($seminar_result['start_date']));?> <?php if ($seminar_result['end_date']!=$seminar_result['start_date']): ?>- <?php echo date('n/j/Y', strtotime($seminar_result['end_date'])); ?><?php endif; ?>
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <strong><?php echo $seminar_result['location']; ?></strong>
                                <br />
                                <?php echo $seminar_result['address1'];?>
                                <?php if (!empty($seminar_result['address2'])): ?>
                                    <br />
                                    <?php echo $seminar_result['address2']; ?>
                                <?php endif; ?>
                                <br />
                                <?php echo $seminar_result['city']; ?>, <?php echo $seminar_result['state']; ?> 
                            </td>
                            <td class="<?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=srm-registrants&seminar_id=<?php echo $seminar_result['id']; ?>"><?php echo SrmSeminars::get_seminar_attendees($seminar_result['id']);?>/<?php echo $seminar_result['max_attendees']; ?></a>
                            </td>
                            <td class="actions <?php if ($is_even_row): ?>even-row<?php else: ?>odd-row<?php endif; ?>">
                                <a href="<?php bloginfo('url');?>/wp-admin/admin.php?page=srm-seminars-add-edit&id=<?php echo $seminar_result['id'];?>" title="Edit <?php echo $seminar_result['title']; ?>"><img src="<?php echo SRM_FRONT_END_PATH; ?>/images/edit.gif" alt="Edit <?php echo $seminar_result['title']; ?>" /></a>
                                <a href="#" title="Delete <?php echo $seminar_result['title']; ?>" onclick="delete_seminar('<?php echo get_bloginfo('url').'/wp-admin/admin.php?page='.$_GET['page'].'&start_date='.$start_date.'&end_date='.$end_date.'&q='.$q.'&page_num='.$page_num.'&delete_seminar='.$seminar_result['id']; ?>')"><img src="<?php echo SRM_FRONT_END_PATH; ?>/images/delete.gif" alt="Delete <?php echo $seminar_result['title']; ?>" /></a>
                            </td>
                        </tr>
                <?php
                        $i++; 
                    endforeach;
                ?>
            </table>
            <?php 
                echo SrmCommon::render_pagination($seminars_count, $page_num);
            ?>
    <?php 
        endif;
    ?>
</div>