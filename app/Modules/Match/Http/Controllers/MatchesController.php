<?php

namespace App\Modules\Match\Http\Controllers;

use App\Events\betdetailUpdateEvent;
use App\Models\Betplace\Betplace;
use App\Models\Club\Club;
use App\Models\Config\Config;
use App\Models\Deposit\Masterdeposit;
use App\Models\Match\Score;
use App\User;
use Exception;

use App\Models\Match\Team;
use App\Models\Match\Match;
use App\Models\Match\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Match\Betdetail;
use App\Models\Match\Betoption;
use App\Models\Match\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Match\Matchbetoption;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Jenssegers\Agent\Agent;
class MatchesController extends Controller
{
    // Function to get the client IP address or Visitor ip address
    protected function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    #Check Ip address.
    public function checkAdminIp() {

        $agent = new Agent();
        $platform = $agent->platform();
        $version  = $agent->version($platform);
        $browser  = $agent->browser();

        $divice = "";
        if($agent->isDesktop() == 1){
            $device = "Computer";
        }else if($agent->isTablet() == 1){
            $device = "Tablet";
        }else if($agent->isMobile() == 1){
            $device = "Mobile";
        }

        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $this->get_client_ip()));
        $userLocationInfo =  'Device:' . $device.' - ';
        $userLocationInfo .=  'Operating System:' . $platform ." ". $version.' - ';
        $userLocationInfo .=  'Browser:' . $browser .' - ';
        $userLocationInfo .=  'IP Address:' . $this->get_client_ip() .' - ';
        $userLocationInfo .=  'Continent:' . ($ipdat->geoplugin_continentName) ? $ipdat->geoplugin_continentName : ' ' .' - ';
        $userLocationInfo .=  'Country: ' . ($ipdat->geoplugin_countryName) ? $ipdat->geoplugin_countryName : ' ' .' - ';
        $userLocationInfo .=  'City:' . ($ipdat->geoplugin_city ) ? $ipdat->geoplugin_city : ' ' .' - ';
        $userLocationInfo .=  'Latitude:' . ($ipdat->geoplugin_latitude) ? $ipdat->geoplugin_latitude : ' ' .' - ';
        $userLocationInfo .=  'Longitude:' . ($ipdat->geoplugin_longitude) ? $ipdat->geoplugin_longitude : ' ' .' - ';
        $userLocationInfo .=  'Timezone:' . ($ipdat->geoplugin_timezone) ? $ipdat->geoplugin_timezone : ' ' ;


        $matchManageSportsByCategories = DB::table('matches')
            ->leftJoin('sports', 'sports.id', '=', 'matches.sport_id')
            ->select("matches.*","sports.sportName")
            ->where('matches.repeatAgainLive','>',1)
            ->orWhere('matches.advanceCount','>',1)
            ->orderBy("matches.repeatAgainLive","DESC")
            ->get();


        //return $matchManageSportsByCategories;
        //$ip = $this->get_client_ip();
        return view('match::matches.checkIp',compact('userLocationInfo','matchManageSportsByCategories'));

    }

    #Match manage sports category by id
    public function matchesManageSportsCategoryId($id) {
    
        $matchManageSportsByCategories = DB::table('matches')
        ->leftJoin('tournaments', 'tournaments.id', '=', 'matches.tournament_id')
        ->leftJoin('sports', 'sports.id', '=', 'matches.sport_id')
        ->leftJoin('teams as teamOne', 'teamOne.id', '=', 'matches.teamOne_id')
        ->leftJoin('teams as teamTwo', 'teamTwo.id', '=', 'matches.teamTwo_id')
        ->select("matches.*","tournaments.tournamentName","sports.sportName","teamOne.teamName as teamOne","teamTwo.teamName as teamTwo")
        ->whereNotIn('matches.status',[4,5,6])
        ->where('matches.sport_id',$id)
        ->orderBy("matches.matchDateTime","ASC")
        ->get();
        
        if($id == 1){
            $sportsName = "Cricket";
        }elseif($id == 2) {
            $sportsName = "Football";
        }elseif($id == 3) {
            $sportsName = "Basketball";
        }elseif($id == 4) {
            $sportsName = "Volley";
        }else{
            $sportsName = "Tennis";
        }
    
        return view('match::matches.index',compact('matchManageSportsByCategories','sportsName'));
    }

    #Matches List
    public function index() {
        return view('match::matches.matchManageSportsCategory');
    }

    #Complete Matches List
    public function completeMatchesManage() {
        //$matches = Match::with(['score','sport','tournament','teamOne','teamTwo','userUpdated'])->where("status",4)->orderBy('matchDateTime','DESC')->paginate(50);
        $matches = DB::table('matches')
            ->leftJoin('tournaments', 'tournaments.id', '=', 'matches.tournament_id')
            ->leftJoin('sports', 'sports.id', '=', 'matches.sport_id')
            ->leftJoin('teams as teamOne', 'teamOne.id', '=', 'matches.teamOne_id')
            ->leftJoin('teams as teamTwo', 'teamTwo.id', '=', 'matches.teamTwo_id')
            ->select("matches.*","tournaments.tournamentName","sports.sportName",
                "teamOne.teamName as teamOne","teamTwo.teamName as teamTwo")

            ->where('matches.status',4)
            ->orderBy("matches.matchDateTime","DESC")
            ->paginate(50);
        return view('match::matches.completeMatches',compact('matches'));
    }

    #Close Matches List
    public function closeMatchesManage() {
        //$matches = Match::with(['score','sport','tournament','teamOne','teamTwo','userUpdated'])->where("status",5)->orderBy('matchDateTime','DESC')->paginate(50);
        $matches = DB::table('matches')
            ->leftJoin('tournaments', 'tournaments.id', '=', 'matches.tournament_id')
            ->leftJoin('sports', 'sports.id', '=', 'matches.sport_id')
            ->leftJoin('teams as teamOne', 'teamOne.id', '=', 'matches.teamOne_id')
            ->leftJoin('teams as teamTwo', 'teamTwo.id', '=', 'matches.teamTwo_id')
            ->select("matches.*","tournaments.tournamentName","sports.sportName",
                "teamOne.teamName as teamOne","teamTwo.teamName as teamTwo")

            ->where('matches.status',5)
            ->orderBy("matches.matchDateTime","DESC")
            ->paginate(50);
        return view('match::matches.closeMatches',compact('matches'));
    }

    #Matches Details complete
    public function matchesDetailClose($id) {

        $match = Match::where('id',$id)->where('status','=',5)->first(); #Get total match.
        if(empty($match)){Toastr::warning("Match already done or not find!","Warning!");
            return redirect()->back();
        }

        $betOptions = Betoption::where('sport_id',$match->sport_id)->get(); #For dropdown select.
        //dd($betOptions);

        $betOptionPluck = DB::table('betoptions')
            ->pluck('betOptionName','id');
        //dd($betOptionPluck);

        $optionBetDetails = [];
        if($betOptionPluck) {
            foreach ($betOptionPluck as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->whereIn("status",[0,1,2])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {

                    $betStatusOff = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",0)
                        ->get()->count();

                    $betHideFromUserStatus = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",2)
                        ->get()->count();

                    $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betKey])->sum("betAmount");

                    $optionBetDetails[$betKey]['matchOption'] = $betOption;
                    $optionBetDetails[$betKey]['betoption_id'] = $betKey;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
                    $optionBetDetails[$betKey]['betHideFromUserStatus'] = $betHideFromUserStatus;
                    $optionBetDetails[$betKey]['betCoin'] = $betCoin;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }
        //dd($optionBetDetails);
        //dd($match);
        //dd($betOptions);
        $config = Config::select("partialOne","partialTwo")->first();
        $allBetPlaces = Betplace::with(["user","club","betoption","betdetail","winnerItem"])->where("match_id",$id)->orderBy("status","ASC")->get();
        $totalSponsorGetThisMatch = Betplace::where("match_id",$id)->where("sponsorName", "!=" ,null)->orderBy("status","ASC")->sum("sponsorGet");
        $totalReturn = Betplace::where("match_id",$id)->where("status", "=" ,5)->get();
        $totalBackUser = Betplace::where("match_id",$id)->whereIn("status", [1,3,4])->get();
        $totalUnpublished = Betplace::where("match_id",$id)->where("status",0)->get();
        return view('match::matches.closeDetail',compact('match','betOptions','optionBetDetails','allBetPlaces',"totalSponsorGetThisMatch","totalBackUser","totalUnpublished","totalReturn","config"));
    }

    #Matches Details action
    public function matchDetailsCloseAction(Request $request,$id) {
        //return $request->all();
        $this->validate($request,[
            'status' => 'required',
        ],[
            'status.required' => 'Status required for update match!',
        ]);

        $match = Match::find($id);
        $match->status = trim(strip_tags($request->status));
        $match->save();

        Toastr::success("Match Disappear","Success!");
        return redirect()->route('close_matches_manage');

    }

    #Match Create
    public function create() {
        $sports = Sport::where('status',1)->get();
        $tournaments = Tournament::where('status',1)->get();
        $teams = Team::where('status',1)->get();
        return view('match::matches.create',compact('sports'));
    }

    #Change Sports
    public function changeSports($id) {
        $tournaments = Tournament::where('sport_id',$id)->where('status',1)->get();
        $teams = Team::where('sport_id',$id)->where('status',1)->get();
        $dataList = array(
            'tournaments' => $tournaments,
            'teams' => $teams,
        );
        $appendTournament = view('match::matches.appendTournament', $dataList)->render();
        $appendTeam = view('match::matches.appendTeam', $dataList)->render();

        $data = array(
            'appendTournament'=>$appendTournament,
            'appendTeam'=>$appendTeam,
        );

        return Response::json($data);
    }

    #Validation activates
    protected function matchServerValidation($request) {

        $this->validate($request,[
            'matchTitle' => 'required',
            'sport_id' => 'required',
            'tournament_id' => 'required',
            'teamOne_id' => 'required',
            'teamTwo_id' => 'required',
            'matchDateTime' => 'required',
        ],[
            'matchTitle.required' => 'Match Title is required',
            'sport_id.required' => 'Sport name is required',
            'tournament_id.required' => 'Tournament name is required',
            'teamOne_id.required' => 'First team is required',
            'teamTwo_id.required' => 'Second team is required',
            'matchDateTime.required' => 'Match date time is required',
        ]);
    }

    #Match Store
    public function store(Request $request) {

        $this->matchServerValidation($request);

        try{

            if($request->teamOne_id === $request->teamTwo_id){
                Toastr::warning("First and Second Team Can\'t be same!","Warning!");
                return redirect()->route('matches_create');
            }

            $alreadyCreateMatch = Match::where('sport_id',$request->sport_id)
            ->where('tournament_id',$request->tournament_id)
            ->where('teamOne_id',$request->teamOne_id)
            ->where('teamTwo_id',$request->teamTwo_id)
            ->where('matchDateTime',$request->matchDate)
            ->first();

            $alreadyCreateMatch = Match::where('sport_id',$request->sport_id)
            ->where('tournament_id',$request->tournament_id)
            ->Where('teamOne_id',$request->teamTwo_id)
            ->Where('teamTwo_id',$request->teamOne_id)
            ->where('matchDateTime',$request->matchDate)
            ->first();

            if($alreadyCreateMatch){
                Toastr::warning("Match already created!","Warning!");
                return redirect()->route('matches_create');
            }

            #Score create
            $score = new Score();
            $score->overs = null;
            $score->score = null;
            $score->save();

            #Match create
            $match = new Match();
            $match->score_id = $score->id;
            $match->sport_id = trim(strip_tags($request->sport_id));
            $match->tournament_id = trim(strip_tags($request->tournament_id));
            $match->teamOne_id = trim(strip_tags($request->teamOne_id));
            $match->teamTwo_id = trim(strip_tags($request->teamTwo_id));
            $match->matchTitle = trim(strip_tags($request->matchTitle));
            $match->matchDateTime  = trim(strip_tags($request->matchDateTime));
            $match->created_by = Auth::guard("admin")->user()->id;
            $match->save();

            $score->match_id = $match->id;
            $score->update();

            Toastr::success("Match created Successfully","Success!");
            return redirect()->route('matches_create');

        }catch(Exception $e) {
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->route('matches_create');
        }
    }

    #Edit Matches
    public function edit($id) {
        try {
            $match = Match::where('id', $id)->where('status', '!=', 4)->first();
            if (!empty($match) || Auth::guard("admin")->user()->userRole->name == 'supperAdmin' && Auth::guard("admin")->user()->type == 0) {
                $sports = Sport::where('status', 1)->get();
                return view('match::matches.edit', compact('sports', 'match'));
            } else {
                Toastr::warning("Match already done or not find!", "Warning!");
                return redirect()->route('matches_manage');
            }
        }catch (Exception $e){
            Toastr::error("Something want worng", "Warning!");
            return redirect()->back();
        }
    }

    #Update Matches
    public function update(Request $request, $id) {

        $this->matchServerValidation($request);

        try{

            if($request->teamOne_id === $request->teamTwo_id){
                Toastr::warning("First and Second Team Can\'t be same!","Warning!");
                return redirect()->route('matches_edit',['id'=>$id]);
            }
            #Validation for enter table.
            $alreadyCreateMatchOne = Match::where('sport_id',$request->sport_id)
            ->where('matchTitle',trim(strip_tags($request->matchTitle)))
            ->where('tournament_id',$request->tournament_id)
            ->where('teamOne_id',$request->teamOne_id)
            ->where('teamTwo_id',$request->teamTwo_id)
            ->where('matchDateTime',$request->matchDateTime)
            ->first();

            if($alreadyCreateMatchOne){
                Toastr::warning("Match already created!","Warning!");
                return redirect()->route('matches_edit',['id'=>$id]);
            }

            #Validation for enter table.
            $alreadyCreateMatchTwo = Match::where('sport_id',$request->sport_id)
            ->where('matchTitle',trim(strip_tags($request->matchTitle)))
            ->where('tournament_id',$request->tournament_id)
            ->Where('teamOne_id',$request->teamTwo_id)
            ->Where('teamTwo_id',$request->teamOne_id)
            ->where('matchDateTime',$request->matchDateTime)
            ->first();

            if($alreadyCreateMatchTwo){
                Toastr::warning("Match already created!","Warning!");
                return redirect()->route('matches_edit',['id'=>$id]);
            }

            $match = Match::find($id);
            $match->sport_id = trim(strip_tags($request->sport_id));
            $match->tournament_id = trim(strip_tags($request->tournament_id));
            $match->teamOne_id = trim(strip_tags($request->teamOne_id));
            $match->teamTwo_id = trim(strip_tags($request->teamTwo_id));
            $match->matchTitle = trim(strip_tags($request->matchTitle));
            $match->matchDateTime = trim(strip_tags($request->matchDateTime));
            $match->updated_by = Auth::guard("admin")->user()->id;
            $match->updated_at = Carbon::now();
            $match->update();

            Toastr::success("Match updated Successfully","Success!");
            return redirect()->route('matches_edit',['id'=>$id]);

        }catch(Exception $e) {
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->route('matches_edit',['id'=>$id]);
        }

    }

    #Matches details
    public function matchDetail($id) {

        $match = Match::with(['sport','tournament','teamOne','teamTwo','userCreated'])->where('id',$id)->first(); #Get total match.
        /*if(empty($match)){Toastr::warning("Match already done or not find!","Warning!");
            return redirect()->route('matches_manage');
        }*/

        $betOptions = Betoption::where('sport_id',$match->sport_id)->get(); #For dropdown select.
        $betOptionPluck = DB::table('betoptions')->where('sport_id',$match->sport_id)->pluck('betOptionName','id');
        //dd($betOptionPluck);

        /*$thisMatchOption = DB::table('betdetails')
        ->leftJoin('betoptions','betdetails.betoption_id','betoptions.id')
        ->select('betoptions.betOptionName','betoptions.id','betdetails.match_id')                            
        ->whereIn("status",[0,1,2])
        ->where('betdetails.match_id',$match->id)
        ->distinct('betdetails.betoption_id')
        ->get()->toArray();
        
        $optionBetDetails = [];
        if($thisMatchOption) {
            foreach ($thisMatchOption as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betOption->id])
                    ->whereIn("status",[0,1,2])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {

                    $betStatusOff = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",0)
                        ->get()->count();
                        
                    $betHideFromUserStatus = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->where("status","=",2)
                    ->get()->count();

                    $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betOption->id])->sum("betAmount");

                    $optionBetDetails[$betKey]['matchOption'] = $betOption->betOptionName;
                    $optionBetDetails[$betKey]['betoption_id'] = $betOption->id;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
                    $optionBetDetails[$betKey]['betHideFromUserStatus']  = $betHideFromUserStatus;
                    $optionBetDetails[$betKey]['betCoin'] = $betCoin;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }*/
        
        $optionBetDetails = [];
        if($betOptionPluck) {
            foreach ($betOptionPluck as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->whereIn("status",[0,1,2])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {

                    $betStatusOff = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->where("status","=",0)
                    ->get()->count();

                    $betHideFromUserStatus = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->where("status","=",2)
                    ->get()->count();

                    $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betKey])->whereIn("status",[0,1,2])->sum("betAmount");

                    $optionBetDetails[$betKey]['matchOption'] = $betOption;
                    $optionBetDetails[$betKey]['betoption_id'] = $betKey;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
                    $optionBetDetails[$betKey]['betHideFromUserStatus'] = $betHideFromUserStatus;
                    $optionBetDetails[$betKey]['betCoin'] = $betCoin;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }
        /*$array = [];
        $optionBetDetails = DB::table('betdetails')
        ->leftJoin('betoptions','betdetails.betoption_id','betoptions.id')
        ->select('betoptions.betOptionName','betoptions.id','betdetails.match_id')
        ->where('betdetails.match_id',$id)
        ->whereIn("status",[0,1,2])
        ->distinct('betdetails.betoption_id')
        ->orderBy("betoptions.id","ASC")
        ->get()->map(function($query) use ($array){
            $betdetails = DB::table('betdetails')
                ->select("id","betName","betRate","match_id","betoption_id","status")
                ->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])
                ->whereIn("status",[0,1,2])
                ->get()->toArray();

                $betStatusOff = DB::table('betdetails')
                    ->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])
                    ->where("status","=",0)->get()->count();

                $betHideFromUserStatus = DB::table('betdetails')
                    ->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])
                    ->where("status","=",2)
                    ->get()->count();

                $betCoin = DB::table("betplaces")->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])->where('status','!=',5)->sum("betAmount");

                if(!empty($betdetails)){
                    $array['matchOption']            = $query->betOptionName;
                    $array['betoption_id']           = $query->id;
                    $array['match_id']               = $query->match_id;
                    $array['betStatus']              = $betStatusOff;
                    $array['betHideFromUserStatus']  = $betHideFromUserStatus;
                    $array['betCoin']                = $betCoin;
                    $array['betDetails']             = $betdetails;
                }
                return $array;
        })->toArray();*/
        
        //dd($optionBetDetails);
        //dd($match);
        //dd($betOptions);
        $config = Config::select("partialOne","partialTwo")->first();

        return view('match::matches.detail',compact('match','betOptions','optionBetDetails',"config"));
    }

    #Match profit or loss
    public function matchProfitLoss($id) {
        $match = DB::table('matches')
        ->leftJoin("sports",'sports.id','=','matches.sport_id')
        ->leftJoin("teams as teamOne",'teamOne.id','=','matches.teamOne_id')
        ->leftJoin("teams as teamTwo",'teamTwo.id','=','matches.teamTwo_id')
        ->leftJoin("tournaments",'tournaments.id','=','matches.tournament_id')
        ->leftJoin("admins",'admins.id','=','matches.created_by')
        ->select('matches.id','matches.score_id','matches.matchTitle','matches.matchDateTime','matches.created_at','sports.sportName','teamOne.teamName as teamFirst','teamTwo.teamName as teamSecond','tournaments.tournamentName','admins.username')
        ->where('matches.id',$id)
        ->get();
        
        
        $allBetPlaces = DB::table('betplaces')->whereIn("status",[0,1,2])->where("match_id",$id)->orderBy("created_at","DESC")->get(); 
        $totalSponsorGetThisMatch = DB::table('betplaces')->where("match_id",$id)->where("sponsorName", "!=" ,null)->orderBy("status","ASC")->sum("sponsorGet");
        $totalReturn = DB::table('betplaces')->where("match_id",$id)->where("status", "=" ,5)->get();
        $totalBackUser = DB::table('betplaces')->where("match_id",$id)->whereIn("status", [1,3,4])->get();
        $totalUnpublished = Betplace::where("match_id",$id)->where("status",0)->get();
        return view('match::matches.profitLoss',compact('match','allBetPlaces',"totalSponsorGetThisMatch","totalBackUser","totalUnpublished","totalReturn"));
    }

    #User bet time cheack and bet return view
    public function singleMatchTotalBets($id) {
        $match = DB::table('matches')
        ->leftJoin("sports",'sports.id','=','matches.sport_id')
        ->leftJoin("teams as teamOne",'teamOne.id','=','matches.teamOne_id')
        ->leftJoin("teams as teamTwo",'teamTwo.id','=','matches.teamTwo_id')
        ->leftJoin("tournaments",'tournaments.id','=','matches.tournament_id')
        ->leftJoin("admins",'admins.id','=','matches.created_by')
        ->select('matches.id','matches.score_id','matches.matchTitle','matches.matchDateTime','matches.created_at','sports.sportName','teamOne.teamName as teamFirst','teamTwo.teamName as teamSecond','tournaments.tournamentName','admins.username')
        ->where('matches.id',$id)
        ->get();

        $bets = DB::table('betplaces')
        ->leftJoin("users",'users.id','=','betplaces.user_id')
        ->leftJoin("clubs",'clubs.id','=','betplaces.club_id')
        ->leftJoin("betoptions",'betoptions.id','=','betplaces.betoption_id')
        ->leftJoin("betdetails",'betdetails.id','=','betplaces.betdetail_id')
        ->leftJoin("betdetails as winners",'winners.id','=','betplaces.winner_id')
        ->select("betplaces.id","betplaces.betAmount","betplaces.betRate","betplaces.created_at","betplaces.updated_at","betplaces.betProfit","betplaces.betLost","betplaces.partialLost","betplaces.partialRate","betplaces.winLost","betplaces.status"
            ,'users.username','clubs.username as clubUsername','betoptions.betOptionName','betdetails.betName', 'winners.betName as winnerOption')
        ->where("betplaces.match_id",$id)
        //->orderBy("betplaces.betoption_id","ASC")
        ->orderBy("betplaces.created_at","desc")
        ->get();
        return view('match::matches.singleMatchTotalBets',compact('match','bets'));
    }

    #Matches Details complete
    public function matchesDetailComplete($id) {

        $match = Match::where('id',$id)->where('status','=',4)->first(); #Get total match.
        if(empty($match)){Toastr::warning("Match already done or not find!","Warning!");
            return redirect()->route('complete_matches_manage');
        }

        $betOptions = Betoption::where('sport_id',$match->sport_id)->get(); #For dropdown select.
        //dd($betOptions);

        $betOptionPluck = DB::table('betoptions')
            ->pluck('betOptionName','id');
        //dd($betOptionPluck);

        $optionBetDetails = [];
        if($betOptionPluck) {
            foreach ($betOptionPluck as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->whereIn("status",[0,1,2])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {

                    $betStatusOff = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",0)
                        ->get()->count();

                    $betHideFromUserStatus = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",2)
                        ->get()->count();

                    $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betKey])->sum("betAmount");

                    $optionBetDetails[$betKey]['matchOption'] = $betOption;
                    $optionBetDetails[$betKey]['betoption_id'] = $betKey;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
                    $optionBetDetails[$betKey]['betHideFromUserStatus'] = $betHideFromUserStatus;
                    $optionBetDetails[$betKey]['betCoin'] = $betCoin;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }
        //dd($optionBetDetails);
        //dd($match);
        //dd($betOptions);
        $config = Config::select("partialOne","partialTwo")->first();
        $allBetPlaces = Betplace::with(["user","club","betoption","betdetail","winnerItem"])->where("match_id",$id)->orderBy("status","ASC")->get();
        $totalSponsorGetThisMatch = Betplace::where("match_id",$id)->where("sponsorName", "!=" ,null)->orderBy("status","ASC")->sum("sponsorGet");
        $totalReturn = Betplace::where("match_id",$id)->where("status", "=" ,5)->get();
        $totalBackUser = Betplace::where("match_id",$id)->whereIn("status", [1,3,4])->get();
        $totalUnpublished = Betplace::where("match_id",$id)->where("status",0)->get();
        return view('match::matches.vanishDetail',compact('match','betOptions','optionBetDetails','allBetPlaces',"totalSponsorGetThisMatch","totalBackUser","totalUnpublished","totalReturn","config"));
    }

    #Matches Unpublished list
    public function matchUnpublishedList($id) {

        $match = Match::where('id',$id)->where('status','!=',4)->first(); #Get total match.

        if(empty($match)){Toastr::warning("Wrong input you given!","Warning!");
            return redirect()->route('matches_manage');
        }

        $betOptionPluck = DB::table('betoptions')
            ->pluck('betOptionName','id');
        //dd($betOptionPluck);

        $optionBetDetails = [];
        if($betOptionPluck) {

            foreach ($betOptionPluck as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey,'status' => 3])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {
                    $optionBetDetails[$betKey]['matchOption'] = $betOption;
                    $optionBetDetails[$betKey]['betoption_id'] = $betKey;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }
        //dd($optionBetDetails);
        //dd($match);

        return view('match::matches.unpublishedlist',compact('match','optionBetDetails'));
    }

    #Bet Win or Lost
    public function betWinLost(Request $request)
    {
        $this->validate($request,[
            "betDetailId"=>"required"
        ],[
            "betDetailId.required" => "Please select who is won the match.",
        ]);

        
        //$match = Match::find($request->match_id); 
        $match = DB::table('matches')->where('id',$request->match_id)->get();
        //return $match;
        date_default_timezone_set('Asia/Dhaka');
        $currentDateTime = date("Y-m-d H:i:s");
        
        if($match[0]->status != 1){
            
            DB::beginTransaction();
            
            try {

                #Winer
                $winners = Betplace::where(["betdetail_id" => $request->betDetailId, "match_id" => $request->match_id, "betoption_id" => $request->betoption_id, "status" => 0])->get();

                foreach ($winners as $winner) {

                    $winner->winner_id = $request->betDetailId;
                    $winner->betProfit = (($winner->betAmount * $winner->betRate) - $winner->betAmount);
                    $winner->winLost = "win";
                    $winner->status = 1;
                    $winner->accepted_id = Auth::guard("admin")->user()->id;
                    $winner->updated_at = Carbon::now();
                    $winner->update();

                    #User Update
                    $user = User::where("id", $winner->user_id)->first();
                    $user->totalProfitAmount = ($user->totalProfitAmount + $winner->betProfit);
                    $user->totalLossAmount = ($user->totalLossAmount - $winner->betAmount);
                    $user->update();

                    #User total balance update
                    $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                    $user->update();
                }

                #Losers
                $losers = Betplace::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id, "status" => 0])->get();

                foreach ($losers as $loser) {

                    $loser->winner_id = $request->betDetailId;
                    $loser->betLost = $loser->betAmount;
                    $loser->winLost = "lost";
                    $loser->status = 2;
                    $loser->accepted_id = Auth::guard("admin")->user()->id;
                    $loser->updated_at = Carbon::now();
                    $loser->update();
                }

                #Betdetail
                $betDetail = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id])
                    ->whereIn("status", [0, 1, 2])
                    ->update(["status" => 3]);

                $message = 1;
                event(new betdetailUpdateEvent($message));
                
                DB::commit();
                
                Toastr::success("Bet option published successfully", "Success!");
                return redirect()->back();
                
            }catch (Exception $e){
                
                DB::rollback();
                
                Toastr::error("Something Went wrong", "Error!");
                return redirect()->back();
            }
        }else{
            //Toastr::error("Bet option not published when match upcoming.", "Wirning!");
            return redirect()->back();
        }

    }

    #Bet unpublished
    public function betUnpublished(Request $request)
    {

        $this->validate($request,[
            "betDetailId"=>"required"
        ],[
            "betDetailId.required" => "Please select who is won the match.",
        ]);
        
        if($request->betDetailId == "three"){
            //return "click three";

            $betPlaces = Betplace::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id,"partialRate"=>3.00,"status"=>3])->get();
            

            if($betPlaces->count() != 0){
                //return $betPlaces;
                DB::beginTransaction();
                try{
                    foreach ($betPlaces as $betPlace) {
                        
                        #User Update
                        $user = User::where("id",$betPlace->user_id)->first();
                        $user->totalLossAmount =  ($user->totalLossAmount + ($betPlace->betAmount - $betPlace->partialLost));
                        $user->update();
    
                        $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                        $user->update();
    
                        //return $betPlace;
                        $betPlace->partialLost = 0.00;
                        $betPlace->partialRate = 0.00;
                        $betPlace->winLost = null;
                        $betPlace->status = 0;
                        $betPlace->accepted_id = Auth::guard("admin")->user()->id;
                        $betPlace->updated_at = Carbon::now();
                        $betPlace->update();
    
                        #MasterDeposit update
                        $mainSiteDeposit = Masterdeposit::first();
                        $mainSiteDeposit->totalPartialFromUser = ($mainSiteDeposit->totalPartialFromUser - $betPlace->partialLost);
                        $mainSiteDeposit->update();
    
                    }
    
                    #Betdetail
                    $betDetail = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id])
                        ->where("status",3)
                        ->update(["status"=>2]);
                        
                    DB::commit();
                    
                    Toastr::success("Partial three undo successfully", "Success!");
                    return redirect()->back();
                }catch (Exception $e){
                    
                    DB::rollback();
                    
                    Toastr::error("Something Went wrong", "Error!");
                    return redirect()->back();
                }
            }else{
                Toastr::error("Select wrong option.", "Wirning!");
                return redirect()->back();
            }
        }

        if($request->betDetailId == "five"){
            
            $betPlaces = Betplace::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id,"partialRate"=>5.00,"status"=>3])->get();
            //return $betPlaces;

            if($betPlaces->count() != 0){
                DB::beginTransaction();
                
                try{
                    foreach ($betPlaces as $betPlace) {
    
                        #User Update
                        $user = User::where("id",$betPlace->user_id)->first();
                        $user->totalLossAmount =  ($user->totalLossAmount + ($betPlace->betAmount - $betPlace->partialLost));
                        $user->update();
    
                        $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                        $user->update();
    
                        //return $betPlace;
                        $betPlace->partialLost = 0.00;
                        $betPlace->partialRate = 0.00;
                        $betPlace->winLost = null;
                        $betPlace->status = 0;
                        $betPlace->accepted_id = Auth::guard("admin")->user()->id;
                        $betPlace->updated_at = Carbon::now();
                        $betPlace->update();
    
                        #MasterDeposit update
                        $mainSiteDeposit = Masterdeposit::first();
                        $mainSiteDeposit->totalPartialFromUser = ($mainSiteDeposit->totalPartialFromUser - $betPlace->partialLost);
                        $mainSiteDeposit->update();
    
                    }

                    #Betdetail
                    $betDetail = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id])
                        ->where("status",3)
                        ->update(["status"=>2]);
                    
                    DB::commit();
                    
                    Toastr::success("Partial five undo successfully", "Success!");
                    return redirect()->back();
                
                }catch (Exception $e){
                    
                    DB::rollback();
                    
                    Toastr::error("Something Went wrong", "Error!");
                    return redirect()->back();
                }
            }else{
                Toastr::error("Select wrong option.", "Wirning!");
                return redirect()->back();
            }
        }
        //return "fail";
        
        DB::beginTransaction();
        
        try {
            #Config
            $config = Config::select("sponsorRate")->first();

            #check you select winner option when you published.
            $winnerOptions = Betplace::where(["winner_id" => $request->betDetailId, "match_id" => $request->match_id, "betoption_id" => $request->betoption_id])->get();
            if ($winnerOptions->count() == 0) {
                Toastr::error("Please select winner option.", "Wirning!");
                return redirect()->back();
            }

            #Winner
            $winners = Betplace::where(["betdetail_id" => $request->betDetailId, "winner_id" => $request->betDetailId, "match_id" => $request->match_id, "betoption_id" => $request->betoption_id, "status" => 1])->get();
            //return $winners;
            if($winners->count() > 0) {
                foreach ($winners as $winner) {

                    #User Update
                    $user = User::where("id", $winner->user_id)->first();
                    $user->totalProfitAmount = ($user->totalProfitAmount - $winner->betProfit);
                    $user->totalLossAmount = ($user->totalLossAmount + $winner->betAmount);
                    $user->update();

                    #User total balance update
                    $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                    $user->update();

                    $winner->winner_id = null;
                    $winner->betProfit = 0.00;
                    $winner->winLost = "match live";
                    $winner->status = 0;
                    $winner->accepted_id = null;
                    $winner->updated_at = null;
                    $winner->update();

                }
            }

            #Losers
            $losers = Betplace::where("betdetail_id", "!=", $request->betDetailId)->where(["winner_id" => $request->betDetailId, "match_id" => $request->match_id, "betoption_id" => $request->betoption_id, "status" => 2])->get();
            if($losers->count() > 0) {
                foreach ($losers as $loser) {

                    $loser->winner_id = null;
                    $loser->betLost = 0.00;
                    $loser->winLost = "match live";
                    $loser->status = 0;
                    $loser->accepted_id = null;
                    $loser->updated_at = null;
                    $loser->update();
                }
            }

            #Betdetail
            $betDetail = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->betoption_id])
                ->where("status", 3)
                ->update(["status" => 2]);
                
            DB::commit();
            
            Toastr::success("Bet option Unpublished successfully", "Success!");
            return redirect()->back();

        }catch (Exception $e){
            
            DB::rollback();
            
            Toastr::error("Something Went wrong", "Error!");
            return redirect()->back();
        }

    }

    #bet partial One match
    public function partialOne ($rate,$matchId,$betOptionId) {

        try {
            $betPlaces = Betplace::where(["match_id"=>$matchId, "betoption_id"=>$betOptionId,"status"=>0])->get();
            //return $betPlaces;
            $config = Config::select("partialOne","sponsorRate")->first();
            //return $config;

            if(!empty($betPlaces)){
                foreach ($betPlaces as $betPlace) {
                    //return $betPlace;
                    $betPlace->partialLost = (($betPlace->betAmount / 100) * $config->partialOne);
                    $betPlace->partialRate = $config->partialOne;
                    $betPlace->winLost = "partial $config->partialOne";
                    $betPlace->status = 3;
                    $betPlace->accepted_id = Auth::guard("admin")->user()->id;
                    $betPlace->updated_at = Carbon::now();
                    $betPlace->update();

                    #User Update
                    $user = User::where("id",$betPlace->user_id)->first();
                    $user->totalLossAmount =  ($user->totalLossAmount - ($betPlace->betAmount - $betPlace->partialLost));
                    $user->update();
                    $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                    $user->update();

                    #MasterDeposit update
                    $mainSiteDeposit = Masterdeposit::first();
                    $mainSiteDeposit->totalPartialFromUser = ($mainSiteDeposit->totalPartialFromUser + $betPlace->partialLost);
                    $mainSiteDeposit->update();

                }

                #Betdetail
                $betDetail = Betdetail::where(["match_id"=>$matchId, "betoption_id"=>$betOptionId])
                    ->whereIn("status",[0,1,2])
                    ->update(["status"=>3]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                Toastr::success("Partial action successfully done", "Success!");
                return redirect()->back();
            }else{
                Toastr::error("Not Found","Error!");
                return redirect()->back();
            }

        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #bet partial Two match
    public function partialTwo ($rate,$matchId,$betOptionId) {
        try {
            $betPlaces = Betplace::where(["match_id"=>$matchId, "betoption_id"=>$betOptionId,"status"=>0])->get();
            //return $betPlaces;
            $config = Config::select("partialTwo","sponsorRate")->first();
            //return $config;

            if(!empty($betPlaces)){
                foreach ($betPlaces as $betPlace) {
                    //return $betPlace;
                    $betPlace->partialRate = $config->partialTwo;
                    $betPlace->partialLost = (($betPlace->betAmount / 100) * $config->partialTwo);
                    $betPlace->winLost = "partial $config->partialTwo";
                    $betPlace->status = 3;
                    $betPlace->accepted_id = Auth::guard("admin")->user()->id;
                    $betPlace->updated_at = Carbon::now();
                    $betPlace->update();

                    #User Update
                    $user = User::where("id",$betPlace->user_id)->first();
                    $user->totalLossAmount =  ($user->totalLossAmount - ($betPlace->betAmount - $betPlace->partialLost));
                    $user->update();
                    $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                    $user->update();

                    #MasterDeposit update
                    $mainSiteDeposit = Masterdeposit::first();
                    $mainSiteDeposit->totalPartialFromUser = ($mainSiteDeposit->totalPartialFromUser + $betPlace->partialLost);
                    $mainSiteDeposit->update();

                }

                #Betdetail
                $betDetail = Betdetail::where(["match_id"=>$matchId, "betoption_id"=>$betOptionId])
                    ->whereIn("status",[0,1,2])
                    ->update(["status"=>3]);

                $message = 1;
                event(new betdetailUpdateEvent($message));

                Toastr::success("Partial action successfully done", "Success!");
                return redirect()->back();

            }else{
                Toastr::error("Not Found","Error!");
                return redirect()->back();
            }

        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }


    #Tune Off
    public function betActionOff($matchId,$betOptionId) {

        try {
            $betDetails = Betdetail::where(["match_id"=>$matchId,"betoption_id"=>$betOptionId])->whereIn("status",[0,1,2])->update(['status'=>0]);
            $message = 1;
            event(new betdetailUpdateEvent($message));
            Toastr::success("Bet Off successfully","Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #Tune On
    public function betActionOn($matchId,$betOptionId) {

        try {
            $betDetails = Betdetail::where(["match_id" => $matchId, "betoption_id" => $betOptionId])->whereIn("status",[0,1,2])->update(['status' => 1]);
            $message = 1;
            event(new betdetailUpdateEvent($message));
            Toastr::success("Bet On successfully", "Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #Hide form Frontend
    public function betActionHideFormUserPage($matchId,$betOptionId) {

        try {
            $betDetails = Betdetail::where(["match_id" => $matchId, "betoption_id" => $betOptionId])->update(['status' => 2]);
            $message = 1;
            event(new betdetailUpdateEvent($message));
            Toastr::success("Bet hide form user page successfully", "Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #Bet delete
    public function betActionDelete($matchId,$betOptionId) {

        try {
            $betDetails = Betdetail::where(["match_id" => $matchId, "betoption_id" => $betOptionId])->update(['status' => 4]);
            Toastr::success("Bet delete successfully", "Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #User bet return
    public function userBetReturn($betPlaceId) {
        
        DB::beginTransaction();
        try {

            $betPlace = Betplace::where("id",$betPlaceId)->first();
            $betPlace->winLost = "bet return";
            $betPlace->status = 5;
            $betPlace->save();

            $user = User::where("id",$betPlace->user_id)->first();
            $user->totalLossAmount = ($user->totalLossAmount - $betPlace->betAmount);
            $user->update();

            #Update user totalBalance
            $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
            $user->update();

            #Club update
            $club = Club::where("id",$betPlace->club_id)->first();
            $club->totalProfit = ($club->totalProfit -  $betPlace->clubGet);
            $club->update();
            $club->totalBalance = ($club->totalProfit - $club->totalWithdrawAmount);
            $club->update();

            #MasterDeposit update
            $mainSiteDeposit = Masterdeposit::first();
            $mainSiteDeposit->totalSiteDeposit = ($mainSiteDeposit->totalSiteDeposit + $betPlace->clubGet);
            $mainSiteDeposit->totalLossToClub  = ($mainSiteDeposit->totalLossToClub - $betPlace->clubGet);
            $mainSiteDeposit->update();
            
            if ($betPlace->sponsorName != null) {
                $userSponsor = User::where("username", $betPlace->sponsorName)->first();
                $userSponsor->totalSponsorAmount = ($userSponsor->totalSponsorAmount - $betPlace->sponsorGet);
                $userSponsor->update();

                #User total balance update
                $userSponsor->totalBalance = ($userSponsor->totalRegularDepositAmount + $userSponsor->totalSpecialDepositAmount + $userSponsor->totalCoinReceiveAmount + $userSponsor->totalSponsorAmount + $userSponsor->totalProfitAmount) - ($userSponsor->totalCoinTransferAmount + $userSponsor->totalLossAmount + $userSponsor->totalWithdrawAmount);
                $userSponsor->update();

                #MasterDeposit update
                $mainSiteDeposit = Masterdeposit::first();
                $mainSiteDeposit->totalSiteDeposit = ($mainSiteDeposit->totalSiteDeposit + $betPlace->sponsorGet);
                $mainSiteDeposit->totalLossToSponsor = ($mainSiteDeposit->totalLossToSponsor - $betPlace->sponsorGet);
                $mainSiteDeposit->update();
            }
            
            DB::commit();
            return response()->json([
                'status'=> true,
                'msg'=> "Bet return successfully"
            ]);
            
        }catch (Exception $e){
            DB::rollback();
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #User bet return
    public function userBetDelete($betPlaceId) {

        try {

            $betPlace = Betplace::where("id",$betPlaceId)->first();
            $betPlace->delete();

            Toastr::success("Bet delete successfully", "Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }

    }

    #Matches Details action
    public function matchDetailsAction(Request $request,$id) {

        //return $request->all();
        $this->validate($request,[
            'status' => 'required',
        ],[
            'status.required' => 'Status required for update match!',
        ]);

        $match = Match::find($id);
        //return $match;
        date_default_timezone_set('Asia/Dhaka');
        $currentDateTime = date("Y-m-d H:i:s");
        if($request->status == 1  && $currentDateTime > $match["matchDateTime"] ) {
            $match->advanceCount = $match->advanceCount + 1;
        }

        $match->status = trim(strip_tags($request->status));

        if ($request->status == 7 && $match->repeatAgainLive == 0) {
            //return $request->status;
            $match->repeatAgainLive = 1;
            date_default_timezone_set('Asia/Dhaka');
            $match->repeatDateTimeOne = date('Y-m-d h:i:s');

        } elseif ($request->status == 7 && $match->repeatAgainLive == 1) {

            $match->repeatAgainLive = 2;
            date_default_timezone_set('Asia/Dhaka');
            $match->repeatDateTimeTwo = date('Y-m-d h:i:s');

        } elseif ($request->status == 7 && $match->repeatAgainLive == 2) {

            $match->repeatAgainLive = 3;
            date_default_timezone_set('Asia/Dhaka');
            $match->repeatDateTimeThree = date('Y-m-d h:i:s');

        } elseif ($request->status == 7 && $match->repeatAgainLive >= 3) {

            $match->repeatAgainLive = $match->repeatAgainLive + 1;
            date_default_timezone_set('Asia/Dhaka');
            $match->repeatDateTimelast = date('Y-m-d h:i:s');

        }

        $match->update();

        $message = 1;
        event(new betdetailUpdateEvent($message));

        if($request->status == 0){
            Toastr::success("Match go drafted","Success!");
            return redirect()->back();
        }elseif ($request->status == 1){
            Toastr::success("Match go to Upcoming","Success!");
            return redirect()->back();
        }elseif ($request->status == 2){
            Toastr::success("Match go live","Success!");
            return redirect()->back();
        }elseif ($request->status == 3){
            Toastr::success("Match done","Success!");
            return redirect()->back();
        }elseif ($request->status == 4){
            Toastr::success("Match fully finished","Success!");
            return redirect()->back();
        }elseif ($request->status == 7){
            Toastr::success("Match hide form user page","Success!");
            return redirect()->back();
        }

    }

    #Matches Details action
    public function matchDetailsVanishAction(Request $request,$id) {
        //return $request->all();
        $this->validate($request,[
            'status' => 'required',
        ],[
            'status.required' => 'Status required for update match!',
        ]);

        $match = Match::find($id);
        $match->status = trim(strip_tags($request->status));
        $match->save();

        Toastr::success("Match vanish","Success!");
        return redirect()->route('complete_matches_manage');

    }

    #Matches matches bet setup
    public function matchesBetSetup(Request $request, $id) {

        $this->validate($request,[
            'betoption_id' => 'required',
            'option_type' => 'required',
            /* 'betName.*' => 'required|regex:/^[a-zA-Z0-9-_()]+$/u', */
            'betName.*' => 'required',
            'betRate.*' => 'required|numeric',
        ],[
            'betoption_id.required' => 'Bet option is required',
            'option_type.required' => 'Option type is required',
            'betName.*.regex' => 'Bet name content letter,underscor,hypen',
        ]);

        $betDetail = Betdetail::where(["match_id"=> $id, "betoption_id"=> trim(strip_tags($request->betoption_id)), "status"=>3])->first();
        if(!empty($betDetail)){
            Toastr::error("Sorry! This bet already published!","Danger!");
            return redirect()->back();
        }

        try{

            for($i=0; $i<count($request->betName); $i++) {

                $betDetail = new Betdetail();
                $betDetail->match_id = $id;
                $betDetail->betoption_id = trim(strip_tags($request->betoption_id));
                $betDetail->betName = trim(strip_tags($request->betName[$i]));
                $betDetail->betRate = trim(strip_tags($request->betRate[$i]));
                $betDetail->created_by   = Auth::guard("admin")->user()->id;
                $betDetail->save();

            }

            Toastr::success("Bet setup Successfully","Success!");
            return redirect()->back();
        } catch(Exception $e) {
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();
        }

    }

    #Matches matches bet setup
    public function matchesBetSetupLive(Request $request, $id) {

        $this->validate($request,[
            'betoption_id' => 'required',
            'option_type' => 'required',
            /* 'betName.*' => 'required|regex:/^[a-zA-Z0-9-_()]+$/u', */
            'betName.*' => 'required',
            'betRate.*' => 'required|numeric',
        ],[
            'betoption_id.required' => 'Bet option is required',
            'option_type.required' => 'Option type is required',
            'betName.*.regex' => 'Bet name content letter,underscor,hypen',
        ]);

        $betDetail = Betdetail::where(["match_id"=> $id, "betoption_id"=> trim(strip_tags($request->betoption_id)), "status"=>3])->first();
        if(!empty($betDetail)){
            Toastr::error("Sorry! This bet already published!","Danger!");
            return redirect()->back();
        }

        try{

            for($i=0; $i<count($request->betName); $i++) {

                $betDetail = new Betdetail();
                $betDetail->match_id = $id;
                $betDetail->betoption_id = trim(strip_tags($request->betoption_id));
                $betDetail->betName = trim(strip_tags($request->betName[$i]));
                $betDetail->betRate = trim(strip_tags($request->betRate[$i]));
                $betDetail->created_by   = Auth::guard("admin")->user()->id;
                $betDetail->save();

            }

            $message = 1;
            event(new betdetailUpdateEvent($message));

            Toastr::success("Bet setup Successfully","Success!");
            return redirect()->back();
        } catch(Exception $e) {
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();
        }

    }


    #Update single bet option
    public function updateSingleBetOption(Request $request, $id) {

        $this->validate($request,[
            /* 'betNameEdit' => 'required|regex:/^[a-zA-Z0-9-_()]+$/u', */
            'betNameEdit' => 'required',
            'betRateEdit' => 'required|numeric',
        ],[
            'betNameEdit.required' => 'Bet name is required',
            'betRateEdit.required' => 'Bet rate is required',
            'betRateEdit.numeric' => 'Bet rate is only taken numeric value',
        ]);

        try{

            $betDetail = Betdetail::find($id);
            $betDetail->betName = trim(strip_tags($request->betNameEdit));
            $betDetail->betRate = trim(strip_tags($request->betRateEdit));
            $betDetail->updated_by   = Auth::guard("admin")->user()->id;
            $betDetail->updated_at   = Carbon::now();
            $betDetail->update();

            $message = 1;
            event(new betdetailUpdateEvent($message));

            Toastr::success("Single bet updated successfully","Success!");
            return redirect()->back();

        } catch(Exception $e) {

            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();

        }
    }

    
    #Single bet delete.
    public function singleBetDelete ($id) {
        try{
            $singleBetDetails = Betdetail::where("id",$id)->first();
            $singleBetDetails->delete();

            Toastr::success("Bet option successfully deleted!","Success!");
            return redirect()->back();

        }catch (Exception $e) {
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();
        }


    }

    #Update live bet matches rate
    public function liveMatchBetRateView ($id,$score_id) {
        /*Check Only live match can available otherwise back*/
        $match = Match::where('id',$id)->whereIn('status',[2,3])->first(); #Get total match.

        if(empty($match)){Toastr::warning("Match already done or not find!","Warning!");
            return redirect()->back();
        }

        $betOptions = Betoption::where('sport_id',$match->sport_id)->get(); #For dropdown select.
        //dd($betOptions);
        
        // $thisMatchOption = DB::table('betdetails')
        // ->leftJoin('betoptions','betdetails.betoption_id','betoptions.id')
        // ->select('betoptions.betOptionName','betoptions.id','betdetails.match_id')                            
        // ->whereIn("status",[0,1])
        // ->where('betdetails.match_id',$match->id)
        // ->distinct('betdetails.betoption_id')
        // ->orderBy("betoptions.id","ASC")
        // ->get()->toArray();
        
        // $optionBetDetails = [];
        // if($thisMatchOption) {
        //     foreach ($thisMatchOption as $betKey => $betOption) {
        //         //return $id;
        //         $betdetails = DB::table('betdetails')
        //             ->where(['match_id' => $id, 'betoption_id' => $betOption->id])
        //             ->whereIn("status",[0,1])
        //             ->get()
        //             ->toArray();


        //         if (!empty($betdetails)) {

        //             $betStatusOff = DB::table('betdetails')
        //                 ->where(['match_id' => $id, 'betoption_id' => $betKey])
        //                 ->where("status","=",0)
        //                 ->get()->count();
                        

        //             $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betOption->id])->sum("betAmount");

        //             $optionBetDetails[$betKey]['matchOption'] = $betOption->betOptionName;
        //             $optionBetDetails[$betKey]['betoption_id'] = $betOption->id;
        //             $optionBetDetails[$betKey]['match_id'] = $id;
        //             $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
        //             $optionBetDetails[$betKey]['betCoin'] = $betCoin;
        //             $optionBetDetails[$betKey]['betDetails'] = $betdetails;
        //         }
        //     }

        // }
        
        // $array = [];
        // $optionBetDetails = DB::table('betdetails')
        // ->leftJoin('betoptions','betdetails.betoption_id','betoptions.id')
        // ->select('betoptions.betOptionName','betoptions.id','betdetails.match_id')
        // ->where('betdetails.match_id',$id)
        // ->whereIn("status",[0,1])
        // ->distinct('betdetails.betoption_id')
        // ->orderBy("betoptions.id","ASC")
        // ->get()->map(function($query) use ($array){
        //     $betdetails = DB::table('betdetails')
        //         ->select("id","betName","betRate","match_id","betoption_id","status")
        //         ->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])
        //         ->whereIn("status",[0,1,2])
        //         ->get()->toArray();

        //         $betStatusOff = DB::table('betdetails')
        //             ->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])
        //             ->where("status","=",0)->get()->count();

        //         $betCoin = DB::table("betplaces")->where(['match_id'=>$query->match_id,'betoption_id'=>$query->id])->sum("betAmount");

        //         if(!empty($betdetails)){
        //             $array['matchOption']  = $query->betOptionName;
        //             $array['betoption_id'] = $query->id;
        //             $array['match_id']     = $query->match_id;
        //             $array['betStatus']    = $betStatusOff;
        //             $array['betCoin']      = $betCoin;
        //             $array['betDetails']   = $betdetails;
        //         }
        //         return $array;
        // })->toArray();

        $betOptionPluck = DB::table('betoptions')->where('sport_id',$match->sport_id)->pluck('betOptionName','id');
        //dd($betOptionPluck);

        $optionBetDetails = [];
        if($betOptionPluck) {
            foreach ($betOptionPluck as $betKey => $betOption) {
                //return $id;
                $betdetails = DB::table('betdetails')
                    ->where(['match_id' => $id, 'betoption_id' => $betKey])
                    ->whereIn("status",[0,1])
                    ->get()
                    ->toArray();


                if (!empty($betdetails)) {

                    $betStatusOff = DB::table('betdetails')
                        ->where(['match_id' => $id, 'betoption_id' => $betKey])
                        ->where("status","=",0)
                        ->get()->count();

                    $betCoin = Betplace::where(['match_id'=>$id,'betoption_id'=>$betKey])->sum("betAmount");

                    $optionBetDetails[$betKey]['matchOption'] = $betOption;
                    $optionBetDetails[$betKey]['betoption_id'] = $betKey;
                    $optionBetDetails[$betKey]['match_id'] = $id;
                    $optionBetDetails[$betKey]['betStatus'] = $betStatusOff;
                    $optionBetDetails[$betKey]['betCoin'] = $betCoin;
                    $optionBetDetails[$betKey]['betDetails'] = $betdetails;
                }
            }

        }
        
        //dd($betOptionAll);
        //dd($optionBetDetails);
        //dd($match);
        //dd($betOptions);
        $allBetOff = Betdetail::where("match_id",$id)->where("status",0)->where("status","!=",3)->get()->count();
        $score = Score::with('match')->where("id",$score_id)->first();
        return view('match::matches.updatebetrate',compact('match','betOptions','optionBetDetails','allBetOff','score'));
        // return view('match::matches.newrate',compact('match','betOptions','optionBetDetails','allBetOff','score'));
        
    }

    public function liveMatchUpdateBetRate(Request $request, $id) {

        $this->validate($request,[
            'betRateEdit' => 'required|numeric',
        ],[
            'betRateEdit.required' => 'Bet rate is required',
        ]);

        try{

            $betDetail = Betdetail::find($id);
            $betDetail->betRate = trim(strip_tags($request->betRateEdit));
            $betDetail->update();

            Toastr::success("Live matches bet rate updated","Success!");
            return redirect()->back();

        } catch(Exception $e) {

            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();

        }
    }
    
    # Match delete forever
    
    public function matchDeleteForever($id) {

        DB::transaction(function () use($id) {
            DB::table('matches')->where('id',$id)->where("status",4)->delete();
            DB::table('scores')->where("match_id",$id)->delete();
        });

        Toastr::success("Match delete forever successfully.","Success!");
        return redirect()->back();

    }
    
    # Single match Group question list
    public function singleMatchGroupQuestionFirst($id)
    {
        
        $match = Match::leftJoin('teams as teamOne', 'matches.teamOne_id', '=', 'teamOne.id')
            ->leftJoin('teams as teamTwo', 'matches.teamTwo_id', '=', 'teamTwo.id')
            ->select("matches.id","matches.sport_id","teamOne.teamName as firstTeam","teamTwo.teamName  as secondTeam")
            ->where("matches.id",$id)->first();
            
            if ($match->sport_id == 1) {
                $questionAnseres = [
                    ["5","$match->firstTeam","1.90"],
                    ["5","$match->secondTeam","1.90"],
                    ["6","$match->firstTeam","2.32"],
                    ["6","$match->secondTeam","1.61"],
                    ["7","Over 0.5","2.12"],
                    ["7","Under 0.5","1.66"],
                    ["8","Over 3.5","6.12"],
                    ["8","Under 3.5","1.12"],
                    ["9","Over 5.5","1.80"],
                    ["9","Under 5.5","1.90"],
                    ["12","Odd","1.90"],
                    ["12","Even","1.90"],
                    ["13","No","1.21"],
                    ["13","Yes","4.12"],
                    ["15","Over 20.5","1.90"],
                    ["15","Under 20.5","1.90"],
                    ["16","Caught","1.44"],
                    ["16","Others","2.67"],
                    ["42","No","1.16"],
                    ["42","Yes","5.12"],
                    ["61","Over 63.5","1.90"],
                    ["61","Under 63.5","1.90"],
                    ["56","No","4.12"],
                    ["56","Yes","1.21"],
                    ["57","No","1.04"],
                    ["57","Yes","7.56"],
                    ["92","Over 0.5","4.12"],
                    ["92","Under 0.5","1.21"],
                    ["791","No","1.02"],
                    ["791","Yes","18.00"],
                    ["706","Caught","1.45"],
                    ["706","LBW","3.75"],
                    ["706","Bowled","4.16"],
                    ["706","Run Out","7.56"],
                    ["706","Stamped","11.00"],
                    ["706","Others","101.00"],
                    ["63","$match->firstTeam","1.90"],
                    ["63","draw","7.56"],
                    ["63","$match->secondTeam","1.90"],
                    ["64","$match->firstTeam","2.00"],
                    ["64","draw","13.00"],
                    ["64","$match->secondTeam","1.72"],
                    ["1202","$match->firstTeam","1.90"],
                    ["1202","draw","13.00"],
                    ["1202","$match->secondTeam","1.90"],
                    ["784","$match->firstTeam","1.90"],
                    ["784","draw","7.56"],
                    ["784","$match->secondTeam","1.90"],
                    ["88","$match->firstTeam","1.90"],
                    ["88","draw","4.56"],
                    ["88","$match->secondTeam","1.90"],
                    ["81","$match->firstTeam","1.90"],
                    ["81","$match->secondTeam","1.90"],
                    ["82","$match->firstTeam","1.90"],
                    ["82","$match->secondTeam","1.90"],
                    ["66","$match->firstTeam","1.90"],
                    ["66","draw","13.00"],
                    ["66","$match->secondTeam","1.90"],
                    ["89","$match->firstTeam","3.36"],
                    ["89","draw","1.83"],
                    ["89","$match->secondTeam","3.36"],
                    ["1205","No Wicket","3.56"],
                    ["1205","1 Wicket","2.56"],
                    ["1205","2+ Wickets","2.12"]
                ];
            } else if($match->sport_id == 2) {
                $questionAnseres = [
                    ["1","$match->firstTeam","3.48"],
                    ["1","draw","1.78"],
                    ["1","$match->secondTeam","3.16"],
                    ["2","$match->firstTeam","2.68"],
                    ["2","draw","2.80"],
                    ["2","$match->secondTeam","2.61"],
                    ["4","No","1.60"],
                    ["4","Yes","2.00"],
                    ["26","Odd","1.85"],
                    ["26","Even","1.85"],
                    ["409","$match->firstTeam","1.71"],
                    ["409","$match->secondTeam","1.44"],
                    ["546","Over 2.5","2.20"],
                    ["546","Under 2.5","1.40"],
                    ["1152","Over 1.5","1.40"],
                    ["1152","Under 1.5","2.20"],
                    ["1158","None","3.25"],
                    ["1158","only $match->firstTeam","4.00"],
                    ["1158","only $match->secondTeam","3.00"],
                    ["1158","Both teams","1.85"],
                    ["1163","Over 9.5","1.90"],
                    ["1163","Under 9.5","1.70"],
                    ["1162","Over 8.5","1.62"],
                    ["1162","Under 8.5","2.16"],
                    ["1164","Over 10.5","2.33"],
                    ["1164","Under 10.5","1.47"],
                    ["1168","0-1","1.85"],
                    ["1168","2-3","1.66"],
                    ["1168","4-6","3.12"],
                    ["1168","7+","35.00"],
                    ["1169","$match->firstTeam","1.90"],
                    ["1169","None","3.25"],
                    ["1169","$match->secondTeam","1.70"],
                    ["1303","$match->firstTeam","1.85"],
                    ["1303","none","3.25"],
                    ["1303","$match->secondTeam","1.70"],
                    ["1304","Over 2.5 & yes","2.65"],
                    ["1304","Under 2.5 & yes","3.56"],
                    ["1304","Over 2.5 & no","7.00"],
                    ["1304","Under 2.5 & no","1.41"],
                    ["1329","1st half","3.00"],
                    ["1329","2nd half","1.80"],
                    ["1329","Equal","2.56"],
                    ["1160","$match->firstTeam","1.75"],
                    ["1160","none","35.00"],
                    ["1160","$match->secondTeam","1.72"],
                    ["1161","$match->firstTeam","1.75"],
                    ["1161","none","35.00"],
                    ["1161","$match->secondTeam","1.72"],
                    ["551","0-8","1.85"],
                    ["551","9-11","2.56"],
                    ["551","12+","3.00"],
                    ["1167","Odd","1.85"],
                    ["1167","Even","1.85"],
                    ["1330","$match->firstTeam","3.05"],
                    ["1330","none","1.75"],
                    ["1330","$match->secondTeam","2.45"],
                    ["1331","$match->firstTeam","2.45"],
                    ["1331","none","2.45"],
                    ["1331","$match->secondTeam","2.02"],
                    ["1396","$match->firstTeam","2.00"],
                    ["1396","Draw","3.56"],
                    ["1396","$match->secondTeam","1.85"],
                    ["1398","$match->firstTeam","1.81"],
                    ["1398","none","13.00"],
                    ["1398","$match->secondTeam","1.75"],
                    ["1397","$match->firstTeam","1.81"],
                    ["1397","none","13.00"],
                    ["1397","$match->secondTeam","1.75"]
                ];
            }

        DB::beginTransaction();
        try{
            foreach($questionAnseres as $key => $single) {
                $betDetail = new Betdetail();
                $betDetail->match_id = $match->id;
                $betDetail->betoption_id = $single[0];
                $betDetail->betName = $single[1];
                $betDetail->betRate = $single[2];
                $betDetail->created_by   = Auth::guard("admin")->user()->id;
                $betDetail->save();
            }
            
            $matchUpdate = Match::where('id',$match->id)->first();
            $matchUpdate->questionStatusFirst = 1;
            $matchUpdate->update();
    
            DB::commit();
            Toastr::success("Group bet store successfully","Success!");
            return redirect()->back();
            
        }catch (Exception $e){
            DB::rollback();
            Toastr::error("Something Went wrong", "Error!");
            return redirect()->back();
        }
        
    }
    
    # Single match Group question list
    public function singleMatchGroupQuestionSecond($id)
    {
        
        $match = Match::leftJoin('teams as teamOne', 'matches.teamOne_id', '=', 'teamOne.id')
            ->leftJoin('teams as teamTwo', 'matches.teamTwo_id', '=', 'teamTwo.id')
            ->select("matches.id","teamOne.teamName as firstTeam","teamTwo.teamName  as secondTeam")
            ->where("matches.id",$id)->first();

        $questionAnseres = [
                                ["96","Over 0.5","2.12"],
                                ["96","Under 0.5","1.66"],
                                ["97","Over 3.5","7.12"],
                                ["97","Under 3.5","1.08"],
                                ["98","Over 4.5","1.31"],
                                ["98","Under 4.5","2.53"],
                                ["99","Over 5.5","1.90"],
                                ["99","Under 5.5","1.90"],
                                ["100","Over 6.5","2.20"],
                                ["100","Under 6.5","1.60"],
                                ["101","Odd","1.90"],
                                ["101","Even","1.90"],
                                ["102","No","1.21"],
                                ["102","Yes","4.12"],
                                ["103","Four","1.12"],
                                ["103","Six","5.12"],
                                ["104","Over 21.5","1.90"],
                                ["104","Under 21.5","1.90"],
                                ["105","Caught","1.44"],
                                ["105","Others","2.56"],
                                ["1016","Odd","1.90"],
                                ["1016","Even","1.90"],
                                ["1017","Odd","1.90"],
                                ["1017","Even","1.90"],
                                ["1032","Odd","1.90"],
                                ["1032","Even","1.90"],
                                ["1031","Odd","1.90"],
                                ["1031","Even","1.90"]
                            ];

        DB::beginTransaction();
        try{
            foreach($questionAnseres as $key => $single) {
                $betDetail = new Betdetail();
                $betDetail->match_id = $match->id;
                $betDetail->betoption_id = $single[0];
                $betDetail->betName = $single[1];
                $betDetail->betRate = $single[2];
                $betDetail->created_by   = Auth::guard("admin")->user()->id;
                $betDetail->save();
            }
            
            $matchUpdate = Match::where('id',$match->id)->first();
            $matchUpdate->questionStatusSecond = 1;
            $matchUpdate->update();
    
            DB::commit();
            Toastr::success("Group bet store 2nd innings successfully","Success!");
            return redirect()->back();
            
        }catch (Exception $e){
            DB::rollback();
            Toastr::error("Something Went wrong", "Error!");
            return redirect()->back();
        }
        
    }
    

}
