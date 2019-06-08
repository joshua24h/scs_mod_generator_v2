<?php

namespace App\Http\Controllers;

use App\Chassis;
use Illuminate\Http\Request;

class GalleryController extends Controller{

    public function index(Request $request){
        $chassis = Chassis::with('dlc');
        if($request->input('q')) $chassis->orWhere('alias', 'like', '%'.$request->input('q').'%')
            ->orWhere('def', 'like', '%'.$request->input('q').'%');
        return view('gallery.index', [
            'chassis_list' => $chassis->where(['active' => 1])->orderBy('alias', 'asc')->get()->groupBy('game')->reverse()
        ]);
    }

    public function getInfo(Request $request){
        if($request->ajax() && $request->input('chassis')){
            $result = null;
            $chassis = Chassis::where('alias_short', $request->input('chassis'))->first();
            $data = array();
            if($chassis->with_paint_job){
                $data['paints'] = $chassis->getAvailablePaints();
            }
            if($chassis->with_accessory){
                $data['accessories'] = $chassis->getAvailableAccessories();
            }
            return response()->json([
                'result' => $data,
                'status' => 'OK'
            ]);
        }
        return false;
    }

}