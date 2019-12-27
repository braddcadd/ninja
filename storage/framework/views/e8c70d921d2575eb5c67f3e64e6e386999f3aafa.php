<table class="table table-striped data-table <?php echo e($class = str_random(8)); ?>">
    <colgroup>
        <?php for($i = 0; $i < count($columns); $i++): ?>
        <col class="con<?php echo e($i); ?>" />
        <?php endfor; ?>
    </colgroup>
    <thead>
    <tr>
        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th align="center" valign="middle" class="head<?php echo e($i); ?>"
            <?php if($c == 'checkbox'): ?>
                style="width:20px"
            <?php endif; ?>
        >
            <?php if($c == 'checkbox' && $hasCheckboxes = true): ?>
                <input type="checkbox" class="selectAll"/>
            <?php else: ?>
                <?php echo e($c); ?>

            <?php endif; ?>
        </th>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <?php $__currentLoopData = $d; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td><?php echo e($dd); ?></td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<script type="text/javascript">
    <?php if(isset($values['clientId']) && $values['clientId']): ?>
            window.load_<?php echo e($values['entityType']); ?> = function load_<?php echo e($values['entityType']); ?>() {
                load_<?php echo e($class); ?>();
            }
    <?php else: ?>
        jQuery(document).ready(function(){
            load_<?php echo e($class); ?>();
        });
    <?php endif; ?>

    function refreshDatatable<?php echo e(isset($values['entityType']) ? '_' . $values['entityType'] : ''); ?>() {
        window['dataTable<?php echo e(isset($values['entityType']) ? '_' . $values['entityType'] : ''); ?>'].api().ajax.reload();
    }

    function load_<?php echo e($class); ?>() {
        window['dataTable<?php echo e(isset($values['entityType']) ? '_' . $values['entityType'] : ''); ?>'] = jQuery('.<?php echo e($class); ?>').dataTable({
            "stateSave": true,
            "stateDuration": 0,
            "fnRowCallback": function(row, data) {
                if (data[0].indexOf('ENTITY_DELETED') > 0) {
                    $(row).addClass('entityDeleted');
                }
                if (data[0].indexOf('ENTITY_ARCHIVED') > 0) {
                    $(row).addClass('entityArchived');
                }
            },
            "bAutoWidth": false,
            "aoColumnDefs": [
                <?php if(isset($values['entityType']) && $values['entityType'] == 'tickets'): ?>
                {
                    'bSortable': false,
                    'aTargets': [ 0, 3 , 7, <?php echo e(count($columns) - 1); ?> ]
                },
                <?php elseif(isset($hasCheckboxes) && $hasCheckboxes): ?>
                // Disable sorting on the first column
                {
                    'bSortable': false,
                    'aTargets': [ 0, <?php echo e(count($columns) - 1); ?> ]
                },
                <?php endif; ?>
                {
                    'sClass': 'right',
                    'aTargets': <?php echo e(isset($values['rightAlign']) ? json_encode($values['rightAlign']) : '[]'); ?>

                }
            ],
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo json_encode($k); ?>: <?php echo json_encode($o); ?>,
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $callbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo json_encode($k); ?>: <?php echo $o; ?>,
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            "fnDrawCallback": function(oSettings) {
                <?php if(isset($values['entityType'])): ?>
                    if (window.onDatatableReady_<?php echo e($values['entityType']); ?>) {
                        window.onDatatableReady_<?php echo e($values['entityType']); ?>();
                    } else if (window.onDatatableReady) {
                        window.onDatatableReady();
                    }
                <?php else: ?>
                    if (window.onDatatableReady) {
                        window.onDatatableReady();
                    }
                <?php endif; ?>
            },
            "stateLoadParams": function (settings, data) {
                // don't save filter to local storage
                data.search.search = "";
                // always start on first page of results
                data.start = 0;
            }
        });
    }
</script>
