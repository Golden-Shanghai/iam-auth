<div class="row">
    <div class="{{$viewClass['label']}}"><h4 class="pull-right">{{ $label }}</h4></div>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.help-block')
        <div id="has-many-{{$column}}">
            <table class="table table-has-many has-many-{{$column}}">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    <th class="hidden"></th>
                    @if($options['allowDelete'])
                        <th></th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)

                            @if (is_a($field, \Encore\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="hidden">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td class="form-group">
                                <div>
                                    <div class="remove pull-right"><i class="fa fa-close">&nbsp;</i></div>
                                </div>
                            </td>
                        @endif

                        <td class="form-group">
                            <div>
                                <a class="arrow-up pull-right"><i class="fa fa-arrow-up">&nbsp;</i></a>
                                <a class="arrow-down pull-right"><i class="fa fa-arrow-down">&nbsp;</i></a>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">
                    {!! $template !!}
                    <td class="form-group">
                        <div>
                            <div class="remove pull-right"><i class="fa fa-close">&nbsp;</i></div>
                        </div>
                    </td>
                    <td class="form-group">
                        <div>
                            <a class="arrow-up pull-right"><i class="fa fa-arrow-up">&nbsp;</i></a>
                            <a class="arrow-down pull-right"><i class="fa fa-arrow-down">&nbsp;</i></a>
                        </div>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="form-group">
                    <div class="{{$viewClass['field']}}">
                        <div class="add btn btn-default btn-sm"><i
                                class="fa fa-plus"></i>&nbsp;{{ $btnAddTxt ?? trans('admin.new') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<hr style="margin-top: 0px;">
