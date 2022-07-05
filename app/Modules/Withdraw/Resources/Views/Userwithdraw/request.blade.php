@extends('backend.backendMaster')
@section('title', 'Request Withdraw')
@section('page_title', 'Request Withdraw')
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
                                    <div class="p-5 table-responsive">
                                        <table class="table table-hover table-striped w-full">
                                            <thead>
                                            <tr>
                                                <th scope="col">SL</th>
                                                <th scope="col">Username</th>
                                                <th scope="col">T.Balance</th>
                                                <th scope="col">T.Deposit</th>
                                                <th scope="col">T.Coin deposit</th>
                                                <th scope="col">T.Coin receive</th>
                                                <th scope="col">T.Coin transfer</th>
                                                <th scope="col">T.Profit</th>
                                                <th scope="col">T.sponser</th>
                                                <th scope="col">T.Loss</th>
                                                <th scope="col">T.Withdraw</th>
                                                <th scope="col">To</th>
                                                <th scope="col">Give Amount</th>
                                                <th scope="col">Payment Type</th>
                                                <th scope="col">Club</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Refund</th>
                                                <th scope="col">H.Bet</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @if($userWithdrawHistories->count() > 0)
                                                @php($i=1)
                                                @foreach($userWithdrawHistories as $userWithdrawHistoy)
                                                    <tr>
                                                        <td scope="row">{{ $i++ }}</td>
                                                        <td scope="row">
                                                            {{ $userWithdrawHistoy->user->username }}
                                                            <a onclick="return confirm('Are you sure to give withdraw ?')" class="btn btn-sm btn-primary" href="{{ route("user_new_withdraw_unpaid",["id"=>$userWithdrawHistoy->id]) }}">Go Processing</a>
                                                        </td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->totalBalance }}</td>
                                                        <td scope="row">
                                                            {{ $userWithdrawHistoy->user->totalRegularDepositAmount }}
                                                            <a class="btn btn-dark" href="{{ route("online_user_deposit_history",['id'=>$userWithdrawHistoy->user_id]) }}">H Deposit</a>
                                                        </td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->totalSpecialDepositAmount }}</td>
                                                        <td scope="row">
                                                            {{ $userWithdrawHistoy->user->totalCoinReceiveAmount }}
                                                            <a class="btn btn-secondary" href="{{ route("online_user_coin_receive_history",['id'=>$userWithdrawHistoy->user_id]) }}">H Coin R</a>
                                                        </td>
                                                        <td scope="row">
                                                            {{ $userWithdrawHistoy->user->totalCoinTransferAmount }}
                                                            <a class="btn btn-info" href="{{ route("online_user_coin_transfer_history",['id'=>$userWithdrawHistoy->user_id]) }}">H Coin T</a>
                                                        </td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->totalProfitAmount }}</td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->totalSponsorAmount }}</td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->totalLossAmount }}</td>

                                                        <td scope="row">
                                                            {{ $userWithdrawHistoy->user->totalWithdrawAmount - $userWithdrawHistoy->withdrawAmount }} 
                                                            <a class="btn btn-danger" href="{{ route("online_user_withdraw_history",['id'=>$userWithdrawHistoy->user_id]) }}">H Withdraw</a>
                                                        </td>
                                                        <td>{{ $userWithdrawHistoy->withdrawNumber }}</td>
                                                        <td><span class="badge badge-warning">{{ $userWithdrawHistoy->withdrawAmount }}</span></td>
                                                        <td>{{ ucwords($userWithdrawHistoy->withdrawPaymentType) }}</td>
                                                        <td scope="row">{{ $userWithdrawHistoy->user->club->username }}</td>
                                                        <td>
                                                            @if($userWithdrawHistoy->created_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $userWithdrawHistoy->created_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($userWithdrawHistoy->status == 0)
                                                                <span class="badge badge-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a onclick="return confirm('Are you sure refund ?')" class="btn btn-sm btn-warning" href="{{ route("admin_user_withdraw_cancel",["id"=>$userWithdrawHistoy->id]) }}">Refund</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-info" href="{{ route("online_user_bet_history",['id'=>$userWithdrawHistoy->user_id]) }}">H Bet</a>
                                                        </td>
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
        $('#withdrawManage').addClass('active open');
        $('#withdrawUserRequest').addClass('active');
    </script>
@endsection
