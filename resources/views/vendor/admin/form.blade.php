<div class="menu_dish_edit">
    <div class="btngroups_top">
        <div class="left">
            <button onclick="history.go(-1)" class="btn btn-sm btn-default">
                <i class="fa fa-arrow-left">&nbsp;</i> 返 回
            </button>
        </div>
        <div class="right">
            <button id="btn-top-submit" type="submit" class="btn btn-sm btn-danger" data-loading-text="提交中">
                <i class="fa fa-save">&nbsp;</i> 提 交
            </button>
        </div>
    </div>
</div>

<div class="box box-info">

    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>
        <div class="box-tools">
            {!! $form->renderTools() !!}
        </div>
    </div>

    {!! $form->open(['class' => "form-horizontal"]) !!}

    <div class="box-body">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($layout->columns() as $column)
                        <div class="col-md-{{ $column->width() }}">
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

    {!! $form->close() !!}

</div>

<script>
    $(function () {
        $('#btn-top-submit').off().on('click', function () {
            $(this).button('loading');
            $(".box-info form").submit();
        });
    });
</script>
