@extends('backend.backendMaster')
@section('title', 'Live matches bet rate update')
@section('page_title', 'Live matches bet rate update')
@section('page_content')
    <!-- Page -->
    <div class="page" style="background: #000">

        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item">{{ $match->matchTitle }}</li>
                <li class="breadcrumb-item active"><span class="badge badge-pill badge-success">{{ $match->teamOne->teamName }} vs {{ $match->teamTwo->teamName }}</span></li>
            </ol>
        </div>

        <div class="page-content container-fluid" >

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-bordered panel-dark border border-dark" style="background: #000">
                        <div class="panel-heading-custom" style="background:#343a40 ">
                            <h3 class="panel-title-custom"><span class="liveNotification"><b class="liveDot"></b>Live Room</span>
                                <a target="_blank" style="float: right;" class="btn btn-xs btn-danger" href="{{ route('matches_unpublished_list',['id'=>$match->id])}}"> <i class="fa fa-eye-slash" aria-hidden="true"></i> Unpublished </a> 
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-dark" href="{{ route('single_match_total_bets',['id'=>$match->id])}}"> <i class="fa fa-history" aria-hidden="true"></i> Bets </a>
                                @if(Auth::guard("admin")->user()->userRole->name == 'supperAdmin')
                                    <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-success" href="{{ route('match_profit_loss',['id'=>$match->id])}}"> <i class="fa fa-money" aria-hidden="true"></i> Profit/Loss </a>
                                @endif
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-primary" href="{{ route('matches_detail',['id'=>$match->id])}}"> <i class="fa fa-plus" aria-hidden="true"></i> Details </a>
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="nav-tabs-horizontal" data-plugin="tabs">
                                <ul class="nav nav-tabs nav-tabs-line tabs-line-top" role="tablist">
                                    {{--<li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#currentBet" aria-controls="currentBet" role="tab" aria-selected="false">Current Bet</a></li>--}}
                                    <li class="nav-item" role="presentation"><a class="nav-link active show" data-toggle="tab" href="#quickbetUpdate" aria-controls="quickbetUpdate" role="tab" aria-selected="false">Quick Bet Update</a></li>
                                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#betAction" aria-controls="betAction" role="tab" aria-selected="false">Bet Action</a></li>
                                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#livescore" aria-controls="livescore" role="tab" aria-selected="false"><span class="badge badge-pill badge-success">Live score</span></a></li>
                                </ul>
                                <div class="tab-content pt-20">
                                    {{-- <div class="tab-pane" id="currentBet" role="tabpanel">
                                        <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                            <button class="btn btn-secondary mb-1" data-target="#betoptionmodal" data-toggle="modal" type="button"><i class="fa fa-plus"></i> Add Bet Option</button>
                                            <div class="row">
                                                @if($optionBetDetails)
                                                    @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                        <div class="col-md-3 col-sm-6 mb-2">
                                                            <div class="panel panel-dark border border-dark"  style="background: #333;">
                                                                <div class="panel-heading" id="accordionId{{ $key }}" role="tab">
                                                                    <a class="panel-title collapsed" data-toggle="collapse" href="#accordionDefaultId{{ $key }}" data-parent="#AccordionDefault" aria-expanded="false" aria-controls="accordionDefaultId{{ $key }}"> {{ ucwords($optionBetDetail['matchOption']) }}</a>
                                                                </div>
                                                                <div class="panel-collapse collapse" id="accordionDefaultId{{ $key }}" aria-labelledby="accordionId{{ $key }}" role="tabpanel">

                                                                    @if($optionBetDetail['betDetails'])
                                                                        <form action="{{ route("total_question_bet_rate_update") }}" method="POST">
                                                                            @csrf
                                                                            @foreach($optionBetDetail['betDetails'] as $key=>$betDetailItem)
                                                                                <input type="hidden" value="{{ $betDetailItem->betoption_id }}" name="betoptionId"/>
                                                                                <input type="hidden" value="{{ $betDetailItem->id }}" name="id[]"/>
                                                                                <div class="row p-2">
                                                                                    <div class="form-group col-md-6">
                                                                                        <input type="text" class="form-control form-control-sm" id="{{ $betDetailItem->id }}" name="betNameEdit" placeholder="bet name" @if($match->status != 0) readonly @endif value="{{ ucfirst($betDetailItem->betName)}}">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <input type="text" class="form-control form-control-sm font-weight-bold" name="betRateEdit[]" placeholder="rate%" value="{{ $betDetailItem->betRate }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                            <div class="form-group col-md-12">
                                                                                <input type="submit" class="btn btn-block btn-secondary" value="Update"/>
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>--}}
                                    
                                    <div class="tab-pane active show" id="quickbetUpdate" role="tabpanel">
                                        <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        @if($optionBetDetails)
                                                            @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                                <div class="col-md-4 col-sm-6 mb-2">
                                                                    <div class="panel panel-dark border border-dark"  style="background: #333;">
                                                                        <div class="panel-heading" id="accordionId{{ $key }}" role="tab">
                                                                            <a style="font-size:16px;font-weight:700;" class="panel-title collapsed" data-toggle="collapse" href="#accordionDefaultId{{ $key }}" data-parent="#AccordionDefault" aria-expanded="false" aria-controls="accordionDefaultId{{ $key }}"> {{ ucwords($optionBetDetail['matchOption']) }}</a>
                                                                        </div>
                                                                        <div class="panel-collapse collapse" id="accordionDefaultId{{ $key }}" aria-labelledby="accordionId{{ $key }}" role="tabpanel">
                                                                        @if($optionBetDetail['betDetails'])
                                                                            @foreach($optionBetDetail['betDetails'] as $key=>$betDetailItem)
                                                                                <form action="" method="POST" autocomplete="off">
                                                                                    @csrf
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-6">
                                                                                            <input style="font-size:16px;font-weight:700;color:red;"  type="text" class="form-control form-control-sm" id="{{ $betDetailItem->id }}" name="betNameEdit" placeholder="bet name" @if($match->status != 0) readonly @endif value="{{ ucfirst($betDetailItem->betName)}}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-6">
                                                                                            <input type="hidden" name="id" value="{{ $betDetailItem->id }}">
                                                                                            <input style="font-size:24px;font-weight:900;color:red;"  type="number" class="form-control form-control-sm editBetRateLive" name="betRateEdit" placeholder="rate%" value="{{ $betDetailItem->betRate }}" autocomplete="off">
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            @endforeach
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="betAction" role="tabpanel" >
                                        <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                            <div class="row">
                                                <div class="col-md-12">
                                                <form action="" method="POST" id="matchesDetails" autocomplete="off">
                                                    @csrf
                                                    <div class="row row-lg">
                                                        <div class="col-xl-4 form-horizontal">

                                                            <div class="form-group">
                                                                <div class="input-group input-group-icon">
                                                                    <div class="input-group-prepend">
                                                                        <div style="font-size:18px;font-weight:bold" class="input-group-text"> Total Bet On Off <span class="required text-danger">*</span> </div>
                                                                    </div>
                                                                    <input type="hidden" value="{{ $match->id }}" id="fullMatchId" />
                                                                    <select class="form-control" id="fullMatchStatus" style="font-size:18px;font-weight:bold">
                                                                        <option class="text-success font-weight-bold" value="1" @if($allBetOff == 0) selected @endif >On</option>
                                                                        <option class="text-warning font-weight-bold" value="0" @if($allBetOff > 0) selected @endif >Off</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-xl-3 form-horizontal">
                                                            <button type="button" class="btn btn-warning" id="matchDetailsUpdate">Total Bet On Off</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                            
                                            <hr class="dashed">
                                            <div class="row">
                                                @if($optionBetDetails)
                                                    @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <p>
                                                                <span class="badge badge-dark font-size-18 font-weight-bold"> Q : {{ ucwords($optionBetDetail['matchOption']) }}</span>
                                                            </p>
                                                            <div class="form-group">
                                                                <form action="" method="POST" autocomplete="off">
                                                                    @csrf
                                                                    <div class="input-group input-group-icon">
                                                                        <div class="input-group-prepend">
                                                                            <div style="font-size:16px;font-weight:bold;" class="input-group-text"> Single ON Off <span class="required text-danger">*</span> </div>
                                                                        </div>
                                                                        <input type="hidden" value="{{$optionBetDetail['match_id']}}" id="match_id"/>
                                                                        <select style="font-size:16px;font-weight:bold" class="form-control optionOnOFF" name="status">
                                                                            <option class="text-success font-weight-bold" value="2">Select</option>
                                                                            <option class="text-success font-weight-bold" value="1">On</option>
                                                                            <option class="text-warning font-weight-bold" value="0">Off</option>
                                                                        </select>
                                                                        <input type="hidden" value="{{$optionBetDetail['betoption_id']}}" id="betoption_id"/>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="form-group">
                                                                <form action="" method="POST" autocomplete="off">
                                                                    @csrf
                                                                    <div class="input-group input-group-icon">
                                                                        <div class="input-group-prepend">
                                                                            <div style="font-size:16px;font-weight:bold;" class="input-group-text"> Single Open Hide<span class="required text-danger">*</span> </div>
                                                                        </div>
                                                                        <input type="hidden" value="{{$optionBetDetail['match_id']}}" id="hide_match_id"/>
                                                                        <select style="font-size:16px;font-weight:bold" class="form-control singleOptionHideOpen">
                                                                            <option class="text-success font-weight-bold" value="3">Select</option>
                                                                            <option class="text-success font-weight-bold" value="1">Open</option>
                                                                            <option class="text-warning font-weight-bold" value="2">Hide</option>
                                                                        </select>
                                                                        <input type="hidden" value="{{$optionBetDetail['betoption_id']}}" id="hide_betoption_id"/>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="livescore" role="tabpanel">
                                        <div class="p-5">
                                            <form action="" method="POST" id="exampleFullForm" autocomplete="off">
                                                @csrf
                                                <div class="row row-lg">
                                                    <div class="col-xl-6 form-horizontal">
                                                        @if($score->match->sport_id == 1)
                                                            <div class="form-group">
                                                                <div class="input-group input-group-icon">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            Overs
                                                                            <span class="required text-danger"> *</span>
                                                                        </div>
                                                                    </div>
                                                                    <input style="font-size:24px;font-weight:bold;color:red;" type="text" class="form-control" id="overs" value="@if($score->overs){{ $score->overs }}@endif">
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <div class="input-group input-group-icon">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        Score Card
                                                                        <span class="required text-danger"> *</span>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" class="form-control" id="scoreId" value="{{ $score->id }}">
                                                                <textarea style="font-size:24px;font-weight:bold;color:red;" class="form-control" id="score" >@if($score->score) {{ $score->score}}@endif</textarea>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row row-lg">
                                                    <div class="form-group col-xl-2 padding-top-m">
                                                        <button type="button" class="btn btn-success" id="updateScore">Update Score</button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="p-5">
                                <form action="{{ route("match_details_action",["id"=>$match->id]) }}" method="POST" id="matchesDetails" autocomplete="off">
                                    @csrf
                                    <div class="row row-lg">
                                        <div class="col-xl-6 form-horizontal">

                                            <div class="form-group">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend ">
                                                        <div class="input-group-text"> Action <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <select class="form-control" id="status" name="status"  style="background: #333;">
                                                        <option class="text-success font-weight-bold" value="2" @if($match['status'] == 2) selected="selected" @endif>Go Dashboard</option>
                                                        <option class="text-warning font-weight-bold" value="3" @if($match['status'] == 3) selected="selected" @endif>Go Live</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row row-lg">
                                        <div class="form-group col-xl-2 padding-top-m">
                                            <button onclick="return confirm('Are you sure to change action?')"  type="submit" class="btn btn-dark" id="matchDetailsUpdate">Change Action</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="betoptionmodal" aria-hidden="true" aria-labelledby="betoptionmodal" role="dialog" tabindex="-1">
                        <div class="modal-dialog modal-simple modal-top modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">Add Bet Option</h4>
                                </div>

                                <form action="{{ route('matches_betsetup_live',['id'=>$match->id]) }}" method="POST" id="exampleFullForm"
                                      autocomplete="off">
                                    @csrf
                                    <div class="modal-body">
                                        <p>
                                            <span class="badge badge-pill badge-primary">{{ ucfirst($match->sport->sportName) }} </span>
                                            <span class="badge badge-pill badge-success">@if($match->matchTitle){{ ucfirst($match->matchTitle) }}@else  Match title not given @endif </span>
                                            <span class="badge badge-pill badge-info">{{ ucwords($match->teamOne->teamName) }} VS {{ ucwords($match->teamTwo->teamName) }} </span>
                                            <span class="badge badge-pill badge-danger">{{ ucwords($match->tournament->tournamentName) }} </span>
                                            <span class="badge badge-pill badge-dark">{{ date("d M y",strtotime($match->matchDateTime)) }} || {{ date("h:i A",strtotime($match->matchDateTime)) }} </span>
                                        </p>

                                        <div class="form-group">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> Choose Bet Option Name <span class="required text-danger">*</span></div>
                                                </div>
                                                <input type="hidden" name="match_id" value="{{ $match->id}}" />
                                                <select class="form-control" id="betoption_id" name="betoption_id" data-plugin="select2" required>
                                                    <option value="">Select One Bet Option Name</option>
                                                    @if($betOptions)
                                                        @foreach ($betOptions as $betOption)
                                                            <option value="{{ $betOption->id}}">
                                                                {{ ucwords($betOption->betOptionName)}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> Choose Option Type <span class="required text-danger">*</span> </div>
                                                </div>
                                                <select class="form-control" id="option_type" name="option_type" data-plugin="select2" required>
                                                    <option value="">Select Option Type</option>
                                                    <option value="twoTeam">Two Team</option>
                                                    <option value="twoTeamDraw">Two Team Draw/Tri</option>
                                                    <option value="customType">Custom</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="twoTeam" class="row wrap-option">

                                            <div class="form-group col-md-6">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"> {{ ucwords($match->teamOne->teamName) }} <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <input required class="form-control twoTeamTeamNameRate" type="hidden" name="betName[]" value="{{ $match->teamOne->teamName }}" />
                                                    <input required class="form-control twoTeamTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"> {{ ucwords($match->teamTwo->teamName) }} <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <input required class="form-control twoTeamTeamNameRate" type="hidden" name="betName[]" value="{{ $match->teamTwo->teamName }}" />
                                                    <input required class="form-control twoTeamTeamNameRate  font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                                </div>
                                            </div>

                                        </div>

                                        <div id="twoTeamDraw" class="row wrap-option">

                                            <div class="form-group col-md-4">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"> {{ ucwords($match->teamOne->teamName) }} <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <input required class="form-control twoTeamDrawTeamNameRate" type="hidden" name="betName[]" value="{{ $match->teamOne->teamName }}" />
                                                    <input required class="form-control twoTeamDrawTeamNameRate  font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"> Draw-Tri <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <input required class="form-control twoTeamDrawTeamNameRate" type="hidden" name="betName[]" value="draw"/>
                                                    <input required class="form-control twoTeamDrawTeamNameRate  font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <div class="input-group input-group-icon">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"> {{ ucwords($match->teamTwo->teamName) }} <span class="required text-danger">*</span> </div>
                                                    </div>
                                                    <input required class="form-control twoTeamDrawTeamNameRate" type="hidden" name="betName[]" value="{{ $match->teamTwo->teamName }}" />
                                                    <input required class="form-control twoTeamDrawTeamNameRate  font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                                </div>
                                            </div>

                                        </div>

                                        <div id="customType" class="row wrap-option">
                                            <div class="col-sm-10 field_wrapper">
                                                <input required class="CustomInputTextStyle customTypeTeamNameRate" type="text" name="betName[]" id="betName" placeholder="Bet Name" />&nbsp;&nbsp;
                                                <input required class="CustomInputTextStyle customTypeTeamNameRate" type="text" name="betRate[]" id="betRate" placeholder="Rate %" /> &nbsp;
                                                <a href="javascript:void(0);" class="add_button btn btn-sm btn-outline-info " title="Add field"> <i class="fa fa-plus-square"></i> </a>
                                                <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-question-circle-o  text-danger"  data-original-title="For add more image click this button"></span><br />
                                            </div>
                                        </div>

                                    </div>

                                    <div style="display:block;" class="modal-footer">
                                        <button type="submit" class="btn btn-sm btn-secondary" id="validateButton1">Add Bet Option</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                </div>
            </div>
        </div>

    </div>
    <!-- End Page -->

@endsection

@section('page_styles')
    @include('backend.partials._styles')
    <link rel="stylesheet" type="text/css" href="{{ asset("/select2/select2.min.css")}}" />
    <style type="text/css">

        .panel-heading-custom {
            color: #fff;
            background-color: #0bb2d4;
            border: none;
            height:50px
        }

        .panel-title-custom {
            display: block;
            padding: 13px 15px;
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

        .liveNotification {
            background: #ff3952;
            border-radius: 5px;
            padding: 3px 8px;
            margin-top: -6px;
            position: relative;
            padding-left: 15px;
        }
        .liveDot {
            width: 5px;
            height: 5px;
            color: red;
            background: red;
            position: absolute;
            border-radius: 5px;
            left: 6px;
            top: 12px;
            animation: customZoomIn 2s infinite;
        }
        @keyframes customZoomIn {
            /* You could think of as "step 1" */
            0% {
                background: #ffffff;
            }
            /* You could think of as "step 2" */
            100% {
                background: #ff3952;
            }
        }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0;
        }
    </style>
@endsection

@section('page_scripts')
    @include('backend.partials._scripts')
    @include('backend.partials._formvalidation_script')
    <script src="{{ asset('/select2/select2.min.js') }}"></script>
    <script src="{{ asset('/validation/betNameValidation.js') }}"></script>
    <script src="{{ asset('/validation/matchDetails.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //For added multiple image option
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="mt-2"><input class="CustomInputTextStyle customTypeTeamNameRate" type="text" id="" name="betName[]" id="betName" placeholder="Bet Name" />&nbsp;&nbsp;&nbsp;<input class="CustomInputTextStyle customTypeTeamNameRate" type="text" id="" name="betRate[]" id="betRate" placeholder="Rate %" /> &nbsp;<a class="remove_button btn btn-sm btn-outline-danger ml-1" href="javascript:void(0);"><i class="fa fa-trash"></i></a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

        $(document).on("keyup",".editBetRateLive", function (e) {
            if(e.keyCode == 13) {
                var id  = $(this).prev().val()
                var betRateEdit   = $(this).val()
                
                $.ajax({
                    type:'POST',
                    url: '/admin/matches/ajax/live/data/update/live/room',
                    data: {
                        betRateEdit  : betRateEdit,
                        id : id,
                    },
                    success:function(response){
                        console.log(response)
                        if((response.status)){
                            toastr.success(response.msg)
                        }else{
                            toastr.warning(response.msg)
                        }
                    }
                });
            }
            
        });
        
        
        //Full match bet off
        $("#matchDetailsUpdate").click(function (e) {
            e.preventDefault();
            var matchId         = $("#fullMatchId").val();
            var fullMatchStatus = $("#fullMatchStatus").val();
            $.ajax({
                type:'POST',
                url: '/admin/matches/total/match/bet/off',
                data: {
                    matchId         : matchId,
                    fullMatchStatus : fullMatchStatus,
                },
                success:function(response){
                    console.log(response);
                    if((response.errors)){
                        toastr.success(response.e)
                    }else{
                        toastr.success(response.msg)
                    }
                }
            });
        });
        
        
        $(document).on("change",".optionOnOFF", function () {
            var status     = $(this).val();
            var match_id   = $(this).prev().val()
            var option_id  = $(this).next().val()
            
            if(status == 2){
                return toastr.warning("Please select right option.")
            }
            
            $.ajax({
                type:'POST',
                url: '/admin/matches/bet/action/on/off',
                data: {
                    status    : status,
                    match_id  : match_id,
                    option_id : option_id,
                },
                success:function(response){
                    console.log(response);
                    if((response.status)){
                        toastr.success(response.msg)
                    }else{
                        toastr.warning(response.msg)
                    }
                }
            });
            
        });

        
        $(document).on("change",".singleOptionHideOpen", function () {
            var status     = $(this).val();
            var match_id   = $(this).prev().val()
            var option_id  = $(this).next().val()
            
            if(status == 3){
                return toastr.warning("Please select right option.")
            }
            
            $.ajax({
                type:'POST',
                url: '/admin/matches/bet/hide/open/user/page/ajax',
                data: {
                    status    : status,
                    match_id  : match_id,
                    option_id : option_id,
                },
                success:function(response){
                    console.log(response);
                    if((response.status)){
                        toastr.success(response.msg)
                    }else{
                        toastr.warning(response.msg)
                    }
                }
            });
            
        });
        
        
        //Score update
        $("#updateScore").click(function (e) {
            e.preventDefault();
            var scoreId   = $("#scoreId").val();
            var overs     = $("#overs").val();
            var score     = $("#score").val();
            if(overs == "undefined"){
                overs = null;
            }
            $.ajax({
                type:'POST',
                url: '/admin/score/live/matches/update',
                data: {
                    scoreId : scoreId,
                    overs   : overs,
                    score   : score,
                },
                success:function(response){
                    console.log(response);
                    if((response.errors)){
                        toastr.success(response.e)
                    }else{
                        toastr.success(response.msg)
                    }
                }
            });
        });
        
        //Once add button is clicked
        $(addButton).click(function(){
            //Check maximum number of input fields
            if(x < maxField){
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });

        //For add post
        $('#option_type').change(function(){
            var option_type = $(this).val();

            if(option_type == ''){
                //console.log('section 1');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
            }

            if(option_type == 'twoTeam'){
                //console.log('section 1');
                $("#twoTeam").removeClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#customType").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', false);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
            }
            if(option_type == 'twoTeamDraw'){
                //console.log('section 2');
                $("#twoTeamDraw").removeClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#customType").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', false);
                $(".customTypeTeamNameRate").prop('disabled', true);
            }
            if(option_type == 'customType'){
                //console.log('section 3');
                $("#customType").removeClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', false);
            }

        });
        
        $('form').on('focus', 'input[type=number]', function (e) {
          $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault()
          })
        })
        $('form').on('blur', 'input[type=number]', function (e) {
          $(this).off('wheel.disableScroll')
        })
    </script>
@endsection
