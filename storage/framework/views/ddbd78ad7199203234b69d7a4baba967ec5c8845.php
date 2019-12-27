<?php $__env->startSection('head'); ?>
	##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

		<?php echo $__env->make('money_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<?php $__currentLoopData = $invoice->client->account->getFontFolders(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $font): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        	<script src="<?php echo e(asset('js/vfs_fonts/'.$font.'.js')); ?>" type="text/javascript"></script>
    	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <script src="<?php echo e(asset('pdf.built.js')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" type="text/javascript"></script>

		<?php if($account->showSignature($invoice)): ?>
			<script src="<?php echo e(asset('js/jSignature.min.js')); ?>"></script>
		<?php endif; ?>

		<style type="text/css">
			body {
				background-color: #f8f8f8;
			}

            .dropdown-menu li a{
                overflow:hidden;
                margin-top:5px;
                margin-bottom:5px;
            }

			#signature {
		        border: 2px dotted black;
		        background-color:lightgrey;
		    }
		</style>

    <?php if(!empty($transactionToken) && $accountGateway->gateway_id == GATEWAY_BRAINTREE && $accountGateway->getPayPalEnabled()): ?>
        <div id="paypal-container"></div>
        <script type="text/javascript" src="https://js.braintreegateway.com/js/braintree-2.23.0.min.js"></script>
        <script type="text/javascript" >
            $(function() {
                var paypalLink = $('a[href$="paypal"]'),
                    paypalUrl = paypalLink.attr('href'),
                    checkout;
                paypalLink.parent().attr('id', 'paypal-container');
                braintree.setup("<?php echo e($transactionToken); ?>", "custom", {
                    onReady: function (integration) {
                        checkout = integration;
                        $('a[href$="#braintree_paypal"]').each(function(){
                            var el=$(this);
                            el.attr('href', el.attr('href').replace('#braintree_paypal','?device_data='+encodeURIComponent(integration.deviceData)))
                        })
                    },
                    paypal: {
                        container: "paypal-container",
                        singleUse: false,
                        enableShippingAddress: false,
                        enableBillingAddress: false,
                        headless: true,
                        locale: "<?php echo e($invoice->client->language ? $invoice->client->language->locale : $invoice->account->language->locale); ?>"
                    },
                    dataCollector: {
                        paypal: true
                    },
                    onPaymentMethodReceived: function (obj) {
                        window.location.href = paypalUrl.replace('#braintree_paypal', '') + '/' + encodeURIComponent(obj.nonce) + "?device_data=" + encodeURIComponent(JSON.stringify(obj.details));
                    }
                });
                paypalLink.click(function(e){
                    e.preventDefault();
					<?php if($account->requiresAuthorization($invoice)): ?>
						window.pendingPaymentFunction = checkout.paypal.initAuthFlow;
						showAuthorizationModal();
					<?php else: ?>
                    	checkout.paypal.initAuthFlow();
					<?php endif; ?>
                })
            });
        </script>
    <?php elseif(!empty($enableWePayACH)): ?>
        <script type="text/javascript" src="https://static.wepay.com/js/tokenization.v2.js"></script>
        <script type="text/javascript">
			function payWithWepay() {
				var achLink = $('a[href$="/bank_transfer"]');
				$('#wepay-error').remove();
				var email = <?php echo json_encode($contact->email); ?> || prompt('<?php echo e(trans('texts.ach_email_prompt')); ?>');
				if (!email) {
					return;
				}

				WePay.bank_account.create({
					'client_id': '<?php echo e(WEPAY_CLIENT_ID); ?>',
					'email':email
				}, function(data){
					dataObj = JSON.parse(data);
					if(dataObj.bank_account_id) {
						window.location.href = achLink.attr('href') + '/' + dataObj.bank_account_id + "?details=" + encodeURIComponent(data);
					} else if(dataObj.error) {
						$('#wepay-error').remove();
						achLink.closest('.container').prepend($('<div id="wepay-error" style="margin-top:20px" class="alert alert-danger"></div>').text(dataObj.error_description));
					}
				});
			}

            $(function() {
                var achLink = $('a[href$="/bank_transfer"]');
                WePay.set_endpoint('<?php echo e(WEPAY_ENVIRONMENT); ?>');
				achLink.click(function(e) {
                	e.preventDefault();
					<?php if($account->requiresAuthorization($invoice)): ?>
						window.pendingPaymentFunction = window.payWithWepay;
						showAuthorizationModal();
					<?php else: ?>
                    	payWithWepay();
					<?php endif; ?>
                });
            });
        </script>
	<?php elseif(! empty($accountGateway) && $accountGateway->getApplePayEnabled()): ?>
		<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
	    <script type="text/javascript">
	        // https://stripe.com/docs/stripe-js/elements/payment-request-button
	        var stripe = Stripe('<?php echo e($accountGateway->getPublishableKey()); ?>');
	        var paymentRequest = stripe.paymentRequest({
	            country: '<?php echo e($invoice->client->getCountryCode()); ?>',
	            currency: '<?php echo e(strtolower($invoice->client->getCurrencyCode())); ?>',
	            total: {
	                label: '<?php echo e(trans('texts.invoice') . ' ' . $invitation->invoice->invoice_number); ?>',
	                amount: <?php echo e($invitation->invoice->getRequestedAmount() * 100); ?>,
	            },
	        });

	        var elements = stripe.elements();
	        var prButton = elements.create('paymentRequestButton', {
	            paymentRequest: paymentRequest,
	        });

	        $(function() {
	            // Check the availability of the Payment Request API first.
	            paymentRequest.canMakePayment().then(function(result) {
	                if (! result) {
						$('#paymentButtons ul.dropdown-menu li').find('a[href$="apple_pay"]').remove();
	                }
	            });

	        });

	    </script>

	<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="container">

		<?php if($message = $invoice->client->customMessage($invoice->getCustomMessageType())): ?>
			<?php echo $__env->make('invited.custom_message', ['message' => $message], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>

        <?php if(!empty($partialView)): ?>
            <?php echo $__env->make($partialView, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php else: ?>
            <div id="paymentButtons" class="pull-right" style="text-align:right">
            <?php if($invoice->isQuote() && $approveRequired): ?>
                <?php echo Button::normal(trans('texts.download'))->withAttributes(['onclick' => 'onDownloadClick()'])->large(); ?>&nbsp;&nbsp;
                <?php if($showApprove): ?>
                    <?php echo Button::success(trans('texts.approve'))->withAttributes(['id' => 'approveButton', 'onclick' => 'onApproveClick()', 'class' => 'require-authorization'])->large(); ?>

				<?php elseif($invoiceLink = $invoice->getInvoiceLinkForQuote($contact->id)): ?>
					<?php echo Button::success(trans('texts.view_invoice'))->asLinkTo($invoiceLink)->large(); ?>

                <?php endif; ?>
			<?php elseif($invoice->isQuote() && $invoiceLink = $invoice->getInvoiceLinkForQuote($contact->id)): ?>
				<?php echo Button::normal(trans('texts.download'))->withAttributes(['onclick' => 'onDownloadClick()'])->large(); ?>

				<?php echo Button::success(trans('texts.view_invoice'))->asLinkTo($invoiceLink)->large(); ?>

			<?php elseif( ! $invoice->canBePaid()): ?>
				<?php echo Button::normal(trans('texts.download'))->withAttributes(['onclick' => 'onDownloadClick()'])->large(); ?>

    		<?php elseif($invoice->client->account->isGatewayConfigured() && floatval($invoice->balance) && !$invoice->is_recurring): ?>
                <?php echo Button::normal(trans('texts.download'))->withAttributes(['onclick' => 'onDownloadClick()'])->large(); ?>&nbsp;&nbsp;
				<span class="require-authorization">
	                <?php if(count($paymentTypes) > 1): ?>
	                    <?php echo DropdownButton::success(trans('texts.pay_now'))->withContents($paymentTypes)->large(); ?>

	                <?php elseif(count($paymentTypes) == 1): ?>
	                    <a href='<?php echo e($paymentURL); ?>' class="btn btn-success btn-lg"><?php echo e(trans('texts.pay_now')); ?> <?php echo $invoice->present()->gatewayFee($gatewayTypeId); ?></a>
	                <?php endif; ?>
				</span>
    		<?php else: ?>
    			<?php echo Button::normal(trans('texts.download'))->withAttributes(['onclick' => 'onDownloadClick()'])->large(); ?>

    		<?php endif; ?>

			<?php if($account->isNinjaAccount()): ?>
				<?php echo Button::primary(trans('texts.return_to_app'))->asLinkTo(URL::to('/settings/account_management'))->large(); ?>

			<?php endif; ?>
    		</div>
        <?php endif; ?>

        <div class="pull-left">
            <?php if(!empty($documentsZipURL)): ?>
                <?php echo Button::normal(trans('texts.download_documents', array('size'=>Form::human_filesize($documentsZipSize))))->asLinkTo($documentsZipURL)->large(); ?>

            <?php endif; ?>
        </div>

		<div class="clearfix"></div><p>&nbsp;</p>
        <?php if($account->isPro() && $invoice->hasDocuments()): ?>
            <div class="invoice-documents">
            <h3><?php echo e(trans('texts.documents_header')); ?></h3>
            <ul>
            <?php $__currentLoopData = $invoice->allDocuments(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a target="_blank" href="<?php echo e($document->getClientUrl($invitation)); ?>"><?php echo e($document->name); ?> (<?php echo e(Form::human_filesize($document->size)); ?>)</a></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            </div>
        <?php endif; ?>

        <?php if($account->hasFeature(FEATURE_DOCUMENTS) && $account->invoice_embed_documents): ?>
            <?php $__currentLoopData = $invoice->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($document->isPDFEmbeddable()): ?>
                    <script src="<?php echo e($document->getClientVFSJSUrl()); ?>" type="text/javascript" async></script>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $invoice->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $expense->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($document->isPDFEmbeddable()): ?>
                        <script src="<?php echo e($document->getClientVFSJSUrl()); ?>" type="text/javascript" async></script>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
		<script type="text/javascript">

			window.invoice = <?php echo $invoice; ?>;
			invoice.features = {
                customize_invoice_design:<?php echo e($invoice->client->account->hasFeature(FEATURE_CUSTOMIZE_INVOICE_DESIGN) ? 'true' : 'false'); ?>,
                remove_created_by:<?php echo e($invoice->client->account->hasFeature(FEATURE_REMOVE_CREATED_BY) ? 'true' : 'false'); ?>,
                invoice_settings:<?php echo e($invoice->client->account->hasFeature(FEATURE_INVOICE_SETTINGS) ? 'true' : 'false'); ?>

            };
			invoice.is_quote = <?php echo e($invoice->isQuote() ? 'true' : 'false'); ?>;
			invoice.contact = <?php echo $contact; ?>;

			function getPDFString(cb) {
    	  	    return generatePDF(invoice, invoice.invoice_design.javascript, true, cb);
			}

            if (window.hasOwnProperty('pjsc_meta')) {
                window['pjsc_meta'].remainingTasks++;
            }

			function waitForSignature() {
				if (window.signatureAsPNG || ! invoice.invitations[0].signature_base64) {
					writePdfAsString();
				} else {
					window.setTimeout(waitForSignature, 100);
				}
			}

			function writePdfAsString() {
				doc = getPDFString();
				doc.getDataUrl(function(pdfString) {
					document.write(pdfString);
					document.close();
					if (window.hasOwnProperty('pjsc_meta')) {
						window['pjsc_meta'].remainingTasks--;
					}
				});
			}

			$(function() {
                <?php if(Input::has('phantomjs')): ?>
					<?php if(Input::has('phantomjs_balances')): ?>
						document.write(calculateAmounts(invoice).total_amount);
						document.close();
						if (window.hasOwnProperty('pjsc_meta')) {
							window['pjsc_meta'].remainingTasks--;
						}
					<?php else: ?>
						<?php if($account->signature_on_pdf): ?>
							refreshPDF();
							waitForSignature();
						<?php else: ?>
							writePdfAsString();
						<?php endif; ?>
					<?php endif; ?>
                <?php else: ?>
                    refreshPDF();
                <?php endif; ?>

				<?php if($account->requiresAuthorization($invoice) && ! $invitation->signature_date): ?>
					$('.require-authorization a').on('click', function(e) {
						e.preventDefault();
						window.pendingPaymentHref = $(this).attr('href');
						showAuthorizationModal();
					});

					<?php if($account->showSignature($invoice)): ?>
						$('#authorizationModal').on('shown.bs.modal', function () {
							if ( ! window.pendingPaymentInit) {
								window.pendingPaymentInit = true;
								$("#signature").jSignature().bind('change', function(e) {
									setModalPayNowEnabled();
								});;
							}
						});
					<?php endif; ?>
				<?php endif; ?>
			});

			function showAuthorizationModal() {
				<?php if($account->showSignature($invoice)): ?>
					if (window.pendingPaymentInit) {
						$("#signature").jSignature('reset');
					}
				<?php endif; ?>
				<?php if($account->showAcceptTerms($invoice)): ?>
					$('#termsCheckbox').attr('checked', false);
				<?php endif; ?>
				$('#authorizationModal').modal('show');
			}

			function onApproveClick() {
				<?php if($account->requiresAuthorization($invoice)): ?>
					window.pendingPaymentFunction = approveQuote;
					showAuthorizationModal();
				<?php else: ?>
					approveQuote();
				<?php endif; ?>
			}

			function approveQuote() {
				$('#approveButton').prop('disabled', true);
				location.href = "<?php echo e(url('/approve/' . $invitation->invitation_key)); ?>";
			}

			function onDownloadClick() {
				try {
					var doc = generatePDF(invoice, invoice.invoice_design.javascript, true);
	                var fileName = invoice.is_quote ? invoiceLabels.quote : invoiceLabels.invoice;
					doc.save(fileName + '_' + invoice.invoice_number + '.pdf');
			    } catch (exception) {
					if (location.href.indexOf('/view/') > 0) {
			            location.href = location.href.replace('/view/', '/download/');
			        }
				}
			}

			function showCustom1Modal() {
                $('#custom1GatewayModal').modal('show');
            }

			function showCustom2Modal() {
                $('#custom2GatewayModal').modal('show');
            }

			function showCustom3Modal() {
                $('#custom3GatewayModal').modal('show');
            }

			function onModalPayNowClick() {
				<?php if($account->showSignature($invoice)): ?>
					var data = {
						signature: $('#signature').jSignature('getData', 'svgbase64')[1]
					};
				<?php else: ?>
					var data = false;
				<?php endif; ?>
				$.ajax({
				    url: "<?php echo e(URL::to('authorize/' . $invitation->invitation_key)); ?>",
				    type: 'PUT',
					data: data,
				    success: function(response) {
				 		redirectToPayment();
				    },
					error: function(response) {
						alert("<?php echo e(trans('texts.error_refresh_page')); ?>");
					}
				});
			}

			function redirectToPayment() {
				$('#authorizationModal').modal('hide');
				if (window.pendingPaymentFunction) {
					window.pendingPaymentFunction();
				} else {
					location.href = window.pendingPaymentHref;
				}
			}

			function setModalPayNowEnabled() {
				var disabled = false;

				<?php if($account->showAcceptTerms($invoice)): ?>
					if ( ! $('#termsCheckbox').is(':checked')) {
						disabled = true;
					}
				<?php endif; ?>

				<?php if($account->showSignature($invoice)): ?>
					if ( ! $('#signature').jSignature('isModified')) {
						disabled = true;
					}
				<?php endif; ?>

				$('#modalPayNowButton').attr('disabled', disabled);
			}


		</script>

		<?php echo $__env->make('invoices.pdf', ['account' => $invoice->client->account], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<p>&nbsp;</p>

	</div>


	<?php if($customGateway = $account->getGatewayByType(GATEWAY_TYPE_CUSTOM1)): ?>
		<?php echo $__env->make('invited.custom_gateway', ['customGateway' => $customGateway, 'number' => 1], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>

	<?php if($customGateway = $account->getGatewayByType(GATEWAY_TYPE_CUSTOM2)): ?>
		<?php echo $__env->make('invited.custom_gateway', ['customGateway' => $customGateway, 'number' => 2], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>

	<?php if($customGateway = $account->getGatewayByType(GATEWAY_TYPE_CUSTOM3)): ?>
		<?php echo $__env->make('invited.custom_gateway', ['customGateway' => $customGateway, 'number' => 3], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>


	<?php if($account->requiresAuthorization($invoice)): ?>
		<div class="modal fade" id="authorizationModal" tabindex="-1" role="dialog" aria-labelledby="authorizationModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo e(trans('texts.authorization')); ?></h4>
			  </div>

			 <div class="panel-body">
				 <?php if($invoice->terms): ?>
					 <div class="well" style="max-height:300px;overflow-y:scroll">
						 <?php echo nl2br(e($invoice->terms)); ?>

					 </div>
				 <?php endif; ?>
				 <?php if($account->showSignature($invoice)): ?>
				 	<div>
						<?php echo e(trans('texts.sign_here')); ?>

					</div>
				 	<div id="signature"></div><br/>
				 <?php endif; ?>
			  </div>

			  <div class="modal-footer">
				 <?php if($account->showAcceptTerms($invoice)): ?>
 					<div class="pull-left">
 						<label for="termsCheckbox" style="font-weight:normal">
 							<input id="termsCheckbox" type="checkbox" onclick="setModalPayNowEnabled()"/>
 							&nbsp;<?php echo e(trans('texts.i_agree')); ?>

 						</label>
 					</div>
 				 <?php endif; ?>
				<button id="modalPayNowButton" type="button" class="btn btn-success" onclick="onModalPayNowClick()" disabled="">
					<?php echo e($invoice->isQuote() ? trans('texts.approve') : trans('texts.pay_now')); ?>

				</button>
			  </div>
			</div>
		  </div>
		</div>
	<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>