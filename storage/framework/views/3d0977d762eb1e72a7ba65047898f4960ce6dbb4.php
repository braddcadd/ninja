<?php $__env->startSection('head'); ?>
    ##parent-placeholder-1a954628a960aaef81d7b2d4521929579f3541e6##

    <script src="<?php echo e(asset('js/jquery.datetimepicker.js')); ?>" type="text/javascript"></script>
    <link href="<?php echo e(asset('css/jquery.datetimepicker.css')); ?>" rel="stylesheet" type="text/css"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <style type="text/css">

    input.time-input {
        width: 100%;
        font-size: 14px !important;
    }

    </style>

    <?php if($errors->first('time_log')): ?>
        <div class="alert alert-danger"><li><?php echo e(trans('texts.task_errors')); ?>  </li></div>
    <?php endif; ?>

    <?php echo Former::open($url)
            ->addClass('col-lg-10 col-lg-offset-1 warn-on-exit task-form')
            ->onsubmit('return onFormSubmit(event)')
            ->autocomplete('off')
            ->method($method); ?>


    <?php if($task): ?>
        <?php echo Former::populate($task); ?>

        <?php echo Former::populateField('id', $task->public_id); ?>

    <?php endif; ?>

    <div style="display:none">
        <?php if($task): ?>
            <?php echo Former::text('id'); ?>

            <?php echo Former::text('invoice_id'); ?>

        <?php endif; ?>
        <?php echo Former::text('action'); ?>

        <?php echo Former::text('time_log'); ?>

    </div>

    <div class="row" onkeypress="formEnterClick(event)">
        <div class="col-md-12">

            <div class="panel panel-default">
            <div class="panel-body">

            <?php if($task && $task->invoice_id): ?>
                <?php echo Former::plaintext()
                        ->label('client')
                        ->value($task->client->present()->link); ?>

                <?php if($task->project): ?>
                    <?php echo Former::plaintext()
                            ->label('project')
                            ->value($task->present()->project); ?>

                <?php endif; ?>
            <?php else: ?>
                <?php echo Former::select('client')->addOption('', '')->addGroupClass('client-select'); ?>

                <?php echo Former::select('project_id')
                        ->addOption('', '')
                        ->addGroupClass('project-select')
                        ->label(trans('texts.project')); ?>

            <?php endif; ?>

            <?php echo $__env->make('partials/custom_fields', ['entityType' => ENTITY_TASK], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <?php echo Former::textarea('description')->rows(4); ?>


            <?php if($task): ?>

                <div class="form-group simple-time" id="editDetailsLink">
                    <label for="simple-time" class="control-label col-lg-4 col-sm-4">
                    </label>
                    <div class="col-lg-8 col-sm-8" style="padding-top: 10px">
                        <?php if($task->getStartTime()): ?>
                            <p><?php echo e($task->getStartTime()); ?> -
                            <?php if(Auth::user()->account->timezone_id): ?>
                                <?php echo e($timezone); ?>

                            <?php else: ?>
                                <?php echo link_to('/settings/localization?focus=timezone_id', $timezone, ['target' => '_blank']); ?>

                            <?php endif; ?>
                            <p/>
                        <?php endif; ?>

                        <?php if($task->hasPreviousDuration()): ?>
                            <?php echo e(trans('texts.duration') . ': ' . Utils::formatTime($task->getDuration())); ?><br/>
                        <?php endif; ?>

                        <?php if(!$task->is_running): ?>
                            <p><?php echo Button::primary(trans('texts.edit_times'))->withAttributes(['onclick'=>'showTimeDetails()'])->small(); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($task->is_running): ?>
                    <center>
                        <div id="duration-text" style="font-size: 36px; font-weight: 300; padding: 30px 0 20px 0"/>
                    </center>
                <?php endif; ?>

            <?php else: ?>
                <?php echo Former::radios('task_type')->radios([
                        trans('texts.timer') => array('name' => 'task_type', 'value' => 'timer'),
                        trans('texts.manual') => array('name' => 'task_type', 'value' => 'manual'),
                ])->inline()->check('timer')->label('&nbsp;'); ?>

            <?php endif; ?>

            <div class="form-group simple-time" id="datetime-details" style="display: none">
                <label for="simple-time" class="control-label col-lg-4 col-sm-4">
                    <?php echo e(trans('texts.times')); ?>

                </label>
                <div class="col-lg-8 col-sm-8">

                <table class="table" style="margin-bottom: 0px !important;">
                    <tbody data-bind="foreach: $root.time_log">
                        <tr data-bind="event: { mouseover: showActions, mouseout: hideActions }">
                            <td style="padding: 0px 12px 12px 0 !important">
                                <div data-bind="css: { 'has-error': !isStartValid() }">
                                    <input type="text" data-bind="dateTimePicker: startTime.pretty, event:{ change: $root.refresh }"
                                        class="form-control time-input time-input-start" placeholder="<?php echo e(trans('texts.start_time')); ?>"/>
                                </div>
                            </td>
                            <td style="padding: 0px 12px 12px 0 !important">
                                <div data-bind="css: { 'has-error': !isEndValid() }">
                                    <input type="text" data-bind="dateTimePicker: endTime.pretty, event:{ change: $root.refresh }"
                                        class="form-control time-input time-input-end" placeholder="<?php echo e(trans('texts.end_time')); ?>"/>
                                </div>
                            </td>
                            <td style="padding: 0px 12px 12px 0 !important; width:100px">
                                <input type="text" data-bind="value: duration.pretty, visible: !isEmpty()" class="form-control duration"></div>
                                <a href="#" data-bind="click: function() { setNow(), $root.refresh() }, visible: isEmpty()"><?php echo e(trans('texts.set_now')); ?></a>
                            </td>
                            <td style="width:30px" class="td-icon">
                                <i style="width:12px;cursor:pointer" data-bind="click: $root.removeItem, visible: actionsVisible() &amp;&amp; !isEmpty()" class="fa fa-minus-circle redlink" title="Remove item"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

            </div>
            </div>

        </div>
    </div>


    <center class="buttons">

    <?php if(Auth::user()->canCreateOrEdit(ENTITY_TASK, $task)): ?>
        <?php if(Auth::user()->hasFeature(FEATURE_TASKS)): ?>
            <?php if($task && $task->is_running): ?>
                <?php echo Button::success(trans('texts.save'))->large()->appendIcon(Icon::create('floppy-disk'))->withAttributes(['id' => 'save-button']); ?>

                <?php echo Button::primary(trans('texts.stop'))->large()->appendIcon(Icon::create('stop'))->withAttributes(['id' => 'stop-button']); ?>

            <?php elseif($task && $task->is_deleted): ?>
                <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/tasks'))->appendIcon(Icon::create('remove-circle')); ?>

                <?php echo Button::primary(trans('texts.restore'))->large()->withAttributes(['onclick' => 'submitAction("restore")'])->appendIcon(Icon::create('cloud-download')); ?>

            <?php elseif($task && $task->trashed()): ?>
                <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/tasks'))->appendIcon(Icon::create('remove-circle')); ?>

                <?php echo Button::success(trans('texts.save'))->large()->appendIcon(Icon::create('floppy-disk'))->withAttributes(['id' => 'save-button']); ?>

                <?php echo Button::primary(trans('texts.restore'))->large()->withAttributes(['onclick' => 'submitAction("restore")'])->appendIcon(Icon::create('cloud-download')); ?>

            <?php else: ?>
                <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/tasks'))->appendIcon(Icon::create('remove-circle')); ?>

                <?php if($task): ?>
                    <?php echo Button::success(trans('texts.save'))->large()->appendIcon(Icon::create('floppy-disk'))->withAttributes(['id' => 'save-button']); ?>

                    <?php echo Button::primary(trans('texts.resume'))->large()->appendIcon(Icon::create('play'))->withAttributes(['id' => 'resume-button']); ?>

                    <?php echo DropdownButton::normal(trans('texts.more_actions'))
                          ->withContents($actions)
                          ->large()
                          ->dropup(); ?>

                <?php else: ?>
                    <?php echo Button::success(trans('texts.start'))->large()->appendIcon(Icon::create('play'))->withAttributes(['id' => 'start-button']); ?>

                    <?php echo Button::success(trans('texts.save'))->large()->appendIcon(Icon::create('floppy-disk'))->withAttributes(['id' => 'save-button', 'style' => 'display:none']); ?>

                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo Button::normal(trans('texts.cancel'))->large()->asLinkTo(HTMLUtils::previousUrl('/tasks'))->appendIcon(Icon::create('remove-circle')); ?>

        <?php endif; ?>
    <?php endif; ?>

</center>

    <?php echo Former::close(); ?>


    <script type="text/javascript">

    // Add moment support to the datetimepicker
    Date.parseDate = function( input, format ){
      return moment(input, format).toDate();
    };
    Date.prototype.dateFormat = function( format ){
      return moment(this).format(format);
    };

    ko.bindingHandlers.dateTimePicker = {
      init: function (element, valueAccessor, allBindingsAccessor) {
         var value = ko.utils.unwrapObservable(valueAccessor());
         // http://xdsoft.net/jqplugins/datetimepicker/
         $(element).datetimepicker({
            lang: '<?php echo e($appLanguage); ?>',
            lazyInit: true,
            validateOnBlur: false,
            step: <?php echo e(env('TASK_TIME_STEP', 15)); ?>,
            format: '<?php echo e($datetimeFormat); ?>',
            formatDate: '<?php echo e($account->getMomentDateFormat()); ?>',
            formatTime: '<?php echo e($account->military_time ? 'H:mm' : 'h:mm A'); ?>',
            onSelectTime: function(current_time, $input){
                current_time.setSeconds(0);
                $(element).datetimepicker({
                    value: current_time
                });
                // set end to an hour after the start time
                if ($(element).hasClass('time-input-start')) {
                    var timeModel = ko.dataFor(element);
                    if (!timeModel.endTime()) {
                        timeModel.endTime((current_time.getTime() / 1000));
                    }
                }
            },
            dayOfWeekStart: <?php echo e(Session::get('start_of_week')); ?>

         });

         $(element).change(function() {
            var value = valueAccessor();
            value($(element).val());
         })
      },
      update: function (element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        if (value) {
            $(element).val(value);
        }
      }
    }

    var clients = <?php echo $clients; ?>;
    var projects = <?php echo $projects; ?>;

    var timeLabels = {};
    <?php $__currentLoopData = ['hour', 'minute', 'second']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        timeLabels['<?php echo e($period); ?>'] = '<?php echo e(strtolower(trans("texts.{$period}"))); ?>';
        timeLabels['<?php echo e($period); ?>s'] = '<?php echo e(strtolower(trans("texts.{$period}s"))); ?>';
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    function onFormSubmit(event) {
        <?php if(Auth::user()->canCreateOrEdit(ENTITY_TASK, $task)): ?>
            return true;
        <?php else: ?>
            return false
        <?php endif; ?>
    }

    function tock(startTime) {
        var duration = new Date().getTime() - startTime;
        duration = Math.floor(duration / 100) / 10;
        var str = convertDurationToString(duration);
        $('#duration-text').html(str);

        setTimeout(function() {
            tock(startTime);
        }, 1000);
    }

    function convertDurationToString(duration) {
        var data = [];
        var periods = ['hour', 'minute', 'second'];
        var parts = secondsToTime(duration);

        for (var i=0; i<periods.length; i++) {
            var period = periods[i];
            var letter = period.charAt(0);
            var value = parts[letter];
            if (!value) {
                continue;
            }
            period = value == 1 ? timeLabels[period] : timeLabels[period + 's'];
            data.push(value + ' ' + period);
        }

        return data.length ? data.join(', ') : '0 ' + timeLabels['seconds'];
    }

    function submitAction(action, invoice_id) {
        model.refresh();
        var data = [];
        for (var i=0; i<model.time_log().length; i++) {
            var timeLog = model.time_log()[i];
            if (!timeLog.isEmpty()) {
                data.push([timeLog.startTime(),timeLog.endTime()]);
            }
        }
        $('#invoice_id').val(invoice_id);
        $('#time_log').val(JSON.stringify(data));
        $('#action').val(action);
        $('.task-form').submit();
    }

    function onDeleteClick() {
        if (confirm(<?php echo json_encode(trans("texts.are_you_sure")); ?>)) {
            submitAction('delete');
        }
    }

    function showTimeDetails() {
        $('#datetime-details').fadeIn();
        $('#editDetailsLink').hide();
    }

    function TimeModel(data) {
        var self = this;

        var dateTimeFormat = '<?php echo e($datetimeFormat); ?>';
        var timezone = '<?php echo e($timezone); ?>';

        self.startTime = ko.observable(0);
        self.endTime = ko.observable(0);
        self.duration = ko.observable(0);
        self.actionsVisible = ko.observable(false);
        self.isStartValid = ko.observable(true);
        self.isEndValid = ko.observable(true);

        if (data) {
            self.startTime(data[0]);
            self.endTime(data[1]);
        };

        self.isEmpty = ko.computed(function() {
            return !self.startTime() && !self.endTime();
        });

        self.startTime.pretty = ko.computed({
            read: function() {
                return self.startTime() ? moment.unix(self.startTime()).tz(timezone).format(dateTimeFormat) : '';
            },
            write: function(data) {
                self.startTime(moment(data, dateTimeFormat).tz(timezone).unix());
            }
        });

        self.endTime.pretty = ko.computed({
            read: function() {
                return self.endTime() ? moment.unix(self.endTime()).tz(timezone).format(dateTimeFormat) : '';
            },
            write: function(data) {
                self.endTime(moment(data, dateTimeFormat).tz(timezone).unix());
            }
        });

        self.setNow = function() {
            self.startTime(moment.tz(timezone).unix());
            self.endTime(moment.tz(timezone).unix());
        }

        self.duration.pretty = ko.computed({
            read: function() {
                var duration = false;
                var start = self.startTime();
                var end = self.endTime();

                if (start && end) {
                    var duration = end - start;
                }

                var duration = moment.duration(duration * 1000);
                return Math.floor(duration.asHours()) + moment.utc(duration.asMilliseconds()).format(":mm:ss")
            },
            write: function(data) {
                self.endTime(self.startTime() + convertToSeconds(data));
            }
        });

        /*
        self.duration.pretty = ko.computed(function() {
        }, self);
        */

        self.hideActions = function() {
            self.actionsVisible(false);
        };

        self.showActions = function() {
            self.actionsVisible(true);
        };
    }

    function convertToSeconds(str) {
        if (!str) {
            return 0;
        }
        if (str.indexOf(':') >= 0) {
            return moment.duration(str).asSeconds();
        } else {
            return parseFloat(str) * 60 * 60;
        }
    }

    function loadTimeLog(data) {
        model.time_log.removeAll();
        data = JSON.parse(data);
        for (var i=0; i<data.length; i++) {
            model.time_log.push(new TimeModel(data[i]));
        }
        model.time_log.push(new TimeModel());
    }

    function ViewModel(data) {
        var self = this;
        self.time_log = ko.observableArray();

        if (data) {
            data = JSON.parse(data.time_log);
            for (var i=0; i<data.length; i++) {
                self.time_log.push(new TimeModel(data[i]));
            }
        }
        self.time_log.push(new TimeModel());

        self.removeItem = function(item) {
            self.time_log.remove(item);
            self.refresh();
        }

        self.removeItems = function() {
            self.time_log.removeAll();
            self.refresh();
        }

        self.refresh = function() {
            var hasEmpty = false;
            var lastTime = 0;
            for (var i=0; i<self.time_log().length; i++) {
                var timeLog = self.time_log()[i];
                if (timeLog.isEmpty()) {
                    hasEmpty = true;
                }
            }
            if (!hasEmpty) {
                self.addItem();
            }
        }

        self.showTimeOverlaps = function() {
            var lastTime = 0;
            for (var i=0; i<self.time_log().length; i++) {
                var timeLog = self.time_log()[i];
                var startValid = true;
                var endValid = true;
                if (!timeLog.isEmpty()) {
                    if (timeLog.startTime() < lastTime || timeLog.startTime() > timeLog.endTime()) {
                        startValid = false;
                    }
                    if (timeLog.endTime() < Math.min(timeLog.startTime(), lastTime)) {
                        endValid = false;
                    }
                    lastTime = Math.max(lastTime, timeLog.endTime());
                }
                timeLog.isStartValid(startValid);
                timeLog.isEndValid(endValid);
            }
        }

        self.addItem = function() {
            self.time_log.push(new TimeModel());
        }
    }

    window.model = new ViewModel(<?php echo $task; ?>);
    ko.applyBindings(model);

    function onTaskTypeChange() {
        var val = $('input[name=task_type]:checked').val();
        if (val == 'timer') {
            $('#datetime-details').hide();
        } else {
            $('#datetime-details').fadeIn();
        }
        setButtonsVisible();
        if (isStorageSupported()) {
            localStorage.setItem('last:task_type', val);
        }
    }

    function setButtonsVisible() {
        var val = $('input[name=task_type]:checked').val();
        if (val == 'timer') {
            $('#start-button').show();
            $('#save-button').hide();
        } else {
            $('#start-button').hide();
            $('#save-button').show();
        }
    }

    function formEnterClick(event) {
        if (event.keyCode === 13){
            if (event.target.type == 'textarea') {
                return;
            }
            event.preventDefault();
            <?php if($task && $task->trashed()): ?>
                return;
            <?php endif; ?>
            submitAction('');
            return false;
        }
    }

    $(function() {
        $('input[type=radio]').change(function() {
            onTaskTypeChange();
        })

        setButtonsVisible();

        $('#start-button').click(function() {
            submitAction('start');
        });
        $('#save-button').click(function() {
            submitAction('save');
        });
        $('#stop-button').click(function() {
            submitAction('stop');
        });
        $('#resume-button').click(function() {
            submitAction('resume');
        });

        <?php if($task): ?>
            <?php if($task->is_running): ?>
                tock(<?php echo e($task->getLastStartTime() * 1000); ?>);
            <?php endif; ?>
        <?php endif; ?>

        <?php if($errors->first('time_log')): ?>
            loadTimeLog(<?php echo json_encode(Input::old('time_log')); ?>);
            model.showTimeOverlaps();
            showTimeDetails();
        <?php endif; ?>

        $('input.duration').keydown(function(event){
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        // setup clients and project comboboxes
        var clientId = <?php echo e($clientPublicId); ?>;
        var projectId = <?php echo e($projectPublicId); ?>;

        var clientMap = {};
        var projectMap = {};
        var projectsForClientMap = {};
        var projectsForAllClients = [];
        var $clientSelect = $('select#client');

        for (var i=0; i<projects.length; i++) {
          var project = projects[i];
          projectMap[project.public_id] = project;

          var client = project.client;
          if (!client) {
              projectsForAllClients.push(project);
          } else {
              if (!projectsForClientMap.hasOwnProperty(client.public_id)) {
                projectsForClientMap[client.public_id] = [];
              }
              projectsForClientMap[client.public_id].push(project);
          }
        }

        for (var i=0; i<clients.length; i++) {
          var client = clients[i];
          clientMap[client.public_id] = client;
        }

        $clientSelect.append(new Option('', ''));
        for (var i=0; i<clients.length; i++) {
          var client = clients[i];
          var clientName = getClientDisplayName(client);
          if (!clientName) {
              continue;
          }
          $clientSelect.append(new Option(clientName, client.public_id));
        }

        if (clientId) {
          $clientSelect.val(clientId);
        }

        $clientSelect.combobox({highlighter: comboboxHighlighter});
        $clientSelect.on('change', function(e) {
          var clientId = $('input[name=client]').val();
          var projectId = $('input[name=project_id]').val();
          var project = projectMap[projectId];
          if (project && ((project.client && project.client.public_id == clientId) || !project.client)) {
            e.preventDefault();return;
          }
          setComboboxValue($('.project-select'), '', '');
          $projectCombobox = $('select#project_id');
          $projectCombobox.find('option').remove().end().combobox('refresh');
          $projectCombobox.append(new Option('', ''));
          <?php if(Auth::user()->can('createEntity', ENTITY_PROJECT)): ?>
            if (clientId) {
                $projectCombobox.append(new Option("<?php echo e(trans('texts.create_project')); ?>: $name", '-1'));
            }
          <?php endif; ?>
          var list = clientId ? (projectsForClientMap.hasOwnProperty(clientId) ? projectsForClientMap[clientId] : []).concat(projectsForAllClients) : projects;
          for (var i=0; i<list.length; i++) {
            var project = list[i];
            $projectCombobox.append(new Option(project.name,  project.public_id));
          }
          $('select#project_id').combobox('refresh');
        });

        var $projectSelect = $('select#project_id').on('change', function(e) {
            $clientCombobox = $('select#client');
            var projectId = $('input[name=project_id]').val();
            if (projectId == '-1') {
                $('input[name=project_name]').val(projectName);
            } else if (projectId) {
                // when selecting a project make sure the client is loaded
                var project = projectMap[projectId];
                if (project && project.client) {
                    var client = clientMap[project.client.public_id];
                    if (client) {
                        project.client = client;
                        setComboboxValue($('.client-select'), client.public_id, getClientDisplayName(client));
                    }
                }
            } else {
                $clientSelect.trigger('change');
            }
        });

        <?php echo $__env->make('partials/entity_combobox', ['entityType' => ENTITY_PROJECT], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        if (projectId) {
           var project = projectMap[projectId];
           if (project) {
               setComboboxValue($('.project-select'), project.public_id, project.name);
               $projectSelect.trigger('change');
           }
        } else {
           $clientSelect.trigger('change');
        }

        <?php if(!$task): ?>
            var taskType = localStorage.getItem('last:task_type');
            if (taskType) {
                $('input[name=task_type][value='+taskType+']').prop('checked', true);
                onTaskTypeChange();
            }
        <?php endif; ?>

        <?php if(!$task && !$clientPublicId): ?>
            $('.client-select input.form-control').focus();
        <?php else: ?>
            $('#description').focus();
        <?php endif; ?>
    });

    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>