@extends('backend.backendMaster')
@section('title', 'Zero balance user')
@section('page_title', 'Zero balance user')
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
                            <h3 class="panel-title">@yield('page_title')</h3>
                        </div>
                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <table class="table table-hover table-responsive dataTable table-striped w-full" id="">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Balance</th>
                                                <th>R.Deposit</th>
                                                <th>S.Deposit</th>
                                                <th>C.Receive</th>
                                                <th>Sponsor</th>
                                                <th>Profit</th>
                                                <th>C.Transfer</th>
                                                <th>Loss</th>
                                                <th>Withdraw</th>
                                                <th>H.Bet</th>
                                                <th>H.Deposit</th>
                                                <th>H.Coin T</th>
                                                <th>H.Coin R</th>
                                                <th>H.Withdraw</th>
                                                <th>Club</th>
                                                <th>Sponsor</th>
                                                <th>Country</th>
                                                <th>Status</th>
                                                <th>Created at</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @if(!empty($onlineUsers))
                                                @foreach($onlineUsers as $onlineUser)
                                                    <tr>
                                                        <td> {{ $onlineUser->username }} </td>
                                                        <td> {{ $onlineUser->totalBalance }} </td>
                                                        <td> {{ $onlineUser->totalRegularDepositAmount }} </td>
                                                        <td> {{ $onlineUser->totalSpecialDepositAmount }} </td>
                                                        <td> {{ $onlineUser->totalCoinReceiveAmount }} </td>
                                                        <td> {{ $onlineUser->totalSponsorAmount }} </td>
                                                        <td> {{ $onlineUser->totalProfitAmount }} </td>
                                                        <td> {{ $onlineUser->totalCoinTransferAmount }} </td>
                                                        <td> {{ $onlineUser->totalLossAmount }} </td>
                                                        <td> {{ $onlineUser->totalWithdrawAmount }} </td>
                                                        <td>
                                                            <a class="btn btn-info" href="{{ route("online_user_bet_history",['id'=>$onlineUser->id]) }}">H Bet</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-dark" href="{{ route("online_user_deposit_history",['id'=>$onlineUser->id]) }}">H Deposit</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-info" href="{{ route("online_user_coin_transfer_history",['id'=>$onlineUser->id]) }}">H Coin T</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-secondary" href="{{ route("online_user_coin_receive_history",['id'=>$onlineUser->id]) }}">H Coin R</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-danger" href="{{ route("online_user_withdraw_history",['id'=>$onlineUser->id]) }}">H Withdraw</a>
                                                        </td>
                                                        <td> {{ $onlineUser->club->clubName }} </td>
                                                        <td> @if($onlineUser->sponsorName){{ $onlineUser->sponsorName }}@else Not Given @endif </td>
                                                        <td> {{ $onlineUser->country }} </td>

                                                        <td>
                                                            @if($onlineUser->status == 0)
                                                                <span class="badge badge-round badge-warning">In Active</span>
                                                            @endif

                                                            @if($onlineUser->status == 1)
                                                                <span class="badge badge-round badge-success">Active</span>
                                                            @endif

                                                            @if($onlineUser->status == 2)
                                                                <span class="badge badge-round badge-danger">Password Changed</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($onlineUser->created_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $onlineUser->created_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                        @if(!empty($onlineUsers))
                                        {{ $onlineUsers->links() }}
                                        @endif
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
        $('#zero').addClass('active');
    </script>
@endsection
