@extends('backend.backendMaster')
@section('title', 'Profite or loss')
@section('page_title', 'Profite or loss')
@section('page_content')
<!-- Page -->
<div class="page" style="background: #333">

    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item active">@yield('page_title')</li>
        </ol>
    </div>

    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered panel-info border border-info" style="background: #000">
                    <div class="panel-heading-custom">
                        <h3 class="panel-title-custom">@yield('page_title')</h3>
                    </div>
                    <div class="p-5">
                        <ul class="list-group bg-blue-grey-100 bg-inherit">
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-primary">{{ ucfirst($match[0]->sportName) }}</span> Sports </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-success">@if($match[0]->matchTitle){{ $match[0]->matchTitle }}@else Match title not given @endif</span> Matches Title </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-info">{{ ucwords($match[0]->teamFirst) }} VS {{ ucwords($match[0]->teamSecond) }}</span> Match </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-success">{{ date("d M y",strtotime($match[0]->matchDateTime)) }} || {{ date("h:i A",strtotime($match[0]->matchDateTime)) }} </span> Match Date Time </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-danger">{{ $match[0]->tournamentName }}</span> Tournament </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark">{{ ucfirst($match[0]->username) }} ( <?php  $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $match[0]->created_at, new DateTimeZone("UTC")); echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A'); ?> ) </span> Match added </li>
                        </ul>

                        <ul class="list-group bg-blue-grey-100 bg-inherit">
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> 
                                <a target="_blank" style="float: right;" class="btn btn-xs btn-danger" href="{{ route('matches_unpublished_list',['id'=>$match[0]->id])}}"> <i class="fa fa-eye-slash" aria-hidden="true"></i> Unpublished </a> 
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-success" href="{{ route('match_profit_loss',['id'=>$match[0]->id])}}"> <i class="fa fa-money" aria-hidden="true"></i> Profit/Loss </a>
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-dark" href="{{ route('single_match_total_bets',['id'=>$match[0]->id])}}"> <i class="fa fa-history" aria-hidden="true"></i> Bets </a>
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-primary" href="{{ route('live_matches_betrate_view',['id'=>$match[0]->id,'score_id'=>$match[0]->score_id])}}"> <i class="fa fa-plus" aria-hidden="true"></i> Live Room </a>
                            </li>
                        </ul>

                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs nav-tabs-line tabs-line-top" role="tablist">
                                <li class="nav-item" role="presentation"><a class="nav-link active show" data-toggle="tab" href="#details" aria-controls="details" role="tab" aria-selected="true">Details</a></li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="tab-pane active show" id="details" role="tabpanel">
                                    <ul class="list-group list-group-dividered">
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 ) {{ ($allBetPlaces->count()) }} @else 0 @endif </span> Total bets </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalUnpublished->count() > 0 )  {{ ($totalUnpublished->count()) }} @else 0 @endif </span> Total Unpublished Item </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalReturn->count() > 0 ) {{ $totalReturn->count() }} @else 0 @endif </span> Total return bets </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 )  {{ $allBetPlaces->sum("clubGet") }} @else 0 @endif Tk</span> Club Get </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalSponsorGetThisMatch > 0 )  {{ $totalSponsorGetThisMatch }} @else 0 @endif Tk</span> Sponsor Get</li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 )  {{ ($allBetPlaces->sum("betProfit")) }} @else 0 @endif Tk</span> User Get </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 )  {{ ($allBetPlaces->sum("betAmount")) }} @else 0 @endif Tk </span> Total bet amount </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalUnpublished->count() > 0 )  {{ ($totalUnpublished->sum("betAmount")) }} @else 0 @endif Tk </span> Total Unpublished amount </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalReturn->count() > 0 )  {{ $totalReturn->sum("betAmount") }} @else 0 @endif Tk </span> Total return bet amount </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($totalBackUser->count() > 0 )  {{ ($totalBackUser->sum("betAmount")) }} @else 0 @endif Tk</span> User back </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 )  {{ ($allBetPlaces->sum("betLost")) }} @else 0 @endif Tk</span> Site Get </li>
                                        <li  style="background: #333; font-size:20px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark"> @if($allBetPlaces->count() > 0 )  {{ ($allBetPlaces->sum("partialLost")) }} @else 0 @endif Tk</span> Partial Get </li>
                                        <li  style="background: #333; font-size:24px;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-success">@if($allBetPlaces->count() > 0 )  ({{ $allBetPlaces->sum("betLost") }} + {{ $allBetPlaces->sum("partialLost") }}) - ({{ $allBetPlaces->sum("betProfit") }} + {{ $allBetPlaces->sum("clubGet") }} + {{ $totalSponsorGetThisMatch }}) =  {{ ($allBetPlaces->sum("betLost") + $allBetPlaces->sum("partialLost")) - ($allBetPlaces->sum("clubGet") + $totalSponsorGetThisMatch + $allBetPlaces->sum("betProfit")) }} @else 0 @endif Tk</span> Total Profit </li>
                                    </ul>
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
    <style type="text/css">
        .panel-heading-custom {
            color: #fff;
            background-color: #0bb2d4;
            border: none;
        }

        .panel-title-custom {
            display: block;
            padding: 10px 15px;
            margin-top: 0px;
            margin-bottom: 0;
            font-size: 18px;
            color: #fff;
        }

        .CustomInputTextStyle {
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 35px;
            padding: 5px 10px;
            width: 130px;
        }

        .wrap-option {
            display: none;
        }
        .cusHoverCls:hover{background:#ff0000 !important;color:#fff !important;}
        span.select2-results {
            color: #0adc85;
            font-weight: 900;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #b14202;
            font-weight: 900;
        }
        .select2-container--default .select2-results>.select2-results__options {
            max-height: 450px;
        }
    </style>
@endsection

@section('page_scripts')
@include('backend.partials._scripts')
@include('backend.partials._formvalidation_script')
    <script type="text/javascript">
        $('#matchManage').addClass('active open');
        $('#matchManageChildLi').addClass('active');        
    </script>
@endsection
