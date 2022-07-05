<?php

namespace App\Modules\Match\Http\Controllers;

use App\Events\betdetailUpdateEvent;
use App\Models\Match\Score;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ScoreController extends Controller
{
    #Match Live Score View
    public function matchesLiveScoreView($score_id) {
        $score = Score::with('match')->where("id",$score_id)->first();
        return view('match::matches.livescore',compact('score'));
    }

    #Match Live Score Update
    public function matchesLiveScoreUpdate(Request $request) {
        $score = Score::where("id",$request ->scoreId)->first();
        try {
            if(!empty($request->overs)){
                $score->overs = trim(strip_tags($request->overs));
            }else{
                $score->overs = null;
            }
            $score->score = trim(strip_tags($request->score));
            $score->update();

            $message = 1;
            event(new betdetailUpdateEvent($message));
            
            return response()->json([
                'status'    => 201,
                'msg'      => 'Score update succesfully'
            ]);

        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'error'  => $e
            ]);
        }
    }
}
