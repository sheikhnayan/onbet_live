@extends("frontend.frontendMaster")

@section("mainMenu")
    @include("frontend.partials.header")
@endsection

@section("content")
    <!-- breadcrumb Start -->
    <section class="breadcrumbs-custom-wrap">
        <h1 class="text-center">
            <span class="teamVSteam">{{ ucwords($match[0]->teamFirst) }}</span>
            <span style="color:#fff">VS</span>
            <span class="teamVSteam">{{ ucwords($match[0]->teamSecond) }}</span>
            <ul class="breadcroumbLink">
                <li><a href="{{ url("/") }}">Home</a></li>
                <li><a> / {{ ucwords($match[0]->matchTitle) }}</a></li>
            </ul>
        </h1>
    </section>
    <!-- banner-section start -->
    <section class="banner-section">
        <div class="banner-content-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <!-- online-play-tab-part end -->
                        <div class="online-play-tab-part">
                            <div class="tab-content">
                                <div class="sports_single_category">
                                    <h4 class="text-center">
                                        {{ ucwords($match[0]->sportName) }}
                                    </h4>
                                    @if(!empty($match))

                                        <div class="matchTournamentLiveWrap">
                                            <div class="matchTournamentDetailPart">
                                                <p>
                                                    {{ ucwords($match[0]->tournamentName) }}
                                                    <span class="badge badge-warning">
                                                {{ date("d M Y",strtotime($match[0]->matchDateTime)) }}
                                            </span>
                                                    <span class="badge badge-danger">
                                                {{ date("h:i A",strtotime($match[0]->matchDateTime)) }}
                                            </span>
                                                </p>
                                                <p>
                                                    {{ ucwords($match[0]->matchTitle) }}
                                                </p>
                                            </div>

                                            <div class="matchTournamentLivePart">
                                                <?php date_default_timezone_set('Asia/Dhaka'); $currentDateTime = date("Y-m-d H:i:s"); ?>
                                                @if($currentDateTime > $match[0]->matchDateTime && $match[0]->status == 2 || $match[0]->status == 3)
                                                    <span class="liveNotification">
                                                    <b class="liveDot"></b>
                                                    Live
                                                </span>
                                                    <span class="matchOver"> Over: 35 </span>
                                                    <span class="matchScores"> Ind : 100/3 </span>
                                                @else
                                                    <span class="upcomingNotification">Upcoming</span>
                                                @endif
                                            </div>

                                        </div>
                                        @foreach($optionBetDetails as $optionBetDetail)
                                            <div class="team-name-part">
                                                <div class="content">
                                                    <span class="name badge badge-dark">{{ ucwords($optionBetDetail["matchOption"]) }} </span>
                                                </div>
                                            </div>
                                            <div class="choice-team-part">
                                                @foreach($optionBetDetail["betDetails"] as $betDetail)
                                                    <button value="{{ $betDetail->id }}" class="@if(count($optionBetDetail["betDetails"]) == 2) single-item-for-mobile @else single-item @endif  clickSingleBetDetail" data-target="#placeBetBtn" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                                                        <span>{{ ucwords($betDetail->betName) }} &nbsp;<b class="text-primary"> @if($betDetail->status == 0) $  @else {{ $betDetail->betRate }} @endif </b> </span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- online-play-tab-part end -->
                    </div>
                    <div class="col-lg-3">
                        <div class="sidebar-frontend p-2" style="border: 1px solid #000040;background: #000040;margin-top:10px">
                            <h3><button class="btn btn-block mb-3"> Upcoming </button></h3>

                            @if($upcomingMatches->count() > 0)

                                @foreach($upcomingMatches as $upcomingMatch)
                                    <a href="{{ route("single_match_details",["id"=>$upcomingMatch->id]) }}" class="single-match p-2 rounded mb-3">
                                        <p class="text-center text-capitalize mb-1"> <span class="badge badge-sm badge-secondary">{{ $upcomingMatch->sportName }} : {{ $upcomingMatch->tournamentName }}</span></p>
                                        <p class="text-center">
                                            <button class="btn btn-sm btn-secondary text-capitalize">{{ $upcomingMatch->teamFirst }}</button>
                                            <span class="text-white">vs</span>
                                            <button class="btn btn-sm btn-secondary text-capitalize">{{ $upcomingMatch->teamSecond }}</button>
                                        </p>

                                        <p class="text-center">
                                            <span class="badge badge-warning"> {{ date("d M Y",strtotime($upcomingMatch->matchDateTime)) }}  </span>
                                            <span class="badge badge-danger"> {{ date("h:i A",strtotime($upcomingMatch->matchDateTime)) }}  </span>
                                        </p>
                                    </a>
                                @endforeach

                            @else
                                <p class="text-center"><span class="badge badge-warning">No Match Upcomming</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-section end -->

    <!-- Modal -->
    <div class="modal fade" id="placeBetBtn" aria-hidden="true" aria-labelledby="placeBetBtn" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div style="display:block;background:#eee;height:58px" class="modal-header">
                    <button id="customModelClose" type="button" class="close bg-warning" data-dismiss="modal" aria-label="Close">
                        <span class="text-secondary fa fa-close" aria-hidden="true"></span>
                    </button>
                    <h4 style="text-align:center" class="modal-title">Place a bet</h4>
                </div>
                <form>
                    <div class="modal-body">
                        @if(!Auth::guard("web")->check())
                            <p class="text-danger text-center" id="errorLogin">["error":"Login First."]</p>
                        @endif

                        <p class="text-danger text-center" id="errorMsg"></p>
                        <p class="text-success text-center" id="successMsg"></p>
                        <p class="text-info text-center" id="processingMsg"></p>

                        <div class="modalCustomBody" id="modalCustomBodyId">
                            <p class="text-success text-center">Minimum Bet Amount {{ $config->betMinimum }} & Maximum {{ $config->betMaximum }}</p>
                            <div class="modalQusAnsBlock">
                                <p style="text-transform: capitalize" class="text-secondary" id="betDetailQus"></p>
                                <p style="text-transform: capitalize" class="text-secondary" id="betDetailAns"></p>
                                <p class="text-secondary">
                                    Bet Rate : <input type="text" name="betRate" id="betDetailRate" value="" readonly/>
                                </p>
                                <input type="number" name="betAmount" id="betAmount" placeholder="0" value="" min="0"/>
                                <span class="text-secondary">
                                    Est. Return: <input type="text" name="" id="betEstReturn" value="0.00" readonly/>
                                </span>
                                <input type="hidden" id="betMinimum" value="{{ $config->betMinimum }}"/>
                                <input type="hidden" id="betMaximum" value="{{ $config->betMaximum }}"/>
                                <input type="hidden" id="betDetail_id" value=""/>
                                <input type="hidden" id="match_id" value=""/>
                                <input type="hidden" id="betoption_id" value=""/>

                            </div>
                        </div>
                    </div>

                    <div style="display:block;background:#eee" class="modal-footer  text-center">

                        <button class="btn btn-block btn-secondary placeBetSubmitCls " id="placeBetSubmit" disabled="disabled" type="button" name="placeBet"> Place Bet </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@section("main-footer")
    @include("frontend.partials.footer")
@endsection

@section("main-script")
    <script src="{{ asset('/js/frontendadvances.js') }}"> </script>
    <script src="{{ asset("/js/frontcheck.js") }}"></script>
@endsection
