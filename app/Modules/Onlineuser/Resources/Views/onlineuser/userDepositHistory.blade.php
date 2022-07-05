@extends('backend.backendMaster')
@section('title', 'Deposit History')
@section('page_title', "Deposit History")
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
                            <h3 class="panel-title">@yield('page_title') <b class="text-dark">Total Deposit: {{ $totalDeposit }}</b></h3>
                        </div>

                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <table class="table table-responsive table-hover dataTable table-striped w-full" id="">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Form</th>
                                                <th>To</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Accepted At</th>
                                                <th>Accepted</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($getMoneys) > 0)
                                                @php($i=1)
                                                @foreach ($getMoneys as $getMoney)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td> <span class="badge badge-danger"> {{ $getMoney->username }} <span class="badge badge-danger"></td>
                                                        <td> {{ $getMoney->depositAmount }}</td>
                                                        <td> {{ $getMoney->paymentMethodType }}</td>
                                                        <td> <span class="badge badge-pill badge-dark p-2"> {{ $getMoney->phoneForm }} </span></td>
                                                        <td> {{ $getMoney->phoneTo }}</td>
                                                        <td>
                                                            <span class="badge badge-pill badge-success p-2"> {{ $getMoney->depositType }} </span>
                                                        </td>
                                                        <td>
                                                            @if($getMoney->created_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $getMoney->created_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($getMoney->updated_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $getMoney->updated_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $getMoney->adminusername }}
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
@endsection
