<script type="text/javascript">

$(function() {

    validateSignUp();

    $('#signUpModal').on('shown.bs.modal', function () {
        trackEvent('/account', '/view_sign_up');
        // change the type after page load to prevent errors in Chrome console
        $('#new_password').attr('type', 'password');
        $(['first_name','last_name','email','password']).each(function(i, field) {
            var $input = $('form.signUpForm #new_'+field);
            if (!$input.val()) {
                $input.focus();
                return false;
            }
        });
    })

    <?php if(Auth::check() && !Utils::isNinja() && ! Auth::user()->registered): ?>
    $('#closeSignUpButton').hide();
    showSignUp();
    <?php elseif(Session::get('sign_up') || Input::get('sign_up')): ?>
    showSignUp();
    <?php endif; ?>

    // Ensure terms is checked for sign up form
    <?php if(Auth::check()): ?>
    setSignupEnabled(false);
    $("#terms_checkbox, #privacy_checkbox").change(function() {
        setSignupEnabled($('#terms_checkbox').is(':checked') && $('#privacy_checkbox').is(':checked'));
    });
    <?php endif; ?>

});


function showSignUp() {
    if (location.href.indexOf('/dashboard') == -1) {
        location.href = "<?php echo e(url('/dashboard')); ?>?sign_up=true";
    } else {
        $('#signUpModal').modal('show');
    }
}

function hideSignUp() {
    $('#signUpModal').modal('hide');
}

function setSignupEnabled(enabled) {
    $('.signup-form input[type=text]').prop('disabled', !enabled);
    $('.signup-form input[type=password]').prop('disabled', !enabled);
    if (enabled) {
        $('.signup-form a.btn').removeClass('disabled');
    } else {
        $('.signup-form a.btn').addClass('disabled');
    }
}

function validateSignUp(showError) {
    var isFormValid = true;
    $(['first_name','last_name','email','password']).each(function(i, field) {
        var $input = $('form.signUpForm #new_'+field),
        val = $.trim($input.val());
        var isValid = val && val.length >= (field == 'password' ? 8 : 1);

        if (field == 'password') {
            var score = scorePassword(val);
            if (isValid) {
                isValid = score > 50;
            }

            showPasswordStrength(val, score);
        }

        if (isValid && field == 'email') {
            isValid = isValidEmailAddress(val);
        }
        if (isValid) {
            $input.closest('div.form-group').removeClass('has-error').addClass('has-success');
        } else {
            isFormValid = false;
            $input.closest('div.form-group').removeClass('has-success');
            if (showError) {
                $input.closest('div.form-group').addClass('has-error');
            }
        }
    });

    if (! $('#terms_checkbox').is(':checked') || ! $('#privacy_checkbox').is(':checked')) {
        isFormValid = false;
    }

    $('#saveSignUpButton').prop('disabled', !isFormValid);

    return isFormValid;
}

function validateServerSignUp() {
    if (!validateSignUp(true)) {
        return;
    }

    $('#signUpDiv, #signUpFooter').hide();
    $('#working').show();

    $.ajax({
        type: 'POST',
        url: '<?php echo e(URL::to('signup/validate')); ?>',
        data: 'email=' + $('form.signUpForm #new_email').val(),
        success: function(result) {
            if (result == 'available') {
                submitSignUp();
            } else {
                $('#errorTaken').show();
                $('form.signUpForm #new_email').closest('div.form-group').removeClass('has-success').addClass('has-error');
                $('#signUpDiv, #signUpFooter').show();
                $('#working').hide();
            }
        }
    });
}

