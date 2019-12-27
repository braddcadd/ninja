<?php echo e(trans('texts.powered_by')); ?>



<?php echo link_to('https://www.invoiceninja.com/?utm_source=powered_by', 'Invoice Ninja', ['target' => '_blank', 'title' => trans('texts.created_by', ['name' => 'Hillel Coren'])]); ?> -
<?php echo link_to(RELEASES_URL, 'v' . NINJA_VERSION, ['target' => '_blank', 'title' => trans('texts.trello_roadmap')]); ?> |

<?php if(Auth::user()->account->hasFeature(FEATURE_WHITE_LABEL)): ?>
  <?php echo e(trans('texts.white_labeled')); ?>

  <?php if(! Utils::isNinja() && $company->hasActivePlan() && $company->daysUntilPlanExpires() <= 10 && $company->daysUntilPlanExpires() > 0): ?>
    <br/><b><?php echo trans('texts.license_expiring', [
        'count' => $company->daysUntilPlanExpires(),
        'link' => '<a href="#" onclick="showWhiteLabelModal()">' . trans('texts.click_here') . '</a>',
    ]); ?></b>
  <?php endif; ?>
<?php else: ?>
  <a href="#" onclick="showWhiteLabelModal()"><?php echo e(trans('texts.white_label_link')); ?></a>
<?php endif; ?>

  <div class="modal fade" id="whiteLabelModal" tabindex="-1" role="dialog" aria-labelledby="whiteLabelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.white_label_header')); ?></h4>
        </div>

        <div class="container" style="width: 100%; padding-bottom: 0px !important">
        <div class="panel panel-default">
        <div class="panel-body">
          <p><?php echo e(trans('texts.white_label_text', ['price' => WHITE_LABEL_PRICE])); ?></p>
          <div class="row">
              <div class="col-md-6">
                  <h4><?php echo e(trans('texts.before')); ?></h4>
                  <img src="<?php echo e(BLANK_IMAGE); ?>" data-src="<?php echo e(asset('images/pro_plan/white_label_before.png')); ?>" width="100%" alt="before">
              </div>
              <div class="col-md-6">
                  <h4><?php echo e(trans('texts.after')); ?></h4>
                  <img src="<?php echo e(BLANK_IMAGE); ?>" data-src="<?php echo e(asset('images/pro_plan/white_label_after.png')); ?>" width="100%" alt="after">
              </div>
          </div>
          <br/>
          <p><?php echo trans('texts.reseller_text', ['email' => HTML::mailto('contact@invoiceninja.com')]); ?></p>
        </div>
        </div>
        </div>

        <div class="modal-footer" id="signUpFooter" style="margin-top: 0px">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.close')); ?> </button>
          <!-- <button type="button" class="btn btn-primary" onclick="showRecoverLicense()"><?php echo e(trans('texts.recover')); ?> </button> -->
          <button type="button" class="btn btn-primary" onclick="showApplyLicense()"><?php echo e(trans('texts.apply')); ?> </button>
          <button type="button" class="btn btn-success" onclick="buyWhiteLabel()"><?php echo e(trans('texts.purchase')); ?> </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="applyWhiteLabelModal" tabindex="-1" role="dialog" aria-labelledby="applyWhiteLabelModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.apply_white_label_header')); ?></h4>
          </div>

          <div class="container" style="width: 100%; padding-bottom: 0px !important">
          <div class="panel panel-default">
          <div class="panel-body">
              <?php echo Former::open()->rules(['white_label_license_key' => 'required|min:24|max:24']); ?>

              <?php echo Former::input('white_label_license_key'); ?>

              <?php echo Former::close(); ?>

          </div>
          </div>
          </div>

          <div class="modal-footer" id="signUpFooter" style="margin-top: 0px">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.close')); ?> </button>
            <button type="button" class="btn btn-success" onclick="applyLicense()"><?php echo e(trans('texts.submit')); ?> </button>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="recoverWhiteLabelModal" tabindex="-1" role="dialog" aria-labelledby="recoverWhiteLabelModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo e(trans('texts.recover_white_label_header')); ?></h4>
          </div>

          <div class="container" style="width: 100%; padding-bottom: 0px !important">
          <div class="panel panel-default">
          <div class="panel-body">
              <?php echo Former::open()->rules(['white_label_license_email' => 'required|email']); ?>

              <?php echo Former::input('white_label_license_email')->label('email'); ?>

              <?php echo Former::close(); ?>

          </div>
          </div>
          </div>

          <div class="modal-footer" id="signUpFooter" style="margin-top: 0px">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(trans('texts.close')); ?> </button>
            <button type="button" class="btn btn-success" onclick="applyLicense()"><?php echo e(trans('texts.submit')); ?> </button>
          </div>
        </div>
      </div>
  </div>

<script type="text/javascript">

    function showWhiteLabelModal() {
        loadImages('#whiteLabelModal');
        $('#whiteLabelModal').modal('show');
    }

    function buyWhiteLabel() {
        buyProduct('<?php echo e(WHITE_LABEL_AFFILIATE_KEY); ?>', '<?php echo e(PRODUCT_WHITE_LABEL); ?>');
    }

    function buyProduct(affiliateKey, productId) {
        location.href = "<?php echo e(url('white_label/purchase')); ?>";
    }

    function showApplyLicense() {
        $('#whiteLabelModal').modal('hide');
        $('#applyWhiteLabelModal').modal('show');
    }

    function showRecoverLicense() {
        $('#whiteLabelModal').modal('hide');
        $('#recoverWhiteLabelModal').modal('show');
    }

    function applyLicense() {
        var license = $('#white_label_license_key').val();
        window.location = "<?php echo e(url('')); ?>/dashboard?license_key=" + license + "&product_id=<?php echo e(PRODUCT_WHITE_LABEL); ?>";
    }

</script>
