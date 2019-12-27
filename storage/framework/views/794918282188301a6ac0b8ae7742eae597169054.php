<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <style type="text/css">
        body {
            line-height: 1.5em;
        }

        #main-row {
            background: #fff;
            line-height: 1.5;
            position: relative;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        #main-row > div {
            padding: 25px;
            position: static;
        }

        @media (max-width: 991px) {
            #contact-details {
                text-align: center;
            }
        }

        @media (min-width: 992px) {
            #main-row,
            #account-row {
                display: flex;
                align-items: center;
            }

            #main-row {
                margin-top: 80px;
                margin-bottom: 80px;
            }
        }

        #main-row h3 {
            font-weight: 700;
            color: #424343;
            margin-top: 0;
            margin-bottom: 30px;
        }

        #main-row,
        #main-row a,
        #account-row,
        #account-row a {
            color: #838181;
        }

        #main-row .amount-label {
            color: #868787;
            font-size: 21px;
            font-weight: 300;
        }

        #main-row .amount {
            color: #222;
            font-size: 30px;
            font-weight: 700;
            line-height: 1.1;
        }

        #main-row .amount-col {
            padding: 40px 20px;
        }

        @media (min-width: 1200px) {
            #main-row .amount {
                font-size: 38px;
            }

            #main-row > div {
                padding: 60px;
            }
        }

        #main-row .amount,
        #main-row .amount-label {
            position: relative;
            z-index: 9;
        }

        .amount-col .inner {
            text-align: center;
        }

        #main-row i {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #42d165;
            position: absolute;
            top: -20px;
            margin-left: -28px;
            text-align: center;
            padding: 13px;
            z-index: 9;
        }

        #main-row i img {
            height: 30px;
        }

        #total-invoiced-col:before,
        #paidtodate-col:before,
        #balance-col:before {
            content: '';
            background: #dbd9d9;
            width: 1px;
            position: absolute;
            height: 100%;
            top: 0;
            margin-left: -20px;
        }

        #total-invoiced-col:before {
            width: 67px;
            background: transparent;
            background: -webkit-linear-gradient(0deg, #f8f8f8 0%, #ffffff 100%);
            background: -moz-linear-gradient(0deg, #f8f8f8 0%, #ffffff 100%);
            background: -o-linear-gradient(0deg, #f8f8f8 0%, #ffffff 100%);
            background: -ms-linear-gradient(0deg, #f8f8f8 0%, #ffffff 100%);
            background: linear-gradient(90deg, #f8f8f8 0%, #ffffff 100%);
        }

        @media (max-width: 991px) {
            #main-row .amount-col {
                position: relative;
            }

            #total-invoiced-col:before,
            #paidtodate-col:before,
            #balance-col:before {
                width: 100%;
                top: auto;
                height: 1px;
                margin-top: -40px;
            }

            #main-row i {
                left: -20px;
                top: 50%;
                margin-top: -28px;
                margin-left: 0;
            }

            #total-invoiced-col:before {
                background: -webkit-linear-gradient(270deg, #f8f8f8 0%, #ffffff 100%);
                background: -moz-linear-gradient(270deg, #f8f8f8 0%, #ffffff 100%);
                background: -o-linear-gradient(270deg, #f8f8f8 0%, #ffffff 100%);
                background: -ms-linear-gradient(270deg, #f8f8f8 0%, #ffffff 100%);
                background: linear-gradient(180deg, #f8f8f8 0%, #ffffff 100%);

                height: 50px;
            }
        }

        @media (max-width: 815px) {
            #main-row i {
                left: 30px;
            }
        }

        @media (max-width: 400px) {
            #main-row i {
                display: none
            }
        }

        .invoices-from {
            color: #424343;
            font-weight: 700;
            letter-spacing: 1px;
            font-size: 13px;
            text-transform: uppercase;
        }

        div.logo img {
            max-width: 100%;
            max-height: 75px;
        }

        #account-row > div {
            padding: 20px 50px 20px 110px;
        }

        #account-row .invoices-from {
            padding-left: 0;
        }

        #account-row .phone-web-details {
            padding-right: 0;
        }

        #account-row .phone-web-details .inner {
            text-align: right;
        }

        #account-row > div:before {
            content: '';
            border-left: 1px solid #dbd9d9;
            position: absolute;
            height: 100%;
            top: 0;
            margin-left: -80px;
        }

        #account-row .logo {
            padding-left: 70px;
        }

        #account-row .invoices-from:before {
            content: none;
        }

        @media (max-width: 1199px) {
            #account-row .logo,
            #account-row > div {
                padding-left: 40px;
                padding-right: 5px;
            }

            #account-row > div:before {
                margin-left: -25px;
            }
        }

        @media (max-width: 991px) {
            #account-row,
            #account-row .phone-web-details .inner {
                text-align: center;
            }

            #account-row > div {
                padding: 15px 30px !important;
            }

            #account-row > div:before {
                content: none;
            }
        }


        table.dataTable thead > tr > th, table.invoice-table thead > tr > th {
            background-color: <?php echo e($color); ?> !important;
        }

        .pagination>.active>a,
        .pagination>.active>span,
        .pagination>.active>a:hover,
        .pagination>.active>span:hover,
        .pagination>.active>a:focus,
        .pagination>.active>span:focus {
            background-color: <?php echo e($color); ?>;
            border-color: <?php echo e($color); ?>;
        }

        table.table thead .sorting:after { content: '' !important }
        table.table thead .sorting_asc:after { content: '' !important }
        table.table thead .sorting_desc:after { content: '' !important }
        table.table thead .sorting_asc_disabled:after { content: '' !important }
        table.table thead .sorting_desc_disabled:after { content: '' !important }

    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="container main-container">

        <?php if($message = $client->customMessage(CUSTOM_MESSAGE_DASHBOARD)): ?>
            <?php echo $__env->make('invited.custom_message', ['message' => $message], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>

        <div class="row" id="main-row">
            <div class="col-md-3" id="contact-details">
                <h3><?php echo e($client->name); ?></h3>
                <?php if($contact->first_name || $contact->last_name): ?>
                    <?php echo e($contact->first_name.' '.$contact->last_name); ?><br>
                <?php endif; ?>
                <?php if($client->address1): ?>
                    <?php echo e($client->address1); ?><br/>
                <?php endif; ?>
                <?php if($client->address2): ?>
                    <?php echo e($client->address2); ?><br/>
                <?php endif; ?>
                <?php if($client->getCityState()): ?>
                    <?php echo e($client->getCityState()); ?><br/>
                <?php endif; ?>
                <?php if($client->country): ?>
                    <?php echo e($client->country->getName()); ?><br/>
                <?php endif; ?>
                <br>
                <?php if($contact->email): ?>
                    <?php echo HTML::mailto($contact->email, $contact->email); ?><br>
                <?php endif; ?>
                <?php if($client->website): ?>
                    <?php echo e($client->present()->websiteLink); ?><br>
                <?php endif; ?>
                <?php if($contact->phone): ?>
                    <?php echo e($contact->phone); ?><br>
                <?php endif; ?>
            </div>
            <div class="col-md-3 amount-col" id="total-invoiced-col">
                <div class="inner">
                    <i><img src="<?php echo e(asset('images/icon-total-invoiced.svg')); ?>"></i>
                    <div class="amount-label">
                        <?php echo e(trans('texts.total_invoiced')); ?>

                    </div>
                    <div class="amount">
                        <?php echo e(Utils::formatMoney($client->paid_to_date + $client->balance, $client->currency_id ?: $account->currency_id)); ?>

                    </div>
                </div>
            </div>
            <div class="col-md-3 amount-col" id="paidtodate-col">
                <div class="inner">
                    <i><img src="<?php echo e(asset('images/icon-paidtodate.svg')); ?>"></i>
                    <div class="amount-label">
                        <?php echo e(trans('texts.paid_to_date')); ?>

                    </div>
                    <div class="amount">
                        <?php echo e(Utils::formatMoney($client->paid_to_date, $client->currency_id ?: $account->currency_id)); ?>

                    </div>
                </div>
            </div>
            <div class="col-md-3 amount-col" id="balance-col">
                <div class="inner">
                    <i><img src="<?php echo e(asset('images/icon-balance.svg')); ?>"></i>
                    <div class="amount-label">
                        <?php echo e(trans('texts.open_balance')); ?>

                    </div>
                    <div class="amount">
                        <?php echo e(Utils::formatMoney($client->balance, $client->currency_id ?: $account->currency_id)); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?php if(!empty($account->getTokenGatewayId())): ?>
                    <div class="pull-left">
                        <?php echo $__env->make('payments.paymentmethods_list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                <?php endif; ?>
                <div class="pull-right">
                    <?php echo Button::success(strtoupper(trans("texts.edit_details")))->asLinkTo(URL::to('/client/details'))->withAttributes(['id' => 'editDetailsButton']); ?>

                    <?php if($client->hasRecurringInvoices()): ?>
                         &nbsp; <?php echo Button::primary(strtoupper(trans("texts.recurring")))->asLinkTo(URL::to('/client/invoices/recurring')); ?>

                    <?php endif; ?>
                    &nbsp; <?php echo Button::primary(strtoupper(trans("texts.view_statement")))->asLinkTo(URL::to('/client/statement')); ?>

                </div>
            </div>
        </div>

        <br/>

        <div class="row" id="account-row">
            <div class="col-md-2 invoices-from">
                <?php echo e(trans('texts.invoice_from')); ?>

            </div>
            <div class="col-md-4 logo">
                <?php if($account->hasLogo()): ?>
                    <?php echo HTML::image($account->getLogoURL()); ?>

                <?php else: ?>
                    <h2><?php echo e($account->name); ?></h2>
                <?php endif; ?>
            </div>
            <div class="col-md-3 address-details">
                <?php if($account->address1): ?>
                    <?php echo e($account->address1); ?><br/>
                <?php endif; ?>
                <?php if($account->address2): ?>
                    <?php echo e($account->address2); ?><br/>
                <?php endif; ?>
                <?php if($account->getCityState()): ?>
                    <?php echo e($account->getCityState()); ?><br/>
                <?php endif; ?>
                <?php if($account->country): ?>
                    <?php echo e($account->country->getName()); ?>

                <?php endif; ?>
            </div>
            <div class="col-md-3 phone-web-details">
                <div class="inner">
                    <?php if($account->work_phone): ?>
                        <?php echo e($account->work_phone); ?><br/>
                    <?php endif; ?>
                    <?php if($account->website): ?>
                        <a href="<?php echo e(Utils::addHttp($account->website)); ?>" target="_blank"><?php echo e($account->website); ?></a>
                        <br/>
                    <?php endif; ?>
                    <?php if($account->work_email): ?>
                        <?php echo HTML::mailto($account->work_email, $account->work_email); ?><br/>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <p>&nbsp;</p>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>