function submitSignUp() {
    $.ajax({
        type: 'POST',
        url: '<?php echo e(URL::to('signup/submit')); ?>',
        data: 'new_email=' + encodeURIComponent($('form.signUpForm #new_email').val()) +
        '&new_password=' + encodeURIComponent($('form.signUpForm #new_password').val()) +
        '&new_first_name=' + encodeURIComponent($('form.signUpForm #new_first_name').val()) +
        '&new_last_name=' + encodeURIComponent($('form.signUpForm #new_last_name').val()) +
        '&go_pro=' + $('#go_pro').val(),
        success: function(result) {
            if (result) {
                <?php if(Auth::user()->registered): ?>
                hideSignUp();
                NINJA.formIsChanged = false;
                location.href = "<?php echo e(url('/dashboard')); ?>";
                <?php else: ?>
                handleSignedUp();
                NINJA.isRegistered = true;
                $('#gettingStartedIframe').attr('src', '<?php echo e(str_replace('watch?v=', 'embed/', config('ninja.video_urls.getting_started'))); ?>');
                $('#signUpButton').hide();
                $('#myAccountButton').html(result);
                $('#signUpSuccessDiv, #signUpFooter, #closeSignUpButton').show();
                $('#working, #saveSignUpButton').hide();
                <?php endif; ?>
            }
        }
    });
}

function handleSignedUp() {
    if (isStorageSupported()) {
        localStorage.setItem('guest_key', '');
    }
    fbq('track', 'CompleteRegistration');
    trackEvent('/account', '/signed_up');
}

</script>

