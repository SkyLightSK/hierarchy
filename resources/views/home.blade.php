
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Hierarchy</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    @php $depth = 0 @endphp
                    <div  class="hierarchy dd">
                        <ol class="dd-list" data-depth="{{$depth}}" data-level-position="{{$users[0]['position']}}" >
                            @foreach($users as $user)
                                <li class="dd-item" data-id="{{$user['user']['id']}}" data-position="{{$user['position']}}" data-position-id="{{$user['id']}}">
                                    <div class="dd-handle">
                                        <span class="dd-content" data-user-id="{{$user['user']['id']}}" data-position="{{$user['position']}}" data-position-id="{{$user['id']}}">
                                            {{ $user['name'] . ' User id:' . $user['user']['id'] . ' Position id:' . $user['id'] }}
                                            <strong class="position-text">{{ ' Position:' . $user['position']  }} </strong>
                                        </span>
                                    </div>
                                    @if(count($user['subordinates']))
                                        @include('layouts.manageChild',['childs' => $user['subordinates'], 'depth' => $depth+1])
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    {{--<textarea rows="50" cols="130" id="nestable-output"></textarea>--}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
