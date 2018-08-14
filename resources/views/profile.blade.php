@extends('layouts.app')

@section('content')
    <div class="container">
        <main>
            <img src="/images/{{$user->avatar}}" alt="" style="width:150px;height: 150px; border-radius: 50%; float: left; margin-right: 25px;">
            <h2>{{$user->name}}</h2>

            <form enctype="multipart/form-data" action="/profile" method="POST">
                <label for="">Update Profile Image</label>
                <input type="file" name="avatar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group row mb-0">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>

        </main>

    </div>
@endsection