<?php if(\Request::is('dashboard')): ?>
<div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo e(Auth::user()->registered ? trans('texts.add_company') : trans('texts.sign_up')); ?></h4>
      </div>

      <div class="container" style="width: 100%; padding-bottom: 0px !important">
      <div class="panel panel-default">
      <div class="panel-body">

      <div id="signUpDiv" onkeyup="validateSignUp()" onclick="validateSignUp()" onkeydown="checkForEnter(event)">
        <?php echo Former::open('signup/submit')->addClass('signUpForm')->autocomplete('off'); ?>


        <?php if(Auth::check() && ! Auth::user()->registered): ?>
            <?php echo Former::populateField('new_first_name', Auth::user()->first_name); ?>

            <?php echo Former::populateField('new_last_name', Auth::user()->last_name); ?>

            <?php echo Former::populateField('new_email', Auth::user()->email); ?>

        <?php endif; ?>

        <div style="display:none">
          <?php echo Former::text('path')->value(Request::path()); ?>

          <?php echo Former::text('go_pro'); ?>

        </div>

        <div class="row signup-form">
            <div class="col-md-12">
                <?php echo Former::checkbox('terms_checkbox')
                    ->label(' ')
                    ->value(1)
                    ->text(trans('texts.agree_to_terms', [
                        'terms' => link_to(config('ninja.terms_of_service_url.' . (Utils::isSelfHost() ? 'selfhost' : 'hosted')), trans('texts.terms_of_service'), ['target' => '_blank']),
                    ]))
                    ->raw(); ?>

                    <?php echo Former::checkbox('privacy_checkbox')
                        ->label(' ')
                        ->value(1)
                        ->text(trans('texts.agree_to_terms', [
                            'terms' => link_to(config('ninja.privacy_policy_url.' . (Utils::isSelfHost() ? 'selfhost' : 'hosted')), trans('texts.privacy_policy'), ['target' => '_blank']),
                        ]))
                        ->raw(); ?>

                <br/>
            </div>
            <br/>&nbsp;<br/>
            <?php if(Utils::isOAuthEnabled() && ! Auth::user()->registered): ?>
                <div class="col-md-5">
                    <?php $__currentLoopData = App\Services\AuthService::$providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(URL::to('auth/' . $provider)); ?>" class="btn btn-primary btn-block"
                        style="padding-top:10px;padding-bottom:10px;margin-top:10px;margin-bottom:10px"
                        id="<?php echo e(strtolower($provider)); ?>LoginButton">
                        <i class="fa fa-<?php echo e(strtolower($provider)); ?>"></i> &nbsp;
                        <?php echo e($provider); ?>

                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="col-md-1">
                    <div style="border-right:thin solid #CCCCCC;height:90px;width:8px;margin-bottom:10px;"></div>
                    <?php echo e(trans('texts.or')); ?>

                    <div style="border-right:thin solid #CCCCCC;height:90px;width:8px;margin-top:10px;"></div>
                </div>
                <div class="col-md-6">
            <?php else: ?>
                <div class="col-md-12">
            <?php endif; ?>
                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 0)); ?>

                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 0)); ?>


                <?php echo Former::text('new_first_name')
                        ->placeholder(trans('texts.first_name'))
                        ->autocomplete('given-name')
                        ->data_lpignore('true')
                        ->label(' '); ?>

                <?php echo Former::text('new_last_name')
                        ->placeholder(trans('texts.last_name'))
                        ->autocomplete('family-name')
                        ->data_lpignore('true')
                        ->label(' '); ?>

                <?php echo Former::text('new_email')
                        ->placeholder(trans('texts.email'))
                        ->autocomplete('email')
                        ->data_lpignore('true')
                        ->label(' '); ?>

                <?php echo Former::text('new_password')
                        ->placeholder(trans('texts.password'))
                        ->autocomplete('new-password')
                        ->data_lpignore('true')
                        ->label(' ')
                        ->help('<span id="passwordStrength">&nbsp;</span>'); ?>


                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 4)); ?>

                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 4)); ?>

            </div>

            <center><div id="errorTaken" style="display:none">&nbsp;<br/><b><?php echo e(trans('texts.email_taken')); ?></b></div></center>

            <div class="col-md-12">
                <div style="padding-top:20px;padding-bottom:10px;">
                    <?php if(Auth::user()->registered): ?>
                        <?php echo trans('texts.email_alias_message'); ?>

                    <?php elseif(Utils::isNinjaProd()): ?>
                        <?php if(Utils::isPro()): ?>
                            <?php echo e(trans('texts.free_year_message')); ?>

                        <?php else: ?>
                            <?php echo e(trans('texts.trial_message')); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php echo Former::close(); ?>


      </div>

      <div style="padding-left:40px;padding-right:40px;display:none;min-height:130px" id="working">
        <h3><?php echo e(trans('texts.working')); ?>...</h3>
        <div class="progress progress-striped active">
          <div class="progress-bar"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>

      <div style="background-color: #fff; padding-right:20px;padding-left:20px; display:none" id="signUpSuccessDiv">
        <h3><?php echo e(trans('texts.success')); ?></h3>
        <br/>
        <?php if(Utils::isNinja()): ?>
          <?php echo e(trans('texts.success_message')); ?>

          <br/>&nbsp;<br/>
        <?php endif; ?>
        <?php if(! auth()->user()->registered): ?>
            <iframe id="gettingStartedIframe" width="100%" height="315"></iframe>
        <?php endif; ?>
      </div>

      </div>
      </div>

      <div class="modal-footer" style="margin-top: 0px;padding-right:0px">
        <span id="signUpFooter">
            <button type="button" class="btn btn-default" id="closeSignUpButton" data-dismiss="modal"><?php echo e(trans('texts.close')); ?> <i class="glyphicon glyphicon-remove-circle"></i></button>
            <button type="button" class="btn btn-primary" id="saveSignUpButton" onclick="validateServerSignUp()" disabled><?php echo e(trans('texts.save')); ?> <i class="glyphicon glyphicon-floppy-disk"></i></button>
        </span>
      </div>
    </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.logout')); ?></h4>
      </div>

      <div class="container" style="width: 100%; padding-bottom: 0px !important">
      <div class="panel panel-default">
      <div class="panel-body">
        <h3><?php echo e(trans('texts.are_you_sure')); ?></h3><br/>
        <p><?php echo e(trans('texts.erase_data')); ?></p>
      </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.cancel')); ?></button>
        <button type="button" class="btn btn-danger" onclick="logout(true)"><?php echo e(trans('texts.logout_and_delete')); ?></button>
      </div>
    </div>
  </div>
</div>
