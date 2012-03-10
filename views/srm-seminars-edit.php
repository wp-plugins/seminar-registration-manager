<div class="srm-seminars">
    <div class="srm-header">
        <?php if (!$is_edit_mode): ?>
            <h1>Add a Seminar</h1>
        <?php else: ?>
            <h1>Edit Seminar: <i><?php echo $seminar_data['title']; ?></i></h1>
        <?php endif; ?>
        <div class="srm-back-link"><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=seminar-registration-manager">Back to All Seminars</a></div>
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
        <form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=<?php echo $_GET['page']; ?><?php if ($is_edit_mode): ?>&id=<?php echo $_GET['id']; ?><?php endif; ?>" method="post" enctype="multipart/form-data" id="add-edit-seminar-form" onsubmit="return srm_validate_form(this.id);">
            <fieldset>
                <legend>Seminar Information</legend>
                <div class="form-row">
                    <div class="left">Title:</div>
                    <div class="right"><input type="text" name="title" id="srm-title" value="<?php echo $seminar_data['title']; ?>" class="<?php if ($seminar_fields['title']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Location:</div>
                    <div class="right"><input type="text" name="location" id="srm-location" value="<?php echo $seminar_data['location']; ?>" class="<?php if ($seminar_fields['location']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Address (line 1):</div>
                    <div class="right"><input type="text" name="address1" id="srm-address1" value="<?php echo $seminar_data['address1']; ?>" class="<?php if ($seminar_fields['address1']==1): ?>required<?php endif; ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Address (line 2):</div>
                    <div class="right"><input type="text" name="address2" id="srm-address2" value="<?php echo $seminar_data['address2']; ?>" class="<?php if ($seminar_fields['address2']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">City:</div>
                    <div class="right"><input type="text" name="city" id="srm-city" value="<?php echo $seminar_data['city']; ?>" class="<?php if ($seminar_fields['city']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">State:</div>
                    <div class="right">
                    	<select name="state" id="srm-state">
							<?php foreach($us_states as $abbrev=>$state): ?>
                                <option value="<?php echo $abbrev; ?>" <?php if ($seminar_data['state']==$abbrev): ?>selected<?php endif; ?>><?php echo $state; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Zip:</div>
                    <div class="right"><input type="text" name="zip" id="srm-zip" value="<?php echo $seminar_data['zip']; ?>" class="<?php if ($seminar_fields['zip']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Phone:</div>
                    <div class="right"><input type="text" name="phone" id="srm-phone" value="<?php echo $seminar_data['phone']; ?>" class="<?php if ($seminar_fields['phone']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Seminar Photo</legend>
                <div class="form-row">
                    <div class="left">Image:</div>
                    <div class="right"><input type="file" name="image" id="srm-image" /> <?php if (!empty($seminar_data['image'])): ?><img src="<?php echo SRM_IMAGE_PATH_FRONTEND; ?>/seminars/<?php echo $seminar_data['image']; ?>" alt="Photo" width="40" class="preview-img" /><?php endif; ?></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Additional Information</legend>
                <div class="form-row">
                    <div class="left">Registrant Price:</div>
                    <div class="right">$<input type="text" name="registrant_price" id="srm-registrant_price" value="<?php echo $seminar_data['registrant_price']; ?>" class="<?php if ($seminar_fields['registrant_price']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Room Rate:</div>
                    <div class="right">$<input type="text" name="room_rate" id="srm-room_rate" value="<?php echo $seminar_data['room_rate']; ?>" class="<?php if ($seminar_fields['room_rate']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Description:</div>
                    <div class="right"><textarea name="description" id="srm-description" class="<?php if ($seminar_fields['description']==1): ?>required<?php endif; ?>"><?php echo $seminar_data['description'];?></textarea></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Start Date:</div>
                    <div class="right"><input type="text" name="start_date" id="srm-start_date" value="<?php echo $seminar_data['start_date']; ?>" class="srm-date <?php if ($seminar_fields['start_date']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">End Date:</div>
                    <div class="right"><input type="text" name="end_date" id="srm-end_date" value="<?php echo $seminar_data['end_date']; ?>" class="srm-date <?php if ($seminar_fields['end_date']==1): ?>required<?php endif; ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Start Time:</div>
                    <div class="right"><input type="text" name="start_time" id="srm-start_time" value="<?php echo $seminar_data['start_time']; ?>"  class="<?php if ($seminar_fields['start_time']==1): ?>required<?php endif; ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">End Time:</div>
                    <div class="right"><input type="text" name="end_time" id="srm-end_time" value="<?php echo $seminar_data['end_time']; ?>"  class="<?php if ($seminar_fields['end_time']==1): ?>required<?php endif; ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="form-row">
                    <div class="left">Maximum Attendees</div>
                    <div class="right"><input type="text" name="max_attendees" id="srm-max_attendees" value="<?php echo $seminar_data['max_attendees']; ?>"  class="<?php if ($seminar_fields['max_attendees']==1): ?>required<?php endif; ?>"/></div>
                    <div class="clear"></div>
                </div>
                <?php
                    if ($is_edit_mode):
                ?>
                        <div class="center"><input type="submit" name="submit" value="Edit Seminar" /></div>
                <?php
                    else:
                ?>
                        <div class="center"><input type="submit" name="submit" value="Create Seminar" /></div>
                <?php
                    endif;
                ?>
            </fieldset>
            <input type="hidden" name="add_edit_seminar" value="1" />
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