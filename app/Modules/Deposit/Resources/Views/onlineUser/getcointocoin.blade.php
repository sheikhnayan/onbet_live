@extends('backend.backendMaster')
@section('title', 'Get Coin to Coin list')
@section('page_title', 'Get Coin to Coin list')
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
                                        <table class="table table-hover dataTable table-striped w-full" id="">
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
                                                <th>Refund</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
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
                                                <th>Refund</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(count($getCoinToCoins) > 0)
                                                @php($i=1)
                                                @foreach ($getCoinToCoins as $getCoinToCoin)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td> {{ $getCoinToCoin->userCreated->username }}</td>
                                                        <td> {{ $getCoinToCoin->depositAmount }}</td>
                                                        <td> 
                                                            @if($getCoinToCoin->paymentMethodType == 1)
                                                                Bkash
                                                            @elseif($getCoinToCoin->paymentMethodType == 2)
                                                                Nagod
                                                            @elseif($getCoinToCoin->paymentMethodType == 3)
                                                                Roket
                                                            @endif
                                                        </td>
                                                        <td> <span class="badge badge-pill badge-dark p-2"> {{ $getCoinToCoin->phoneForm }} </span></td>
                                                        <td> {{ $getCoinToCoin->phoneTo }}</td>
                                                        <td>
                                                            <span class="badge badge-pill badge-success p-2"> {{ $getCoinToCoin->depositType }} </span>
                                                        </td>
                                                        <td>
                                                            @if($getCoinToCoin->created_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $getCoinToCoin->created_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($getCoinToCoin->updated_at)
                                                                <?php
                                                                $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $getCoinToCoin->updated_at, new DateTimeZone("UTC"));
                                                                echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $getCoinToCoin->acceptedBy->username }}
                                                        </td>
                                                        <td>
                                                            
                                                                <a onclick="return confirm('Are you sure to refund ?')" class="btn btn-sm btn-danger" href="{{ route("accepted_user_deposit_refund_for_cointocoin",["id"=>$getCoinToCoin->id]) }}">Refund Coin</a>
                                                           
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
        $('#userdeposit').addClass('active open');
        $('#getCoinToCoin').addClass('active');
    </script>
@endsection
