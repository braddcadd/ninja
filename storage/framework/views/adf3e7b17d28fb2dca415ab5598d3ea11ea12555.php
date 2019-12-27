<?php $__env->startSection('head'); ?>
    <?php if(!empty($clientFontUrl)): ?>
        <link href="<?php echo e($clientFontUrl); ?>" rel="stylesheet" type="text/css">
    <?php endif; ?>
    <link href="<?php echo e(asset('css/built.public.css')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" rel="stylesheet" type="text/css"/>
    <style type="text/css"><?php echo !empty($account)?$account->clientViewCSS():''; ?></style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

<?php echo Form::open(array('url' => 'get_started', 'id' => 'startForm')); ?>

<?php echo Form::hidden('guest_key'); ?>

<?php echo Form::hidden('sign_up', Input::get('sign_up')); ?>

<?php echo Form::hidden('redirect_to', Input::get('redirect_to')); ?>

<?php echo Form::close(); ?>


<script>
    if (isStorageSupported()) {
        $('[name="guest_key"]').val(localStorage.getItem('guest_key'));
    }

    function isStorageSupported() {
        if ('localStorage' in window && window['localStorage'] !== null) {
          var storage = window.localStorage;
      } else {
          return false;
      }
      var testKey = 'test';
      try {
          storage.setItem(testKey, '1');
          storage.removeItem(testKey);
          return true;
      } catch (error) {
          return false;
      }
  }

  function getStarted() {
    $('#startForm').submit();
    return false;
  }

  $(function() {
      function positionFooter() {
          // check that the footer appears at the bottom of the screen
          var height = $(window).height() - ($('#header').height() + $('#footer').height());
          if ($('#mainContent').height() < height) {
              $('#mainContent').css('min-height', height);
          }
      }

      if (inIframe()) {
          $('#footer').hide();
      } else {
          positionFooter();
          $(window).resize(positionFooter);
      }
  })

</script>


