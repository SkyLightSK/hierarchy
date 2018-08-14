@if(isset($childs) && isset($depth))
    <ol class="dd-list" data-depth="{{$depth}}" data-level-position="{{$childs[0]['position']}}">
        @foreach($childs as $child)
            <li class="dd-item dd-collapsed" data-id="{{$child['user']['id']}}" data-position="{{$child['position']}}"  data-position-id="{{$child['id']}}">

                @if($depth >= 2 && $depth < 4)
                    <button class="dd-expand {{ $depth > 1 ? 'u-subordinates' : '' }}" data-action="expand" type="button">Expand</button>
                    <button class="dd-collapse" data-action="collapse" type="button">Collapse</button>
                @endif

                @php
                    if(isset($child['name'])){

                        $child_name = $child['name'];

                    }elseif(isset($child['user']['name'])){

                        $child_name = $child['user']['name'];

                    }else{

                        $child_name = 'Unset';

                    }

                @endphp
                <div class="dd-handle">
                    <span class="dd-content" data-user-id="{{$child['user']['id']}}" data-position="{{$child['position']}}" data-position-id="{{$child['id']}}">
                        {{ $child_name . ' User id:' . $child['user']['id'] . ' Position id:' . $child['id']}}
                        <strong class="position-text">{{ ' Position:' . $child['position']  }} </strong>
                    </span>
                </div>
                @if(isset($child['subordinates']) && $depth != 3)
                    @if(count($child['subordinates']))
                        @include('layouts.manageChild',['childs' => $child['subordinates'], 'depth' => $depth+1])
                    @endif
                @endif
            </li>
        @endforeach
    </ol>

@elseif(isset($depth))

    <h1>No depth</h1>

@else

    <h1>No data</h1>

@endif