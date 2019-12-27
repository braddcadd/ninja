<?php $__env->startSection('form'); ?>

    <?php echo $__env->make('partials.warn_session', ['redirectTo' => '/logout?reason=inactive'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="container">

        <?php echo Former::open('login')
                ->rules(['email' => 'required|email', 'password' => 'required'])
                ->addClass('form-signin'); ?>


        <h2 class="form-signin-heading">
            <?php if(strstr(session('url.intended'), 'time_tracker')): ?>
                <?php echo e(trans('texts.time_tracker_login')); ?>

            <?php else: ?>
                <?php echo e(trans('texts.account_login')); ?>

            <?php endif; ?>
        </h2>
        <hr class="green">

        <?php if(count($errors->all())): ?>
            <div class="alert alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if(Session::has('warning')): ?>
            <div class="alert alert-warning"><?php echo Session::get('warning'); ?></div>
        <?php endif; ?>

        <?php if(Session::has('message')): ?>
            <div class="alert alert-info"><?php echo Session::get('message'); ?></div>
        <?php endif; ?>

        <?php if(Session::has('error')): ?>
            <div class="alert alert-danger">
                <li><?php echo Session::get('error'); ?></li>
            </div>
        <?php endif; ?>

        <?php if(env('REMEMBER_ME_ENABLED')): ?>
            <?php echo e(Former::populateField('remember', 'true')); ?>

            <?php echo Former::hidden('remember')->raw(); ?>

        <?php endif; ?>

        <div>
            <?php echo Former::text('email')->placeholder(trans('texts.email_address'))->raw(); ?>

            <?php echo Former::password('password')->placeholder(trans('texts.password'))->raw(); ?>

        </div>

        <?php echo Button::success(trans('texts.login'))
                    ->withAttributes(['id' => 'loginButton', 'class' => 'green'])
                    ->large()->submit()->block(); ?>


        <?php if(Utils::isOAuthEnabled()): ?>
            <div class="row existing-accounts">
                <p><?php echo e(trans('texts.login_or_existing')); ?></p>
                <?php $__currentLoopData = App\Services\AuthService::$providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-xs-6">
                        <a href="<?php echo e(URL::to('auth/' . $provider)); ?>" class="btn btn-primary btn-lg" title="<?php echo e($provider); ?>"
                           id="<?php echo e(strtolower($provider)); ?>LoginButton">
                            <?php if($provider == SOCIAL_GITHUB): ?>
                                <i class="fa fa-github-alt"></i>
                            <?php else: ?>
                                <i class="fa fa-<?php echo e(strtolower($provider)); ?>"></i>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="row meta">
            <?php if(Utils::isWhiteLabel()): ?>
                <center>
                    <br/><?php echo link_to('/recover_password', trans('texts.recover_password')); ?>

                </center>
            <?php else: ?>
                <div class="col-md-7 col-sm-12">
                    <?php echo link_to('/recover_password', trans('texts.recover_password')); ?>

                </div>
                <div class="col-md-5 col-sm-12">
                    <?php if(Utils::isTimeTracker()): ?>
                        <?php echo link_to('#', trans('texts.self_host_login'), ['onclick' => 'setSelfHostUrl()']); ?>

                    <?php else: ?>
                        <?php echo link_to(NINJA_WEB_URL.'/knowledge-base/', trans('texts.knowledge_base'), ['target' => '_blank']); ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php echo Former::close(); ?>


        <?php if(Utils::allowNewAccounts() && ! strstr(session('url.intended'), 'time_tracker')): ?>
            <div class="row sign-up">
                <div class="col-md-3 col-md-offset-3 col-xs-12">
                    <h3><?php echo e(trans('texts.not_a_member_yet')); ?></h3>
                    <p><?php echo e(trans('texts.login_create_an_account')); ?></p>
                </div>
                <div class="col-md-3 col-xs-12">
                    <?php echo Button::primary(trans('texts.sign_up_now'))->asLinkTo(URL::to('/invoice_now?sign_up=true'))->withAttributes(['class' => 'blue'])->large()->submit()->block(); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>


    <script type="text/javascript">
        $(function() {
            if ($('#email').val()) {
                $('#password').focus();
            } else {
                $('#email').focus();
            }

            <?php if(Utils::isTimeTracker()): ?>
                if (isStorageSupported()) {
                    var selfHostUrl = localStorage.getItem('last:time_tracker:url');
                    if (selfHostUrl) {
                        location.href = selfHostUrl;
                        return;
                    }
                    $('#email').change(function() {
                        localStorage.setItem('last:time_tracker:email', $('#email').val());
                    })
                    var email = localStorage.getItem('last:time_tracker:email');
                    if (email) {
                        $('#email').val(email);
                        $('#password').focus();
                    }
                }
            <?php endif; ?>
        })

        <?php if(Utils::isTimeTracker()): ?>
            function setSelfHostUrl() {
                if (! isStorageSupported()) {
                    swal("<?php echo e(trans('texts.local_storage_required')); ?>");
                    return;
                }
                swal({
                    title: "<?php echo e(trans('texts.set_self_hoat_url')); ?>",
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                }).then(function (value) {
                    if (! value || value.indexOf('http') !== 0) {
                        swal("<?php echo e(trans('texts.invalid_url')); ?>")
                        return;
                    }
                    value = value.replace(/\/+$/, '') + '/time_tracker';
                    localStorage.setItem('last:time_tracker:url', value);
                    location.reload();
                }).catch(swal.noop);
            }
        <?php endif; ?>

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>