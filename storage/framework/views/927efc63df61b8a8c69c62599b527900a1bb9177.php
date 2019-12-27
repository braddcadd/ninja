var <?php echo e($entityType); ?>Name = '';

$<?php echo e($entityType); ?>Select.combobox({
    highlighter: function (item) {
        if (item.indexOf("<?php echo e(trans("texts.create_{$entityType}")); ?>") == 0) {
            <?php echo e($entityType); ?>Name = this.query;
            return "<?php echo e(trans("texts.create_{$entityType}")); ?>: " + this.query;
        } else {
            var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&');
            item = _.escape(item);
            return item.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
              return match ? '<strong>' + match + '</strong>' : query;
            })
        }
    },
    template: '<div class="combobox-container"> <input type="hidden" /> <div class="input-group"> <input type="text" id="<?php echo e($entityType); ?>_name" name="<?php echo e($entityType); ?>_name" autocomplete="off" /> <span class="input-group-addon dropdown-toggle" data-dropdown="dropdown"> <span class="caret" /> <i class="fa fa-times"></i> </span> </div> </div> ',
    matcher: function (item) {
        // if the user has entered a value show the 'Create ...' option
        if (item.indexOf("<?php echo e(trans("texts.create_{$entityType}")); ?>") == 0) {
            return this.query.length;
        }
        return ~item.toLowerCase().indexOf(this.query.toLowerCase());
    }
}).on('change', function(e) {
    var <?php echo e($entityType); ?>Id = $('input[name=<?php echo e($entityType); ?>_id]').val();
    if (<?php echo e($entityType); ?>Id == '-1') {
        $('#<?php echo e($entityType); ?>_name').val(<?php echo e($entityType); ?>Name);
    }
});
