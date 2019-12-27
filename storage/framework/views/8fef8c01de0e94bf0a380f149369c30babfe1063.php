<?php $__env->startSection('head'); ?>
##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <link href='https://fonts.googleapis.com/css?family=Roboto+Mono' rel='stylesheet' type='text/css'>

    <style>
    .checkbox-inline input[type="checkbox"] {
        margin-left:-20px !important;
    }
    .iframe_url {
        display: none;
    }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

<?php echo Former::open_for_files()
        ->rules([
            'iframe_url' => 'url',
        ])
        ->addClass('warn-on-exit'); ?>


<?php echo Former::populate($account); ?>

<?php echo Former::populateField('enable_client_portal', intval($account->enable_client_portal)); ?>

<?php echo Former::populateField('enable_client_portal_dashboard', intval($account->enable_client_portal_dashboard)); ?>

<?php echo Former::populateField('enable_portal_password', intval($enable_portal_password)); ?>

<?php echo Former::populateField('send_portal_password', intval($send_portal_password)); ?>

<?php echo Former::populateField('enable_buy_now_buttons', intval($account->enable_buy_now_buttons)); ?>

<?php echo Former::populateField('show_accept_invoice_terms', intval($account->show_accept_invoice_terms)); ?>

<?php echo Former::populateField('show_accept_quote_terms', intval($account->show_accept_quote_terms)); ?>

<?php echo Former::populateField('require_invoice_signature', intval($account->require_invoice_signature)); ?>

<?php echo Former::populateField('require_quote_signature', intval($account->require_quote_signature)); ?>

<?php echo Former::populateField('signature_on_pdf', intval($account->signature_on_pdf)); ?>


