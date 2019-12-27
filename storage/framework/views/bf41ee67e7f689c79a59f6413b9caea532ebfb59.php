<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/select2.min.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/select2.css')); ?>" rel="stylesheet" type="text/css"/>

    <?php if($vendor->showMap()): ?>
        <style>
          #map {
            width: 100%;
            height: 200px;
            border-width: 1px;
            border-style: solid;
            border-color: #ddd;
          }
        </style>

        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>"></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-7">
        <ol class="breadcrumb">
          <li><?php echo e(link_to('/vendors', trans('texts.vendors'))); ?></li>
          <li class='active'><?php echo e($vendor->getDisplayName()); ?></li> <?php echo $vendor->present()->statusLabel; ?>

        </ol>
    </div>
    <div class="col-md-5">
        <div class="pull-right">

          <?php echo Former::open('vendors/bulk')->autocomplete('off')->addClass('mainForm'); ?>

      		<div style="display:none">
      			<?php echo Former::text('action'); ?>

      			<?php echo Former::text('public_id')->value($vendor->public_id); ?>

      		</div>

              <?php if( ! $vendor->is_deleted): ?>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $vendor)): ?>
                      <?php echo DropdownButton::normal(trans('texts.edit_vendor'))
                          ->withAttributes(['class'=>'normalDropDown'])
                          ->withContents([
                            ($vendor->trashed() ? false : ['label' => trans('texts.archive_vendor'), 'url' => "javascript:onArchiveClick()"]),
                            ['label' => trans('texts.delete_vendor'), 'url' => "javascript:onDeleteClick()"],
                          ]
                        )->split(); ?>

                  <?php endif; ?>
                  <?php if( ! $vendor->trashed()): ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', ENTITY_EXPENSE)): ?>
                          <?php echo Button::primary(trans("texts.new_expense"))
                                  ->asLinkTo(URL::to("/expenses/create/0/{$vendor->public_id}"))
                                  ->appendIcon(Icon::create('plus-sign')); ?>

                      <?php endif; ?>
                  <?php endif; ?>
              <?php endif; ?>

              <?php if($vendor->trashed()): ?>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $vendor)): ?>
                      <?php echo Button::primary(trans('texts.restore_vendor'))
                              ->appendIcon(Icon::create('cloud-download'))
                              ->withAttributes(['onclick' => 'onRestoreClick()']); ?>

                  <?php endif; ?>
              <?php endif; ?>


      	  <?php echo Former::close(); ?>


        </div>
    </div>
