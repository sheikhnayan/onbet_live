@extends('backend.backendMaster')
@section('title', 'Matches Details')
@section('page_title', 'Matches details')
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
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-primary">{{ ucfirst($match->sport->sportName) }}</span> Sports </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-success">@if($match->matchTitle){{ $match->matchTitle }}@else Match title not given @endif</span> Matches Title </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-info">{{ ucwords($match->teamOne->teamName) }} VS {{ ucwords($match->teamTwo->teamName) }}</span> Match </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-success">{{ date("d M y",strtotime($match->matchDateTime)) }} || {{ date("h:i A",strtotime($match->matchDateTime)) }} </span> Match Date Time </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-danger">{{ $match->tournament->tournamentName }}</span> Tournament </li>
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> <span class="badge badge-pill badge-dark">{{ ucfirst($match->userCreated->userRole->name) }} ( <?php  $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $match->created_at, new DateTimeZone("UTC")); echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A'); ?> ) </span> Match added </li>
                        </ul>

                        <ul class="list-group bg-blue-grey-100 bg-inherit">
                            <li  style="background: #333;" class="list-group-item blue-grey-500"> 
                                <a target="_blank" style="float: right;" class="btn btn-xs btn-danger" href="{{ route('matches_unpublished_list',['id'=>$match->id])}}"> <i class="fa fa-eye-slash" aria-hidden="true"></i> Unpublished </a> 
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-success" href="{{ route('match_profit_loss',['id'=>$match->id])}}"> <i class="fa fa-money" aria-hidden="true"></i> Profit/Loss </a>
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-dark" href="{{ route('single_match_total_bets',['id'=>$match->id])}}"> <i class="fa fa-history" aria-hidden="true"></i> Bets </a>
                                <a target="_blank" style="float: right;margin-right:10px;" class="btn btn-xs btn-primary" href="{{ route('live_matches_betrate_view',['id'=>$match->id,'score_id'=>$match->score_id])}}"> <i class="fa fa-plus" aria-hidden="true"></i> Live Room </a>
                            </li>
                        </ul>
                        
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs nav-tabs-line tabs-line-top" role="tablist">
                                <li class="nav-item" role="presentation"><a class="nav-link active show" data-toggle="tab" href="#currentBet" aria-controls="currentBet" role="tab" aria-selected="false">Current Bet</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#betAction" aria-controls="betAction" role="tab" aria-selected="false">Bet Action</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#betIndividualAmount" aria-controls="betIndividualAmount" role="tab" aria-selected="false">Bet Individual Amount</a></li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="tab-pane active show" id="currentBet" role="tabpanel">
                                    <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                        <button class="btn btn-dark mb-1" data-target="#betoptionmodal" data-toggle="modal" type="button"><i class="fa fa-plus"></i> Add Bet Option</button>
                                        <div class="row">
                                            @if($optionBetDetails)
                                            @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                <div class="col-md-6 mb-2">
                                                    <div class="panel panel-dark border border-dark">
                                                        <div class="panel-heading" id="accordionId{{ $key }}" role="tab">
                                                            <a class="panel-title collapsed" data-toggle="collapse" href="#accordionDefaultId{{ $key }}" data-parent="#AccordionDefault" aria-expanded="false" aria-controls="accordionDefaultId{{ $key }}"> {{ ucwords($optionBetDetail['matchOption']) }}</a>
                                                        </div>
                                                        <div class="panel-collapse collapse" id="accordionDefaultId{{ $key }}" aria-labelledby="accordionId{{ $key }}" role="tabpanel"  style="background: #333;">

                                                            @if($optionBetDetail['betDetails'])
                                                                @foreach($optionBetDetail['betDetails'] as $key=>$betDetailItem)
                                                                    <form action="{{ route("update_single_bet_option",["id"=>$betDetailItem->id]) }}" method="POST">
                                                                        @csrf
                                                                        <div class="row p-2">
                                                                            <div class="form-group col-md-2">
                                                                                <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete?')" href="{{route("single_bet_delete",["id"=>$betDetailItem->id])}}">Delete</a>
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <input type="text" class="form-control form-control-sm" id="{{ $betDetailItem->id }}" name="betNameEdit" placeholder="bet name" value="{{ ucfirst($betDetailItem->betName)}}">
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <input type="text" class="form-control form-control-sm" name="betRateEdit" placeholder="rate%" value="{{ $betDetailItem->betRate }}" autocomplete="off">
                                                                            </div>
                                                                            <div class="form-group col-md-2">
                                                                                <input type="submit" class="btn btn-sm btn-dark" value="Update"/>
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
                                <div class="tab-pane" id="betAction" role="tabpanel">
                                    <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                        <div class="row">
                                            @if($optionBetDetails)
                                                @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                    <div class="col-md-4 mb-2">
                                                        <p>{{ ucwords($optionBetDetail['matchOption']) }}</p>
                                                        <form action="{{ route("bet_win_lost") }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">

                                                                <select class="form-control" name="betDetailId"  style="background: #333;">
                                                                    @if($optionBetDetail['betDetails'])
                                                                        <option value=""> Please select </option>
                                                                        @foreach($optionBetDetail['betDetails'] as $key=>$betDetailItem)
                                                                            <option value="{{ $betDetailItem->id }}" >{{ ucfirst($betDetailItem->betName)}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>

                                                                <input type="hidden" name="match_id" value="{{ $optionBetDetail["match_id"] }}" />
                                                                <input type="hidden" name="betoption_id" value="{{ $optionBetDetail["betoption_id"] }}" />

                                                                @if($optionBetDetail["betHideFromUserStatus"] > 0)
                                                                    <span class="badge badge-secondary">Hide form user page</span>
                                                                @endif

                                                                @if($optionBetDetail["betStatus"] == 0)
                                                                    <span class="badge badge-success">On</span>
                                                                @else
                                                                    <span class="badge badge-warning">Off</span>
                                                                @endif
                                                                <span class="badge badge-info">Bet Coin : {{ $optionBetDetail["betCoin"] }}</span>
                                                            </div>

                                                            <div class="form-group">
                                                                {{--@if($optionBetDetail["betHideFromUserStatus"] < 0 || Auth::guard("admin")->user()->userRole->name == 'supperAdmin' && Auth::guard("admin")->user()->type == 0)--}}
                                                                    @if($optionBetDetail["betHideFromUserStatus"] == 0)
                                                                    @if($optionBetDetail["betStatus"] == 0)
                                                                        <a class="btn btn-sm btn-warning cusHoverCls" href="{{ route("bet_action_off",["matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}">Turn Off</a>
                                                                    @else
                                                                        <a class="btn btn-sm btn-info cusHoverCls" href="{{ route("bet_action_on",["matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}">Turn On</a>
                                                                    @endif
                                                                    @else
                                                                        <a class="btn btn-sm btn-secondary cusHoverCls" href="{{ route("bet_action_on",["matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}">Turn On</a>
                                                                    @endif
                                                                {{--@endif--}}
                                                                
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-danger dropdown-toggle" id="action" data-toggle="dropdown" aria-expanded="false"></button>
                                                                    <button type="button" class="btn btn-danger">Action</button>
                                                                    <div class="dropdown-menu dropdown-menu-danger" aria-labelledby="action" role="menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(77px, -5px, 0px);">
                                                                        @if($optionBetDetail["betHideFromUserStatus"] == 0)
                                                                            <a class="dropdown-item text-info cusHoverCls" onclick="return confirm('Are you sure hide form user page?')" href="{{ route("bet_action_hide_form_user_page",["matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}" role="menuitem">Hide Form User Page</a>
                                                                        @endif
                                                                        @if($match->status == 0)
                                                                            <a class="dropdown-item text-danger cusHoverCls" onclick="return confirm('Attention! Are you sure to delete ?')" href="{{ route("bet_action_delete",["matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}" role="menuitem">Delete</a>
                                                                        @endif
                                                                        <a class="dropdown-item text-success cusHoverCls" onclick="return confirm('Are you agree to cut partial 5% ?')" href="{{ route("partial_one",["rate"=>$config->partialOne,"matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}" role="menuitem">Partial {{ $config->partialOne }}%</a>
                                                                        <a class="dropdown-item text-success cusHoverCls" onclick="return confirm('Are you agree to cut partial 3% ?')" href="{{ route("partial_two",["rate"=>$config->partialTwo,"matchId"=>$optionBetDetail['match_id'],"betOptionId"=>$optionBetDetail['betoption_id']]) }}" role="menuitem">Partial {{ $config->partialTwo }}%</a>
                                                                        <a class="dropdown-item text-danger cusHoverCls" onclick="return confirm('Are you sure retrun all bets?')" href="{{ route('return_question_all_bets',['matchId'=>$optionBetDetail['match_id'],'betOptionId'=>$optionBetDetail['betoption_id']]) }}" role="menuitem">Return all bets this question</a>
                                                                    </div>
                                                                </div>

                                                                <input type="submit" onclick="return confirm('Are you sure to published?')" class="btn btn-sm btn-success cusHoverCls" value="Published"/>
                                                                
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="betIndividualAmount" role="tabpanel">
                                    <div class="panel-group" id="AccordionDefault" aria-multiselectable="true" role="tablist">
                                        <div class="row">
                                            @if($optionBetDetails)
                                                @foreach($optionBetDetails as $key=>$optionBetDetail)
                                                    <div class="col-md-4 mb-2">
                                                        <p>{{ ucwords($optionBetDetail['matchOption']) }}</p>
                                                        <div class="form-group">
                                                            <select class="form-control" name="betDetailId"  style="background: #333;">
                                                                @if($optionBetDetail['betDetails'])
                                                                    <option value=""> Check Bet Individual Amount </option>
                                                                    @foreach($optionBetDetail['betDetails'] as $key=>$betDetailItem)
                                                                        <option value="{{ $betDetailItem->id }}" class="text-danger">{{ ucfirst($betDetailItem->betName)}} ({!! \Illuminate\Support\Facades\DB::table('betplaces')->whereIn("status",[0,1,2])->where('betdetail_id',$betDetailItem->id)->sum('betAmount') !!})</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
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
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> Action <span class="required text-danger">*</span> </div>
                                                </div>
                                                <select class="form-control" id="status" name="status"  style="background: #333;">
                                                    {{--<option value="">Select Action</option>--}}
                                                    <option class="text-primary font-weight-bold" value="" @if($match['status'] == 0) selected="selected" @endif>Draft</option>
                                                    <option class="text-info font-weight-bold" value="1" @if($match['status'] == 1) selected="selected" @endif>Upcoming</option>
                                                    <option class="text-success font-weight-bold" value="2" @if($match['status'] == 2) selected="selected" @endif>Go Dashboard</option>
                                                    <option class="text-warning font-weight-bold" value="7" @if($match['status'] == 7) selected="selected" @endif>Hide form user page</option>
                                                    <option class="text-danger font-weight-bold" value="4" @if($match['status'] == 4) selected="selected" @endif>Match Finish</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row row-lg">
                                    <div class="form-group col-xl-2 padding-top-m">
                                        <button onclick="return confirm('Are you sure to change action?')"  type="submit" class="btn btn-info" id="matchDetailsUpdate">Change Match Status</button>
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

                            <form action="{{ route('matches_betsetup',['id'=>$match->id]) }}" method="POST" id="exampleFullForm"
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
                                         <input type="hidden" name="match_id" value="{{ $match->id}}" />
                                        <select class="form-control text-warning" id="betoption_id" name="betoption_id"  data-plugin="select2" required>
                                            <option value="" style="color:#000;font-weight:bold">Select One Bet Option Name</option>
                                                @if($betOptions)
                                                    @foreach ($betOptions as $betOption)
                                                        <option style="color:red;font-weight:900" value="{{ $betOption->id}}">
                                                            {{ ucwords($betOption->betOptionName)}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group input-group-icon">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"> Choose Option Type <span class="required text-danger">*</span> </div>
                                            </div>
                                            <select class="form-control" id="option_type" name="option_type"> <!--data-plugin="select2"--> required>
                                                <option style="color:#000;; font-weight:bold" value="">Select Option Type</option>
                                                <option style="color:#000; font-weight:bold" value="twoTeam">Two Team</option>
                                                <option style="color:#000; font-weight:bold" value="twoTeamDraw">Two Team Draw/Tri</option>
                                                <option style="color:#000; font-weight:bold" value="customType">Custom</option>
                                                <option style="color:#000; font-weight:bold" value="fbofi">1st Ball Runs of 1st & 2nd Innings</option>
                                                <option style="color:#000; font-weight:bold" value="forofi">1st Over Runs of 1st & 2nd Innings</option>
                                                <option style="color:#000; font-weight:bold" value="forofioe">1st Over Runs of 1st & 2nd Innings [Odd/Even]</option>
                                                <option style="color:#000; font-weight:bold" value="fotrofsou">1st Over Total Runs of 1st & 2nd Innings [Over/Under]</option>
                                                <option style="color:#000; font-weight:bold" value="fdmofsi">1st Dismissal Method of 1st Innings</option>
                                                <option style="color:#000; font-weight:bold" value="tycon">Total Yellow Card of Innings</option>
                                                <option style="color:#000; font-weight:bold" value="yesNo">No/Yes</option>
                                                <option style="color:#000; font-weight:bold" value="wicketInPower">Wicket in powerplay</option>
                                                <option style="color:#000; font-weight:bold" value="runtorun">Run to Run</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="twoTeam" class="row wrap-option">

                                        <div class="form-group col-md-6">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> {{ ucwords($match->teamOne->teamName) }} <span class="required text-danger">*</span> </div>
                                                </div>
                                                <input required class="form-control twoTeamTeamNameRate font-weight-bold" type="hidden" name="betName[]" value="{{ $match->teamOne->teamName }}" />
                                                <input style="color:#000" required class="form-control twoTeamTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> {{ ucwords($match->teamTwo->teamName) }} <span class="required text-danger">*</span> </div>
                                                </div>
                                                <input required class="form-control twoTeamTeamNameRate font-weight-bold" type="hidden" name="betName[]" value="{{ $match->teamTwo->teamName }}" />
                                                <input required style="color:#000" class="form-control twoTeamTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                            </div>
                                        </div>

                                    </div>

                                    <div id="twoTeamDraw" class="row wrap-option">

                                        <div class="form-group col-md-4">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> {{ ucwords($match->teamOne->teamName) }} <span class="required text-danger">*</span> </div>
                                                </div>
                                                <input required class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="hidden" name="betName[]" value="{{ $match->teamOne->teamName }}" />
                                                <input required style="color:#000" class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> Draw-Tri <span class="required text-danger">*</span> </div>
                                                </div>
                                                <input required class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="hidden" name="betName[]" value="draw"/>
                                                <input style="color:#000" required class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <div class="input-group input-group-icon">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> {{ ucwords($match->teamTwo->teamName) }} <span class="required text-danger">*</span> </div>
                                                </div>
                                                <input required class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="hidden" name="betName[]" value="{{ $match->teamTwo->teamName }}" />
                                                <input style="color:#000" required class="form-control twoTeamDrawTeamNameRate font-weight-bold" type="text" name="betRate[]" value="" placeholder="rate %" />
                                            </div>
                                        </div>

                                    </div>

                                    <div id="customType" class="row wrap-option">
                                        <div class="col-sm-10 field_wrapper">
                                            <input required class="CustomInputTextStyle customTypeTeamNameRate" type="text" name="betName[]" id="betName" placeholder="Bet Name" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle customTypeTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" placeholder="Rate %" /> &nbsp;
                                            <a href="javascript:void(0);" class="add_button btn btn-sm btn-outline-info " title="Add field"> <i class="fa fa-plus-square"></i> </a>
                                            <span data-toggle="tooltip" data-placement="top" title="" class="fa fa-question-circle-o  text-danger"  data-original-title="For add more image click this button"></span><br />
                                        </div>
                                    </div>

                                    <div id="fbofi" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="Dot Ball" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.45" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="1 Run" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.36" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="2 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="15.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="3 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="35.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="4 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="5.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fbofiTeamNameRate" type="text" name="betName[]" id="betName" value="6 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fbofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="20.00" /> &nbsp;
                                            

                                        </div>
                                    </div>

                                    <div id="forofi" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle forofiTeamNameRate" type="text" name="betName[]" id="betName" value="0-3 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle forofiTeamNameRate" type="text" name="betName[]" id="betName" value="4-6 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.50" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle forofiTeamNameRate" type="text" name="betName[]" id="betName" value="7-9 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.30" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle forofiTeamNameRate" type="text" name="betName[]" id="betName" value="10+ Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.00" /> &nbsp;
                                        </div>
                                    </div>

                                    <div id="forofioe" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle forofioeTeamNameRate" type="text" name="betName[]" id="betName" value="Odd" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofioeTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle forofioeTeamNameRate" type="text" name="betName[]" id="betName" value="Even" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle forofioeTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                        </div>
                                    </div>

                                    <div id="fotrofsou" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle fotrofsouTeamNameRate" type="text" name="betName[]" id="betName" value="Over 6.5" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fotrofsouTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fotrofsouTeamNameRate" type="text" name="betName[]" id="betName" value="Under 6.5" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fotrofsouTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                        </div>
                                    </div>

                                    <div id="fdmofsi" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="Caught" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.50" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="LBW" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="Bowled" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="Run Out" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="5.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="Stamped" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="7.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle fdmofsiTeamNameRate" type="text" name="betName[]" id="betName" value="Others" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle fdmofsiTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="30.00" /> &nbsp;
                                        </div>
                                    </div>

                                    <div id="tycon" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle tyconTeamNameRate" type="text" name="betName[]" id="betName" value="1 Card" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle tyconTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.20" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle tyconTeamNameRate" type="text" name="betName[]" id="betName" value="2 Card" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle tyconTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.10" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle tyconTeamNameRate" type="text" name="betName[]" id="betName" value="3 Card" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle tyconTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle tyconTeamNameRate" type="text" name="betName[]" id="betName" value="4+ Card" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle tyconTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="4.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle tyconTeamNameRate" type="text" name="betName[]" id="betName" value="No Yellow Card" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle tyconTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.75" /> &nbsp;
                                        </div>
                                    </div>
                                    
                                    <div id="yesNo" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle yesNoTeamNameRate" type="text" name="betName[]" id="betName" value="No" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle yesNoTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle yesNoTeamNameRate" type="text" name="betName[]" id="betName" value="Yes" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle yesNoTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="1.85" /> &nbsp;
                                        </div>
                                    </div>
                                    
                                    
                                    <div id="wicketInPower" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle wicketInPowerTeamNameRate" type="text" name="betName[]" id="betName" value="No Wicket" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle wicketInPowerTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="3.56" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle wicketInPowerTeamNameRate" type="text" name="betName[]" id="betName" value="1 Wicket" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle wicketInPowerTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.56" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle wicketInPowerTeamNameRate" type="text" name="betName[]" id="betName" value="2+ Wickets" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle wicketInPowerTeamNameRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.12" /> &nbsp;
                                        </div>
                                    </div>
                                    
                                    
                                    <div id="runtorun" class="row wrap-option">
                                        <div class="col-sm-10">
                                            <input required class="CustomInputTextStyle runtorunRate" type="text" name="betName[]" id="betName" value="0-36 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle runtorunRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle runtorunRate" type="text" name="betName[]" id="betName" value="37-47 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle runtorunRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle runtorunRate" type="text" name="betName[]" id="betName" value="48-56 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle runtorunRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.00" /> &nbsp;
                                            <br/>
                                            <input required class="CustomInputTextStyle runtorunRate" type="text" name="betName[]" id="betName" value="48-56 Runs" />&nbsp;&nbsp;
                                            <input style="color:#000" required class="CustomInputTextStyle runtorunRate font-weight-bold" type="text" name="betRate[]" id="betRate" value="2.00" /> &nbsp;
                                        </div>
                                    </div>
                                    
                                    
                                </div>

                                <div style="display:block;" class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-info" id="validateButton1">Add Bet Option</button>
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
            color: #000;
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
    <script src="{{ asset('/select2/select2.min.js') }}"></script>
    <script src="{{ asset('/validation/betNameValidation.js') }}"></script>
    <script src="{{ asset('/validation/matchDetails.js') }}"></script>
    <script type="text/javascript">

        $('#matchManage').addClass('active open');
        $('#matchManageChildLi').addClass('active');

        //For added multiple image option

        var maxField = 15; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="mt-2"><input class="CustomInputTextStyle customTypeTeamNameRate" type="text" id="" name="betName[]" id="betName" placeholder="Bet Name" />&nbsp;&nbsp;&nbsp;<input style="color:#000" class="CustomInputTextStyle customTypeTeamNameRate font-weight-bold" type="text" id="" name="betRate[]" id="betRate" placeholder="Rate %" /> &nbsp;<a class="remove_button btn btn-sm btn-outline-danger ml-1" href="javascript:void(0);"><i class="fa fa-trash"></i></a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

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
                $("#fbofi").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');
            }

            if(option_type == 'twoTeam'){
                //console.log('section 1');
                $("#twoTeam").removeClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', false);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'twoTeamDraw'){
                //console.log('section 2');
                $("#twoTeamDraw").removeClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', false);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'customType'){
                //console.log('section 3');
                $("#customType").removeClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', false);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'fbofi') {
                $("#fbofi").removeClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', false);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'forofi') {
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").removeClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', false);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'forofioe') {
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").removeClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', false);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            if(option_type == 'fotrofsou') {
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").removeClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', false);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            
            if(option_type == 'fdmofsi') {
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").removeClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', false);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            
            if(option_type == 'tycon') {
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").removeClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', false);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            
            if(option_type == 'yesNo') {
                $("#yesNo").removeClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', false);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', true);
            }
            
            
            if(option_type == 'wicketInPower') {
                $("#wicketInPower").removeClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#runtorun").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', false);
                $(".runtorunRate").prop('disabled', true);
            }
            
            if(option_type == 'runtorun') {
                $("#runtorun").removeClass('wrap-option');
                $("#fbofi").addClass('wrap-option');
                $("#customType").addClass('wrap-option');
                $("#twoTeam").addClass('wrap-option');
                $("#twoTeamDraw").addClass('wrap-option');
                $("#forofi").addClass('wrap-option');
                $("#forofioe").addClass('wrap-option');
                $("#fotrofsou").addClass('wrap-option');
                $("#fdmofsi").addClass('wrap-option');
                $("#tycon").addClass('wrap-option');
                $("#yesNo").addClass('wrap-option');
                $("#wicketInPower").addClass('wrap-option');

                $(".twoTeamTeamNameRate").prop('disabled', true);
                $(".twoTeamDrawTeamNameRate").prop('disabled', true);
                $(".customTypeTeamNameRate").prop('disabled', true);
                $(".fbofiTeamNameRate").prop('disabled', true);
                $(".forofiTeamNameRate").prop('disabled', true);
                $(".forofioeTeamNameRate").prop('disabled', true);
                $(".fotrofsouTeamNameRate").prop('disabled', true);
                $(".fdmofsiTeamNameRate").prop('disabled', true);
                $(".tyconTeamNameRate").prop('disabled', true);
                $(".yesNoTeamNameRate").prop('disabled', true);
                $(".wicketInPowerTeamNameRate").prop('disabled', true);
                $(".runtorunRate").prop('disabled', false);
            }
        });
        
    </script>
@endsection
