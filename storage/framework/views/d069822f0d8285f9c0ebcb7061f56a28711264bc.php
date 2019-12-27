<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo e(trans('texts.application_settings')); ?></h3>
      </div>
      <div class="panel-body form-padding-right">
        <?php echo Former::text('app[url]')->label(trans('texts.url'))->value(isset($_ENV['APP_URL']) ? $_ENV['APP_URL'] : Request::root()); ?>

        <?php echo Former::checkbox('https')->text(trans('texts.require'))->check(env('REQUIRE_HTTPS'))->value(1); ?>

        <?php echo Former::checkbox('debug')->text(trans('texts.enable'))->check(config('app.debug'))->value(1); ?>


      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo e(trans('texts.database_connection')); ?></h3>
      </div>
      <div class="panel-body form-padding-right">
        
        <?php echo Former::plaintext('driver')->value('MySQL'); ?>

        <?php echo Former::text('database[type][host]')->label('host')->value(isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost'); ?>

        <?php echo Former::text('database[type][database]')->label('database')->value(isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : 'ninja'); ?>

        <?php echo Former::text('database[type][username]')->label('username')->value(isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : 'ninja'); ?>

        <?php echo Former::password('database[type][password]')->label('password')->value(isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : 'ninja'); ?>

        <?php echo Former::actions( Button::primary(trans('texts.test_connection'))->small()->withAttributes(['onclick' => 'testDatabase()']), '&nbsp;&nbsp;<span id="dbTestResult"/>' ); ?>

      </div>
    </div>

    <?php if(!isset($_ENV['POSTMARK_API_TOKEN'])): ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo e(trans('texts.email_settings')); ?></h3>
          </div>
          <div class="panel-body form-padding-right">
            <?php echo Former::select('mail[driver]')->label('driver')->options(['smtp' => 'SMTP', 'mail' => 'Mail', 'sendmail' => 'Sendmail', 'mailgun' => 'Mailgun'])
                     ->value(HTMLUtils::getEnvForAccount('MAIL_DRIVER', 'smtp'))->setAttributes(['onchange' => 'mailDriverChange()']); ?>

            <?php echo Former::text('mail[from][name]')->label('from_name')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_FROM_NAME')); ?>

            <?php echo Former::text('mail[from][address]')->label('from_address')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_FROM_ADDRESS')); ?>

            <?php echo Former::text('mail[username]')->label('username')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_USERNAME')); ?>

            <div id="standardMailSetup">
              <?php echo Former::text('mail[host]')->label('host')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_HOST')); ?>

              <?php echo Former::text('mail[port]')->label('port')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_PORT', '587')); ?>

              <?php echo Former::select('mail[encryption]')->label('encryption')
                      ->options(['tls' => 'TLS', 'ssl' => 'SSL', '' => trans('texts.none')])
                      ->value(HTMLUtils::getEnvForAccount('MAIL_ENCRYPTION', 'tls')); ?>

              <?php echo Former::password('mail[password]')->label('password')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_PASSWORD')); ?>

            </div>
            <div id="mailgunMailSetup">
              <?php echo Former::text('mail[mailgun_domain]')->label('mailgun_domain')
                      ->value(isset($_ENV['MAILGUN_DOMAIN']) ? $_ENV['MAILGUN_DOMAIN'] : ''); ?>

              <?php echo Former::text('mail[mailgun_secret]')->label('mailgun_private_key')
                      ->value(isset($_ENV['MAILGUN_SECRET']) ? $_ENV['MAILGUN_SECRET'] : ''); ?>

            </div>
              <?php echo Former::actions( Button::primary(trans('texts.send_test_email'))->small()->withAttributes(['onclick' => 'testMail()']), '&nbsp;&nbsp;<span id="mailTestResult"/>' ); ?>

          </div>
        </div>
    <?php endif; ?>

  <script type="text/javascript">

    var db_valid = false
    var mail_valid = false
    mailDriverChange();

    function testDatabase()
    {
      var data = $("form").serialize() + "&test=db";

      // Show Progress Text
      $('#dbTestResult').html('Working...').css('color', 'black');

      // Send / Test Information
      $.post( "<?php echo e(URL::to('/setup')); ?>", data, function( data ) {
        var color = 'red';
        if(data == 'Success'){
          color = 'green';
          db_valid = true;
        }
        $('#dbTestResult').html(data).css('color', color);
      });

      return db_valid;
    }

    function mailDriverChange() {
      if ($("select[name='mail[driver]']").val() == 'mailgun') {
        $("#standardMailSetup").hide();
        $("#standardMailSetup").children('select,input').prop('disabled',true);
        $("#mailgunMailSetup").show();
        $("#mailgunMailSetup").children('select,input').prop('disabled',false);

      } else {
        $("#standardMailSetup").show();
        $("#standardMailSetup").children('select,input').prop('disabled',false);

        $("#mailgunMailSetup").hide();
        $("#mailgunMailSetup").children('select,input').prop('disabled',true);

      }
    }

    function testMail()
    {
      var data = $("form").serialize() + "&test=mail";

      // Show Progress Text
      $('#mailTestResult').html('Working...').css('color', 'black');

      // Send / Test Information
      $.post( "<?php echo e(URL::to('/setup')); ?>", data, function( data ) {
        var color = 'red';
        if(data == 'Sent'){
          color = 'green';
          mail_valid = true;
        }
        $('#mailTestResult').html(data).css('color', color);
      });

      return mail_valid;
    }

    // Prevent the Enter Button from working
    $("form").bind("keypress", function (e) {
      if (e.keyCode == 13) {
        return false;
      }
    });

  </script>