</div>



    <div class="panel panel-default">
    <div class="panel-body">
	<div class="row">
		<div class="col-md-3">
			<h3><?php echo e(trans('texts.details')); ?></h3>
            <?php if($vendor->id_number): ?>
                <p><i class="fa fa-id-number" style="width: 20px"></i><?php echo e(trans('texts.id_number').': '.$vendor->id_number); ?></p>
            <?php endif; ?>
            <?php if($vendor->vat_number): ?>
		  	   <p><i class="fa fa-vat-number" style="width: 20px"></i><?php echo e(trans('texts.vat_number').': '.$vendor->vat_number); ?></p>
            <?php endif; ?>

            <?php if($vendor->account->customLabel('vendor1') && $vendor->custom_value1): ?>
                <?php echo e($vendor->account->present()->customLabel('vendor1') . ': '); ?> <?php echo nl2br(e($vendor->custom_value1)); ?><br/>
            <?php endif; ?>
            <?php if($vendor->account->customLabel('vendor2') && $vendor->custom_value2): ?>
                <?php echo e($vendor->account->present()->customLabel('vendor2') . ': '); ?> <?php echo nl2br(e($vendor->custom_value2)); ?><br/>
            <?php endif; ?>


            <?php if($vendor->address1): ?>
                <?php echo e($vendor->address1); ?><br/>
            <?php endif; ?>
            <?php if($vendor->address2): ?>
                <?php echo e($vendor->address2); ?><br/>
            <?php endif; ?>
            <?php if($vendor->getCityState()): ?>
                <?php echo e($vendor->getCityState()); ?><br/>
            <?php endif; ?>
            <?php if($vendor->country): ?>
                <?php echo e($vendor->country->getName()); ?><br/>
            <?php endif; ?>

            <?php if($vendor->account->custom_vendor_label1 && $vendor->custom_value1): ?>
                <?php echo e($vendor->account->custom_vendor_label1 . ': ' . $vendor->custom_value1); ?><br/>
            <?php endif; ?>
            <?php if($vendor->account->custom_vendor_label2 && $vendor->custom_value2): ?>
                <?php echo e($vendor->account->custom_vendor_label2 . ': ' . $vendor->custom_value2); ?><br/>
            <?php endif; ?>

            <?php if($vendor->work_phone): ?>
                <i class="fa fa-phone" style="width: 20px"></i><?php echo e($vendor->work_phone); ?>

            <?php endif; ?>

            <?php if($vendor->private_notes): ?>
                <p><i><?php echo nl2br(e($vendor->private_notes)); ?></i></p>
            <?php endif; ?>

  	        <?php if($vendor->vendor_industry): ?>
                <?php echo e($vendor->vendor_industry->name); ?><br/>
            <?php endif; ?>
            <?php if($vendor->vendor_size): ?>
                <?php echo e($vendor->vendor_size->name); ?><br/>
            <?php endif; ?>

		  	<?php if($vendor->website): ?>
		  	   <p><?php echo Utils::formatWebsite($vendor->website); ?></p>
            <?php endif; ?>

            <?php if($vendor->language): ?>
                <p><i class="fa fa-language" style="width: 20px"></i><?php echo e($vendor->language->name); ?></p>
            <?php endif; ?>

		  	<p><?php echo e($vendor->payment_terms ? trans('texts.payment_terms') . ": " . trans('texts.payment_terms_net') . " " . $vendor->payment_terms : ''); ?></p>
		</div>

		<div class="col-md-3">
			<h3><?php echo e(trans('texts.contacts')); ?></h3>
		  	<?php $__currentLoopData = $vendor->vendor_contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($contact->first_name || $contact->last_name): ?>
                    <b><?php echo e($contact->first_name.' '.$contact->last_name); ?></b><br/>
                <?php endif; ?>
                <?php if($contact->email): ?>
                    <i class="fa fa-envelope" style="width: 20px"></i><?php echo HTML::mailto($contact->email, $contact->email); ?><br/>
                <?php endif; ?>
                <?php if($contact->phone): ?>
                    <i class="fa fa-phone" style="width: 20px"></i><?php echo e($contact->phone); ?><br/>
                <?php endif; ?>
		  	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>

		<div class="col-md-4">
			<h3><?php echo e(trans('texts.standing')); ?>

			<table class="table" style="width:100%">
				<tr>
					<td style="vertical-align: top"><small><?php echo e(trans('texts.balance')); ?></small></td>
                    <td style="text-align: right">
                        <?php $__currentLoopData = $vendor->getUnpaidExpenses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e(Utils::formatMoney($currency->amount, $currency->expense_currency_id)); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
				</tr>
			</table>
			</h3>
		</div>
	</div>
    </div>
    </div>

    <?php if($vendor->showMap()): ?>
        <div id="map"></div>
        <br/>
    <?php endif; ?>

	<ul class="nav nav-tabs nav-justified">
		<?php echo Form::tab_link('#expenses', trans('texts.expenses')); ?>

	</ul><br/>

	<div class="tab-content">
        <div class="tab-pane" id="expenses">
            <?php echo $__env->make('list', [
                'entityType' => ENTITY_EXPENSE,
                'datatable' => new \App\Ninja\Datatables\ExpenseDatatable(true, true),
                'vendorId' => $vendor->public_id,
                'url' => url('api/vendor_expenses/' . $vendor->public_id),
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

	<script type="text/javascript">

    var loadedTabs = {};

	$(function() {
		$('.normalDropDown:not(.dropdown-toggle)').click(function(event) {
            openUrlOnClick('<?php echo e(URL::to('vendors/' . $vendor->public_id . '/edit')); ?>', event)
		});

        $('.nav-tabs a[href="#expenses"]').tab('show');
	});

	function onArchiveClick() {
		$('#action').val('archive');
		$('.mainForm').submit();
	}

	function onRestoreClick() {
		$('#action').val('restore');
		$('.mainForm').submit();
	}

	function onDeleteClick() {
		if (confirm(<?php echo json_encode(trans('texts.are_you_sure')); ?>)) {
			$('#action').val('delete');
			$('.mainForm').submit();
		}
	}

    <?php if($vendor->showMap()): ?>
        function initialize() {
            var mapCanvas = document.getElementById('map');
            var mapOptions = {
                zoom: <?php echo e(DEFAULT_MAP_ZOOM); ?>,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoomControl: true,
            };

            var map = new google.maps.Map(mapCanvas, mapOptions)
            var address = <?php echo json_encode(e("{$vendor->address1} {$vendor->address2} {$vendor->city} {$vendor->state} {$vendor->postal_code} " . ($vendor->country ? $vendor->country->getName() : ''))); ?>;

            geocoder = new google.maps.Geocoder();
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                  if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                    var result = results[0];
                    map.setCenter(result.geometry.location);

                    var infowindow = new google.maps.InfoWindow(
                        { content: '<b>'+result.formatted_address+'</b>',
                        size: new google.maps.Size(150, 50)
                    });

                    var marker = new google.maps.Marker({
                        position: result.geometry.location,
                        map: map,
                        title:address,
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(map, marker);
                    });
                } else {
                    $('#map').hide();
                }
            } else {
              $('#map').hide();
          }
      });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    <?php endif; ?>

	</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>