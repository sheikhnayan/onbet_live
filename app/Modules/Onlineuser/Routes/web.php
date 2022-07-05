<?php


Route::group(['prefix' => 'admin/online/user', 'middleware' => ['auth:admin','logout_admins','super_admin']], function () {

    Route::get('/manage',"OnlineuserController@onlineUserManage")->name("online_user_manage")->middleware("auth");
    Route::get('/zero/balance/user',"OnlineuserController@zeroBalanceUser")->name("online_zero_balance_user")->middleware("auth");
    Route::get('/minus/user',"OnlineuserController@minusUser")->name("minus_user_manage")->middleware("auth");
    Route::get('/online/user/search',"OnlineuserController@onlineUserSearch")->name("online_user_search")->middleware("auth");
    Route::get('/search/online/indivisual/user',"OnlineuserController@searchIndivisualUser")->name("search_online_indivisual_user")->middleware("auth");
    Route::get('/online/user/set/new/password/{id}',"OnlineuserController@onlineUserPasswordChange")->name("online_user_password_change")->middleware("auth");
    Route::post('/update/online/user/password/{id}',"OnlineuserController@updateOnlineUserPassword")->name("update_online_user_password_from_admin")->middleware("auth");
    Route::post('/status/change',"OnlineuserController@onlineUserStatusChange")->name("online_user_status_change")->middleware("auth");
    
    Route::get('/online/user/bet/history{id}',"OnlineuserController@onlineUserBetHistory")->name("online_user_bet_history")->middleware("auth");
    Route::get('/online/user/deposit/history/{id}',"OnlineuserController@onlineUserDepositHistory")->name("online_user_deposit_history")->middleware("auth");
    Route::get('/online/user/coin/transfer/history/{id}',"OnlineuserController@onlineUserCoinTransferHistory")->name("online_user_coin_transfer_history")->middleware("auth");
    Route::get('/online/user/coin/receive/history/{id}',"OnlineuserController@onlineUserCoinReceiveHistory")->name("online_user_coin_receive_history")->middleware("auth");
    Route::get('/online/user/withdraw/history/{id}',"OnlineuserController@onlineUserWithdrawHistory")->name("online_user_withdraw_history")->middleware("auth");
    Route::post('/online/user/stand',"OnlineuserController@standPermission")->name("online_user_stand")->middleware("auth");
    Route::get('/online/userlist/stand/list',"OnlineuserController@standList")->name("online_userlist_standlist")->middleware("auth");
    Route::get('/shortcut/user/{username}',"OnlineuserController@shortcutUser")->name("shortcut_user")->middleware("auth");

});