<?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_CLIENT_PORTAL, 'advanced' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo trans('texts.settings'); ?></h3>
            </div>
            <div class="panel-body">

                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="border: none">
                        <li role="presentation" class="active">
                            <a href="#link" aria-controls="link" role="tab" data-toggle="tab"><?php echo e(trans('texts.link')); ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#navigation" aria-controls="navigation" role="tab" data-toggle="tab"><?php echo e(trans('texts.navigation')); ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><?php echo e(trans('texts.messages')); ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#custom_css" aria-controls="custom_css" role="tab" data-toggle="tab"><?php echo e(trans('texts.custom_css')); ?></a>
                        </li>
                        <?php if(Utils::isSelfHost()): ?>
                            <li role="presentation">
                                <a href="#custom_js" aria-controls="custom_js" role="tab" data-toggle="tab"><?php echo e(trans('texts.custom_js')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="link">
                        <div class="panel-body col-lg-10 col-lg-offset-1">
                            <?php if(Utils::isNinja() && ! Utils::isReseller()): ?>
                                <?php echo Former::inline_radios('domain_id')
                                        ->label(trans('texts.domain'))
                                        ->radios([
                                            'invoiceninja.com' => ['value' => \Domain::INVOICENINJA_COM, 'name' => 'domain_id'],
                                            'invoice.services' => ['value' => \Domain::INVOICE_SERVICES, 'name' => 'domain_id'],
                                        ])->check($account->domain_id)
                                        ->help($account->iframe_url ? 'domain_help_website' : 'domain_help'); ?>

                            <?php endif; ?>

                            <?php if(Utils::isNinja()): ?>

                                <?php echo Former::inline_radios('custom_invoice_link')
                                        ->onchange('onCustomLinkChange()')
                                        ->label(trans('texts.custom'))
                                        ->radios([
                                            trans('texts.subdomain') => ['value' => 'subdomain', 'name' => 'custom_link'],
                                            'iFrame' => ['value' => 'iframe', 'name' => 'custom_link'],
                                            trans('texts.domain') => ['value' => 'domain', 'name' => 'custom_link'],
                                        ])->check($account->iframe_url ? ($account->is_custom_domain ? 'domain' : 'iframe') : 'subdomain'); ?>

                                <?php echo e(Former::setOption('capitalize_translations', false)); ?>


                                <?php echo Former::text('subdomain')
                                            ->placeholder(Utils::isNinja() ? 'app' : trans('texts.www'))
                                            ->onchange('onSubdomainChange()')
                                            ->addGroupClass('subdomain')
                                            ->label(' ')
                                            ->help(trans('texts.subdomain_help')); ?>

                            <?php endif; ?>

                            <?php echo Former::text('iframe_url')
                                        ->placeholder('https://www.example.com')
                                        ->appendIcon('question-sign')
                                        ->addGroupClass('iframe_url')
                                        ->label(Utils::isNinja() ? ' ' : trans('texts.website'))
                                        ->help(trans(Utils::isNinja() ? 'texts.subdomain_help' : 'texts.website_help')); ?>


                            <?php if(Utils::isNinja()): ?>
                                <div style="display:none">
                                    <?php echo Former::text('is_custom_domain'); ?>

                                </div>
                            <?php endif; ?>

                            <div id="domainHelp" style="display:none">
                                <?php echo Former::plaintext(' ')
                                            ->value('Using a custom domain requires an <a href="' . url('/settings/account_management?upgrade=true') . '" target="_blank">enterprise plan</a>'); ?>

                            </div>

                            <?php echo Former::plaintext('preview')
                                        ->value($account->getSampleLink()); ?>


                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="navigation">
                        <div class="panel-body col-lg-10 col-lg-offset-1">

                            <?php echo Former::checkbox('enable_client_portal')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.enable_client_portal_help'))
                                ->value(1); ?>



                            <?php echo Former::checkbox('enable_client_portal_dashboard')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.enable_client_portal_dashboard_help'))
                                ->value(1); ?>


                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages">
                        <div class="panel-body">

                            <?php $__currentLoopData = App\Models\Account::$customMessageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo Former::textarea('custom_messages[' . $type . ']')
                                        ->label($type); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="custom_css">
                        <div class="panel-body">

                            <?php echo Former::textarea('client_view_css')
                                ->label(trans('texts.custom_css'))
                                ->rows(10)
                                ->raw()
                                ->maxlength(60000)
                                ->style("min-width:100%;max-width:100%;font-family:'Roboto Mono', 'Lucida Console', Monaco, monospace;font-size:14px;'"); ?>


                        </div>
                    </div>
                    <?php if(Utils::isSelfHost()): ?>
                        <div role="tabpanel" class="tab-pane" id="custom_js">
                            <div class="panel-body">

                                <?php echo Former::textarea('client_view_js')
                                    ->label(trans('texts.custom_js'))
                                    ->rows(10)
                                    ->raw()
                                    ->maxlength(60000)
                                    ->style("min-width:100%;max-width:100%;font-family:'Roboto Mono', 'Lucida Console', Monaco, monospace;font-size:14px;'"); ?>


                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo trans('texts.authorization'); ?></h3>
            </div>
            <div class="panel-body">
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="border: none">
                        <li role="presentation" class="active"><a href="#password" aria-controls="password" role="tab" data-toggle="tab"><?php echo e(trans('texts.password')); ?></a></li>
                        <li role="presentation"><a href="#checkbox" aria-controls="checkbox" role="tab" data-toggle="tab"><?php echo e(trans('texts.checkbox')); ?></a></li>
                        <li role="presentation"><a href="#signature" aria-controls="signature" role="tab" data-toggle="tab"><?php echo e(trans('texts.invoice_signature')); ?></a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="password">
                        <div class="panel-body">
                          <div class="row col-lg-10 col-lg-offset-1">
                            <?php echo Former::checkbox('enable_portal_password')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.enable_portal_password_help'))
                                ->label(trans('texts.enable_portal_password'))
                                ->value(1); ?>

                            <?php echo Former::checkbox('send_portal_password')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.send_portal_password_help'))
                                ->label(trans('texts.send_portal_password'))
                                ->value(1); ?>

                            <?php echo Former::plaintext('client_login')
                                ->value(link_to($account->present()->clientLoginUrl, null, ['target' => '_blank']))
                                ->help(Utils::isNinja() && ! $account->subdomain && ! $account->iframe_url ? 'improve_client_portal_link' : ''); ?>

                        </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="checkbox">
                        <div class="panel-body">
                          <div class="row col-lg-10 col-lg-offset-1">
                            <?php echo Former::checkbox('show_accept_invoice_terms')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.show_accept_invoice_terms_help'))
                                ->label(trans('texts.show_accept_invoice_terms'))
                                ->value(1); ?>

                            <?php echo Former::checkbox('show_accept_quote_terms')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.show_accept_quote_terms_help'))
                                ->label(trans('texts.show_accept_quote_terms'))
                                ->value(1); ?>

                        </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="signature">
                        <div class="panel-body">
                          <div class="row col-lg-10 col-lg-offset-1">
                            <?php echo Former::checkbox('require_invoice_signature')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.require_invoice_signature_help'))
                                ->label(trans('texts.require_invoice_signature'))
                                ->value(1); ?>


                            <?php echo Former::checkbox('require_quote_signature')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.require_quote_signature_help'))
                                ->label(trans('texts.require_quote_signature'))
                                ->value(1); ?>


                            <?php echo Former::checkbox('signature_on_pdf')
                                ->text(trans('texts.enable'))
                                ->help(trans('texts.signature_on_pdf_help'))
                                ->value(1); ?>

                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" id="buy_now">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo trans('texts.buy_now_buttons'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row col-lg-10 col-lg-offset-1">

                    <?php if(count($gateway_types) && count($products)): ?>

                        <?php echo Former::checkbox('enable_buy_now_buttons')
                            ->text(trans('texts.enable'))
                            ->label(' ')
                            ->help(trans('texts.enable_buy_now_buttons_help'))
                            ->value(1); ?>


                        <?php if($account->enable_buy_now_buttons): ?>
                            <?php echo Former::select('product')
                                ->onchange('updateBuyNowButtons()')
                                ->addOption('', '')
                                ->inlineHelp('buy_now_buttons_warning')
                                ->addGroupClass('product-select'); ?>


                            <?php if(count($account->present()->customTextFields)): ?>
                                <?php echo Former::inline_checkboxes('custom_fields')
                                        ->onchange('updateBuyNowButtons()')
                                        ->checkboxes($account->present()->customTextFields); ?>

                            <?php endif; ?>

                            <?php echo Former::inline_radios('landing_page')
                                    ->onchange('showPaymentTypes();updateBuyNowButtons();')
                                    ->radios([
                                        trans('texts.invoice') => ['value' => 'invoice', 'name' => 'landing_page_type'],
                                        trans('texts.payment') => ['value' => 'payment', 'name' => 'landing_page_type'],
                                    ])->check('invoice'); ?>


                            <div id="paymentTypesDiv" style="display:none">
                                <?php echo Former::select('payment_type')
                                    ->onchange('updateBuyNowButtons()')
                                    ->options($gateway_types); ?>

                            </div>

                            <?php echo Former::text('redirect_url')
                                    ->onchange('updateBuyNowButtons()')
                                    ->placeholder('https://www.example.com')
                                    ->help('redirect_url_help'); ?>



                            <?php echo Former::checkbox('is_recurring')
                                ->text('enable')
                                ->label('recurring')
                                ->onchange('showRecurring();updateBuyNowButtons();')
                                ->value(1); ?>


                            <div id="recurringDiv" style="display:none">

                                <?php echo Former::select('frequency_id')
                                        ->options(\App\Models\Frequency::selectOptions())
                                        ->onchange('updateBuyNowButtons()')
                                        ->value(FREQUENCY_MONTHLY); ?>


                                <?php echo Former::select('auto_bill')
                                        ->onchange('updateBuyNowButtons()')
                                        ->options([
                                            AUTO_BILL_OFF => trans('texts.off'),
                                            AUTO_BILL_OPT_IN => trans('texts.opt_in'),
                                            AUTO_BILL_OPT_OUT => trans('texts.opt_out'),
                                            AUTO_BILL_ALWAYS => trans('texts.always'),
                                        ]); ?>

                            </div>

                            <p>&nbsp;</p>

                            <div role="tabpanel">
                                <ul class="nav nav-tabs" role="tablist" style="border: none">
                                    <li role="presentation" class="active">
                                        <a href="#buy_now_link" aria-controls="buy_now_link" role="tab" data-toggle="tab"><?php echo e(trans('texts.link')); ?></a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#form" aria-controls="form" role="tab" data-toggle="tab"><?php echo e(trans('texts.form')); ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="buy_now_link">
                                    <textarea id="linkTextarea" class="form-control" rows="4" readonly></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="form">
                                    <textarea id="formTextarea" class="form-control" rows="4" readonly></textarea>
                                </div>
                            </div>

                        <?php endif; ?>
                        &nbsp;
                    <?php else: ?>

                        <center style="font-size:16px;color:#888888;">
                            <?php echo e(trans('texts.buy_now_buttons_disabled')); ?>

                        </center>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php if(Auth::user()->isPro()): ?>
    <center>
    	<?php echo Button::success(trans('texts.save'))->submit()->large()->appendIcon(Icon::create('floppy-disk')); ?>

    </center>
<?php endif; ?>

<?php echo Former::close(); ?>



<div class="modal fade" id="iframeHelpModal" tabindex="-1" role="dialog" aria-labelledby="iframeHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="min-width:150px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="iframeHelpModalLabel"><?php echo e(trans('texts.iframe_url')); ?></h4>
            </div>

            <div class="container" style="width: 100%; padding-bottom: 0px !important">
            <div class="panel panel-default">
            <div class="panel-body" id="iframeModalHelp">
                <p><?php echo e(trans('texts.iframe_url_help1')); ?></p>
                <pre>&lt;center&gt;
&lt;iframe id="invoiceIFrame" width="100%" height="1200" style="max-width:1000px"&gt;&lt;/iframe&gt;
&lt;/center&gt;
&lt;script language="javascript"&gt;
var iframe = document.getElementById('invoiceIFrame');
var search = window.location.search + '//';
var silent = search.indexOf('silent') > 0;
var parts = search.replace('?silent=true', '').split('/');
iframe.src = '<?php echo e(rtrim(SITE_URL ,'/')); ?>/' + parts[1] + '/' + parts[0].substring(1, 33) + '/' + parts[2] + (silent ? '?silent=true' : '');
&lt;/script&gt;</pre>
                <p><?php echo e(trans('texts.iframe_url_help2')); ?></p>
                <p><b><?php echo e(trans('texts.iframe_url_help3')); ?></b></p>
            </div>

            <div class="panel-body" id="domainModalHelp" style="display:none">
                <p>Create a DNS A Record entry for your custom domain and point to the following IP address <code>96.126.107.105</code>.</p>
                <p>Once this is setup please send an email to <?php echo e(env('CONTACT_EMAIL')); ?> and we'll complete the process.</p>
            </div>

            </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
            </div>


        </div>
    </div>
</div>


<script type="text/javascript">

    var products = <?php echo $products; ?>;

    $(function() {
        var $productSelect = $('select#product');
        for (var i=0; i<products.length; i++) {
            var product = products[i];

            $productSelect.append(new Option(formatMoney(product.cost) + ' - ' + product.product_key, product.public_id));
        }
        $productSelect.combobox({highlighter: comboboxHighlighter});

        updateCheckboxes();
        updateBuyNowButtons();
    })

	$('#enable_portal_password, #enable_client_portal, #require_invoice_signature, #require_quote_signature').change(updateCheckboxes);

	function updateCheckboxes() {
		var checked = $('#enable_portal_password').is(':checked');
		$('#send_portal_password').prop('disabled', ! checked);

        var checked = $('#enable_client_portal').is(':checked');
		$('#enable_client_portal_dashboard').prop('disabled', ! checked);

        var checked = $('#require_invoice_signature').is(':checked') || $('#require_quote_signature').is(':checked');
		$('#signature_on_pdf').prop('disabled', ! checked);
	}

    function showPaymentTypes() {
        var val = $('input[name=landing_page_type]:checked').val()
        if (val == '<?php echo e(ENTITY_PAYMENT); ?>') {
            $('#paymentTypesDiv').fadeIn();
        } else {
            $('#paymentTypesDiv').fadeOut();
        }
    }

    function showRecurring() {
        var val = $('input[name=is_recurring]:checked').val()
        if (val) {
            $('#recurringDiv').fadeIn();
        } else {
            $('#recurringDiv').fadeOut();
        }
    }

    function updateBuyNowButtons() {
        var productId = $('#product').val();
        var landingPage = $('input[name=landing_page_type]:checked').val()
        var paymentType = (landingPage == 'payment') ? '/' + $('#payment_type').val() : '/';
        var redirectUrl = $('#redirect_url').val();
        var isRecurring = $('input[name=is_recurring]:checked').val()
        var frequencyId = $('#frequency_id').val();
        var autoBillId = $('#auto_bill').val();

        var form = '';
        var link = '';

        if (productId) {
            <?php if(Utils::isNinjaProd()): ?>
                var domain = '<?php echo e($account->present()->clientPortalLink(true)); ?>/buy_now';
            <?php else: ?>
                var domain = '<?php echo e(url('/buy_now')); ?>';
            <?php endif; ?>
            var link = domain + paymentType +
                '?account_key=<?php echo e($account->account_key); ?>' +
                '&product_id=' + productId;

            var form = '<form action="' + link + '" method="post" target="_top">' + "\n";

            <?php $__currentLoopData = $account->present()->customTextFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                if ($('input#<?php echo e($val['name']); ?>').is(':checked')) {
                    form += '<input type="text" name="<?php echo e($val['name']); ?>" placeholder="<?php echo e($field); ?>" required/>' + "\n";
                    link += '&<?php echo e($val['name']); ?>=';
                }
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            if (redirectUrl) {
                link += '&redirect_url=' + encodeURIComponent(redirectUrl);
                form += '<input type="hidden" name="redirect_url" value="' + redirectUrl + '"/>' + "\n";
            }

            if (isRecurring) {
                link += "&is_recurring=true&frequency_id=" + frequencyId + "&auto_bill_id=" + autoBillId;
                form += '<input type="hidden" name="is_recurring" value="true"/>' + "\n"
                        + '<input type="hidden" name="frequency_id" value="' + frequencyId + '"/>' + "\n"
                        + '<input type="hidden" name="auto_bill_id" value="' + autoBillId + '"/>' + "\n";
            }

            form += '<input type="submit" value="Buy Now" name="submit"/>' + "\n" + '</form>';
        }

        $('#formTextarea').text(form);
        $('#linkTextarea').text(link);
    }


    function onSubdomainChange() {
        var input = $('#subdomain');
        var val = input.val();
        if (!val) return;
        val = val.replace(/[^a-zA-Z0-9_\-]/g, '').toLowerCase().substring(0, <?php echo e(MAX_SUBDOMAIN_LENGTH); ?>);
        input.val(val);
    }

    function onCustomLinkChange() {
        $('.iframe_url, .subdomain').hide();
        $('.subdomain').hide();
        $('#domainHelp, #iframeModalHelp, #domainModalHelp').hide();
        $('#is_custom_domain').val(0);

        var val = $('input[name=custom_link]:checked').val() || 'iframe';

        if (val == 'subdomain') {
            $('.subdomain').show();
        } else if (val == 'iframe') {
            $('.iframe_url, #iframeModalHelp').show();
        } else {
            <?php if(auth()->user()->isEnterprise()): ?>
                $('.iframe_url, #domainModalHelp').show();
                $('#is_custom_domain').val(1);
            <?php else: ?>
                $('#domainHelp').show();
            <?php endif; ?>
        }
    }

    $('.iframe_url .input-group-addon').click(function() {
        $('#iframeHelpModal').modal('show');
    });

    $(function() {
        onCustomLinkChange();

        $('#subdomain').change(function() {
            $('#iframe_url').val('');
        });
        $('#iframe_url').change(function() {
            $('#subdomain').val('');
        });
    });


</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>