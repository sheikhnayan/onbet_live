<?php

namespace App\Http\Controllers;

use App\Models\Config\Config;
use App\Models\Deposit\Masterdeposit;
use App\Models\Withdraw\Userwithdraw;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserwithdrawController extends Controller
{

   #Withdraw page view.
    public function withdraw() {
        $config = Config::first();
        return view("frontend.pages.withdraw",compact("config"));
    }
    #Withdraw page view.
    public function userWithdrawStore(Request $request) {

        /*Validation*/
        $this->validate($request,[
            "withdrawAmount" => "required",
            "withdrawNumber" => "required",
            "withdrawPaymentType" => "required",
            "password" => "required",
        ],[
            "withdrawAmount.required" => "Withdraw amount is required",
            "withdrawNumber.required" => "Withdraw number is required",
            "withdrawPaymentType.required" => "Withdraw payment type is required",
            "password.required" => "Password is required",
        ]);


        try{

            /*Amount withdraw range*/
            $config = Config::first();
            if(trim(strip_tags($request->withdrawAmount)) < $config->userWithdrawMinimum || trim(strip_tags($request->withdrawAmount)) > $config->userWithdrawMaximum ){
                return redirect()->back()->with('warning', "Sorry! Withdraw range $config->userWithdrawMinimum to $config->userWithdrawMaximum");
            }

            #check old and requested new password.
            $user = User::find(Auth::guard("web")->user()->id);
            if(!Hash::check(trim(strip_tags($request->password)), $user->password)) {
                return redirect()->back()->with('warning', "Sorry! Your password not match");
            }

            /*Coin transfer permission*/
            if($config->userWithdrawStatus == 0){
                return redirect()->back()->with('warning', "Sorry! Withdraw not available right now");
            }
            if($user->totalBalance < 0 || $user->totalRegularDepositAmount < 0 || $user->totalSpecialDepositAmount < 0 || $user->totalCoinReceiveAmount < 0 || $user->totalSponsorAmount < 0 || $user->totalProfitAmount < 0 || $user->totalCoinTransferAmount < 0  || $user->totalLossAmount < 0   || $user->totalWithdrawAmount < 0 ){
                return redirect()->back()->with('warning', "Sorry! Account problem contact admin.");
            }

            /*user balance check*/
            if($user->totalBalance < trim(strip_tags($request->withdrawAmount))){
                return redirect()->back()->with('warning', "Sorry! Your balance not enough.");
            }

            /*user rest balance check*/
            if(($user->totalBalance - trim(strip_tags($request->withdrawAmount))) < 20 ){
                return redirect()->back()->with('warning', "Sorry! After withdraw account must have 20 coin");
            }

            $userWithdraw = new Userwithdraw();
            $userWithdraw->user_id = $user->id;
            $userWithdraw->withdrawAmount = trim(strip_tags($request->withdrawAmount));
            $userWithdraw->withdrawNumber   = trim(strip_tags($request->withdrawNumber));
            $userWithdraw->withdrawPaymentType = trim(strip_tags($request->withdrawPaymentType));
            /*$userWithdraw->withdrawUserPcMac  = strtok(exec('getmac'), ' ');*/
            $userWithdraw->save();

            #withdraw user table update
            $user->totalWithdrawAmount = ($user->totalWithdrawAmount + $userWithdraw->withdrawAmount);
            $user->update();

            #withdraw user table update
            $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
            $user->update();

            return redirect()->back()->with('success', "Withdraw request successfully.");
        }catch (Exception $e){
            return redirect()->back()->with('warning', "Something want wrong!");
        }

    }

    #Withdraw page view.
    public function withdrawHistory() {
        $userWithdrawHistories = Userwithdraw::with("user")
            ->where("user_id",Auth::guard("web")->user()->id)
            ->whereIn("status",[0,1,3])
            ->orderBy("created_at","desc")
            ->paginate(10);
        return view("frontend.pages.withdrawhistory",compact("userWithdrawHistories"));
    }

    #User Withdraw Return.
    public function userWithdrawCancel($id) {

        $userWithdrawHistory = Userwithdraw::where("id",$id)->where("status",0)->first();
        if(empty($userWithdrawHistory)){
            return redirect()->back()->with('warning', "Withdraw processing wait for sometime.");
        }
        $userWithdrawHistory->withdrawReturnDateTime = Carbon::now();
        $userWithdrawHistory->status = 2;
        $userWithdrawHistory->update();

        #withdraw user table update
        $user = User::where("id",$userWithdrawHistory->user_id)->first();
        $user->totalWithdrawAmount = ($user->totalWithdrawAmount - $userWithdrawHistory->withdrawAmount);
        $user->update();

        #withdraw user table update
        $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
        $user->update();

        return redirect()->back()->with('success', "Withdraw cancel successfully.");
    }

    #User New Withdraw admin view.
    public function userNewWithdrawView() {
        $userWithdrawHistories = Userwithdraw::with("user")->where("status",0)->get();
        return view("withdraw::Userwithdraw.request",compact("userWithdrawHistories"));
    }

    #User New Withdraw admin Edit.
    public function userNewWithdrawEdit($id) {
        
        $userWithdrawAll = Userwithdraw::select('id','created_at')->where("status",3)->orderBy("created_at","ASC")->first();
        $userWithdrawHistory = Userwithdraw::where("id",$id)->where("status",3)->first();

        /*if($userWithdrawAll->id != $userWithdrawHistory->id){
            Toastr::warning("You should follow the order first come first serve.","Warning!");
            return redirect()->back();
        }*/

        //return $userWithdrawHistory->id;
        return view("withdraw::Userwithdraw.requestEdit",compact("userWithdrawHistory"));
    }

    #User New Withdraw admin Edit.
    public function userNewWithdrawAccept(Request $request, $id) {

        /*Validation*/
        $this->validate($request,[
            "reference" => "required"
        ],[
            "reference.required" => "Reference is required"
        ]);

        /*Accepted by Admin*/
        $userWithdrawHistory = Userwithdraw::where("id",$id)->where("status",3)->first();
        $userWithdrawHistory->withdrawAcceptedBy = Auth::guard("admin")->user()->id;
        /*$userWithdrawHistory->withdrawAcceptedPcMac = strtok(exec('getmac'), ' ');*/
        $userWithdrawHistory->reference = trim(strip_tags($request->reference));
        $userWithdrawHistory->status = 1;
        $userWithdrawHistory->updated_at = Carbon::now();
        $userWithdrawHistory->update();

        #Update master
        $mainSiteDeposit = Masterdeposit::first();
        $mainSiteDeposit->totalWithdrawFromUser = ($mainSiteDeposit->totalWithdrawFromUser + $userWithdrawHistory->withdrawAmount);
        $mainSiteDeposit->update();

        Toastr::success("Success! User Withdraw Accepted", "Success!");
        return redirect()->route("user_unpaid_withdraw_view");
    }

    #User Cancel Withdraw admin view.
    public function userCancelWithdrawView() {
        
        $userWithdrawHistories = DB::table('userwithdraws')
                ->leftJoin('users', 'users.id', '=', 'userwithdraws.user_id')
                ->leftJoin('clubs', 'clubs.id', '=', 'users.club_id')
                ->leftJoin('admins', 'admins.id', '=', 'userwithdraws.withdrawAcceptedBy')
                ->select("userwithdraws.*","users.username","clubs.username as clubusername","admins.username as adminusername")
                ->where("userwithdraws.status",1)
                ->orderBy("userwithdraws.created_at","DESC")
                ->paginate(30);
                
        //$userWithdrawHistories = Userwithdraw::with("user")->where("status",2)->get();
        return view("withdraw::Userwithdraw.requestCancel",compact("userWithdrawHistories"));
    }

    #User Accept Withdraw admin view.
    public function userAcceptWithdrawView() {
        $userWithdrawHistories = DB::table('userwithdraws')
                ->leftJoin('users', 'users.id', '=', 'userwithdraws.user_id')
                ->leftJoin('clubs', 'clubs.id', '=', 'users.club_id')
                ->leftJoin('admins', 'admins.id', '=', 'userwithdraws.withdrawAcceptedBy')
                ->select("userwithdraws.*","users.username","clubs.username as clubusername","admins.username as adminusername")
                ->where("userwithdraws.status",1)
                ->orderBy("userwithdraws.created_at","DESC")
                ->paginate(30);
//                return $userWithdrawHistories;
                
        //$userWithdrawHistories = Userwithdraw::with(["user","acceptUser"])->where("status",1)->get();
        return view("withdraw::Userwithdraw.requestAccept",compact("userWithdrawHistories"));
    }

     #User Unpaid Withdraw admin view.
     public function userUnpaidWithdrawView() {
        $userWithdrawHistories = Userwithdraw::with(["user","acceptUser"])->where("status",3)->get();
        return view("withdraw::Userwithdraw.requestUnpaid",compact("userWithdrawHistories"));
    }

    #admin end cancel user withdraw
    public function adminUserWithdrawCancel($id) {
        DB::beginTransaction();
        try{
            $userWithdrawHistory = Userwithdraw::where("id",$id)->where("status",0)->first();
            $userWithdrawHistory->withdrawReturnDateTime = Carbon::now();
            $userWithdrawHistory->status = 2;
            $userWithdrawHistory->update();

            #withdraw user table update
            $user = User::where("id",$userWithdrawHistory->user_id)->first();
            $user->totalWithdrawAmount = ($user->totalWithdrawAmount - $userWithdrawHistory->withdrawAmount);
            $user->update();

            #withdraw user table update
            $user->totalBalance = ($user->totalRegularDepositAmount + $user->totalSpecialDepositAmount + $user->totalCoinReceiveAmount + $user->totalSponsorAmount + $user->totalProfitAmount) - ($user->totalCoinTransferAmount + $user->totalLossAmount + $user->totalWithdrawAmount);
            $user->update();

            DB::commit();
            Toastr::success("Withdraw cancel successfully.","Success!");
            return redirect()->back();

        }catch (Exception $e){
            DB::rollback();
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }

    #admin end go unpaid user withdraw
    public function adminUserWithdrawUnpaid($id) {
        
        try{
            $userWithdrawAll = Userwithdraw::select('id','created_at')->where("status",0)->orderBy("created_at","ASC")->first();
            $userWithdrawHistory = Userwithdraw::where("id",$id)->where("status",0)->first();

            if($userWithdrawAll->id != $userWithdrawHistory->id){
                Toastr::warning("You should follow the order first come first serve.","Warning!");
                return redirect()->back();
            }

            $userWithdrawHistory->withdrawReturnDateTime = Carbon::now();
            $userWithdrawHistory->withdrawAcceptedBy = Auth::guard("admin")->user()->id;
            $userWithdrawHistory->status = 3;
            $userWithdrawHistory->update();


            Toastr::success("Withdraw processing successfully.","Success!");
            return redirect()->back();

        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }

    public function coinTransferRecords() {

        try {
            $userCoinTransfers = DB::table('cointransfers')
                ->leftJoin('users as fromuser', 'fromuser.id', '=', 'cointransfers.fromuserid')
                ->leftJoin('clubs as fromclub', 'fromclub.id', '=', 'fromuser.club_id')
                ->leftJoin('users as touser', 'touser.id', '=', 'cointransfers.touserid')
                ->leftJoin('clubs as toclub', 'toclub.id', '=', 'touser.club_id')
                ->select("cointransfers.*","fromuser.username as fromUser","fromuser.totalCoinReceiveAmount as fromUserReceiveAmount","fromuser.totalCoinTransferAmount as fromUserTransferAmount","fromclub.username as fromClub","touser.username as toUser","touser.totalCoinReceiveAmount as toUserReceiveAmount","touser.totalCoinTransferAmount as toUserTransferAmount","toclub.username as toClub")
                ->orderBy("cointransfers.created_at","DESC")
                ->paginate(30);
            return view("withdraw::Usercointransfer.index",compact("userCoinTransfers"));
        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }

}
