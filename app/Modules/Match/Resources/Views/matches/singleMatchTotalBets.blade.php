@extends('backend.backendMaster')
@section('title', 'Single match total bets')
@section('page_title', 'Single match total bets')
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
                    <div class="p-5" >
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
                                <li class="nav-item" role="presentation"><a class="nav-link  active show" data-toggle="tab" href="#bets" aria-controls="bets" role="tab" aria-selected="false">Bets</a></li>
                            </ul>
                            <div class="tab-content pt-20">                              
                                <div class="tab-pane active show table-responsive" id="bets" role="tabpanel">

                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            {{--<th>#</th>--}}
                                            <th>Action</th>
                                            <th>User</th>
                                            <th width="10%">Bet Option</th>
                                            <th>Bet On</th>
                                            <th>Bet Amount</th>
                                            <th>Bet Rate</th>
                                            <th width="10%">Time</th>
                                            <th>Winner On</th>
                                            <th>Club</th>
                                            <th>User Profit</th>
                                            <th>Site Profit</th>
                                            <th>Partial Profit</th>
                                            <th>Partial Rate</th>
                                            <th>User Status</th>
                                            <th>Admin Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($bets->count() > 0)
                                            @php($i=1)
                                                @foreach($bets as $allBetPlace)
                                                    <tr>
                                                        {{--<td>{{ $i++ }}</td>--}}
                                                        <td>
                                                            <div class="btn-group">
                                                                @if($allBetPlace->status == 0)
                                                                <!--<button type="button" class="btn btn-danger dropdown-toggle" id="action" data-toggle="dropdown" aria-expanded="false"></button>
                                                                    <div class="dropdown-menu dropdown-menu-danger" aria-labelledby="action" role="menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(77px, -5px, 0px);">
                                                                        <a style="text-decoration: none;" class="dropdown-item text-danger" onclick="return confirm('Are you sure return this bet item?')" href="{{ route("user_bet_return",["betplaceid"=>$allBetPlace->id]) }}" role="menuitem">Return</a>
                                                                    </div>-->
                                                                    <input type="button" id="betplaceId" class="btn btn-danger returnSingle" value="Return" />
                                                                    <input type="hidden" value="{{ $allBetPlace->id }}" />
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $allBetPlace->username }}</td>
                                                        <td>{{ ucwords($allBetPlace->betOptionName) }}</td>
                                                        <td>{{ ucwords($allBetPlace->betName) }}</td>
                                                        <td>{{ $allBetPlace->betAmount }}</td>
                                                        <td>{{ $allBetPlace->betRate }}</td>
                                                        <td>
                                                            @if($allBetPlace->created_at)
                                                                <?php
                                                                    $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $allBetPlace->created_at, new DateTimeZone("UTC"));
                                                                    echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M y h:i A');
                                                                ?>
                                                            @endif
                                                        </td>
                                                        <td> @if(isset($allBetPlace->winnerOption)) {{ucwords($allBetPlace->winnerOption)  }} @endif</td>
                                                        <td>{{ $allBetPlace->clubUsername }}</td>
                                                        <td>{{ $allBetPlace->betProfit }}</td>
                                                        <td>{{ $allBetPlace->betLost }}</td>
                                                        <td>{{ $allBetPlace->partialLost }}</td>
                                                        <td>{{ $allBetPlace->partialRate }}</td>
                                                        <td>
                                                            @if($allBetPlace->winLost == 'win')
                                                                <span class="badge badge-success">{{ ucwords($allBetPlace->winLost) }}</span>
                                                            @endif
                                                            @if($allBetPlace->winLost == 'lost')                                                            
                                                                <span class="badge badge-dark">{{ ucwords($allBetPlace->winLost) }}</span>
                                                            @endif
                                                            @if($allBetPlace->winLost == 'bet return')                                                            
                                                                <span class="badge badge-warning">{{ ucwords($allBetPlace->winLost) }}</span> 
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($allBetPlace->status == 0)
                                                                <span class="badge badge-primary">Unpublished</span>
                                                            @else
                                                                <span class="badge badge-success">Published</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="15"><h5 class="text-danger text-center">No bet found</h5></td>
                                            </tr>
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
    </style>
@endsection

@section('page_scripts')
@include('backend.partials._scripts')
@include('backend.partials._formvalidation_script')
    <script type="text/javascript">
        $('#matchManage').addClass('active open');
        $('#matchManageChildLi').addClass('active');
        
        $(document).on("click",".returnSingle", function (e) {
            if (confirm('Are you sure return this bet item?')) {
                var id  = $(this).next().val();
                $(this).css("display", "none");
                $.ajax({
                    type:'GET',
                    url: '/admin/matches/user/bet/return/' + id,
                    success:function(response){
                        console.log('response', response)
                        if((response.status)){
                            toastr.success(response.msg)
                        }else{
                            toastr.warning(response.msg)
                        }
                    }
                });
            }
        });
    </script>
@endsection
