<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.help-block')

        <table class="table no-padding-tbl">

            <tbody class="name-values-tbody-{{$column}}">

            @foreach (old($column, $value ?: []) as $row)

                @php($nameKey = "{$column}.{$loop->index}.name")

                <tr name="{{$column}}[{{$loop->index}}]">
                    <td style="width: 40%">
                        <div class="form-group {{ $errors->has($nameKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input
                                    name="{{$column}}[{{$loop->index}}][name]"
                                    value="{{ old($nameKey, $row['name']) }}"
                                    class="form-control"
                                    required="required"
                                    placeholder="{{$nameText}}名"
                                />
                                @if ($errors->has($nameKey))
                                    @foreach ($errors->get($nameKey) as $message)
                                        <label class="control-label" for="inputError"><i
                                                class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="width: 40%">
                        @foreach ($row['values'] as $_value)
                            @php($valueKey = "{$column}.{$loop->parent->index}.values.{$loop->index}")
                            <div class="form-group {{ $errors->has($valueKey) ? 'has-error' : '' }}">
                                <div class="col-sm-12">
                                    <input
                                        name="{{$column}}[{{$loop->parent->index}}][values][{{$loop->index}}]"
                                        value="{{ old($valueKey, $_value) }}"
                                        class="form-control pull-left"
                                        required="required"
                                        placeholder="{{$nameText}}值"
                                        style="width: 85%"
                                    />
                                    @if (! $loop->first)
                                        <div class="pull-left {{$column}}-remove-name-value btn-remove-name-value">
                                            <i class="fa fa-trash-o">&nbsp;</i>
                                        </div>
                                    @endif
                                    @if ($errors->has($valueKey))
                                        @foreach ($errors->get($valueKey) as $message)
                                            <label class="control-label" for="inputError"><i
                                                    class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <a class="{{$column}}-add-name-value btn-add-name-value col-sm-12">
                                <i class="fa fa-plus">&nbsp;</i> 新增{{$nameText}}值
                            </a>
                        </div>
                    </td>
                    <td style="width: 20%">
                        <div>
                            <div class="{{$column}}-remove pull-right">
                                <i class="fa fa-close">&nbsp;</i>
                            </div>
                        </div>
                    </td>
                </tr>

            @endforeach

            </tbody>

            <tfoot>
            <tr>
                <td colspan="3">
                    <div class="{{$column}}-add btn btn-default btn-sm pull-left">
                        <i class="fa fa-plus"></i>&nbsp;新增{{$nameText}}
                    </div>
                </td>
            </tr>
            </tfoot>

        </table>
    </div>
</div>

<template class="{{$column}}-tpl">
    <tr name="__ITEM_NAME__">
        <td style="width: 40%">
            <div class="form-group">
                <div class="col-sm-12">
                    <input name="__ITEM_NAME__[name]" class="form-control" required placeholder="{{$nameText}}名"/>
                </div>
            </div>
        </td>
        <td style="width: 40%">
            <div class="form-group">
                <div class="col-sm-12">
                    <input name="__ITEM_NAME__[values][]" class="form-control pull-left" required
                           placeholder="{{$nameText}}值" style="width: 85%"/>
                </div>
            </div>
            <div class="form-group">
                <a class="{{$column}}-add-name-value btn-add-name-value col-sm-12">
                    <i class="fa fa-plus">&nbsp;</i> 新增{{$nameText}}值
                </a>
            </div>
        </td>
        <td style="width: 20%">
            <div>
                <div class="{{$column}}-remove pull-right">
                    <i class="fa fa-close">&nbsp;</i>
                </div>
            </div>
        </td>
    </tr>
</template>

<template class="{{$column}}-tpl-value">
    <div class="form-group">
        <div class="col-sm-12">
            <input name="__ITEM_NAME__[values][]" class="form-control pull-left" required placeholder="{{$nameText}}值"
                   style="width: 85%"/>
            <div class="pull-left {{$column}}-remove-name-value btn-remove-name-value">
                <i class="fa fa-trash-o">&nbsp;</i>
            </div>
        </div>
    </div>
</template>
