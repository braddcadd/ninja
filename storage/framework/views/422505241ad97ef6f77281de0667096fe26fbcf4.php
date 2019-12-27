<table width="100%">
    <tr>
        <td width="50%" style="vertical-align:top;">
            <table class="table table-striped datatable">
                <tbody>
                <tr><td class="td-left"><?php echo trans('texts.ticket_number'); ?></td><td class="td-right"><?php echo $ticket->ticket_number; ?></td></tr>
                <tr><td class="td-left"><?php echo trans('texts.category'); ?>:</td><td class="td-right"><?php echo $ticket->category->name; ?></td></tr>
                <tr><td class="td-left"><?php echo trans('texts.subject'); ?>:</td><td class="td-right"><?php echo substr($ticket->subject, 0, 30); ?></td></tr>
                <?php if($ticket->client): ?>
                    <tr><td class="td-left" style="height:60px"><?php echo trans('texts.client'); ?>:</td><td class="td-right"><?php echo $ticket->client->name; ?></td></tr>
                <?php else: ?>
                    <tr><td class="td-left" style="height:60px"><?php echo trans('texts.client'); ?>:</td>
                        <td class="td-right">
                            <?php echo Former::select('client_public_id')
                            ->label('')
                            ->addOption('', '')
                            ->data_bind("dropdown: client_public_id, enable: isAdminUser, dropdownOptions: {highlighter: comboboxHighlighter}")
                            ->addClass('')
                            ->addGroupClass(''); ?>

                        </td></tr>
                <?php endif; ?>

                <?php if(count($ticket->child_tickets) > 0): ?>
                    <tr><td class="td-left"><?php echo trans('texts.linked_tickets'); ?></td><td class="td-right">
                            <?php $__currentLoopData = $ticket->child_tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo link_to("tickets/{$child->public_id}", $child->public_id ?: '')->toHtml(); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td></tr>
                <?php elseif($ticket->getContactName()): ?>
                    <tr><td class="td-left" style="height:77px"><?php echo trans('texts.contact'); ?>:</td><td class="td-right"><?php echo $ticket->getContactName(); ?></td></tr>
                <?php elseif($ticket->parent_ticket_id): ?>
                    <tr><td class="td-left"><?php echo trans('texts.parent_ticket'); ?></td><td class="td-right">
                            <?php echo link_to("tickets/{$ticket->parent_ticket->public_id}", $ticket->parent_ticket->public_id ?: '')->toHtml(); ?>

                        </td></tr>
                <?php endif; ?>
                <tr><td class="td-left"><?php echo trans('texts.assigned_to'); ?>:</td><td class="td-right">
                        <?php if(Auth::user()->id == Auth::user()->account->account_ticket_settings->ticket_master->id): ?>
                            <div id="">
                                <?php echo Former::select('agent_id')
                                    ->label('')
                                    ->text(trans('texts.ticket_master'))
                                    ->addOption('', '')
                                    ->fromQuery($account->users, 'displayName', 'id'); ?>

                            </div>
                        <?php elseif($ticket->agent): ?>
                            <?php echo $ticket->agent->getName(); ?> <?php echo Icon::create('random'); ?>

                        <?php endif; ?>
                    </td></tr>
                </tbody>
            </table>
        </td>
        <td width="50%" style="vertical-align:top;">
            <table class="table table-striped datatable">
                <tbody>
                <tr><td class="td-left"><?php echo trans('texts.created_at'); ?>:</td><td class="td-right"><?php echo \App\Libraries\Utils::fromSqlDateTime($ticket->created_at); ?></td></tr>
                <tr><td class="td-left"><?php echo trans('texts.last_updated'); ?>:</td><td class="td-right"><?php echo \App\Libraries\Utils::fromSqlDateTime($ticket->updated_at); ?></td></tr>
                <tr><td class="td-left"><?php echo trans('texts.status'); ?>:</td><td class="td-right"> <?php echo $ticket->getStatusName(); ?> </td></tr>

                <tr><td class="td-left"><?php echo trans('texts.due_date'); ?>:</td>
                    <td class="td-right">
                        <input id="due_date" type="text" data-bind="value: due_date.pretty, enable: isAdminUser" name="due_date"
                               class="form-control time-input time-input-end" placeholder="<?php echo e(trans('texts.due_date')); ?>"/>
                    </td>
                </tr>
                <tr><td class="td-left"><?php echo trans('texts.priority'); ?>:</td>
                    <td class="td-right">
                        <?php echo Former::select('priority_id')->label('')
                        ->fromQuery(\App\Models\Ticket::getPriorityArray(), 'name', 'id'); ?>

                    </td>
                </tr>

                <?php if(!$ticket->merged_parent_ticket_id): ?>
                    <tr>
                        <td></td>
                        <td><span class="pull-right">
                                        <?php echo Button::primary(trans('texts.save'))->small()->withAttributes(['onclick' => 'saveAction()']); ?>

                                    </span></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="td-left"><?php echo trans('texts.parent_ticket'); ?>:</td>
                        <td> <?php echo link_to("tickets/{$ticket->merged_ticket_parent->public_id}", $ticket->merged_ticket_parent->public_id ?: '')->toHtml(); ?>

                        </td>
                    </tr>
                <?php endif; ?>

                <?php if(count($ticket->merged_children) > 0): ?>
                    <tr>
                        <td class="td-left"><?php echo trans('texts.linked_tickets'); ?>:</td>
                        <td>
                            <?php $__currentLoopData = $ticket->merged_children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(trans('texts.ticket_number')); ?> <?php echo link_to("tickets/{$child->public_id}", $child->public_id ?: '')->toHtml(); ?> <br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>