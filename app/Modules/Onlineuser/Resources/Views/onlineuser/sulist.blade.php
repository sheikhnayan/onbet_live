@extends('backend.backendMaster')
@section('title', 'Coin permission List')
@section('page_title', 'Coin permission List')
@section('page_content')
    <!-- Page -->
    <div class="page">
        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">@yield('page_title')</li>
            </ol>
        </div>
        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered panel-info border border-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">@yield('page_title') <b class="text-dark">Total : {{ $countPermissionUser }}</b></h3>
                        </div>
                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <table class="table table-hover table-responsive dataTable table-striped w-full" id="">
                                            <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Username</th>
                                                <th>Club</th>
                                                <th>Balance</th>
                                                <th>R.Deposit</th>
                                                <th>S.Deposit</th>
                                                <th>C.Receive</th>
                                                <th>Sponsor</th>
                                                <th>Profit</th>
                                                <th>C.Transfer</th>
                                                <th>Loss</th>
                                                <th>Withdraw</th>
                                                @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                                    <th>Change Status</th>
                                                @endif
                                                @if(Auth::guard("admin")->user()->type == 0)
                                                    <th>Stand</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($onlineUsers)
                                            @php($i=1)
                                            @foreach($onlineUsers as $onlineUser)
                                                <tr>
                                                    <td> {{ $i++ }} </td>
                                                    <td> {{ $onlineUser->username }} </td>
                                                    <td> {{ $onlineUser->club->clubName }} </td>
                                                    <td> {{ $onlineUser->totalBalance }} </td>
                                                    <td> {{ $onlineUser->totalRegularDepositAmount }} </td>
                                                    <td> {{ $onlineUser->totalSpecialDepositAmount }} </td>
                                                    <td> {{ $onlineUser->totalCoinReceiveAmount }} </td>
                                                    <td> {{ $onlineUser->totalSponsorAmount }} </td>
                                                    <td> {{ $onlineUser->totalProfitAmount }} </td>
                                                    <td> {{ $onlineUser->totalCoinTransferAmount }} </td>
                                                    <td> {{ $onlineUser->totalLossAmount }} </td>
                                                    <td> {{ $onlineUser->totalWithdrawAmount }} </td>
                                                    @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                                    <td>
                                                        <form action="{{ route("online_user_status_change") }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $onlineUser->id }}"/>
                                                            <select name="status" class="form-control">
                                                                <option value="0" @if($onlineUser->status == 0) selected @endif>Account Lock</option>
                                                                <option value="1" @if($onlineUser->status == 1) selected @endif>Account published</option>
                                                                <option @if($onlineUser->status == 2) selected @endif>Account password changed</option>
                                                            </select>
                                                            <input class="btn btn-primary btn-sm" onclick="return confirm('Are you sure?')" type="submit" value="Change Status"/>
                                                        </form>
                                                    </td>
                                                    @endif

                                                    @if(Auth::guard("admin")->user()->type == 0)
                                                    <td>
                                                        <form action="{{ route("online_user_stand") }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $onlineUser->id }}"/>
                                                            <select name="stand" class="form-control">
                                                                <option value="0" @if($onlineUser->stand == 0) selected @endif>Off</option>
                                                                <option value="1" @if($onlineUser->stand == 1) selected @endif>On</option>
                                                            </select>
                                                            <input class="btn btn-primary btn-sm" onclick="return confirm('Are you sure?')" type="submit" value="Change Stand"/>
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Page -->

@endsection

@section('page_styles')
    @include('backend.partials._styles')
    @include('backend.partials._datatable_style')
@endsection


@section('page_scripts')
    @include('backend.partials._scripts')
    @include('backend.partials._datatable_script')
    <script type="text/javascript">
        $('#OnlineUserManage').addClass('active open');
        $('#transfer').addClass('active');
    </script>
@endsection
