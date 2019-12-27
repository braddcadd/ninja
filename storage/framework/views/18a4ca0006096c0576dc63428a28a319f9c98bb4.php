<script type="text/javascript">

$(function() {

    window.grapesjsEditor = grapesjs.init({
        container : '#gjs',
        components: <?php echo json_encode($entity ? $entity->html : ''); ?>,
        style: <?php echo json_encode($entity ? $entity->css : ''); ?>,
        showDevices: false,
        noticeOnUnload: false,
        plugins: ['gjs-preset-newsletter'],
        pluginsOpts: {
            'gjs-preset-newsletter': {
                'categoryLabel': "<?php echo e(trans('texts.standard')); ?>"
            }
        },
        storageManager: {
            type: 'none',
            autosave: false,
            autoload: false,
            storeComponents: false,
            storeStyles: false,
            storeHtml: false,
            storeCss: false,
        },
        assetManager: {
            assets: <?php echo json_encode($documents); ?>,
            noAssets: "<?php echo e(trans('texts.no_assets')); ?>",
            addBtnText: "<?php echo e(trans('texts.add_image')); ?>",
            modalTitle: "<?php echo e(trans('texts.select_image')); ?>",
            <?php if(Utils::isSelfHost() || $account->isEnterprise()): ?>
                upload: <?php echo json_encode(url('/documents')); ?>,
                uploadText: "<?php echo e(trans('texts.dropzone_default_message')); ?>",
            <?php else: ?>
                upload: false,
                uploadText: "<?php echo e(trans('texts.upgrade_to_upload_images')); ?>",
            <?php endif; ?>
            uploadName: 'files',
            params: {
                '_token': '<?php echo e(Session::token()); ?>',
                'grapesjs': true,
            }
        }
    });

    var panelManager = grapesjsEditor.Panels;
    panelManager.addButton('options', [{
        id: 'undo',
        className: 'fa fa-undo',
        command: 'undo',
        attributes: { title: 'Undo (CTRL/CMD + Z)'}
    },{
        id: 'redo',
        className: 'fa fa-repeat',
        attributes: {title: 'Redo'},
        command: 'redo',
        attributes: { title: 'Redo (CTRL/CMD + SHIFT + Z)' }
    }]);

    var blockManager = grapesjsEditor.BlockManager;
    
    blockManager.get('text').set('content', {
        type: 'text',
        content: 'Insert your text here',
        activeOnRender: 1
    });

    blockManager.get('grid-items').set('content', '\
    <table>\
        <tr>\
            <td class="card-content">\
                <img src="" alt="Image"/>\
                <h1 class="card-title">Title here</h1>\
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>\
            </td>\
            <td class="card-content">\
                <img src="" alt="Image"/>\
                <h1 class="card-title">Title here</h1>\
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>\
            </td>\
        </tr>\
    </table>');

    blockManager.get('list-items').set('content', '\
  <table>\
    <tr>\
      <td class="card-content">\
        <img alt="Image"//>\
      </td>\
      <td class="card-content">\
        <h1 class="card-title">Title here</h1>\
        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>\
      </td>\
    </tr>\
  </table>\
  <table>\
    <tr>\
      <td class="card-content">\
        <img alt="Image"/>\
      </td>\
      <td class="card-content">\
        <h1 class="card-title">Title here</h1>\
        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>\
      </td>\
    </tr>\
  </table>');


    <?php $__currentLoopData = $snippets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $snippet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        blockManager.add("h<?php echo e(($loop->index + 1)); ?>-block", {
            label: '<?php echo e($snippet->name); ?>',
            category: '<?php echo e($snippet->proposal_category ? $snippet->proposal_category->name : trans('texts.custom')); ?>',
            content: <?php echo json_encode($snippet->html); ?>,
            style: <?php echo json_encode($snippet->css); ?>,
            attributes: {
                title: <?php echo json_encode($snippet->private_notes); ?>,
                class:'fa fa-<?php echo e($snippet->icon ?: 'font'); ?>'
            }
        });
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(count($snippets)): ?>
        var blockCategories = blockManager.getCategories();
        for (var i=0; i<blockCategories.models.length; i++) {
            var blockCategory = blockCategories.models[i];
            blockCategory.set('open', false);
        }
    <?php endif; ?>

    grapesjsEditor.on('component:update', function(a, b) {
        NINJA.formIsChanged = true;
    });

    grapesjsEditor.on('asset:remove', function(asset) {
        sweetConfirm(function() {
            $.ajax({
                url: "<?php echo e(url('/documents')); ?>/" + asset.attributes.public_id,
                type: 'DELETE',
                success: function(result) {
                    console.log('result: %s', result);
                }
            });
        }, "<?php echo e(trans('texts.delete_image_help')); ?>", "<?php echo e(trans('texts.delete_image')); ?>", function() {
            var assetManager = grapesjsEditor.AssetManager;
            assetManager.add([asset.attributes]);
        });
    });

});

</script>
