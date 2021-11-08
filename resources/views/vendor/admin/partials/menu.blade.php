@if(Admin::user()->visible($item['roles']) && (empty($item['permission']) ?: Admin::user()->can($item['permission'])))

    <li class="{{ isset($item['children']) ? 'seconds' : '' }}">

        @if(isset($item['children']))
            <a href="javascript:;">
                @elseif(url()->isValidUrl($item['uri']))
                    <a href="{{ $item['uri'] }}" target="_blank">
                        @else
                            <a href="{{ admin_base_path($item['uri']) }}">
                                @endif
                                <i class="fa {{$item['icon']}}" style="font-size: 1.6em"></i>
                                <span>{{ admin_trans($item['title']) }}</span>
                            </a>
    </li>
@endif
