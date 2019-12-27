<li style="margin-top: 4px; margin-bottom: 4px; min-width: 220px; cursor: pointer">
    <?php if(Utils::isAdmin()): ?>
        <?php if(isset($user_id) && $user_id != Auth::user()->id): ?>
            <a href="<?php echo e(URL::to("/switch_account/{$user_id}")); ?>">
        <?php else: ?> 
            <a href="<?php echo e(URL::to("/settings/company_details")); ?>">
        <?php endif; ?>
    <?php else: ?>
        <a href="<?php echo e(URL::to("/settings/user_details")); ?>">
    <?php endif; ?>

        <?php if(!empty($logo_url)): ?>
            <div class="pull-left" style="height: 40px; margin-right: 16px;">
                <img style="width: 40px; margin-top:6px" src="<?php echo e(asset($logo_url)); ?>"/>
            </div>
        <?php else: ?>
            <div class="pull-left" style="width: 40px; min-height: 40px; margin-right: 16px">&nbsp;</div>
        <?php endif; ?>

        <?php if(isset($selected) && $selected): ?>
            <b>
        <?php endif; ?>

        <div class="account" style="padding-right:90px"><?php echo e($account_name); ?></div>
        <div class="user" style="padding-right:90px"><?php echo e($user_name); ?></div>

        <?php if(isset($selected) && $selected): ?>            
            </b>
        <?php endif; ?>
    </a>

</li>