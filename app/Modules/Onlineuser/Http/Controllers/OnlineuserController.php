<?php

namespace App\Modules\Onlineuser\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;

class OnlineuserController extends Controller
{
    #online user manage
    public function onlineUserManage() {
        $onlineUsers = User::with("club")->whereIn("status",[0,1,2])->orderBy('created_at','desc')->paginate(50);
        return view("onlineuser::onlineuser.index",compact('onlineUsers'));
    }
    
    #Zero Balance user manage
    public function zeroBalanceUser() {
        $onlineUsers = User::where('totalRegularDepositAmount',0)
        ->where('totalCoinReceiveAmount',0)
        ->where('totalSpecialDepositAmount',0)
        ->where('totalSponsorAmount',0)
        ->where('totalProfitAmount',0)
        ->orderBy('created_at','desc')
        ->paginate(100);
        
        return view("onlineuser::onlineuser.zero",compact('onlineUsers'));
    }

    #online user serarch
    public function onlineUserSearch() {
        return view("onlineuser::onlineuser.onlineusersearch");
    }
    
    #online minus user 
    public function minusUser() {
        $onlineUsers = User::with("club")->where("totalBalance","<",0)->get();
        return view("onlineuser::onlineuser.minusUser",compact('onlineUsers'));
    }

    #online user serarch
    public function searchIndivisualUser(Request $request) {        
        $onlineUser = User::with("club")->where('username',trim($request->searchuseranme))->whereIn("status",[0,1,2])->first();
        return view("onlineuser::onlineuser.index",compact('onlineUser'));
    }
    
    #shortcut User.
    public function shortcutUser($username) {        
        $onlineUser = User::with("club")->where('username',$username)->whereIn("status",[0,1,2])->first();
        return view("onlineuser::onlineuser.index",compact('onlineUser'));
    }
    
    #online user manage
    public function onlineUserStatusChange(Request $request) {
        //return $request->all();
        try {
            
            $admin = User::find($request->id);
            $admin->status = trim(strip_tags($request->status));
            $admin->save();
            Toastr::success("User status change successfully","Success!");
            return redirect()->back();

        }catch(Exception $e){
            Toastr::error("Something went wrong!","Danger!");
            return redirect()->back();
        }

    }

    public function onlineUserPasswordChange($id) {
        return view("onlineuser::onlineuser.changepassword",compact('id'));
    }

    public function updateOnlineUserPassword (Request $request, $id){

        $this->validate($request,[
            "password"    => "required",
        ],[
            'password.required' => 'Password is required'
        ]);

        $user  = User::where("id",$id)->first();
        //dd($user);
        $user->password = trim(strip_tags(bcrypt($request->password)));
        //$user->status = 2;
        $user->save();

        Toastr::success("User password updated successfully","Success!");
        return redirect()->back();
    }
    
    #online User Bet History.
    public function onlineUserBetHistory ($id) {

        $betHistories = DB::table('betplaces')
            ->leftJoin('matches', 'matches.id', '=', 'betplaces.match_id')
            ->leftJoin('users', 'users.id', '=', 'betplaces.user_id')
            ->leftJoin('sports', 'sports.id', '=', 'matches.sport_id')
            ->leftJoin('teams as teamOne', 'teamOne.id', '=', 'matches.teamOne_id')
            ->leftJoin('teams as teamTwo', 'teamTwo.id', '=', 'matches.teamTwo_id')
            ->leftJoin('betoptions', 'betoptions.id', '=', 'betplaces.betoption_id')
            ->leftJoin('betdetails as userans', 'userans.id', '=', 'betplaces.betdetail_id')
            ->leftJoin('betdetails as rightans', 'rightans.id', '=', 'betplaces.winner_id')
            ->select("users.username","betplaces.created_at","betplaces.betAmount","betplaces.betRate","betplaces.betProfit","betplaces.betLost","betplaces.partialLost","betplaces.winLost",
                "matches.matchTitle","matches.matchDateTime","sports.sportName","teamOne.teamName as teamOne",
                "teamTwo.teamName as teamTwo","betoptions.betOptionName as question",
                "userans.betName as userAns","rightans.betName as rightAns")
            ->where("betplaces.user_id",$id)
            ->orderBy("betplaces.created_at","DESC")
            ->get();
        //dd($betHistories);
        return view("onlineuser::onlineuser.onlineUserBetHistory",compact('betHistories'));
    }
    
