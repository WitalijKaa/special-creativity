@if (session(APP_ERR) || session(APP_MSG))
    @if (!empty($divTop))
        <x-layout.divider />
    @endif
    <x-layout.container>
        @if (session(APP_ERR))
            <div class="alert alert-danger" role="alert">{{ session(APP_ERR) }}</div>
        @elseif (session(APP_MSG))
            <div class="alert alert-success" role="alert">{{ session(APP_MSG) }}</div>
        @endif
    </x-layout.container>
    @if (!empty($divBottom))
        <x-layout.divider />
    @endif
@endif
