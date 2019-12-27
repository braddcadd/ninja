<?php $__env->startSection('form'); ?>
<div class="container">

  <?php echo Former::open($url)
        ->addClass('form-signin')
        ->autocomplete('off')
        ->rules(array(
        'email' => 'required|email',
        'password' => 'required',
        'password_confirmation' => 'required',
  )); ?>


    <?php echo $__env->make('partials.autocomplete_fix', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <h2 class="form-signin-heading"><?php echo e(trans('texts.set_password')); ?></h2>
    <hr class="green">

    <?php if(count($errors->all())): ?>
        <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <!-- if there are login errors, show them here -->
    <?php if(Session::has('warning')): ?>
        <div class="alert alert-warning"><?php echo e(Session::get('warning')); ?></div>
    <?php endif; ?>

    <?php if(Session::has('message')): ?>
        <div class="alert alert-info"><?php echo e(Session::get('message')); ?></div>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger"><?php echo e(Session::get('error')); ?></div>
    <?php endif; ?>

    <input type="hidden" name="token" value="<?php echo e($token); ?>">

    <div onkeyup="validateForm()" onclick="validateForm()" onkeydown="validateForm(event)">
        <?php echo Former::text('email')->placeholder(trans('texts.email'))->raw(); ?>

        <?php echo Former::password('password')->placeholder(trans('texts.password'))->autocomplete('new-password')->raw(); ?>

        <?php echo Former::password('password_confirmation')->placeholder(trans('texts.confirm_password'))->autocomplete('new-password')->raw(); ?>

    </div>

    <div id="passwordStrength" style="font-weight:normal;padding:16px">
        &nbsp;
    </div>

    <p><?php echo Button::success(trans('texts.save'))->large()->submit()->withAttributes(['class' => 'green', 'id' => 'saveButton', 'disabled' => true])->block(); ?></p>


    <?php echo Former::close(); ?>

</div>
<script type="text/javascript">
    $(function() {
        $('#password').focus();
        validateForm();
    })

    function validateForm() {
        var isValid = true;

        if (! $('#email').val()) {
            isValid = false;
        }

        var password = $('#password').val();
        var confirm = $('#password_confirmation').val();

        if (! password || password != confirm || password.length < 8) {
            isValid = false;
        }

        var score = scorePassword(password);
        if (score < 50) {
            isValid = false;
        }

        showPasswordStrength(password, score);

        $('#saveButton').prop('disabled', ! isValid);
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('login', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>