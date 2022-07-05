@extends('backend.backendMaster')
@section('title', 'User Withdraw History')
@section('page_title', 'User Withdraw History')
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
                            <h3 class="panel-title">@yield('page_title') <b class="text-dark">Total Deposit: {{ $totalWithdraw }}</b> </h3>
                        </div>
                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <table class="table table-hover table-responsive dataTable table-striped w-full" id="">
                                            <thead>
                                            <tr>
                                                <th scope="col">Username</th>
                                                <th scope="col">To</th>
                                                <th scope="col">Coin</th>
                                                <th scope="col">Payment Type</th>
                                                <th scope="col">Ref</th>
                                                <th scope="col">Request Date</th>
                                                <th scope="col">Accept Date</th>
                                                <th scope="col">Accept By</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @if($userWithdrawHistories->count() > 0)
                                                @foreach($userWithdrawHistories as $userWithdrawHistoy)
                                                    <tr>
                                                        <td scope="row"> <span class="badge badge-danger"> {{ $userWithdrawHistoy->username }} </span></td>
                                                        <td>{{ $userWithdrawHistoy->withdrawNumber }}</td>
                                                        <td>{{ $userWithdrawHistoy->withdrawAmount }}</td>
                                                        <td>{{ ucwords($userWithdrawHistoy->withdrawPaymentType) }}</td>
                                                        <td>
                                                            {{ $userWithdrawHistoy->reference }}
                                                        </td>
                                                        <td>
                                                            @if($userWithdrawHistoy->created_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $userWithdrawHistoy->created_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($userWithdrawHistoy->updated_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $userWithdrawHistoy->updated_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>{{ $userWithdrawHistoy->adminusername }}</td>
                                                        <td>
                                                            @if($userWithdrawHistoy->status == 1)
                                                                <span class="badge badge-warning">Paid</span>
                                                            @endif
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
        $('#withdrawUserRequestAccept').addClass('active');
    </script>
@endsection
