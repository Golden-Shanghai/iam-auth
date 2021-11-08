<style type="text/css">
    .slimScrollDiv, .sidebar {
        overflow: initial !important;
    }
</style>

{{-- 子菜单 --}}
<div class="sliders" style="display: none;">
    @foreach(Admin::menu() as $item)
        @if(Admin::user()->visible($item['roles']) && (empty($item['permission']) ?: Admin::user()->can($item['permission'])) && isset($item['children']))
            <div class="slide">
                <div class="typename">
                    {{ admin_trans($item['title']) }}
                </div>
                @foreach($item['children'] as $childredItem)
                    @if(url()->isValidUrl($childredItem['uri']))
                        <a href="{{ $childredItem['uri'] }}" target="_blank" id="menu-one-{{ $childredItem['id'] }}">
                            @else
                                <a href="{{ admin_base_path($childredItem['uri']) }}"
                                   id="menu-one-{{ $childredItem['id'] }}">
                                    @endif
                                    {{ admin_trans($childredItem['title']) }}
                                </a>
                                @endforeach
                                <div class="switch"></div>
            </div>
        @endif
    @endforeach
</div>

<aside class="main-sidebar">
    <!--主菜单-->
    <section class="sidebar" style="overflow:initial">
        <ul class="sidebar-menu tree">
            @each('admin::partials.menu', Admin::menu(), 'item')
        </ul>
    </section>
</aside>

<script>

    $(function () {

        // 点选「主菜单」
        $('.sidebar-menu li.seconds').on('click', function () {
            // 下属子菜单
            var subMenu = $('.sliders .slide').eq($(this).index('.seconds'));
            // 显示当前子菜单
            subMenu.toggleClass('current');
            // 隐藏其他子菜单
            subMenu.siblings().removeClass('current');
            if ($('.sliders .slide.current').length == 1) {
                // 右侧内容区变小
                $('.content-wrapper').addClass('current');
                $('.sliders').show();
            } else {
                // 右侧内容区变大
                $('.content-wrapper').removeClass('current');
                $('.sliders').hide();
            }
        });

        // 收起全部子菜单
        $('.sliders .slide .switch').on('click', function () {
            $('.content-wrapper').removeClass('current');
            $('.sliders .slide').removeClass('current');
            $('.sliders').hide();
        });

        // 点选「子菜单」
        $('.sliders .slide a').on('click', function () {
            $('.sliders .slide .current').removeClass('current');
            $(this).addClass('current');
        });

    });

</script>
