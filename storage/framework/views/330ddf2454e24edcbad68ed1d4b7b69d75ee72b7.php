<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/jquery.datetimepicker.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/jquery.datetimepicker.css')); ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="<?php echo e(asset('js/tinymce.min.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('js/tinymce-mentions-plugin.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/tinymce-mentions-autocomplete.css')); ?>" rel="stylesheet" type="text/css"/>



<?php $__env->stopSection(); ?>

<style>
    .td-left {width:1%; white-space:nowrap; text-align: right; height:40px;}
    .td-right {width:1%; white-space:nowrap; text-align: left; height:40px;}
    #accordion .ui-accordion-header {background: #033e5e; color: #fff;}
</style>

<?php $__env->startSection('content'); ?>

    <?php echo Former::open($url)
            ->addClass('col-lg-10 col-lg-offset-1 warn-on-exit main-form')
            ->autocomplete('off')
            ->method($method)
            ->rules([
                'name' => 'required',
            ]); ?>


    <?php if($ticket): ?>
        <?php echo Former::populate($ticket); ?>

    <?php endif; ?>

    <div style="display:none">
        <?php echo Former::text('data')->data_bind('value: ko.mapping.toJSON(model)'); ?>

        <?php echo Former::hidden('category_id')->value(1); ?>

        <?php if($ticket): ?>
            <?php echo Former::hidden('public_id')->value($ticket->public_id); ?>

            <?php echo Former::hidden('status_id')->value($ticket->status_id)->id('status_id'); ?>

            <?php echo Former::hidden('closed')->value($ticket->closed)->id('closed'); ?>

            <?php echo Former::hidden('reopened')->value($ticket->reopened)->id('reopened'); ?>

            <?php echo Former::hidden('subject')->value($ticket->subject)->id('subject'); ?>

            <?php echo Former::hidden('contact_key')->value($ticket->contact_key)->id('contact_key'); ?>

            <?php echo Former::hidden('is_internal')->value($ticket->is_internal); ?>

        <?php else: ?>
            <?php echo Former::hidden('status_id')->value(1); ?>

        <?php endif; ?>
    </div>

    <div class="panel panel-default">
        <?php if($isAdminUser): ?>
            <?php echo $__env->make('tickets.partials.ticket_meta_data_admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('tickets.partials.ticket_meta_data_agent', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
    </div>

    <?php if($ticket && $ticket->is_internal == true): ?>
        <div class="panel panel-default">
            <center class="buttons">
                <h3><?php echo trans('texts.internal_ticket'); ?></h3>
            </center>
            <table width="100%">
                <tr>
                    <td width="50%" style="vertical-align:top;">
                        <table class="table table-striped datatable">
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <?php if($ticket): ?>
        <div style="height:80px;">
            <div class="pull-right">
                <?php echo Button::info(trans('texts.show_hide_all'))->large()->withAttributes(['onclick' => 'toggleAllComments()']); ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="panel-default ui-accordion ui-widget ui-helper-reset" id="accordion" role="tablist">
        <?php $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <h3 class="ui-accordion-header ui-corner-top ui-state-default ui-accordion-header-active ui-state-active ui-accordion-icons" role="tab" id="accordion"><?php echo $comment->getCommentHeader(); ?></h3>
            <div>
                <p>
                    <?php echo $comment->description; ?>

                </p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if(!$ticket->merged_parent_ticket_id): ?>

        <div class="panel panel-default" style="margin-top:30px; padding-bottom: 0px !important">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php if($ticket): ?>
                        <?php echo trans('texts.reply'); ?>

                    <?php else: ?>
                        <?php echo trans('texts.new_ticket'); ?>

                    <?php endif; ?></h3>
            </div>

            <div class="panel-body">

                <?php if(!$ticket): ?>
                    <?php echo e(trans('texts.subject')); ?>

                    <?php echo Former::small_text('subject')
                             ->label('')
                             ->id('subject')
                             ->style('width:100%;'); ?>


                    <?php echo e(trans('texts.description')); ?>

                <?php endif; ?>

                <textarea id="description" name="description"></textarea>


            </div>
        </div>

        </div>


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $ticket)): ?>
        <div class="row">
            <center class="buttons">

                <?php if(!$ticket->is_internal && $ticket->client): ?>
                    <?php echo DropdownButton::normal(trans('texts.more_actions'))
                    ->withContents([
                        ['label'=>trans('texts.ticket_merge'),'url'=>'/tickets/merge/'. $ticket->public_id ],
                        ['label'=>trans('texts.new_internal_ticket'), 'url'=>'/tickets/create/'.$ticket->public_id],
                    ])
                    ->large()
                    ->dropup(); ?>

                <?php endif; ?>

                <?php if($ticket && $ticket->status_id == 3): ?>
                    <?php echo Button::warning(trans('texts.ticket_reopen'))->large()->withAttributes(['onclick' => 'reopenAction()']); ?>

                <?php elseif(!$ticket): ?>
                    <?php echo Button::primary(trans('texts.ticket_open'))->large()->withAttributes(['onclick' => 'submitAction()']); ?>

                <?php else: ?>
                    <?php echo Button::danger(trans('texts.ticket_close'))->large()->withAttributes(['onclick' => 'closeAction()']); ?>

                    <?php echo Button::primary(trans('texts.ticket_update'))->large()->withAttributes(['onclick' => 'submitAction()']); ?>

                <?php endif; ?>

            </center>
        </div>
        <?php endif; ?>
        <div id='downhere'>
            <?php endif; ?>

            <div role="tabpanel" class="panel-default" style="margin-top:30px;">

                <ul class="nav nav-tabs" role="tablist" style="border: none">
                    <li role="presentation" class="active"><a href="#private_notes" aria-controls="private_notes" role="tab" data-toggle="tab"><?php echo e(trans("texts.private_notes")); ?></a></li>
                    <?php if($account->hasFeature(FEATURE_DOCUMENTS)): ?>
                        <li role="presentation"><a href="#attached-documents" aria-controls="attached-documents" role="tab" data-toggle="tab">
                                <?php echo e(trans("texts.documents")); ?>

                                <?php if($ticket->documents()->count() >= 1): ?>
                                    (<?php echo e($ticket->documents()->count()); ?>)
                                <?php endif; ?>
                            </a></li>
                    <?php endif; ?>
                    <li role="presentation"><a href="#linked_objects" aria-controls="linked_objects" role="tab" data-toggle="tab"><?php echo e(trans("texts.linked_objects")); ?></a></li>
                </ul>

                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 0)); ?>

                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 0)); ?>


                <div class="tab-content" style="padding-right:12px;">

                    <div role="tabpanel" class="tab-pane active" id="private_notes" style="padding-bottom:44px">
                        <?php echo Former::textarea('private_notes')
                                ->data_bind("value: private_notes, valueUpdate: 'afterkeydown'")
                                ->label(null)->style('width: 100%')->rows(4); ?>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="attached-documents" style="position:relative;z-index:9">
                        <div id="document-upload">
                            <div class="dropzone">
                                <div data-bind="foreach: documents">
                                    <input type="hidden" name="document_ids[]" data-bind="value: public_id"/>
                                </div>
                            </div>
                            <?php if($ticket->documents()): ?>
                                <?php $__currentLoopData = $ticket->documents(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($document->name); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div row="tabpanel" class="tab-pane" id="linked_objects" style="width:600px;min-height: 300px; height: auto !important;">

                        <div style="">

                            <div style="float:left;margin:10px;">
                                <?php echo Former::select('linked_object')
                                    ->style('width:170px;padding:10px;')
                                    ->label('')
                                    ->text(trans('texts.type'))
                                    ->addOption('', '')
                                    ->fromQuery(\App\Models\Ticket::relationEntities())
                                    ->data_bind("event: {change: onEntityChange }"); ?>

                            </div>

                            <div style="float:left;margin:10px;">
                                <?php echo Former::select('linked_item')
                                    ->style('width:170px;padding:10px;')
                                    ->label('')
                                    ->text(trans('texts.type'))
                                    ->addOption('', '')
                                    ->data_bind("options: entityItems"); ?>

                            </div>

                            <div style="float:left;margin:10px;">
                                <?php echo Button::normal(trans('texts.link'))
                                            ->small()
                                            ->withAttributes(['onclick' => 'addRelation()', 'data-bind' => 'enable: checkObjectAndItemExist']); ?>

                            </div>

                        </div>

                        <div style="clear:both; float:left;">
                            <ul data-bind="foreach: relations">
                                <li data-bind="html: entity_url"></li>
                            </ul>
                        </div>


                    </div>


                </div>
                <?php if(!$ticket->merged_parent_ticket_id && Auth::user()->can('edit', $ticket)): ?>
                    <div class="pull-right">
                        <?php echo Button::primary(trans('texts.save'))->large()->withAttributes(['onclick' => 'saveAction()']); ?>

                    </div>
                <?php endif; ?>
                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.large', 4)); ?>

                <?php echo e(Former::setOption('TwitterBootstrap3.labelWidths.small', 4)); ?>


            </div>

            <?php echo Former::close(); ?>




                    <!--
     Modals
    -->

            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="error" aria-hidden="true">
                <div class="modal-dialog" style="min-width:150px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="recurringModalLabel"><?php echo e(trans('texts.error_title')); ?></h4>
                        </div>

                        <div class="container" style="width: 100%; padding-bottom: 0px !important">
                            <div class="panel panel-default">
                                <div class="panel-body" id="ticket_message">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('texts.close')); ?></button>
                        </div>

                    </div>
                </div>
            </div>

            <script type="text/javascript">

                tinymce.init({
                    selector: '#description',
                    plugins : "mention textcolor",
                    menubar : false,
                    //statusbar : false,
                    toolbar : "styleselect | fontselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
                    mentions : {
                        delimiter : ['#','$'],
                        queryBy : 'description',
                        source: function (query, process, delimiter) {
                            // Do your ajax call
                            // When using multiple delimiters you can alter the query depending on the delimiter used
                            if (delimiter === '#') {
                                $.ajax({
                                    type: "POST",
                                    url:"/tickets/search",
                                    data: {term: query},
                                    success: function (msg, status, jqXHR) {
                                        process(msg);
                                    },
                                    dataType: 'json'
                                });
                            }
                            else if(delimiter === '$') {
                                process(<?php echo \App\Models\Ticket::templateVariables(); ?>)
                            }
                        }
                    },

                });

                <!-- Initialize ticket_comment accordion -->
                $( function() {
                    $( "#accordion" ).accordion();

                    window.model = new ViewModel(<?php echo $ticket; ?>);
                    ko.applyBindings(model);
                    $('#description').text('');

                    <?php echo $__env->make('partials.dropzone', ['documentSource' => 'model.documents()'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                } );

                // Add moment support to the datetimepicker
                Date.parseDate = function( input, format ){
                    return moment(input, format).toDate();
                };
                Date.prototype.dateFormat = function( format ){
                    return moment(this).format(format);
                };

                <!-- Initialize date time picker for due date -->
                jQuery('#due_date').datetimepicker({
                    lazyInit: true,
                    validateOnBlur: false,
                    step: '<?php echo e(env('TASK_TIME_STEP', 15)); ?>',
                    minDate: '<?php echo e($ticket->getMinDueDate()); ?>',
                    format: '<?php echo e($datetimeFormat); ?>',
                    formatDate: '<?php echo e($account->getMomentDateFormat()); ?>',
                    formatTime: '<?php echo e($account->military_time ? 'H:mm' : 'h:mm A'); ?>',
                    validateOnBlur: false
                });


                <!-- Initialize drop zone file uploader -->
                $('.main-form').submit(function(){
                    if($('#document-upload .dropzone .fallback input').val())$(this).attr('enctype', 'multipart/form-data')
                    else $(this).removeAttr('enctype')
                })

                var ViewModel = function (data) {
                    var self = this;
                    var dateTimeFormat = '<?php echo e($datetimeFormat); ?>';
                    var timezone = '<?php echo e($timezone); ?>';

                    self.client_public_id = ko.observable();
                    self.documents = ko.observableArray();
                    self.due_date = ko.observable(data.due_date);
                    self.mapping = {
                        'documents': {
                            create: function (options) {
                                return new DocumentModel(options.data);
                            }
                        }
                    }

                    self.relations = ko.observableArray(<?php echo $ticket->relations; ?>);
                    self.entityItems = ko.observableArray();
                    self.checkObjectAndItemExist = ko.observable(false);

                    self.due_date.pretty = ko.computed({
                        read: function() {

                            if(self.due_date() == '0000-00-00 00:00:00')
                                return;
                            else
                                return self.due_date() ? moment(self.due_date()).format(dateTimeFormat) : '';

                        },
                        write: function(data) {
                            self.due_date(moment($('#due_date').val(), dateTimeFormat, timezone).format("YYYY-MM-DD HH:mm:ss"));

                        }
                    });

                    self.isAdminUser = ko.observable(<?php echo $isAdminUser; ?>);

                    if (data) {
                        ko.mapping.fromJS(data, self.mapping, this);
                    }

                    self.addDocument = function() {
                        var documentModel = new DocumentModel();
                        self.documents.push(documentModel);
                        return documentModel;
                    }

                    self.removeDocument = function(doc) {
                        var public_id = doc.public_id?doc.public_id():doc;
                        self.documents.remove(function(document) {
                            return document.public_id() == public_id;
                        });
                    }


                    self.onEntityChange = function(obj, event) {

                        getItemsForEntity();

                    }

                };


                function buildEntityList(data) {
                    model.entityItems([]);

                    for(j=0; j<data.length; j++) {
                        model.entityItems.push(data[j].public_id);
                    }

                    if (data.length > 0)
                        model.checkObjectAndItemExist(true);
                    else
                        model.checkObjectAndItemExist(false);

                }

                function getItemsForEntity()
                {
                    model.entityItems([]);
                    model.checkObjectAndItemExist(false);

                    var linked_object = $('#linked_object').val();
                    var ticket_id = <?php echo e($ticket->id); ?>;
                    var account_id = <?php echo e($account->id); ?>;
                    var client_public_id = <?php echo e($ticket->client ? $ticket->client->public_id : 'null'); ?>;

                    if(!linked_object)
                        return;

                    var obj = { client_public_id: client_public_id, account_id: account_id, entity: linked_object, ticket_id: ticket_id };

                    $.ajax({
                        url: "/tickets/entities/",
                        type: "GET",
                        data: obj,
                        success: function (result) {
                            buildEntityList(result);
                        }
                    });

                }

                function addRelation()
                {
                    var linked_object = $('#linked_object').val();
                    var linked_item = $('#linked_item').val()
                    var ticket_id = <?php echo e($ticket->id); ?>;

                    var obj = { entity: linked_object, entity_id: linked_item, ticket_id: ticket_id };

                    $.ajax({
                        url: "/tickets/entities/create",
                        type: "POST",
                        data: obj,
                        success: function (result) {

                            if(!result.entity_url)
                                return alert('<?php echo e(trans('texts.error_title')); ?>');

                            model.relations.push(result);
                            getItemsForEntity();

                        }
                    });
                }

                function removeRelation(entityId)
                {
                    var obj = {id : entityId};

                    $.ajax({
                        url: "/tickets/entities/remove",
                        type: "POST",
                        data: obj,
                        success: function (relationId) {

                            if(!relationId)
                                return alert('<?php echo e(trans('texts.error_title')); ?>');

                            model.relations.remove(function(relation) {
                                return relation.id == relationId;
                            });

                            getItemsForEntity();

                        }
                    });


                }


                function DocumentModel(data) {
                    var self = this;
                    self.public_id = ko.observable(0);
                    self.size = ko.observable(0);
                    self.name = ko.observable('');
                    self.type = ko.observable('');
                    self.url = ko.observable('');

                    self.update = function(data){
                        ko.mapping.fromJS(data, {}, this);
                    }

                    if (data) {
                        self.update(data);
                    }
                }

                function addDocument(file) {
                    file.index = model.documents().length;
                    model.addDocument({name:file.name, size:file.size, type:file.type});
                }

                function addedDocument(file, response) {
                    model.documents()[file.index].update(response.document);
                }

                function deleteDocument(file) {
                    model.removeDocument(file.public_id);
                }

                function toggleAllComments() {
                    $(".ui-accordion-content").toggle();
                }


                function saveAction() {

                    var dateTimeFormat = '<?php echo e($datetimeFormat); ?>';

                    if($('#due_date').val().length > 1)
                        $('#due_date').val(moment($('#due_date').val(), dateTimeFormat).format("YYYY-MM-DD HH:mm:ss"));

                    $('.main-form').submit();
                }

                function submitAction() {

                    if(checkCommentText('<?php echo e(trans('texts.enter_ticket_message')); ?>')) {
                        saveAction();
                    }

                }

                function reopenAction() {

                    if(checkCommentText('<?php echo e(trans('texts.reopen_reason')); ?>')){
                        $('#reopened').val(moment().format("YYYY-MM-DD HH:mm:ss"));
                        $('#closed').val(null);
                        $('#status_id').val(2);
                        saveAction();
                    }

                }

                function closeAction() {
                    if(checkCommentText('<?php echo e(trans('texts.close_reason')); ?>')) {
                        $('#closed').val(moment().format("YYYY-MM-DD HH:mm:ss"));
                        $('#reopened').val(null);
                        $('#status_id').val(3);
                        saveAction();
                    }

                }

                function checkCommentText(errorString) {

                    if( tinyMCE.activeEditor.getContent({format : 'raw'}).length < 1 ) {
                        $('#ticket_message').text(errorString);
                        $('#errorModal').modal('show');

                        return false;
                    }
                    else if($('#subject').val().length < 1 ) {
                        $('#ticket_message').text('<?php echo e(trans('texts.subject_required')); ?>');
                        $('#errorModal').modal('show');
                    }
                    else {
                        return true;
                    }

                }



                <!-- Initialize client selector -->
                        <?php if($clients): ?>

                var clients = <?php echo $clients; ?>;
                var clientMap = {};
                var $clientSelect = $('select#client_public_id');

                // create client dictionary

                for (var i=0; i<clients.length; i++) {
                    var client = clients[i];
                    clientMap[client.public_id] = client;
                    <?php if(! $ticket->id): ?>
                    if (!getClientDisplayName(client)) {
                        continue;
                    }
                            <?php endif; ?>

                    var clientName = client.name || '';
                    for (var j=0; j<client.contacts.length; j++) {
                        var contact = client.contacts[j];
                        var contactName = getContactDisplayNameWithEmail(contact);
                        if (clientName && contactName) {
                            clientName += '<br/>  • ';
                        }
                        if (contactName) {
                            clientName += contactName;
                        }
                    }
                    $clientSelect.append(new Option(clientName, client.public_id));
                }

                //harvest and set the client_id and contact_id here
                var $input = $('select#client_public_id');
                $input.combobox().on('change', function(e) {
                    var clientId = parseInt($('input[name=client_public_id]').val(), 10) || 0;

                    if (clientId > 0) {

                        for (var j=0; j<client.contacts.length; j++) {
                            var contact = client.contacts[j];

                            if(contact.email == $('#contact_key').val()) {
                                $('#contact_key').val(contact.contact_key);
                                $('#client_public_id').val(clientId);
                            }
                        }
                    }
                });

                <?php endif; ?>

            </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>