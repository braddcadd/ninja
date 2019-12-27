<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <link href="<?php echo e(asset('css/quill.snow.css')); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo e(asset('js/quill.min.js')); ?>" type="text/javascript"></script>

    <style type="text/css">
        .iframe_url {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##
    <?php echo $__env->make('accounts.nav', ['selected' => ACCOUNT_EMAIL_SETTINGS, 'advanced' => true], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo Former::open()->rules([
            'bcc_email' => 'email',
            'reply_to_email' => 'email',
        ])->addClass('warn-on-exit'); ?>


    <?php echo e(Former::populate($account)); ?>

    <?php echo e(Former::populateField('pdf_email_attachment', intval($account->pdf_email_attachment))); ?>

    <?php echo e(Former::populateField('ubl_email_attachment', intval($account->ubl_email_attachment))); ?>

    <?php echo e(Former::populateField('document_email_attachment', intval($account->document_email_attachment))); ?>

    <?php echo e(Former::populateField('email_design_id', intval($account->account_email_settings->email_design_id))); ?>

    <?php echo e(Former::populateField('enable_email_markup', intval($account->account_email_settings->enable_email_markup))); ?>

    <?php echo e(Former::populateField('email_footer', $account->account_email_settings->email_footer)); ?>

    <?php echo e(Former::populateField('bcc_email', $account->account_email_settings->bcc_email)); ?>

    <?php echo e(Former::populateField('reply_to_email', $account->account_email_settings->reply_to_email)); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.email_settings'); ?></h3>
        </div>
        <div class="panel-body form-padding-right">

            <?php echo Former::text('reply_to_email')
                    ->placeholder(Auth::user()->registered ? Auth::user()->email : ' ')
                    ->help('reply_to_email_help'); ?>


            <?php echo Former::text('bcc_email')
                    ->help('bcc_email_help'); ?>


            &nbsp;

            <?php echo Former::checkbox('pdf_email_attachment')
                    ->text(trans('texts.enable'))
                    ->value(1)
                    ->help(Utils::isNinjaProd() ? '' : (config('pdf.phantomjs.bin_path') ? (config('pdf.phantomjs.cloud_key') ? trans('texts.phantomjs_local_and_cloud') : trans('texts.phantomjs_local')) : trans('texts.phantomjs_help', [
                        'link_phantom' => link_to('https://phantomjscloud.com/', 'phantomjscloud.com', ['target' => '_blank']),
                        'link_docs' => link_to('https://invoice-ninja.readthedocs.io/en/latest/configure.html#phantomjs', 'PhantomJS', ['target' => '_blank'])
                    ])) . ' | ' . link_to('/test_headless', trans('texts.test'), ['target' => '_blank'])); ?>


            <?php echo Former::checkbox('document_email_attachment')
                    ->text(trans('texts.enable'))
                    ->value(1); ?>


            <?php echo Former::checkbox('ubl_email_attachment')
                    ->text(trans('texts.enable'))
                    ->label(sprintf('%s [%s]', trans('texts.ubl_email_attachment'), trans('texts.beta')))
                    ->value(1); ?>



            &nbsp;

            

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.email_design'); ?></h3>
        </div>
        <div class="panel-body form-padding-right">

            <?php echo Former::select('email_design_id')
                        ->appendIcon('question-sign')
                        ->addGroupClass('email_design_id')
                        ->addOption(trans('texts.plain'), EMAIL_DESIGN_PLAIN)
                        ->addOption(trans('texts.light'), EMAIL_DESIGN_LIGHT)
                        ->addOption(trans('texts.dark'), EMAIL_DESIGN_DARK)
                        ->help(trans('texts.email_design_help')); ?>


            &nbsp;

            <?php if(Utils::isNinja()): ?>
                <?php echo Former::checkbox('enable_email_markup')
                        ->text(trans('texts.enable') .
                            '<a href="'.EMAIL_MARKUP_URL.'" target="_blank" title="'.trans('texts.learn_more').'">' . Icon::create('question-sign') . '</a> ')
                        ->help(trans('texts.enable_email_markup_help'))
                        ->value(1); ?>

            <?php endif; ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo trans('texts.signature'); ?></h3>
        </div>
        <div class="panel-body">
            <?php echo Former::textarea('email_footer')->style('display:none')->raw(); ?>

            <div id="signatureEditor" class="form-control" style="min-height:160px" onclick="focusEditor()"></div>
            <div class="pull-right" style="padding-top:10px;text-align:right">
                <?php echo Button::normal(trans('texts.raw'))->withAttributes(['onclick' => 'showRaw()'])->small(); ?>

            </div>
            <?php echo $__env->make('partials/quill_toolbar', ['name' => 'signature'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

    <?php if(Auth::user()->hasFeature(FEATURE_CUSTOM_EMAILS)): ?>
        <center>
            <?php echo Button::success(trans('texts.save'))->large()->submit()->appendIcon(Icon::create('floppy-disk')); ?>

        </center>
    <?php endif; ?>

    <div class="modal fade" id="rawModal" tabindex="-1" role="dialog" aria-labelledby="rawModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:800px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="rawModalLabel"><?php echo e(trans('texts.raw_html')); ?></h4>
                </div>

                <div class="container" style="width: 100%; padding-bottom: 0px !important">
                <div class="panel panel-default">
                <div class="modal-body">
                    <textarea id="raw-textarea" rows="20" style="width:100%"></textarea>
                </div>
                </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
                    <button type="button" onclick="updateRaw()" class="btn btn-success" data-dismiss="modal"><?php echo e(trans('texts.update')); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="designHelpModal" tabindex="-1" role="dialog" aria-labelledby="designHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width:150px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="designHelpModalLabel"><?php echo e(trans('texts.email_designs')); ?></h4>
                </div>

                <div class="container" style="width: 100%; padding-bottom: 0px !important">
                <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row" style="text-align:center">
                        <div class="col-md-4">
                            <h4><?php echo e(trans('texts.plain')); ?></h4><br/>
                            <img src="<?php echo e(asset('images/emails/plain.png')); ?>" class="img-responsive"/>
                        </div>
                        <div class="col-md-4">
                            <h4><?php echo e(trans('texts.light')); ?></h4><br/>
                            <img src="<?php echo e(asset('images/emails/light.png')); ?>" class="img-responsive"/>
                        </div>
                        <div class="col-md-4">
                            <h4><?php echo e(trans('texts.dark')); ?></h4><br/>
                            <img src="<?php echo e(asset('images/emails/dark.png')); ?>" class="img-responsive"/>
                        </div>
                    </div>
                </div>
                </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
                </div>

            </div>
        </div>
    </div>

    <?php echo Former::close(); ?>


    <script type="text/javascript">

        var editor = false;
        $(function() {
            editor = new Quill('#signatureEditor', {
                modules: {
                    'toolbar': { container: '#signatureToolbar' },
                    'link-tooltip': true
                },
                theme: 'snow'
            });
            editor.setHTML($('#email_footer').val());
            editor.on('text-change', function(delta, source) {
                if (source == 'api') {
                    return;
                }
                var html = editor.getHTML();
                $('#email_footer').val(html);
                NINJA.formIsChanged = true;
            });
        });

        function focusEditor() {
            editor.focus();
        }

        function showRaw() {
            var signature = $('#email_footer').val();
            $('#raw-textarea').val(formatXml(signature));
            $('#rawModal').modal('show');
        }

        function updateRaw() {
            var value = $('#raw-textarea').val();
            editor.setHTML(value);
            $('#email_footer').val(value);
        }

        $('.email_design_id .input-group-addon').click(function() {
            $('#designHelpModal').modal('show');
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>