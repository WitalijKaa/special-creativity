<div class="btn-group">
    @foreach($items as $btn)
        @if (!empty($btn['dropdown']))
            @php($ddID = mb_strtolower(str_replace(' ', '_', $btn['dropdown']['label'])))
            <div class="btn-group">
                <button id="{{$ddID}}" type="button" class="btn btn-{{$btn['dropdown']['cc'] ?? 'primary'}} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{$btn['dropdown']['label']}}
                </button>
                <ul class="dropdown-menu" aria-labelledby="{{$ddID}}">
                    @foreach($btn['dropdown']['items'] as $ddBtn)
                        <li><a class="dropdown-item" href="{{$ddBtn['route']}}">{{$ddBtn['label']}}</a></li>
                    @endforeach
                </ul>
            </div>
        @elseif (!empty($btn['form']))
            <form onclick="this.querySelector('button')?.click()" action="{{$btn['route']}}" method="post"  class="btn {{empty($btn['cc']) ? 'btn-primary' : 'btn-' . $btn['cc']}} btn-lg">
                {{$btn['label']}}
                @csrf
                <button type="submit" style="opacity: 0; width: 0; height: 0; position: absolute;"></button>
            </form>
        @else
            <x-button.link :cc="$btn['cc'] ?? 'primary'" :route="$btn['route']" :label="$btn['label']" :badge="$btn['badge'] ?? null" />
        @endif
    @endforeach
</div>
