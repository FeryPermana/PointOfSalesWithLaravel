<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index');
    }
    public function show()
    {
        return Setting::first();
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        $setting->nama_perusahaan = $request->nama_perusahaan;
        $setting->telepon = $request->telepon;
        $setting->alamat = $request->alamat;
        $setting->diskon = $request->diskon;
        $setting->tipe_nota = $request->tipe_nota;



        $image_path_logo = $setting->path_logo;
        if($request->hasFile('path_logo'))
        {
            $file = $request->file('path_logo');
            $image = $request->path_logo;
            $image_name = 'logo-' . date('Y-m-dHis') . $file->getClientOriginalExtension();
            $image->move('img/',$image_name);

            $image_path_logo = '/img/' . $image_name;
        }
        $setting->path_logo = $image_path_logo;


        $image_path_member = $setting->path_kartu_member;
        if($request->hasFile('path_kartu_member'))
        {
            $file = $request->file('path_kartu_member');
            $image = $request->path_kartu_member;
            $image_name = 'logo-' . date('Y-m-dHis') . $file->getClientOriginalExtension();
            $image->move('img/',$image_name);

            $image_path_member = '/img/' . $image_name;
        }

        $setting->path_kartu_member = $image_path_member;

        $setting->save();

        return response()->json('Data berhasil disimpan', 200);
    }
}
