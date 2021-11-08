@php($listErrorKey = "$column.values")

<div class="{{$viewClass['form-group']}} {{ $errors->has($listErrorKey) ? 'has-error' : '' }}">

    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.help-block')

        @if($errors->has($listErrorKey))
            @foreach($errors->get($listErrorKey) as $message)
                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label>
                <br/>
            @endforeach
        @endif

        <table class="table no-padding-tbl">

            <tbody class="list-{{$column}}-table">

            @foreach(old("{$column}.values", ($value ?: [])) as $k => $v)

                @php($itemErrorKey = "{$column}.values.{$loop->index}")

                <tr>
                    <td>
                        <div class="form-group {{ $errors->has($itemErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $column }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}"
                                       class="form-control"/>
                                @if($errors->has($itemErrorKey))
                                    @foreach($errors->get($itemErrorKey) as $message)
                                        <label class="control-label" for="inputError"><i
                                                class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>

                    <td style="width: 75px;">
                        <div class="{{$column}}-remove pull-right">
                            <i class="fa fa-close">&nbsp;</i>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <div class="{{ $column }}-add btn btn-default btn-sm pull-left">
                        <i class="fa fa-plus"></i>&nbsp;{{ __('admin.new') }}
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<template class="{{$column}}-tpl">
    <tr>
        <td>
            <div class="form-group">
                <div class="col-sm-12">
                    <input name="{{ $column }}[values][]" class="form-control"/>
                </div>
            </div>
        </td>
        <td style="width: 75px;">
            <div class="{{$column}}-remove pull-right">
                <i class="fa fa-close">&nbsp;</i>
            </div>
        </td>
    </tr>
</template>
