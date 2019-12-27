<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true" style="z-index:10000">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #f8f8f8">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="emailModalLabel"><?php echo e(trans($invoice->isQuote() ? 'texts.email_quote' : 'texts.email_invoice')); ?></h4>
            </div>

            <div class="container" style="width: 100%; padding-bottom: 0px !important">
            <div class="panel panel-default">
            <div class="panel-body">

                <?php echo Former::plaintext('recipients')
                        ->value(''); ?>


                <?php if(Utils::isPro()): ?>
                    <?php echo Former::select('template_type')
                            ->label('template')
                            ->onchange('loadTemplate()')
                            ->options([
                                $invoice->getEntityType() => trans('texts.initial_email'),
                                'reminder1' => trans('texts.first_reminder'),
                                'reminder2' => trans('texts.second_reminder'),
                                'reminder3' => trans('texts.third_reminder'),
                            ]); ?>

                <?php endif; ?>

                <br/>
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="border: none">
                        <li role="presentation" class="active">
                            <a href="#preview" aria-controls="preview" role="tab" data-toggle="tab"><?php echo e(trans('texts.preview')); ?></a>
                        </li>
                        <?php if(Utils::isPro()): ?>
                            <li role="presentation">
                                <a href="#customize" aria-controls="customize" role="tab" data-toggle="tab">
                                    <?php echo e(trans('texts.customize')); ?> <?php echo Auth::user()->isTrial() ? '<sup>' . trans('texts.pro') . '</sup>' : ''; ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <li role="presentation">
                            <a href="#history" aria-controls="history" role="tab" data-toggle="tab"><?php echo e(trans('texts.history')); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="preview">
                        <div style="padding:10px 14px 0px 14px">
                            <div id="emailSubjectDiv"></div>
                            <br/>
                            <div id="emailBodyDiv"></div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="customize">
                        <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 0)); ?>

                        <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 0)); ?>

                        <?php echo Former::text('emailSubject')
                                ->placeholder('subject')
                                ->label(false)
                                ->onchange('onEmailSubjectChange()')
                                ->oninput('onEmailSubjectInput()')
                                ->appendIcon('question-sign')
                                ->addGroupClass('email-subject'); ?>

                        <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 4)); ?>

                        <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 4)); ?>


                        <br/>
                        <div id="templateEditor" class="form-control" style="min-height:160px"></div>
                        <div style="display:none">
                            <?php echo Former::textarea("template[body]")->raw(); ?>

                            <?php echo Former::text('template[subject]')->raw(); ?>

                            <?php echo Former::text('reminder')->raw(); ?>

                        </div>
                        <?php echo $__env->make('partials/quill_toolbar', ['name' => 'template'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="history">
                        <?php if(count($activities = $invoice->emailHistory())): ?>
                        <table class="table table-striped data-table">
                            <tr>
                                <th><?php echo e(trans('texts.template')); ?></th>
                                <th><?php echo e(trans('texts.contact')); ?></th>
                                <th><?php echo e(trans('texts.date')); ?></th>
                            </tr>
                            <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($activity->present()->notes); ?></td>
                                <td>
                                    <span title="<?php echo e(trans('texts.sent_by', ['user' => $activity->present()->user])); ?>">
                                        <?php echo e($activity->contact->getDisplayName()); ?>

                                    </span>
                                </td>
                                <td>
                                    <span title="<?php echo e($activity->present()->createdAt); ?>">
                                        <?php echo e($activity->present()->createdAtDate); ?> - <?php echo e($activity->created_at->diffForHumans()); ?>

                                    </span>
                                </td>
                                <script type="text/javascript">
                                    <?php if($activity->notes == 'reminder3'): ?>
                                        if (!window.defaultTemplate) window.defaultTemplate = 'reminder3';
                                    <?php elseif($activity->notes == 'reminder2'): ?>
                                        if (!window.defaultTemplate) window.defaultTemplate = 'reminder3';
                                    <?php elseif($activity->notes == 'reminder1'): ?>
                                        if (!window.defaultTemplate) window.defaultTemplate = 'reminder2';
                                    <?php else: ?>
                                        if (!window.defaultTemplate) window.defaultTemplate = 'reminder1';
                                    <?php endif; ?>
                                </script>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                        <?php else: ?>
                            <center style="font-size:16px;color:#888888;padding:30px;">
                                <?php echo e(trans("texts.{$invoice->getEntityType()}_not_emailed")); ?>

                            </center>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer" style="margin-top: 2px; padding-right:0px">
                <div id="defaultDiv" style="display:none" class="pull-left">
                    <?php echo Former::checkbox('save_as_default')
                            ->text('save_as_default')
                            ->raw(); ?>

                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.cancel')); ?></button>
                <button id="sendEmailButton" type="button" class="btn btn-info" onclick="onConfirmEmailClick()"><?php echo e(trans('texts.send_email')); ?></button>
            </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var emailSubjects = [];
    emailSubjects['<?php echo e($invoice->getEntityType()); ?>'] = "<?php echo e($account->getEmailSubject($invoice->getEntityType())); ?>";
    emailSubjects['reminder1'] = "<?php echo e($account->getEmailSubject('reminder1')); ?>";
    emailSubjects['reminder2'] = "<?php echo e($account->getEmailSubject('reminder2')); ?>";
    emailSubjects['reminder3'] = "<?php echo e($account->getEmailSubject('reminder3')); ?>";

    var emailTemplates = [];
    emailTemplates['<?php echo e($invoice->getEntityType()); ?>'] = "<?php echo e($account->getEmailTemplate($invoice->getEntityType())); ?>";
    emailTemplates['reminder1'] = "<?php echo e($account->getEmailTemplate('reminder1')); ?>";
    emailTemplates['reminder2'] = "<?php echo e($account->getEmailTemplate('reminder2')); ?>";
    emailTemplates['reminder3'] = "<?php echo e($account->getEmailTemplate('reminder3')); ?>";

    function showEmailModal() {
        loadTemplate();
        $('#recipients').html(getSendToEmails());
		$('#emailModal').modal('show');
    }

    $('#emailModal').on('shown.bs.modal', function () {
        $('#sendEmailButton').focus();
    });

    function loadTemplate() {
        <?php if(Utils::isPro()): ?>
            var templateType = $('#template_type').val();
        <?php else: ?>
            var templateType = '<?php echo e($invoice->getEntityType()); ?>';
        <?php endif; ?>

        var template = dencodeEntities(emailSubjects[templateType]);
        $("#emailSubject").val(template);

        var template = dencodeEntities(emailTemplates[templateType]);
        emailEditor.setHTML(template);

        var reminder = $('#template_type').val();
        if (reminder == '<?php echo e($invoice->getEntityType()); ?>') {
            reminder = '';
        }
        $('#reminder').val(reminder);

        $('#defaultDiv').hide();
        refreshPreview();
    }

    function refreshPreview() {
        var invoice = createInvoiceModel();
        invoice = calculateAmounts(invoice);
        var template = $("#emailSubject").val();
        $('#emailSubjectDiv').html('<b>' + renderEmailTemplate(template, invoice) + '</b>');
        var template = emailEditor.getHTML();
        $('#emailBodyDiv').html(renderEmailTemplate(template, invoice));
    }

    function dencodeEntities(s) {
		return $("<div/>").html(s).text();
	}

    function onEmailSubjectInput() {
        $('#defaultDiv').show();
        NINJA.formIsChanged = true;
    }

    function onEmailSubjectChange() {
        $("#template\\[subject\\]").val($('#emailSubject').val());
        refreshPreview();
    }

    var emailEditor;

    $(function() {
      emailEditor = new Quill('#templateEditor', {
          modules: {
            'toolbar': { container: '#templateToolbar' },
            'link-tooltip': true
          },
          theme: 'snow'
      });
      emailEditor.setHTML('test');
      emailEditor.on('text-change', function(delta, source) {
          if (source == 'api') {
            return;
          }
          var html = emailEditor.getHTML();
          $("#template\\[body\\]").val(html);
          $('#defaultDiv').show();
          refreshPreview();
          NINJA.formIsChanged = true;
        });

      <?php if(Utils::isPro() && $invoice->isStandard()): ?>
          if (window.defaultTemplate) {
              $('#template_type').val(window.defaultTemplate);
          }
      <?php endif; ?>

      $('.email-subject .input-group-addon').click(function() {
          $('#templateHelpModal').modal('show');
      });
    });

</script>

<style type="text/css">
    #emailModal #preview.tab-pane,
    #emailModal #history.tab-pane {
        margin-top: 20px;
        max-height: 320px;
        overflow-y:auto;
    }

    #emailModal #customize.tab-pane {
        margin-top: 20px;
    }

    #templateEditor {
        max-height: 300px;
    }

    @media  only screen and (min-width : 767px) {
        .modal-dialog {
            width: 690px;
        }
    }
</style>
