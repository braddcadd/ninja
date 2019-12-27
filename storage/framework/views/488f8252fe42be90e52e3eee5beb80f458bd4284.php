<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/select2.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/select2.css')); ?>" rel="stylesheet" type="text/css"/>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-7">
            <ol class="breadcrumb">
              <li><?php echo e(link_to('/clients', trans('texts.clients'))); ?></li>
              <li class='active'><?php echo e($client->getDisplayName()); ?></li> <?php echo $client->present()->statusLabel; ?>

            </ol>
        </div>
        <div class="col-md-5">
            <div class="pull-right">
                <?php echo Former::open('clients/bulk')->autocomplete('off')->addClass('mainForm'); ?>

                <div style="display:none">
                    <?php echo Former::text('action'); ?>

                    <?php echo Former::text('public_id')->value($client->public_id); ?>

                </div>

                <?php if($gatewayLink): ?>
                    <?php echo Button::normal(trans('texts.view_in_gateway', ['gateway'=>$gatewayName]))
                            ->asLinkTo($gatewayLink)
                            ->withAttributes(['target' => '_blank']); ?>

                <?php endif; ?>

                <?php if( ! $client->is_deleted): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $client)): ?>
                        <?php echo DropdownButton::normal(trans('texts.edit_client'))
                            ->withAttributes(['class'=>'normalDropDown'])
                            ->withContents([
                              ($client->trashed() ? false : ['label' => trans('texts.archive_client'), 'url' => "javascript:onArchiveClick()"]),
                              ['label' => trans('texts.delete_client'), 'url' => "javascript:onDeleteClick()"],
                              auth()->user()->is_admin ? \DropdownButton::DIVIDER : false,
                              auth()->user()->is_admin ? ['label' => trans('texts.purge_client'), 'url' => "javascript:onPurgeClick()"] : false,
                            ]
                          )->split(); ?>

                    <?php endif; ?>
                    <?php if( ! $client->trashed()): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', ENTITY_INVOICE)): ?>
                            <?php echo DropdownButton::primary(trans('texts.view_statement'))
                                    ->withAttributes(['class'=>'primaryDropDown'])
                                    ->withContents($actionLinks)->split(); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if($client->trashed()): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $client)): ?>
                        <?php if(auth()->user()->is_admin && $client->is_deleted): ?>
                            <?php echo Button::danger(trans('texts.purge_client'))
                                    ->appendIcon(Icon::create('warning-sign'))
                                    ->withAttributes(['onclick' => 'onPurgeClick()']); ?>

                        <?php endif; ?>
                        <?php echo Button::primary(trans('texts.restore_client'))
                                ->appendIcon(Icon::create('cloud-download'))
                                ->withAttributes(['onclick' => 'onRestoreClick()']); ?>

                    <?php endif; ?>
                <?php endif; ?>


              <?php echo Former::close(); ?>


            </div>
        </div>
    </div>

    <?php if($client->last_login > 0): ?>
    <h3 style="margin-top:0px"><small>
        <?php echo e(trans('texts.last_logged_in')); ?> <?php echo e(Utils::timestampToDateTimeString(strtotime($client->last_login))); ?>

    </small></h3>
    <?php endif; ?>

    <div class="panel panel-default">
    <div class="panel-body">
    <div class="row">

        <div class="col-md-3">
            <h3><?php echo e(trans('texts.details')); ?></h3>
            <?php if($client->id_number): ?>
                <p><i class="fa fa-id-number" style="width: 20px"></i><?php echo e(trans('texts.id_number').': '.$client->id_number); ?></p>
            <?php endif; ?>
            <?php if($client->vat_number): ?>
               <p><i class="fa fa-vat-number" style="width: 20px"></i><?php echo e(trans('texts.vat_number').': '.$client->vat_number); ?></p>
            <?php endif; ?>

            <?php if($client->account->customLabel('client1') && $client->custom_value1): ?>
                <?php echo e($client->account->present()->customLabel('client1') . ': '); ?> <?php echo nl2br(e($client->custom_value1)); ?><br/>
            <?php endif; ?>
            <?php if($client->account->customLabel('client2') && $client->custom_value2): ?>
                <?php echo e($client->account->present()->customLabel('client2') . ': '); ?> <?php echo nl2br(e($client->custom_value2)); ?><br/>
            <?php endif; ?>

            <?php if($client->work_phone): ?>
                <i class="fa fa-phone" style="width: 20px"></i><?php echo e($client->work_phone); ?>

            <?php endif; ?>

            <?php if(floatval($client->task_rate)): ?>
                <p><?php echo e(trans('texts.task_rate')); ?>: <?php echo e(Utils::roundSignificant($client->task_rate)); ?></p>
            <?php endif; ?>

            <p/>

            <?php if($client->public_notes): ?>
                <p><i><?php echo nl2br(e($client->public_notes)); ?></i></p>
            <?php endif; ?>

            <?php if($client->private_notes): ?>
                <p><i><?php echo nl2br(e($client->private_notes)); ?></i></p>
            <?php endif; ?>

            <?php if($client->industry || $client->size): ?>
                <?php if($client->industry): ?>
                    <?php echo e($client->industry->name); ?>

                <?php endif; ?>
                <?php if($client->industry && $client->size): ?>
                    |
                <?php endif; ?>
                <?php if($client->size): ?>
                    <?php echo e($client->size->name); ?><br/>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($client->website): ?>
               <p><?php echo Utils::formatWebsite($client->website); ?></p>
            <?php endif; ?>

            <?php if($client->language): ?>
                <p><i class="fa fa-language" style="width: 20px"></i><?php echo e($client->language->name); ?></p>
            <?php endif; ?>

            <p><?php echo e($client->present()->paymentTerms); ?></p>

            <div class="text-muted" style="padding-top:8px">
            <?php if($client->show_tasks_in_portal): ?>
                • <?php echo e(trans('texts.can_view_tasks')); ?><br/>
            <?php endif; ?>
            <?php if($client->account->hasReminders() && ! $client->send_reminders): ?>
                • <?php echo e(trans('texts.is_not_sent_reminders')); ?></br>
            <?php endif; ?>
            </div>
        </div>

        <div class="col-md-3">
            <h3><?php echo e(trans('texts.address')); ?></h3>

            <?php if($client->addressesMatch()): ?>
                <?php echo $client->present()->address(ADDRESS_BILLING); ?>

            <?php else: ?>
                <?php echo $client->present()->address(ADDRESS_BILLING, true); ?><br/>
                <?php echo $client->present()->address(ADDRESS_SHIPPING, true); ?>

            <?php endif; ?>

        </div>

        <div class="col-md-3">
            <h3><?php echo e(trans('texts.contacts')); ?></h3>
            <?php $__currentLoopData = $client->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($contact->first_name || $contact->last_name): ?>
                    <b><?php echo e($contact->first_name.' '.$contact->last_name); ?></b><br/>
                <?php endif; ?>
                <?php if($contact->email): ?>
                    <i class="fa fa-envelope" style="width: 20px"></i><?php echo HTML::mailto($contact->email, $contact->email); ?><br/>
                <?php endif; ?>
                <?php if($contact->phone): ?>
                    <i class="fa fa-phone" style="width: 20px"></i><?php echo e($contact->phone); ?><br/>
                <?php endif; ?>

                <?php if($client->account->customLabel('contact1') && $contact->custom_value1): ?>
                    <?php echo e($client->account->present()->customLabel('contact1') . ': ' . $contact->custom_value1); ?><br/>
                <?php endif; ?>
                <?php if($client->account->customLabel('contact2') && $contact->custom_value2): ?>
                    <?php echo e($client->account->present()->customLabel('contact2') . ': ' . $contact->custom_value2); ?><br/>
                <?php endif; ?>

                <?php if(Auth::user()->confirmed && $client->account->enable_client_portal): ?>
                    <i class="fa fa-dashboard" style="width: 20px"></i><a href="<?php echo e($contact->link); ?>"
                        onclick="window.open('<?php echo e($contact->link); ?>?silent=true', '_blank');return false;"><?php echo e(trans('texts.view_in_portal')); ?></a>                        
                    <?php if(config('services.postmark')): ?>
                        <div style="padding-top:10px">
                            <a href="#" class="btn btn-sm btn-primary" onclick="showEmailHistory('<?php echo e($contact->email); ?>')">
                                <?php echo e(trans('texts.email_history')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                    <br/>
                <?php endif; ?>
                <br/>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="col-md-3">
            <h3><?php echo e(trans('texts.standing')); ?>

            <table class="table" style="width:100%">
                <tr>
                    <td><small><?php echo e(trans('texts.paid_to_date')); ?></small></td>
                    <td style="text-align: right"><?php echo e(Utils::formatMoney($client->paid_to_date, $client->getCurrencyId())); ?></td>
                </tr>
                <tr>
                    <td><small><?php echo e(trans('texts.balance')); ?></small></td>
                    <td style="text-align: right"><?php echo e(Utils::formatMoney($client->balance, $client->getCurrencyId())); ?></td>
                </tr>
                <?php if($credit > 0): ?>
                <tr>
                    <td><small><?php echo e(trans('texts.credit')); ?></small></td>
                    <td style="text-align: right"><?php echo e(Utils::formatMoney($credit, $client->getCurrencyId())); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td><small>Usually Pays In</small></td>
                    <td style="text-align: right;"><?php echo e($client->present()->usuallyPaysIn()); ?></td>
                </tr>
            </table>
            </h3>
        </div>
    </div>
    </div>
    </div>

    <?php if($client->showMap()): ?>

        <iframe
          width="100%"
          height="200px"
          frameborder="0" style="border:0"
          src="https://www.google.com/maps/embed/v1/place?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>&q=<?php echo e("{$client->address1} {$client->address2} {$client->city} {$client->state} {$client->postal_code} " . ($client->country ? $client->country->getName() : '')); ?>" allowfullscreen>
        </iframe>

    <?php endif; ?>

    <ul class="nav nav-tabs nav-justified">
        <?php echo Form::tab_link('#activity', trans('texts.activity'), true); ?>

        <?php if($hasTasks): ?>
            <?php echo Form::tab_link('#tasks', trans('texts.tasks')); ?>

        <?php endif; ?>
        <?php if($hasExpenses): ?>
            <?php echo Form::tab_link('#expenses', trans('texts.expenses')); ?>

        <?php endif; ?>
        <?php if($hasQuotes): ?>
            <?php echo Form::tab_link('#quotes', trans('texts.quotes')); ?>

        <?php endif; ?>
        <?php if($hasRecurringInvoices): ?>
            <?php echo Form::tab_link('#recurring_invoices', trans('texts.recurring')); ?>

        <?php endif; ?>
        <?php echo Form::tab_link('#invoices', trans('texts.invoices')); ?>

        <?php echo Form::tab_link('#payments', trans('texts.payments')); ?>

        <?php if($account->isModuleEnabled(ENTITY_CREDIT)): ?>
            <?php echo Form::tab_link('#credits', trans('texts.credits')); ?>

        <?php endif; ?>
    </ul><br/>

    <div class="tab-content">

        <div class="tab-pane active" id="activity">
            <?php echo Datatable::table()
                ->addColumn(
                    trans('texts.date'),
                    trans('texts.message'),
                    trans('texts.balance'),
                    trans('texts.adjustment'))
                ->setUrl(url('api/activities/'. $client->public_id))
                ->setCustomValues('entityType', 'activity')
                ->setCustomValues('clientId', $client->public_id)
                ->setCustomValues('rightAlign', [2, 3])
                ->setOptions('sPaginationType', 'bootstrap')
                ->setOptions('bFilter', false)
                ->setOptions('aaSorting', [['0', 'desc']])
                ->render('datatable'); ?>

        </div>

    <?php if($hasTasks): ?>
        <div class="tab-pane" id="tasks">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_TASK,
                'datatable' => new \App\Ninja\Datatables\TaskDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/tasks/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endif; ?>

    <?php if($hasExpenses): ?>
        <div class="tab-pane" id="expenses">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_EXPENSE,
                'datatable' => new \App\Ninja\Datatables\ExpenseDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/client_expenses/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endif; ?>

    <?php if(Utils::hasFeature(FEATURE_QUOTES) && $hasQuotes): ?>
        <div class="tab-pane" id="quotes">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_QUOTE,
                'datatable' => new \App\Ninja\Datatables\InvoiceDatatable(true, true, ENTITY_QUOTE),
                'clientId' => $client->public_id,
                'url' => url('api/quotes/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endif; ?>

    <?php if($hasRecurringInvoices): ?>
        <div class="tab-pane" id="recurring_invoices">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_RECURRING_INVOICE,
                'datatable' => new \App\Ninja\Datatables\RecurringInvoiceDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/recurring_invoices/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endif; ?>

        <div class="tab-pane" id="invoices">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_INVOICE,
                'datatable' => new \App\Ninja\Datatables\InvoiceDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/invoices/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="tab-pane" id="payments">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_PAYMENT,
                'datatable' => new \App\Ninja\Datatables\PaymentDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/payments/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

    <?php if($account->isModuleEnabled(ENTITY_CREDIT)): ?>
        <div class="tab-pane" id="credits">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_CREDIT,
                'datatable' => new \App\Ninja\Datatables\CreditDatatable(true, true),
                'clientId' => $client->public_id,
                'url' => url('api/credits/' . $client->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    <?php endif; ?>

    </div>

    <div class="modal fade" id="emailHistoryModal" tabindex="-1" role="dialog" aria-labelledby="emailHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.email_history')); ?></h4>
                </div>

                <div class="container" style="width: 100%; padding-bottom: 0px !important">
                <div class="panel panel-default">
                <div class="panel-body">

                </div>
                </div>
                </div>

                <div class="modal-footer" id="signUpFooter" style="margin-top: 0px">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.close')); ?> </button>
                    <button type="button" class="btn btn-danger" onclick="onReactivateClick()" id="reactivateButton" style="display:none;"><?php echo e(trans('texts.reactivate')); ?> </button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

    var loadedTabs = {};

    $(function() {
        $('.normalDropDown:not(.dropdown-toggle)').click(function(event) {
            openUrlOnClick('<?php echo e(URL::to('clients/' . $client->public_id . '/edit')); ?>', event);
        });
        $('.primaryDropDown:not(.dropdown-toggle)').click(function(event) {
            openUrlOnClick('<?php echo e(URL::to('clients/statement/' . $client->public_id )); ?>', event);
        });

        // load datatable data when tab is shown and remember last tab selected
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          target = target.substring(1);
          if (isStorageSupported()) {
              localStorage.setItem('client_tab', target);
          }
          if (!loadedTabs.hasOwnProperty(target) && window['load_' + target]) {
            loadedTabs[target] = true;
            window['load_' + target]();
          }
        });

        var tab = window.location.hash || (localStorage.getItem('client_tab') || '');
        tab = tab.replace('#', '');
        var selector = '.nav-tabs a[href="#' + tab + '"]';

        if (tab && tab != 'activity' && $(selector).length && window['load_' + tab]) {
            $(selector).tab('show');
        } else {
            window['load_activity']();
        }
    });

    function onArchiveClick() {
        $('#action').val('archive');
        $('.mainForm').submit();
    }

    function onRestoreClick() {
        $('#action').val('restore');
        $('.mainForm').submit();
    }

    function onDeleteClick() {
        sweetConfirm(function() {
            $('#action').val('delete');
            $('.mainForm').submit();
        });
    }

    function onPurgeClick() {
        sweetConfirm(function() {
            $('#action').val('purge');
            $('.mainForm').submit();
        }, "<?php echo e(trans('texts.purge_client_warning') . "\\n\\n" . trans('texts.mobile_refresh_warning') . "\\n\\n" . trans('texts.no_undo')); ?>");
    }

    function showEmailHistory(email) {
        window.emailBounceId = false;
        $('#emailHistoryModal .panel-body').html("<?php echo e(trans('texts.loading')); ?>...");
        $('#reactivateButton').hide();
        $('#emailHistoryModal').modal('show');
        $.post('<?php echo e(url('/email_history')); ?>', {email: email}, function(data) {
            $('#emailHistoryModal .panel-body').html(data.str);
            window.emailBounceId = data.bounce_id;
            $('#reactivateButton').toggle(!! window.emailBounceId);
        })
    }

    function onReactivateClick() {
        $.post('<?php echo e(url('/reactivate_email')); ?>/' + window.emailBounceId, function(data) {
            $('#emailHistoryModal').modal('hide');
            swal("<?php echo e(trans('texts.reactivated_email')); ?>")
        })
    }

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>