<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}"></div>

    <div class="col-md-{{$width['field']}}">

        @if (in_array('submit', $buttons))
            <div class="btn-group pull-right">
                <button type="submit" class="btn btn-danger btn-lg"><i class="fa fa-save">&nbsp;</i> 提 交</button>
            </div>

            @foreach($submit_redirects as $value => $redirect)
                @if (in_array($redirect, $checkboxes))
                    <label class="pull-right" style="margin: 5px 10px 0 0;">
                        <input type="checkbox" class="after-submit" name="after-save"
                               value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}> {{ trans("admin.{$redirect}") }}
                    </label>
                @endif
            @endforeach
        @endif

        @if (in_array('reset', $buttons))
            <div class="btn-group pull-left">
                <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
            </div>
        @endif
    </div>
</div>
