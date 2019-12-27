<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php echo Former::open_for_files()->addClass('warn-on-exit'); ?>

    <?php echo e(Former::populate($account)); ?>

    <?php echo e(Former::populateField('military_time', intval($account->military_time))); ?>

    <?php echo e(Former::populateField('show_currency_code', intval($account->show_currency_code))); ?>


    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_LOCALIZATION], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo trans('texts.localization'); ?></h3>
            </div>
                <div class="panel-body form-padding-right">

                <?php echo Former::select('currency_id')
                        ->fromQuery($currencies, 'name', 'id')
                        ->onchange('updateCurrencyCodeRadio()'); ?>

                <?php echo Former::radios('show_currency_code')->radios([
                        trans('texts.currency_symbol') . ': <span id="currency_symbol_example"/>' => array('name' => 'show_currency_code', 'value' => 0),
                        trans('texts.currency_code') . ': <span id="currency_code_example"/>' => array('name' => 'show_currency_code', 'value' => 1),
                    ])->inline()
                        ->label('&nbsp;')
                        ->addGroupClass('currrency_radio'); ?>

                <br/>

                <?php echo Former::select('language_id')->addOption('','')
                    ->fromQuery($languages, 'name', 'id')
                    ->help(trans('texts.translate_app', ['link' => link_to(TRANSIFEX_URL, 'Transifex.com', ['target' => '_blank'])])); ?>

                <br/>&nbsp;<br/>

                <?php echo Former::select('timezone_id')->addOption('','')
                    ->fromQuery($timezones, 'location', 'id'); ?>

                <?php echo Former::select('date_format_id')->addOption('','')
                    ->fromQuery($dateFormats); ?>

                <?php echo Former::select('datetime_format_id')->addOption('','')
                    ->fromQuery($datetimeFormats); ?>

                <?php echo Former::checkbox('military_time')->text(trans('texts.enable'))->value(1); ?>


                <br/>&nbsp;<br/>

                <?php echo Former::select('start_of_week')->addOption('','')
                    ->fromQuery($weekdays)
                    ->help('start_of_week_help'); ?>


                <?php echo Former::select('financial_year_start')
                        ->addOption('','')
                        ->options($months)
                        ->help('financial_year_start_help'); ?>



                </div>
            </div>
        </div>
    </div>

    <center>
        <?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

    </center>

    <?php echo Former::close(); ?>


    <script type="text/javascript">

        function updateCurrencyCodeRadio() {
            var currencyId = $('#currency_id').val();
            var currency = currencyMap[currencyId];
            var symbolExample = '';
            var codeExample = '';

            if ( ! currency || ! currency.symbol) {
                $('.currrency_radio').hide();
            } else {
                symbolExample = formatMoney(1000, currencyId, <?php echo e(Auth::user()->account->country_id ?: DEFAULT_COUNTRY); ?>, '<?php echo e(CURRENCY_DECORATOR_SYMBOL); ?>');
                codeExample = formatMoney(1000, currencyId, <?php echo e(Auth::user()->account->country_id ?: DEFAULT_COUNTRY); ?>, '<?php echo e(CURRENCY_DECORATOR_CODE); ?>');
                $('.currrency_radio').show();
            }

            $('#currency_symbol_example').text(symbolExample);
            $('#currency_code_example').text(codeExample);
        }

    </script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('onReady'); ?>
    $('#currency_id').focus();
    updateCurrencyCodeRadio();
<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>