<div class="{{$viewClass['form-group']}}">
    <label class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <table class="table no-padding-tbl">
            <thead>
            <tr>
                <th style="width: 40%">{{ $titles['key'] ?? '键' }}</th>
                <th style="width: 40%">{{ $titles['value'] ?? '值' }}</th>
                <th style="width: 75px;"></th>
            </tr>
            </thead>
            <tbody class="kv-{{$column}}-table">
            @foreach(old("{$column}.keys", ($value ?: [])) as $k => $v)
                @php($keysErrorKey = "{$column}.keys.{$loop->index}")
                @php($valsErrorKey = "{$column}.values.{$loop->index}")
                <tr>
                    <td>
                        <div class="form-group {{ $errors->has($keysErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $column }}[keys][]" value="{{ old("{$column}.keys.{$k}", $k) }}"
                                       class="form-control" required/>

                                @if($errors->has($keysErrorKey))
                                    @foreach($errors->get($keysErrorKey) as $message)
                                        <label class="control-label" for="inputError"><i
                                                class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group {{ $errors->has($valsErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $column }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}"
                                       class="form-control"/>
                                @if($errors->has($valsErrorKey))
                                    @foreach($errors->get($valsErrorKey) as $message)
                                        <label class="control-label" for="inputError"><i
                                                class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="form-group">
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
            <div class="form-group  ">
                <div class="col-sm-12">
                    <input name="{{ $column }}[keys][]" class="form-control" required/>
                </div>
            </div>
        </td>
        <td>
            <div class="form-group  ">
                <div class="col-sm-12">
                    <input name="{{ $column }}[values][]" class="form-control"/>
                </div>
            </div>
        </td>

        <td class="form-group">
            <div>
                <div class="{{$column}}-remove pull-right">
                    <i class="fa fa-close">&nbsp;</i>
                </div>
            </div>
        </td>
    </tr>
</template>
