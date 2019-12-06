<?php

namespace App\Http\Controllers\Admin;

use App\Dlc;
use App\StaticMod;
use App\Transliterator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminStaticModsController extends Controller{

    public function index(Request $request){
        $mods = StaticMod::select(['*']);
        if($request->input('q')) $mods->orWhere('title_ru', 'like', '%'.$request->input('q').'%')
            ->orWhere('title_ru', 'like', '%'.$request->input('q').'%')
            ->orWhere('description_ru', 'like', '%'.$request->input('q').'%')
            ->orWhere('description_en', 'like', '%'.$request->input('q').'%');
        return view('admin.static_mods.index', [
            'mods' => $mods->orderBy('sort', 'desc')->paginate(15)
        ]);
    }

    public function add(Request $request){
        $mod = new StaticMod();

        if($request->method() === 'POST'){
            $this->validate($request, [
                'title_en' => 'required|string',
                'tested_ver' => 'required|string',
                'game' => 'required|string',
                'file' => 'required_without:external_link|file|mimes:scs,zip',
                'external_link' => 'required_without:file',
            ]);
            $file_name = Transliterator::run($request->input('title_en'));
            $last_mod = StaticMod::orderBy('sort', SORT_DESC)->first();
            $mod->fill([
                'game' => $request->input('game', 'ets2'),
                'title_en' => $request->input('title_en'),
                'title_ru' => $request->input('title_ru') ?? $request->input('title_en'),
                'description_en' => $request->input('description_en'),
                'description_ru' => $request->input('description_ru') ?? $request->input('description_en'),
                'tested_ver' => $request->input('tested_ver'),
                'external_link' => $request->input('external_link'),
                'dlc' => $request->input('dlc') ? implode(',', $request->input('dlc')) : null,
                'active' => $request->input('active') == 'on',
                'image' => $mod->saveImage(),
                'sort' => ($last_mod ? intval($last_mod->sort) : 0)+1
            ]);
            if(($request->hasFile('file') && $mod->saveFile($file_name) || !$request->hasFile('file')) && $mod->save())
                return redirect()->route('admin_static_mods')->with(['success' => 'Мод успішно додано!']);
        }

        return view('admin.static_mods.edit', [
            'mod' => $mod,
            'dlc' => Dlc::where(['active' => 1])->get()
        ]);
    }

    public function edit(Request $request, $id){
        if($id === null) return redirect()->back()->withErrors(['Виникла помилка']);
        if($request->method() === 'POST' && $id){
            $this->validate($request, [
                'title_en' => 'required|string',
                'tested_ver' => 'required|string',
                'game' => 'required|string',
            ]);
            $mod = StaticMod::findOrFail($id);
            $mod->fill([
                'game' => $request->input('game', 'ets2'),
                'title_en' => $request->input('title_en'),
                'title_ru' => $request->input('title_ru') ?? $request->input('title_en'),
                'description_en' => $request->input('description_en'),
                'description_ru' => $request->input('description_ru') ?? $request->input('description_en'),
                'tested_ver' => $request->input('tested_ver'),
                'external_link' => $request->input('external_link'),
                'dlc' => $request->input('dlc') ? implode(',', $request->input('dlc')) : null,
                'active' => $request->input('active') == 'on',
                'image' => $request->hasFile('img') ? $mod->saveImage() : $mod->image,
            ]);
            $file_name = Transliterator::run($request->input('title_en'));
            $file = $request->hasFile('file') ? $mod->saveFile($file_name) : true;
            return $file && $mod->save() ?
                redirect()->route('admin_static_mods')->with(['success' => 'Мод успішно відредаговано!']) :
                redirect()->back()->withErrors(['Виникла помилка']);
        }

        $mod = StaticMod::findOrFail($id);
        return view('admin.static_mods.edit', [
            'mod' => $mod,
            'dlc' => Dlc::where(['active' => 1, 'game' => $mod->game])->get()
        ]);
    }

    public function delete(Request $request, $id){
        $mod = StaticMod::findOrFail($id);
        return $mod->delete() ?
            redirect()->back()->with(['success' => 'Мод успішно видалено!']) :
            redirect()->back()->withErrors(['Не вдалось видалити мод']);
    }

    public function toggle(Request $request, $id){
        $mod = StaticMod::findOrFail($id);
        $mod->active = !$mod->active;
        return $mod->save() ?
            redirect()->back()->with(['success' => 'Виконано!']) :
            redirect()->route('accessories')->withErrors(['Виникла помилка!']);
    }

}
