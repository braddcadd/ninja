Dropzone.autoDiscover = false;
window.countUploadingDocuments = 0;

window.dropzone = new Dropzone('#document-upload .dropzone', {
    url: <?php echo json_encode(url('documents')); ?>,
    params: {
        '_token': '<?php echo e(Session::token()); ?>',
        'is_default': <?php echo e(isset($isDefault) && $isDefault ? '1' : '0'); ?>,
    },
    acceptedFiles: <?php echo json_encode(implode(',',\App\Models\Document::$allowedMimes)); ?>,
    addRemoveLinks: true,
    dictRemoveFileConfirmation: "<?php echo e(trans('texts.are_you_sure')); ?>",
    <?php $__currentLoopData = ['default_message', 'fallback_message', 'fallback_text', 'file_too_big', 'invalid_file_type', 'response_error', 'cancel_upload', 'cancel_upload_confirmation', 'remove_file']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        "dict<?php echo e(Utils::toClassCase($key)); ?>" : <?php echo json_encode(trans('texts.dropzone_'.$key)); ?>,
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    maxFilesize: <?php echo e(floatval(MAX_DOCUMENT_SIZE/1000)); ?>,
    parallelUploads: 1,
});

if (dropzone instanceof Dropzone) {
    dropzone.on('addedfile', handleDocumentAdded);
    dropzone.on('removedfile', handleDocumentRemoved);
    dropzone.on('success', handleDocumentUploaded);
    dropzone.on('canceled', handleDocumentCanceled);
    dropzone.on('error', handleDocumentError);
    for (var i=0; i < <?php echo e($documentSource); ?>.length; i++) {
        var document = <?php echo e($documentSource); ?>[i];
        var mockFile = {
            name: ko.utils.unwrapObservable(document.name),
            size: ko.utils.unwrapObservable(document.size),
            type: ko.utils.unwrapObservable(document.type),
            public_id: ko.utils.unwrapObservable(document.public_id),
            status: Dropzone.SUCCESS,
            accepted: true,
            url: ko.utils.unwrapObservable(document.url),
            mock: true,
            index: i,
        };

        dropzone.emit('addedfile', mockFile);
        dropzone.emit('complete', mockFile);

        var documentType = ko.utils.unwrapObservable(document.type);
        var previewUrl = ko.utils.unwrapObservable(document.preview_url);
        var documentUrl = ko.utils.unwrapObservable(document.url);

        if (previewUrl) {
            dropzone.emit('thumbnail', mockFile, previewUrl);
        } else if (documentType == 'jpeg' || documentType == 'png' || documentType == 'svg') {
            dropzone.emit('thumbnail', mockFile, documentUrl);
        }

        dropzone.files.push(mockFile);
    }
}

function handleDocumentAdded(file){
    // open document when clicked
    if (file.url) {
        file.previewElement.addEventListener("click", function() {
            window.open(file.url, '_blank');
        });
    }
    if(file.mock)return;
    if (window.addDocument) {
        addDocument(file);
    }
    window.countUploadingDocuments++;
}

function handleDocumentRemoved(file){
    if (window.deleteDocument) {
        deleteDocument(file);
    }
    $.ajax({
        url: '<?php echo e('/documents/'); ?>' + file.public_id,
        type: 'DELETE',
        success: function(result) {
            // Do something with the result
        }
    });
}

function handleDocumentUploaded(file, response){
    window.countUploadingDocuments--;
    file.public_id = response.document.public_id
    if (window.addedDocument) {
        addedDocument(file, response);
    }
    if(response.document.preview_url){
        dropzone.emit('thumbnail', file, response.document.preview_url);
    }
}

function handleDocumentCanceled() {
    window.countUploadingDocuments--;
}

function handleDocumentError(file) {
    dropzone.removeFile(file);
    window.countUploadingDocuments--;
    swal(<?php echo json_encode(trans('texts.error_refresh_page')); ?>);
}
