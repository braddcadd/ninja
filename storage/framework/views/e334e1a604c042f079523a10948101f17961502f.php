<?php $__env->startSection('head_css'); ?>
    <link href="<?php echo e(asset('css/built.css')); ?>?no_cache=<?php echo e(NINJA_VERSION); ?>" rel="stylesheet" type="text/css"/>

    <?php if(Utils::isNinjaDev()): ?>
        <style type="text/css">
            .nav-footer {
                <?php if(env('TRAVIS')): ?>
                    background-color: #FF0000 !important;
                <?php elseif(config('mail.driver') == 'log' && ! config('services.postmark')): ?>
                    background-color: #50C878 !important;
                <?php else: ?>
                    background-color: #FD6A02 !important;
                <?php endif; ?>
            }
        </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>

<script type="text/javascript">

  function checkForEnter(event)
  {
    if (event.keyCode === 13){
      event.preventDefault();
      validateServerSignUp();
      return false;
    }
  }

  function logout(force)
  {
    if (force) {
      NINJA.formIsChanged = false;
    }

    if (force || NINJA.isRegistered) {
      window.location = '<?php echo e(URL::to('logout')); ?>' + (force ? '?force_logout=true' : '');
    } else {
      $('#logoutModal').modal('show');
    }
  }

  function hideMessage() {
    $('.alert-info').fadeOut();
    $.get('/hide_message', function(response) {
      console.log('Reponse: %s', response);
    });
  }

  function openTimeTracker() {
      var width = 1060;
      var height = 700;
      var left = (screen.width/2)-(width/4);
      var top = (screen.height/2)-(height/1.5);
      window.open("<?php echo e(url('/time_tracker')); ?>", "time-tracker", "width="+width+",height="+height+",scrollbars=no,toolbar=no,screenx="+left+",screeny="+top+",location=no,titlebar=no,directories=no,status=no,menubar=no");
  }

  window.loadedSearchData = false;
  function onSearchBlur() {
      $('#search').typeahead('val', '');
  }

  function onSearchFocus() {
    $('#search-form').show();

    if (!window.loadedSearchData) {
        window.loadedSearchData = true;
        trackEvent('/activity', '/search');
        var request = $.get('<?php echo e(URL::route('get_search_data')); ?>', function(data) {
          $('#search').typeahead({
            hint: true,
            highlight: true,
          }
          <?php if(Auth::check() && Auth::user()->account->customLabel('client1')): ?>
          ,{
            name: 'data',
            limit: 3,
            display: 'value',
            source: searchData(data['<?php echo e(Auth::user()->account->present()->customLabel('client1')); ?>'], 'tokens'),
            templates: {
              header: '&nbsp;<span style="font-weight:600;font-size:16px"><?php echo e(Auth::user()->account->present()->customLabel('client1')); ?></span>'
            }
          }
          <?php endif; ?>
          <?php if(Auth::check() && Auth::user()->account->customLabel('client2')): ?>
          ,{
            name: 'data',
            limit: 3,
            display: 'value',
            source: searchData(data['<?php echo e(Auth::user()->account->present()->customLabel('client2')); ?>'], 'tokens'),
            templates: {
              header: '&nbsp;<span style="font-weight:600;font-size:16px"><?php echo e(Auth::user()->account->present()->customLabel('client2')); ?></span>'
            }
          }
          <?php endif; ?>
          <?php if(Auth::check() && Auth::user()->account->customLabel('invoice_text1')): ?>
          ,{
            name: 'data',
            limit: 3,
            display: 'value',
            source: searchData(data['<?php echo e(Auth::user()->account->present()->customLabel('invoice_text1')); ?>'], 'tokens'),
            templates: {
              header: '&nbsp;<span style="font-weight:600;font-size:16px"><?php echo e(Auth::user()->account->present()->customLabel('invoice_text1')); ?></span>'
            }
          }
          <?php endif; ?>
          <?php if(Auth::check() && Auth::user()->account->customLabel('invoice_text2')): ?>
          ,{
            name: 'data',
            limit: 3,
            display: 'value',
            source: searchData(data['<?php echo e(Auth::user()->account->present()->customLabel('invoice_text2')); ?>'], 'tokens'),
            templates: {
              header: '&nbsp;<span style="font-weight:600;font-size:16px"><?php echo e(Auth::user()->account->present()->customLabel('invoice_text2')); ?></span>'
            }
          }
          <?php endif; ?>
          <?php $__currentLoopData = ['clients', 'contacts', 'invoices', 'quotes', 'navigation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          ,{
            name: 'data',
            limit: 3,
            display: 'value',
            source: searchData(data['<?php echo e($type); ?>'], 'tokens', true),
            templates: {
              header: '&nbsp;<span style="font-weight:600;font-size:16px"><?php echo e(trans("texts.{$type}")); ?></span>'
            }
          }
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          ).on('typeahead:selected', function(element, datum, name) {
            window.location = datum.url;
          }).focus();
        });

        request.error(function(httpObj, textStatus) {
            // if the session has expried show login page
            if (httpObj.status == 401) {
                location.reload();
            }
        });
    }
  }

  $(function() {
    // auto-logout after 8 hours
    window.setTimeout(function() {
        window.location = '<?php echo e(URL::to('/logout?reason=inactive')); ?>';
    }, <?php echo e(1000 * env('AUTO_LOGOUT_SECONDS', (60 * 60 * 8))); ?>);

    // auto-hide status alerts
    window.setTimeout(function() {
        $(".alert-hide").fadeOut();
    }, 3000);

    /* Set the defaults for Bootstrap datepicker */
    $.extend(true, $.fn.datepicker.defaults, {
        //language: '<?php echo e($appLanguage); ?>', // causes problems with some languages (ie, fr_CA) if the date includes strings (ie, July 31, 2016)
        weekStart: <?php echo e(Session::get('start_of_week')); ?>

    });

    if (isStorageSupported()) {
      <?php if(Auth::check() && !Auth::user()->registered): ?>
        localStorage.setItem('guest_key', '<?php echo e(Auth::user()->password); ?>');
      <?php endif; ?>
    }

    $('ul.navbar-settings, ul.navbar-search').hover(function () {
        if ($('.user-accounts').css('display') == 'block') {
            $('.user-accounts').dropdown('toggle');
        }
    });

    <?php echo $__env->yieldContent('onReady'); ?>

    <?php if(Input::has('focus')): ?>
        $('#<?php echo e(Input::get('focus')); ?>').focus();
    <?php endif; ?>

    // Focus the search input if the user clicks forward slash
    $('#search').focusin(onSearchFocus);
    $('#search').blur(onSearchBlur);

    // manage sidebar state
    function setupSidebar(side) {
        $("#" + side + "-menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled-" + side);

            var toggled = $("#wrapper").hasClass("toggled-" + side) ? '1' : '0';
            $.post('<?php echo e(url('save_sidebar_state')); ?>?show_' + side + '=' + toggled);

            if (isStorageSupported()) {
                localStorage.setItem('show_' + side + '_sidebar', toggled);
            }
        });

        if (isStorageSupported()) {
            var storage = localStorage.getItem('show_' + side + '_sidebar') || '0';
            var toggled = $("#wrapper").hasClass("toggled-" + side) ? '1' : '0';

            if (storage != toggled) {
                setTimeout(function() {
                    $("#wrapper").toggleClass("toggled-" + side);
                    $.post('<?php echo e(url('save_sidebar_state')); ?>?show_' + side + '=' + storage);
                }, 200);
            }
        }
    }

    <?php if( ! Utils::isTravis()): ?>
        setupSidebar('left');
        setupSidebar('right');
    <?php endif; ?>

    // auto select focused nav-tab
    if (window.location.hash) {
        setTimeout(function() {
            $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
        }, 1);
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if (isStorageSupported() && /\/settings\//.test(location.href)) {
            var target = $(e.target).attr("href") // activated tab
            if (history.pushState) {
                history.pushState(null, null, target);
            }
            if (isStorageSupported()) {
                localStorage.setItem('last:settings_page', location.href.replace(location.hash, ''));
            }
        }
    });

    // set timeout onDomReady
    setTimeout(delayedFragmentTargetOffset, 500);

    // add scroll offset to fragment target (if there is one)
    function delayedFragmentTargetOffset(){
        var offset = $(':target').offset();
        if (offset) {
            var scrollto = offset.top - 180; // minus fixed header height
            $('html, body').animate({scrollTop:scrollto}, 0);
        }
    }

  });

