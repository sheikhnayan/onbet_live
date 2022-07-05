@extends('backend.backendMaster')
@section('title', 'Online User Manage')
@section('page_title', 'Online User Manage')
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
                                                @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                                    <th>UserInfo</th>
                                                    <th>Change Status</th>
                                                @endif
                                                @if(Auth::guard("admin")->user()->type == 0)
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Change Password </th>
                                                    <th>Stand</th>
                                                @endif
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

                                                        @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                                        <td>
                                                            @if($onlineUser->userInfo){{ $onlineUser->userInfo }}@endif
                                                        </td>
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
                                                        <td> {{ $onlineUser->email }} </td>
                                                        <td> {{ $onlineUser->phone }} </td>
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
                                                        <td>
                                                        <td>
                                                            <a class="btn btn-info" href="{{ route("online_user_password_change",['id'=>$onlineUser->id]) }}">Change Password</a>
                                                        </td>

                                                        @endif

                                                    </tr>
                                                @endforeach
                                            @endif
                                            @if($onlineUser)
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

                                                    @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                                    <td>
                                                        @if($onlineUser->userInfo){{ $onlineUser->userInfo }}@endif
                                                    </td>
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
                                                        <td> {{ $onlineUser->email }} </td>
                                                        <td> {{ $onlineUser->phone }} </td>
                                                        <td><a class="btn btn-info" href="{{ route("online_user_password_change",['id'=>$onlineUser->id]) }}">Change Password</a></td>
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
                                                        <td>
                                                    </td>
                                                    @endif

                                                </tr>
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
        $('#OnlineUserManageChild').addClass('active');
    </script>
@endsection