<div id="header">
    <nav class="navbar navbar-top navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php if(empty($account) || !$account->hasFeature(FEATURE_WHITE_LABEL)): ?>
                    
                    <a class="navbar-brand" href="<?php echo e(URL::to(NINJA_WEB_URL)); ?>" target="_blank"><img
                                src="<?php echo e(asset('images/invoiceninja-logo.png')); ?>" style="height:27px"></a>
                <?php endif; ?>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                <?php if(! empty($account) && $account->enable_client_portal): ?>
                    <?php if(isset($account) && $account->enable_client_portal_dashboard): ?>
                        <li <?php echo Request::is('*client/dashboard*') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/dashboard', trans('texts.dashboard') ); ?>

                        </li>
                    <?php endif; ?>
                    <?php if(request()->contact && request()->contact->client->show_tasks_in_portal): ?>
                        <li <?php echo Request::is('*client/tasks') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/tasks', trans('texts.tasks') ); ?>

                        </li>
                    <?php endif; ?>
                    <?php if(isset($hasQuotes) && $hasQuotes): ?>
                        <li <?php echo Request::is('*client/quotes') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/quotes', trans('texts.quotes') ); ?>

                        </li>
                    <?php endif; ?>
                    <li <?php echo Request::is('*client/invoices') ? 'class="active"' : ''; ?>>
                        <?php echo link_to('/client/invoices', trans('texts.invoices') ); ?>

                    </li>
                    <?php if(!empty($account)
                        && $account->hasFeature(FEATURE_DOCUMENTS)
                        && (isset($hasDocuments) && $hasDocuments)): ?>
                        <li <?php echo Request::is('*client/documents') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/documents', trans('texts.documents') ); ?>

                        </li>
                    <?php endif; ?>
                    <li <?php echo Request::is('*client/payments') ? 'class="active"' : ''; ?>>
                        <?php echo link_to('/client/payments', trans('texts.payments') ); ?>

                    </li>
                    <?php if(isset($hasCredits) && $hasCredits): ?>
                        <li <?php echo Request::is('*client/credits') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/credits', trans('texts.credits') ); ?>

                        </li>
                    <?php endif; ?>
                    <?php if(isset($hasPaymentMethods) && $hasPaymentMethods): ?>
                        <li <?php echo Request::is('*client/payment_methods') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/payment_methods', trans('texts.payment_methods') ); ?>

                        </li>
                    <?php endif; ?>
                    <?php if(isset($account) && $account->enable_client_portal_dashboard): ?>
                        <li <?php echo Request::is('*client/tickets*') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('client/tickets', trans('texts.tickets') ); ?>

                        </li>
                    <?php endif; ?>
                    <?php if($account->enable_portal_password && request()->contact->password): ?>
                        <li>
                            <?php echo link_to('/client/logout?account_key=' . $account->account_key, trans('texts.logout')); ?>

                        </li>
                    <?php endif; ?>
                <?php elseif(! empty($account)): ?>
                    <?php if(isset($hasPaymentMethods) && $hasPaymentMethods): ?>
                        <li <?php echo Request::is('*client/payment_methods') ? 'class="active"' : ''; ?>>
                            <?php echo link_to('/client/payment_methods', trans('texts.payment_methods') ); ?>

                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container">

      <?php echo $__env->make('partials.warn_session', ['redirectTo' => '/'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

      <?php if(Session::has('warning')): ?>
      <div class="alert alert-warning"><?php echo Session::get('warning'); ?></div>
      <?php endif; ?>

      <?php if(Session::has('message')): ?>
      <div class="alert alert-info"><?php echo Session::get('message'); ?></div>
      <?php endif; ?>

      <?php if(Session::has('error')): ?>
      <div class="alert alert-danger"><?php echo Session::get('error'); ?></div>
      <?php endif; ?>
  </div>
</div>

<div id="mainContent" class="container">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<?php if(Utils::isSelfHost() && !empty($account) && !empty($account->clientViewJS())): ?>
  <?php echo $account->clientViewJS(); ?>

<?php endif; ?>

<footer id="footer" role="contentinfo">
    <div class="top">
        <div class="wrap">
            <?php if(empty($account) || !$account->hasFeature(FEATURE_WHITE_LABEL)): ?>
            <div id="footer-menu" class="menu-wrap">
                <ul id="menu-footer-menu" class="menu">
                    <li id="menu-item-31" class="menu-item-31">
                        <?php echo link_to('#', 'Facebook', ['target' => '_blank', 'onclick' => 'openUrl("https://www.facebook.com/invoiceninja", "/footer/social/facebook")']); ?>

                    </li>
                    <li id="menu-item-32" class="menu-item-32">
                        <?php echo link_to('#', 'Twitter', ['target' => '_blank', 'onclick' => 'openUrl("https://twitter.com/invoiceninja", "/footer/social/twitter")']); ?>

                    </li>
                    <li id="menu-item-33" class="menu-item-33">
                        <?php echo link_to('#', 'GitHub', ['target' => '_blank', 'onclick' => 'openUrl("https://github.com/hillelcoren/invoice-ninja", "/footer/social/github")']); ?>

                    </li>
                    <li id="menu-item-30" class="menu-item-30">
                        <?php echo link_to(NINJA_WEB_URL . '/contact', trans('texts.contact')); ?>

                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div><!-- .wrap -->
    </div><!-- .top -->

    <div class="bottom">
        <div class="wrap">
            <?php if(empty($account) || !$account->hasFeature(FEATURE_WHITE_LABEL)): ?>
                <div class="copy">Copyright &copy;<?php echo e(date('Y')); ?> <a href="<?php echo e(NINJA_WEB_URL); ?>" target="_blank">Invoice Ninja</a>. All rights reserved.</div>
            <?php endif; ?>
        </div><!-- .wrap -->
    </div><!-- .bottom -->
</footer><!-- #footer -->


<!--<div class="fb-follow" data-href="https://www.facebook.com/invoiceninja" data-colorscheme="light" data-layout="button" data-show-faces="false"></div>-->

      <!--<a href="https://twitter.com/invoiceninja" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @invoiceninja</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>-->
      <!--<div class="fb-like" data-href="https://www.invoiceninja.com" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>          -->
      <!--
      <div class="fb-share-button" data-href="https://www.invoiceninja.com/" data-type="button"></div>
      &nbsp;

      <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://www.invoiceninja.com/" data-via="invoiceninja" data-related="hillelcoren" data-count="none" data-text="Free online invoicing">Tweet</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
      &nbsp;
      <div class="g-plusone" data-size="medium" data-width="300" data-href="https://www.invoiceninja.com/" data-annotation="none" data-count="false" data-recommendations="false"></div>

      <script type="text/javascript">
        (function() {
          var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
          po.src = 'https://apis.google.com/js/platform.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
      </script>
      &nbsp;
  -->

      <!--
      <script src="//platform.linkedin.com/in.js" type="text/javascript">
        lang: en_US
      </script>
      <script type="IN/Share" data-url="https://www.invoiceninja.com/"></script>
  -->

  <!--<iframe src="http://ghbtns.com/github-btn.html?user=hillelcoren&repo=invoice-ninja&type=watch" allowtransparency="true" frameborder="0" scrolling="0" width="62" height="20"></iframe>-->



  <?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>