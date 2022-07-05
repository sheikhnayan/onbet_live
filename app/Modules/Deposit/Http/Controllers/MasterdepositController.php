<?php

namespace App\Modules\Deposit\Http\Controllers;

use App\Models\Deposit\Masterdeposit;
use App\Models\Deposit\Userdeposit;
use App\Models\Deposit\Masterdepositdetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Exception;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class MasterdepositController extends Controller
{
    
    #Total system search.
    public function totalSystemSearch() {
        return view("deposit::masterdeposit.search");
    }

    
    #Total deposit and withdraw
    public function totalDepositWithdraw(Request $request) {

        if($request->actionType == "both" && $request->accepted_by == 0){

            if(!empty($request->day)){

                $userRegDeps= DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","getmoney")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userCTCDeps = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","cointocoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userWithdraws = DB::table("userwithdraws")
                    ->leftJoin("users","users.id","=","userwithdraws.user_id")
                    ->leftJoin("admins","admins.id","=","userwithdraws.withdrawAcceptedBy")
                    ->whereMonth("userwithdraws.updated_at",$request->month)
                    ->whereYear("userwithdraws.updated_at",$request->year)
                    ->whereDay("userwithdraws.updated_at",$request->day)
                    ->where("userwithdraws.status",1)
                    ->orderBy("userwithdraws.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userwithdraws.*")
                    ->get();

                $clubWithdraws = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->whereDay("clubwithdraws.updated_at", $request->day)
                    ->where("clubwithdraws.withdrawType", 'sendmoney')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubWithdrawsCoin = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->whereDay("clubwithdraws.updated_at", $request->day)
                    ->where("clubwithdraws.withdrawType", 'clubwithdrawcoin')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubDepositCoin = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","clubwithdrawcoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userRegDepTotal= DB::table("userdeposits")
                    ->where("depositType","=","getmoney")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

                $userCTCDepTotal = DB::table("userdeposits")
                    ->where("depositType","=","cointocoin")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

                $userWithdrawTotal = DB::table("userwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawTotal = DB::table("clubwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("withdrawType",'sendmoney')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawCoinTotal = DB::table("clubwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("withdrawType",'clubwithdrawcoin')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubDepositCoinTotal = DB::table("userdeposits")
                    ->where("depositType","=","clubwithdrawcoin")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

            }else {

                $userRegDeps= DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","getmoney")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userCTCDeps = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","cointocoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userWithdraws = DB::table("userwithdraws")
                    ->leftJoin("users","users.id","=","userwithdraws.user_id")
                    ->leftJoin("admins","admins.id","=","userwithdraws.withdrawAcceptedBy")
                    ->whereMonth("userwithdraws.updated_at",$request->month)
                    ->whereYear("userwithdraws.updated_at",$request->year)
                    ->where("userwithdraws.status",1)
                    ->orderBy("userwithdraws.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userwithdraws.*")
                    ->get();

                $clubWithdraws = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->where("clubwithdraws.withdrawType", 'sendmoney')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubWithdrawsCoin = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->where("clubwithdraws.withdrawType", 'clubwithdrawcoin')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubDepositCoin = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","clubwithdrawcoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userRegDepTotal= DB::table("userdeposits")
                    ->where("depositType","=","getmoney")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");

                $userCTCDepTotal = DB::table("userdeposits")
                    ->where("depositType","=","cointocoin")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");

                $userWithdrawTotal = DB::table("userwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawTotal = DB::table("clubwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("withdrawType",'sendmoney')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawCoinTotal = DB::table("clubwithdraws")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("withdrawType",'clubwithdrawcoin')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubDepositCoinTotal = DB::table("userdeposits")
                    ->where("depositType","=","clubwithdrawcoin")
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");
            }
            return view("deposit::masterdeposit.searchResult",compact("userRegDeps","userRegDepTotal","userCTCDeps","userCTCDepTotal","userWithdraws","userWithdrawTotal","clubWithdraws","clubWithdrawTotal","clubWithdrawsCoin","clubDepositCoin","clubWithdrawCoinTotal","clubDepositCoinTotal"));
        }

        if($request->accepted_by != 0){

            if(!empty($request->day)){

                $userRegDeps= DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->where("userdeposits.depositType","=","getmoney")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userCTCDeps = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->where("userdeposits.depositType","=","cointocoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userWithdraws = DB::table("userwithdraws")
                    ->leftJoin("users","users.id","=","userwithdraws.user_id")
                    ->leftJoin("admins","admins.id","=","userwithdraws.withdrawAcceptedBy")
                    ->where("userwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("userwithdraws.updated_at",$request->month)
                    ->whereYear("userwithdraws.updated_at",$request->year)
                    ->whereDay("userwithdraws.updated_at",$request->day)
                    ->where("userwithdraws.status",1)
                    ->orderBy("userwithdraws.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userwithdraws.*")
                    ->get();

                $clubWithdraws = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->where("clubwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->whereDay("clubwithdraws.updated_at", $request->day)
                    ->where("clubwithdraws.withdrawType", 'sendmoney')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubWithdrawsCoin = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->where("clubwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->whereDay("clubwithdraws.updated_at", $request->day)
                    ->where("clubwithdraws.withdrawType", 'clubwithdrawcoin')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubDepositCoin = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->where("userdeposits.depositType","=","clubwithdrawcoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->whereDay("userdeposits.updated_at",$request->day)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userRegDepTotal= DB::table("userdeposits")
                    ->where("depositType","=","getmoney")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

                $userCTCDepTotal = DB::table("userdeposits")
                    ->where("depositType","=","cointocoin")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

                $userWithdrawTotal = DB::table("userwithdraws")
                    ->where("withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawTotal = DB::table("clubwithdraws")
                    ->where("withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("withdrawType",'sendmoney')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawCoinTotal = DB::table("clubwithdraws")
                    ->where("withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->where("withdrawType",'clubwithdrawcoin')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubDepositCoinTotal = DB::table("userdeposits")
                    ->where("depositType","=","clubwithdrawcoin")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->whereDay("updated_at",$request->day)
                    ->get()
                    ->sum("depositAmount");

            }else {

                $userRegDeps= DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->where("userdeposits.depositType","=","getmoney")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userCTCDeps = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->where("userdeposits.depositType","=","cointocoin")
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userWithdraws = DB::table("userwithdraws")
                    ->leftJoin("users","users.id","=","userwithdraws.user_id")
                    ->leftJoin("admins","admins.id","=","userwithdraws.withdrawAcceptedBy")
                    ->where("userwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("userwithdraws.updated_at",$request->month)
                    ->whereYear("userwithdraws.updated_at",$request->year)
                    ->where("userwithdraws.status",1)
                    ->orderBy("userwithdraws.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userwithdraws.*")
                    ->get();

                $clubWithdraws = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->where("clubwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->where("clubwithdraws.withdrawType", 'sendmoney')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubWithdrawsCoin = DB::table("clubwithdraws")
                    ->leftJoin("clubs", "clubs.id", "=", "clubwithdraws.club_id")
                    ->leftJoin("admins", "admins.id", "=", "clubwithdraws.withdrawAcceptedBy")
                    ->where("clubwithdraws.withdrawAcceptedBy",$request->accepted_by)
                    ->whereMonth("clubwithdraws.updated_at", $request->month)
                    ->whereYear("clubwithdraws.updated_at", $request->year)
                    ->where("clubwithdraws.withdrawType", 'clubwithdrawcoin')
                    ->where("clubwithdraws.status", 1)
                    ->orderBy("clubwithdraws.updated_at", "ASC")
                    ->select("clubs.username", "admins.username as acceptedBy", "clubwithdraws.*")
                    ->get();

                $clubDepositCoin = DB::table("userdeposits")
                    ->leftJoin("users","users.id","=","userdeposits.user_id")
                    ->leftJoin("admins","admins.id","=","userdeposits.accepted_by")
                    ->where("userdeposits.depositType","=","clubwithdrawcoin")
                    ->where("userdeposits.accepted_by","=",$request->accepted_by)
                    ->whereMonth("userdeposits.updated_at",$request->month)
                    ->whereYear("userdeposits.updated_at",$request->year)
                    ->orderBy("userdeposits.updated_at","ASC")
                    ->select("users.username","admins.username as acceptedBy","userdeposits.*")
                    ->get();

                $userRegDepTotal= DB::table("userdeposits")
                    ->where("depositType","=","getmoney")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");

                $userCTCDepTotal = DB::table("userdeposits")
                    ->where("depositType","=","cointocoin")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");

                $userWithdrawTotal = DB::table("userwithdraws")
                    ->where("withdrawAcceptedBy","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawTotal = DB::table("clubwithdraws")
                    ->where("withdrawAcceptedBy","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("withdrawType",'sendmoney')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubWithdrawCoinTotal = DB::table("clubwithdraws")
                    ->where("withdrawAcceptedBy","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->where("withdrawType",'clubwithdrawcoin')
                    ->where("status",1)
                    ->get()
                    ->sum("withdrawAmount");

                $clubDepositCoinTotal = DB::table("userdeposits")
                    ->where("depositType","=","clubwithdrawcoin")
                    ->where("accepted_by","=",$request->accepted_by)
                    ->whereMonth("updated_at",$request->month)
                    ->whereYear("updated_at",$request->year)
                    ->get()
                    ->sum("depositAmount");
            }
            return view("deposit::masterdeposit.searchResult",compact("userRegDeps","userRegDepTotal","userCTCDeps","userCTCDepTotal","userWithdraws","userWithdrawTotal","clubWithdraws","clubWithdrawTotal","clubWithdrawsCoin","clubDepositCoin","clubWithdrawCoinTotal","clubDepositCoinTotal"));
        }

    }



    #Main Balance
    public function mainBalance() {
        $mainBalance      = Masterdeposit::first();
        $userTotalBalance = DB::table("users")->get()->sum("totalBalance");
        $betPlaceBalance  = DB::table("betplaces")->where("status",0)->get()->sum("betAmount");
        return view("deposit::masterdeposit.index",compact('mainBalance','userTotalBalance','betPlaceBalance'));
    }

    #Site Summary
    public function siteSummary() {

        $siteDeposit          = Masterdepositdetail::where("status",1)->get()->sum("depositAmount");
        $userRegularDeposit   = Userdeposit::where(["depositType"=>"getmoney","status"=>1])->get()->sum("depositAmount");
        $userSpecialDeposit   = Userdeposit::where(["depositType"=>"cointocoin","status"=>1])->get()->sum("depositAmount");
        $clubProfit           = DB::table("betplaces")->get()->sum("clubGet");
        $sponsorProfit        = DB::table("betplaces")->get()->sum("sponsorGet");
        $userProfit           = DB::table("betplaces")->where("status",1)->get()->sum("betProfit");
        $siteProfit           = DB::table("betplaces")->where("status",2)->get()->sum("betLost");
        $partialProfit        = DB::table("betplaces")->whereIn("status",[3,4])->get()->sum("partialLost");
        $userTotalBalance     = DB::table("users")->get()->sum("totalBalance");
        $betPlaceBalance      = DB::table("betplaces")->where("status",0)->get()->sum("betAmount");
        $userWithdraws        = DB::table("userwithdraws")->where(["status"=>1])->sum("withdrawAmount");
        $clubSendMoney        = DB::table("clubwithdraws")->where(["withdrawType"=>"sendMoney","status"=>1])->sum("withdrawAmount");
        $clubSendCoin         = DB::table("clubwithdraws")->where(["withdrawType"=>"sendCoin","status"=>1])->sum("withdrawAmount");
        $siteWithdraw         = DB::table("masterwithdraws")->where(["status"=>1])->sum("withdrawAmount");

        return view("deposit::masterdeposit.siteSummary",compact("siteDeposit","userRegularDeposit","userSpecialDeposit","clubProfit","sponsorProfit","userProfit","siteProfit","partialProfit","userTotalBalance","betPlaceBalance","userWithdraws","clubSendMoney","clubSendCoin","siteWithdraw"));
    }
    
    #Master deposit detail view
    public function index() {
        $masterDepositAmounts = Masterdepositdetail::where("status",1)->get();
        return view("deposit::masterdepositdetail.index",compact('masterDepositAmounts'));
    }

    #Master deposit detail created
    public function create() {
        return view("deposit::masterdepositdetail.create");
    }

    #Master deposit detail store
    public function store(Request $request) {

        $this->validate($request,[
            "depositAmount" => "required|numeric"
        ],[
            "depositAmount.required" => "Deposit amount is required",
            "depositAmount.numeric" => "Deposit amount only numeric"
        ]);

        try {
            $masterDepositDetail = new Masterdepositdetail();
            $masterDepositDetail->depositAmount = trim(strip_tags($request->depositAmount));
            /*$masterDepositDetail->pcMac = strtok(exec('getmac'), ' ');*/
            $masterDepositDetail->created_by = Auth::guard("admin")->user()->id;
            $masterDepositDetail->save();

            #Update main balance
            $mainSiteDeposit = Masterdeposit::first();
            //return $mainSiteDeposit;
            $mainSiteDeposit->totalSiteDeposit = ($mainSiteDeposit->totalSiteDeposit + $masterDepositDetail->depositAmount);
            $mainSiteDeposit->update();

            Toastr::success("Deposit add successfully","Success!");
            return redirect()->back();

        }catch (Exception $e){
            return $e;
            Toastr::error("Sorry something went wrong!","Danger!");
            return redirect()->back();
        }

    }
}