    #Online User deposit history
    public function onlineUserDepositHistory($id) {
        $getMoneys = DB::table('userdeposits')
            ->leftJoin('users', 'users.id', '=', 'userdeposits.user_id')
            ->leftJoin('admins', 'admins.id', '=', 'userdeposits.accepted_by')
            ->where("userdeposits.status",1)
            ->where("userdeposits.depositType","getmoney")
            ->orderBy("userdeposits.updated_at","DESC")
            ->where("userdeposits.user_id",$id)
            ->select('userdeposits.*','users.username','admins.username as adminusername')
            ->get();
        $totalDeposit = DB::table('userdeposits')->where('user_id',$id)->where('status',1)->sum("depositAmount");
        // return $totalDeposit;
        return view("onlineuser::onlineuser.userDepositHistory",compact('getMoneys','totalDeposit'));
    }

    #Online user coin transfer history
    public function onlineUserCoinTransferHistory($id){
        try {
            $userCoinTransfers = DB::table('cointransfers')
                ->leftJoin('users as fromuser', 'fromuser.id', '=', 'cointransfers.fromuserid')
                ->leftJoin('clubs as fromclub', 'fromclub.id', '=', 'fromuser.club_id')
                ->leftJoin('users as touser', 'touser.id', '=', 'cointransfers.touserid')
                ->leftJoin('clubs as toclub', 'toclub.id', '=', 'touser.club_id')
                ->where("cointransfers.fromuserid",$id)
                ->select("cointransfers.*","fromuser.username as fromUser","fromuser.totalCoinReceiveAmount as fromUserReceiveAmount","fromuser.totalCoinTransferAmount as fromUserTransferAmount","fromclub.username as fromClub","touser.username as toUser","touser.totalCoinReceiveAmount as toUserReceiveAmount","touser.totalCoinTransferAmount as toUserTransferAmount","toclub.username as toClub")
                ->orderBy("cointransfers.created_at","DESC")
                ->paginate(50);
            $totalCoinTransfer  = DB::table('cointransfers')->where("fromuserid",$id)->sum("transferAmount");
            return view("onlineuser::onlineuser.userCoinTransferHistory",compact("userCoinTransfers","totalCoinTransfer"));
        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }

    #Online user coin receive history
    public function onlineUserCoinReceiveHistory($id){
        try {
            $userCoinTransfers = DB::table('cointransfers')
                ->leftJoin('users as fromuser', 'fromuser.id', '=', 'cointransfers.fromuserid')
                ->leftJoin('clubs as fromclub', 'fromclub.id', '=', 'fromuser.club_id')
                ->leftJoin('users as touser', 'touser.id', '=', 'cointransfers.touserid')
                ->leftJoin('clubs as toclub', 'toclub.id', '=', 'touser.club_id')
                ->select("cointransfers.*","fromuser.username as fromUser","fromuser.totalCoinReceiveAmount as fromUserReceiveAmount","fromuser.totalCoinTransferAmount as fromUserTransferAmount","fromclub.username as fromClub","touser.username as toUser","touser.totalCoinReceiveAmount as toUserReceiveAmount","touser.totalCoinTransferAmount as toUserTransferAmount","toclub.username as toClub")
                ->where("cointransfers.touserid",$id)
                ->orderBy("cointransfers.created_at","DESC")
                ->paginate(50);
            $totalCoinReceive  = DB::table('cointransfers')->where("touserid",$id)->sum("transferAmount");
            return view("onlineuser::onlineuser.userCoinReceiveHistory",compact("userCoinTransfers","totalCoinReceive"));
        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }

    #Online user withdraw history
    public function onlineUserWithdrawHistory($id){

        $userWithdrawHistories = DB::table('userwithdraws')
            ->leftJoin('users', 'users.id', '=', 'userwithdraws.user_id')
            ->leftJoin('admins', 'admins.id', '=', 'userwithdraws.withdrawAcceptedBy')
            ->where("userwithdraws.status",1)
            ->where("userwithdraws.user_id",$id)
            ->select('userwithdraws.*','users.username','admins.username as adminusername')
            ->get();
            $totalWithdraw = DB::table('userwithdraws')->where("status",1)->where("user_id",$id)->sum("withdrawAmount");

        return view("onlineuser::onlineuser.userWithdrawHistory",compact("userWithdrawHistories","totalWithdraw"));
    }
    
    
    #coin transfer permission
    public function standPermission(Request $request) {
        try {
            $user = User::where('id',$request->id)->first();
            $user->stand = $request->stand;
            $user->update();
            Toastr::success("Give permission","Success!");
            return redirect()->back();
        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }
    
        
    #Stand list
    public function standList(Request $request) {
        try {
            $onlineUsers = User::where('stand',1)->get();
            $countPermissionUser = $onlineUsers->count();
            return view("onlineuser::onlineuser.sulist",compact('onlineUsers','countPermissionUser'));
        }catch (Exception $e){
            Toastr::error("Something want wrong!","Error!");
            return redirect()->back();
        }
    }
    

}
