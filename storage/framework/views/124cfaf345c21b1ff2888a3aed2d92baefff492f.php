<?php $__env->startSection('content'); ?>
	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_NOTIFICATIONS], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<?php echo Former::open()
			->addClass('warn-on-exit')
			->rules([
				'slack_webhook_url' => 'url',
			]); ?>

	<?php echo e(Former::populate($account)); ?>

	<?php echo e(Former::populateField('slack_webhook_url', auth()->user()->slack_webhook_url)); ?>


	<?php echo $__env->make('accounts.partials.notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Slack</h3>
      </div>
        <div class="panel-body">

			<?php echo Former::text('slack_webhook_url')
					->label('webhook_url')
			 		->help(trans('texts.slack_webhook_help', ['link' => link_to('https://my.slack.com/services/new/incoming-webhook/', trans('texts.slack_incoming_webhooks'), ['target' => '_blank'])])); ?>


		</div>
    </div>

	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo trans('texts.google_analytics'); ?></h3>
      </div>
        <div class="panel-body">

			<?php echo Former::text('analytics_key')
			 		->help(trans('texts.analytics_key_help', ['link' => link_to('https://support.google.com/analytics/answer/1037249?hl=en', 'Google Analytics Ecommerce', ['target' => '_blank'])])); ?>


		</div>
    </div>

    <center class="buttons">
        <?php echo Button::success(trans('texts.save'))
                ->submit()->large()
                ->appendIcon(Icon::create('floppy-disk')); ?>

	</center>

	<?php echo Former::close(); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>