</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

<?php if(Utils::isNinjaProd() && ! Request::is('settings/account_management')): ?>
  <?php echo $__env->make('partials.upgrade_modal', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="height:60px;">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="#" id="left-menu-toggle" class="menu-toggle" title="<?php echo e(trans('texts.toggle_navigation')); ?>">
          <div class="navbar-brand">
                <i class="fa fa-bars hide-phone" style="width:32px;padding-top:2px;float:left"></i>
                
                <img src="<?php echo e(asset('images/invoiceninja-logo.png')); ?>" width="193" height="25" style="float:left"/>
          </div>
      </a>
    </div>

    <a id="right-menu-toggle" class="menu-toggle hide-phone pull-right" title="<?php echo e(trans('texts.toggle_history')); ?>" style="cursor:pointer">
      <div class="fa fa-bars"></div>
    </a>

    <div class="collapse navbar-collapse" id="navbar-collapse-1">
      <div class="navbar-form navbar-right">

        <?php if(Auth::check()): ?>
          <?php if(!Auth::user()->registered): ?>
              <?php if(!Auth::user()->confirmed): ?>
                <?php echo Button::success(trans('texts.sign_up'))->withAttributes(array('id' => 'signUpButton', 'onclick' => 'showSignUp()', 'style' => 'max-width:100px;;overflow:hidden'))->small(); ?> &nbsp;
              <?php endif; ?>
          <?php elseif(Utils::isNinjaProd() && (!Auth::user()->isPro() || Auth::user()->isTrial())): ?>
            <?php if(Auth::user()->account->company->hasActivePromo()): ?>
                <?php echo Button::warning(trans('texts.plan_upgrade'))->withAttributes(array('onclick' => 'showUpgradeModal()', 'style' => 'max-width:100px;overflow:hidden'))->small(); ?> &nbsp;
            <?php else: ?>
                <?php echo Button::success(trans('texts.plan_upgrade'))->withAttributes(array('onclick' => 'showUpgradeModal()', 'style' => 'max-width:100px;overflow:hidden'))->small(); ?> &nbsp;
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>

        <div class="btn-group user-dropdown">
          <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <div id="myAccountButton" class="ellipsis" style="max-width:<?php echo e(Utils::hasFeature(FEATURE_USERS) ? '130' : '100'); ?>px;">
                <?php if(session(SESSION_USER_ACCOUNTS) && count(session(SESSION_USER_ACCOUNTS))): ?>
                    <?php echo e(Auth::user()->account->getDisplayName()); ?>

                <?php else: ?>
                    <?php echo e(Auth::user()->getDisplayName()); ?>

                <?php endif; ?>
              <span class="caret"></span>
            </div>
          </button>
          <ul class="dropdown-menu user-accounts">
            <?php if(session(SESSION_USER_ACCOUNTS)): ?>
                <?php $__currentLoopData = session(SESSION_USER_ACCOUNTS); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->user_id == Auth::user()->id): ?>
                        <?php echo $__env->make('user_account', [
                            'user_account_id' => $item->id,
                            'user_id' => $item->user_id,
                            'account_name' => $item->account_name,
                            'user_name' => $item->user_name,
                            'logo_url' => isset($item->logo_url) ? $item->logo_url : "",
                            'selected' => true,
                        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = session(SESSION_USER_ACCOUNTS); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->user_id != Auth::user()->id): ?>
                        <?php echo $__env->make('user_account', [
                            'user_account_id' => $item->id,
                            'user_id' => $item->user_id,
                            'account_name' => $item->account_name,
                            'user_name' => $item->user_name,
                            'logo_url' => isset($item->logo_url) ? $item->logo_url : "",
                            'selected' => false,
                        ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php echo $__env->make('user_account', [
                    'account_name' => Auth::user()->account->name ?: trans('texts.untitled'),
                    'user_name' => Auth::user()->getDisplayName(),
                    'logo_url' => Auth::user()->account->getLogoURL(),
                    'selected' => true,
                ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            <li class="divider"></li>
            <?php if(Utils::isAdmin() && Auth::user()->confirmed && Utils::getResllerType() != RESELLER_ACCOUNT_COUNT): ?>
              <?php if(!session(SESSION_USER_ACCOUNTS) || count(session(SESSION_USER_ACCOUNTS)) < 5): ?>
                  <li><?php echo link_to('#', trans('texts.add_company'), ['onclick' => 'showSignUp()']); ?></li>
              <?php endif; ?>
            <?php endif; ?>
            <li><?php echo link_to('#', trans('texts.logout'), array('onclick'=>'logout()')); ?></li>
          </ul>
        </div>

      </div>

      <?php echo Former::open('/handle_command')->id('search-form')->addClass('navbar-form navbar-right')->role('search'); ?>

        <div class="form-group has-feedback">
          <input type="text" name="command" id="search" style="width: 280px;padding-top:0px;padding-bottom:0px;margin-right:12px;"
            class="form-control" placeholder="<?php echo e(trans('texts.search') . ': ' . trans('texts.search_hotkey')); ?>"/>
            <?php if(env('SPEECH_ENABLED')): ?>
                <?php echo $__env->make('partials/speech_recognition', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
        </div>
      <?php echo Former::close(); ?>


      <?php if(false && Utils::isAdmin()): ?>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
           <?php $__env->startSection('self-updater'); ?>
            <a href="<?php echo e(URL::to('self-update')); ?>" class="dropdown-toggle">
              <span class="glyphicon glyphicon-cloud-download" title="<?php echo e(trans('texts.update_invoiceninja_title')); ?>"></span>
            </a>
          <?php echo $__env->yieldSection(); ?>
        </li>
      </ul>
      <?php endif; ?>

      <ul class="nav navbar-nav hide-non-phone" style="font-weight: bold">
        <?php $__currentLoopData = [
            'dashboard' => false,
            'clients' => false,
            'products' => false,
            'invoices' => false,
            'payments' => false,
            'recurring_invoices' => 'recurring',
            'credits' => false,
            'quotes' => false,
            'proposals' => false,
            'projects' => false,
            'tasks' => false,
            'expenses' => false,
            'vendors' => false,
            'reports' => false,
            'settings' => false,
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if(!Auth::user()->account->isModuleEnabled(substr($key, 0, -1))): ?>
                  <?php echo e(''); ?>

              <?php elseif(in_array($key, ['dashboard', 'settings'])
                || Auth::user()->can('view', substr($key, 0, -1))
                || Auth::user()->can('create', substr($key, 0, -1))): ?>
                  <?php echo Form::nav_link($key, $value ?: $key); ?>

              <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div><!-- /.navbar-collapse -->

</nav>

<div id="wrapper" class='<?php echo e(session(SESSION_LEFT_SIDEBAR) ? 'toggled-left' : ''); ?> <?php echo e(session(SESSION_RIGHT_SIDEBAR, true) ? 'toggled-right' : ''); ?>'>

    <!-- Sidebar -->
    <div id="left-sidebar-wrapper" class="hide-phone">
        <ul class="sidebar-nav <?php echo e(Auth::user()->dark_mode ? 'sidebar-nav-dark' : 'sidebar-nav-light'); ?>">
            <?php $__currentLoopData = [
                'dashboard',
                'clients',
                'products',
                'invoices',
                'payments',
                'recurring_invoices',
                'credits',
                'quotes',
                'proposals',
                'projects',
                'tasks',
                'expenses',
                'vendors',
                'tickets',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!Auth::user()->account->isModuleEnabled(substr($option, 0, -1))): ?>
                    <?php echo e(''); ?>

                <?php else: ?>
                    <?php echo $__env->make('partials.navigation_option', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if( ! Utils::isNinjaProd()): ?>
                <?php $__currentLoopData = Module::collections(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->renderWhen(empty($module->get('no-sidebar')) || $module->get('no-sidebar') != '1', 'partials.navigation_option', [
                        'option' => $module->get('base-route', $module->getAlias()),
                        'icon' => $module->get('icon', 'th-large'),
                        'moduleName' => $module->getLowerName(),
                    ], array_except(get_defined_vars(), array('__data', '__path'))); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(Auth::user()->hasPermission('view_reports')): ?>
                <?php echo $__env->make('partials.navigation_option', ['option' => 'reports'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endif; ?>
            <?php echo $__env->make('partials.navigation_option', ['option' => 'settings'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <li style="width:100%;">
                <div class="nav-footer">
                    <?php if(Auth::user()->registered): ?>
                        <a href="javascript:showContactUs()" title="<?php echo e(trans('texts.contact_us')); ?>">
                            <i class="fa fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(url(NINJA_FORUM_URL)); ?>" target="_blank" title="<?php echo e(trans('texts.support_forum')); ?>">
                        <i class="fa fa-list-ul"></i>
                    </a>
                    <a href="javascript:showKeyboardShortcuts()" title="<?php echo e(trans('texts.help')); ?>">
                        <i class="fa fa-question-circle"></i>
                    </a>
                    <a href="<?php echo e(url(SOCIAL_LINK_FACEBOOK)); ?>" target="_blank" title="Facebook">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                    <a href="<?php echo e(url(SOCIAL_LINK_TWITTER)); ?>" target="_blank" title="Twitter">
                        <i class="fa fa-twitter-square"></i>
                    </a>
                    <a href="<?php echo e(url(SOCIAL_LINK_GITHUB)); ?>" target="_blank" title="GitHub">
                        <i class="fa fa-github-square"></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <!-- /#left-sidebar-wrapper -->

    <div id="right-sidebar-wrapper" class="hide-phone" style="overflow-y:hidden">
        <ul class="sidebar-nav <?php echo e(Auth::user()->dark_mode ? 'sidebar-nav-dark' : 'sidebar-nav-light'); ?>">
            <?php echo \App\Libraries\HistoryUtils::renderHtml(Auth::user()->account_id); ?>

        </ul>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid">

          <?php echo $__env->make('partials.warn_session', ['redirectTo' => '/dashboard'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

          <?php if(Session::has('warning')): ?>
            <div class="alert alert-warning"><?php echo Session::get('warning'); ?></div>
          <?php elseif(env('WARNING_MESSAGE')): ?>
            <div class="alert alert-warning"><?php echo env('WARNING_MESSAGE'); ?></div>
          <?php endif; ?>

          <?php if(Session::has('message')): ?>
            <div class="alert alert-info alert-hide" style="z-index:9999">
              <?php echo e(Session::get('message')); ?>

            </div>
          <?php elseif(Session::has('news_feed_message')): ?>
            <div class="alert alert-info">
              <?php echo Session::get('news_feed_message'); ?>

              <a href="#" onclick="hideMessage()" class="pull-right"><?php echo e(trans('texts.hide')); ?></a>
            </div>
          <?php endif; ?>

          <?php if(Session::has('error')): ?>
              <div class="alert alert-danger"><?php echo Session::get('error'); ?></div>
          <?php endif; ?>

          <div class="pull-right">
              <?php echo $__env->yieldContent('top-right'); ?>
          </div>

          <?php if(!isset($showBreadcrumbs) || $showBreadcrumbs): ?>
            <?php echo Form::breadcrumbs((! empty($entity) && $entity->exists && !$entity->deleted_at) ? $entity->present()->statusLabel : false); ?>

          <?php endif; ?>

          <?php echo $__env->yieldContent('content'); ?>
          <br/>
          <div class="row">
            <div class="col-md-12">

              <?php if(Utils::isNinjaProd()): ?>
                <?php if(Auth::check() && Auth::user()->hasActivePromo()): ?>
                    <?php echo trans('texts.promotion_footer', [
                            'link' => '<a href="javascript:showUpgradeModal()">' . trans('texts.click_here') . '</a>'
                        ]); ?>

                <?php elseif(Auth::check() && Auth::user()->isTrial()): ?>
                  <?php echo trans(Auth::user()->account->getCountTrialDaysLeft() == 0 ? 'texts.trial_footer_last_day' : 'texts.trial_footer', [
                          'count' => Auth::user()->account->getCountTrialDaysLeft(),
                          'link' => '<a href="javascript:showUpgradeModal()">' . trans('texts.click_here') . '</a>'
                      ]); ?>

                <?php endif; ?>
              <?php else: ?>
                <?php echo $__env->make('partials.white_label', ['company' => Auth::user()->account->company], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
              <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(Utils::isSelfHost()): ?>
        <?php echo $__env->yieldPushContent('component_scripts'); ?>
    <?php endif; ?>
    <!-- /#page-content-wrapper -->
</div>

<?php echo $__env->make('partials.contact_us', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('partials.sign_up', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('partials.keyboard_shortcuts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php if(auth()->check() && auth()->user()->registered && ! auth()->user()->hasAcceptedLatestTerms()): ?>
    <?php echo $__env->make('partials.accept_terms', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

</div>

<p>&nbsp;</p>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>