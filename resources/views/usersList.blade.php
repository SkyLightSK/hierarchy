@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="text-center">
            <form  action="" id="db_filter" class="dataTables_filter">
                <label>Search:
                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="dt">
                </label>
                <button type="submit" hidden class="btn btn-primary">Submit</button>
            </form>
            <label>Press Enter.</label>
        </div>

        <div class="table-content">
            <table id="dt" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">Avatar
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                    <th class="th-sm">Name
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                    <th class="th-sm">Position
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                    <th class="th-sm">Recruitment Date
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                    <th class="th-sm">Salary
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                    <th class="th-Rm">Ruler
                        <i class="fa fa-sort float-right" aria-hidden="true"></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="user-row" data-user-id="{{$user['id']}}" data-user-position="{{$user['position']}}" data-position-id="{{$user['userable']['id']}}">
                        <td><img src="/images/{{$user->avatar}}" alt="" style="width:25px;height: 25px; border-radius: 50%;"></td>
                        <td><div data-initial-text="" data-field-type="user" data-field="name" contenteditable>{{$user['name']}}</div></td>
                        <td><div data-initial-text="" data-field-type="user" data-field="position" contenteditable>{{$user['position']}}</div></td>
                        <td><div data-initial-text="" data-field-type="position" data-field="recruitment_date" contenteditable>{{$user['recruitment_date']}}</div></td>
                        <td><div data-initial-text=""  data-field-type="position" data-field="salary_size" contenteditable>{{$user['salary_size']}}</div></td>
                        <td>
                            @if($user['position'] != 'ceo')
                                <div data-initial-text=""  data-field-type="position" data-field="ruler_name" contenteditable>{{$user['ruler_name']}}</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Avatar
                    </th>
                    <th>Name</i>
                    </th>
                    <th>Position</i>
                    </th>
                    <th>Recruitment Date</i>
                    </th>
                    <th>Salary</i>
                    </th>
                    <th>Ruler</i>
                    </th>
                </tr>
                </tfoot>
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection