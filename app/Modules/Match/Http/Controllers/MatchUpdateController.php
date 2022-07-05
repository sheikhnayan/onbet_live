<?php

namespace App\Modules\Match\Http\Controllers;

use App\Events\betdetailUpdateEvent;
use App\Models\Match\Betdetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Carbon\Carbon;// after
use Exception;// after
use Illuminate\Support\Facades\Auth;// after
use App\Http\Controllers\Controller;
use App\Models\Betplace\Betplace;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Club\Club;
use App\Models\Deposit\Masterdeposit;

class MatchUpdateController extends Controller
{
    #Total question bet rate update
    public function totalQuestionBetRateUpdate (Request $request) {
        //dd($request->id);

        try {

            foreach($request->id as $key=>$betOptionItem){
                $betDetail = Betdetail::findOrFail($betOptionItem);
                $betDetail->betRate = $request->betRateEdit[$key];
                $betDetail->update();
            }

            $message = 1;
            event(new betdetailUpdateEvent($message));

            Toastr::success("Bet rate successfully updated!","Success!");
            return redirect()->back();

        }catch (Exception $e){
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();
        }

    }

    #Total question bet rate update new ajax
    public function totalQuestionBetRateUpdateNewAjax (Request $request) {
       // return $request->all();
        try {

            foreach($request->id as $key=>$betOptionItem){
                $betDetail = Betdetail::findOrFail($betOptionItem);
                $betDetail->betRate = $request->betRateEdit[$key];
                $betDetail->update();
            }

            $message = 1;
            event(new betdetailUpdateEvent($message));
            
            return response()->json([
                'status'    => 201,
                'msg'      => 'Bet rate successfully updated'
            ]);

        }catch (Exception $e){
            return response()->json([
                'status'    => 201,
                'error'      => $e
            ]);
        }

    }
    
    #Total match bet off
    public function totalMatchBetOnOff(Request $request) {
        //return $request->all();

        if($request->fullMatchStatus == 0){
            try {
                $betDetails = Betdetail::where(["match_id"=>$request->matchId])->where("status",1)->update(['status'=>0]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                //Toastr::success("Total bet Off successfully","Success!");
                return response()->json([
                    'status'    => 201,
                    'msg'      => 'Total bet Off successfully'
                ]);

            }catch (Exception $e){
                return response()->json([
                    'status'    => 201,
                    'error'      => $e
                ]);
            }

        }else{
            try {
                $betDetails = Betdetail::where(["match_id"=>$request->matchId])->where("status",0)->update(['status'=>1]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                //Toastr::success("Total bet on successfully","Success!");
                return response()->json([
                    'status'    => 201,
                    'msg'      => 'Total bet on successfully'
                ]);
            }catch (Exception $e){
                return response()->json([
                    'status'    => 201,
                    'error'      => $e
                ]);
            }
        }
    }
    
        // after
    #Update single bet option
    public function updateSingleBetOptionAjax(Request $request) {
        
        $this->validate($request,[
            'betRateEdit' => 'required|numeric',
        ],[
            'betRateEdit.required' => 'Bet rate is required',
            'betRateEdit.numeric' => 'Bet rate is only taken numeric value',
        ]);
        
        
        $betDetail = Betdetail::where('id',$request->id)->where('status',3)->first();
        
        if(!empty($betDetail)){
            return response()->json([
                'status'    => false,
                'msg'      => "Already published refresh this page"
            ]);
        }

        try{
            $betDetail = Betdetail::find($request->id);
            $betDetail->betRate = trim(strip_tags($request->betRateEdit));
            $betDetail->updated_by   = Auth::guard("admin")->user()->id;
            $betDetail->updated_at   = Carbon::now();
            $betDetail->update();
            $message = 1;
            event(new betdetailUpdateEvent($message));
            return response()->json([
                'status'    => true,
                'msg'      => 'Single bet updated successfully'
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status'    => false,
                'msg'      => $e
            ]);
        }
    }
    
    #Bet Action On Off
    public function betActionOnOff(Request $request) {
        //return $request->all();
        $alreadyPublished = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->option_id,"status"=>3])->get();
        //return $alreadyPublished;
        if($alreadyPublished->count()>0){
            return response()->json([
                'status'    => false,
                'msg'      => "Already published refresh this page"
            ]);
        }
        if($request->status == 0){
           try {
                $betDetails = Betdetail::where(["match_id"=>$request->match_id,"betoption_id"=>$request->option_id])->whereIn("status",[0,1,2])->update(['status'=>0]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                return response()->json([
                    'status'    => true,
                    'msg'      => 'Bet Off successfully'
                ]);
            }catch (Exception $e){
                return response()->json([
                    'status'    => false,
                    'msg'      => $e
                ]);
            }
        }
        if($request->status == 1){
            try {
                $betDetails = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->option_id])->whereIn("status",[0,1,2])->update(['status' => 1]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                
                return response()->json([
                    'status'    => true,
                    'msg'      => 'Bet On successfully'
                ]);
                
            }catch (Exception $e){
                return response()->json([
                    'status'    => false,
                    'msg'      => $e
                ]);
            }
        }
        
    }

    #Hide form Frontend
    public function betHideOpenUserPageAjax(Request $request) {
    
        $alreadyPublished = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->option_id,"status"=>3])->get();
        
        if($alreadyPublished->count()>0){
            return response()->json([
                'status'    => false,
                'msg'      => "Already published refresh this page"
            ]);
        }
        
        if($request->status == 1){
            try {
                $betDetails = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->option_id])->update(['status' => 0]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                return response()->json([
                    'status'    => true,
                    'msg'      => 'Bet open for user successfully'
                ]);
            }catch (Exception $e){
                return response()->json([
                    'status'    => false,
                    'msg'      => $e
                ]);
            }
        }
        if($request->status == 2){
            try {
                $betDetails = Betdetail::where(["match_id" => $request->match_id, "betoption_id" => $request->option_id])->update(['status' => 2]);
                $message = 1;
                event(new betdetailUpdateEvent($message));
                
                return response()->json([
                    'status'    => true,
                    'msg'      => 'Bet hide form user page successfully'
                ]);
                
            }catch (Exception $e){
                return response()->json([
                    'status'    => false,
                    'msg'      => $e
                ]);
            }
        }

    }
    
    # All bet return for individual question
    public function returnQuestionAllBets($matchId,$betOptionId) {
        //return $matchId . ' = ' . $betOptionId;
        DB::beginTransaction();
        try{
            $lists = Betplace::where(["match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 0])->get();
            //$lists = DB::table('betplaces')->where(["club_id" => 11,"match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 0])->get();
            // $lists = DB::table('betplaces')->where(["match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 0])->sum('betAmount');
             //return $lists;
            if(!empty($lists)){
                foreach($lists as $betPlace){
    
                    $betPlace->winLost = "bet return";
                    $betPlace->status = 5;
                    $betPlace->save();
    
                    $user = User::where("id",$betPlace->user_id)->where("status",1)->first();
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
    
                }
                #Betdetail
                $betDetail = Betdetail::where(["match_id" => $matchId, "betoption_id" => $betOptionId])
                    ->whereIn("status", [0, 1, 2])
                    ->update(["status" => 3]);
            }
            DB::commit();
            Toastr::success("Return all bets for this question successfully ", "Success!");
            return redirect()->back();

        }catch (Exception $e){
            DB::rollback();
            Toastr::error("Something went wrong","Error!");
            return redirect()->back();
        }
    }
    
    # All bet return for individual question
    public function returnQuestionAllBetsBack($matchId,$betOptionId) {
        return "Not work yet";
        // return $matchId . ' = ' . $betOptionId;
        //DB::beginTransaction();
        try{
            $lists = Betplace::where(["match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 5])->get();
            //$lists = DB::table('betplaces')->where(["club_id" => 11,"match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 0])->get();
            // $lists = DB::table('betplaces')->where(["match_id" => $matchId, "betoption_id" => $betOptionId, "status" => 0])->sum('betAmount');
            // return $lists->count();
            if(!empty($lists)){
                foreach($lists as $betPlace){
    
                    $betPlace->winLost = "match live";
                    $betPlace->status = 0;
                    $betPlace->save();
    
                    $user = User::where("id",$betPlace->user_id)->first();
                    $user->totalLossAmount = ($user->totalLossAmount - $betPlace->betAmount);
                    $user->update();
    
                    #Update user totalBalance
                    $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
                    $user->update();
    
                    #Club update
                    // $club = Club::where("id",$betPlace->club_id)->first();
                    // $club->totalProfit = ($club->totalProfit +  $betPlace->clubGet);
                    // $club->update();
                    // $club->totalBalance = ($club->totalProfit + $club->totalWithdrawAmount);
                    // $club->update();
    
                    // #MasterDeposit update
                    // $mainSiteDeposit = Masterdeposit::first();
                    // $mainSiteDeposit->totalSiteDeposit = ($mainSiteDeposit->totalSiteDeposit + $betPlace->clubGet);
                    // $mainSiteDeposit->totalLossToClub  = ($mainSiteDeposit->totalLossToClub - $betPlace->clubGet);
                    // $mainSiteDeposit->update();
                    
                    // if ($betPlace->sponsorName != null) {
                    //     $userSponsor = User::where("username", $betPlace->sponsorName)->first();
                    //     $userSponsor->totalSponsorAmount = ($userSponsor->totalSponsorAmount - $betPlace->sponsorGet);
                    //     $userSponsor->update();
    
                    //     #User total balance update
                    //     $userSponsor->totalBalance = ($userSponsor->totalRegularDepositAmount + $userSponsor->totalSpecialDepositAmount + $userSponsor->totalCoinReceiveAmount + $userSponsor->totalSponsorAmount + $userSponsor->totalProfitAmount) - ($userSponsor->totalCoinTransferAmount + $userSponsor->totalLossAmount + $userSponsor->totalWithdrawAmount);
                    //     $userSponsor->update();
    
                    //     #MasterDeposit update
                    //     $mainSiteDeposit = Masterdeposit::first();
                    //     $mainSiteDeposit->totalSiteDeposit = ($mainSiteDeposit->totalSiteDeposit + $betPlace->sponsorGet);
                    //     $mainSiteDeposit->totalLossToSponsor = ($mainSiteDeposit->totalLossToSponsor - $betPlace->sponsorGet);
                    //     $mainSiteDeposit->update();
                    // }
    
                }
                #Betdetail
                /*$betDetail = Betdetail::where(["match_id" => $matchId, "betoption_id" => $betOptionId])
                    ->where("status", 3)
                    ->update(["status" => 2]);*/
            }
            //DB::commit();
            /*Toastr::success("Return back successfully ", "Success!");
            return redirect()->back();*/

        }catch (Exception $e){
            
            //DB::rollback();
            Toastr::error("Something went wrong $e","Error!");
            return redirect()->back();
        }
    }
}
