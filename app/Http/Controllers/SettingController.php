<?php

namespace App\Http\Controllers;

// use App\Http\Requests\KontrakRequest;

use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
   


    public function edit()
    {
        $data_awal = DB::table('config_app')->where('id', 1)->first();
        $data = [
            'data_setting' => $data_awal
        ];

        return view('master.setting.edit')->with($data);
    }

    public function update(Request $request)
    {   

        // $request = array_map('htmlentities', $request->validated());
        // kondisi 1
        if($request['logo_pemda_warna']=='' && $request['logo_pemda_hp']==''){
            DB::table('config_app')->where('id', 1)->update([
                'nm_pemda'      => $request['nm_pemda'],
                'nm_badan'      => $request['nm_badan'],
                'thn_ang'       => $request['thn_ang'],
                'updated_at'    => date('Y-m-d  h:m:s')
            ]);
        // kondisi 2
        }else if($request['logo_pemda_warna']!='' && $request['logo_pemda_hp']==''){
            $file = $request->file('logo_pemda_warna');
            $new_logo       = 'logo_pemda'.'.'.$file->getClientOriginalExtension();
            $image = public_path('/template/assets/images/'.$new_logo);
            if(file_exists($image)) {
                unset($image);
           }
            $path = public_path('template/assets/images/');

            
            $file->move($path, $new_logo);

            DB::table('config_app')->where('id', 1)->update([
                'nm_pemda'          => $request['nm_pemda'],
                'nm_badan'          => $request['nm_badan'],
                'thn_ang'           => $request['thn_ang'],
                'logo_pemda_warna'  => $new_logo,
                'updated_at'        => date('Y-m-d  h:m:s')
            ]);
        // kondisi 3
        }else if($request['logo_pemda_warna']=='' && $request['logo_pemda_hp']!=''){
            $file = $request->file('logo_pemda_hp');
            $new_logo_hp        = 'logo_pemda_hp'.'.'.$file->getClientOriginalExtension();
            $image = public_path('/template/assets/images/'.$new_logo_hp);
            if(file_exists($image)) {
                unset($image);
           }
            $path = public_path('template/assets/images/');
            $file->move($path, $new_logo_hp);

            DB::table('config_app')->where('id', 1)->update([
                'nm_pemda'      => $request['nm_pemda'],
                'nm_badan'      => $request['nm_badan'],
                'thn_ang'       => $request['thn_ang'],
                'logo_pemda_hp' => $new_logo_hp,
                'updated_at'    => date('Y-m-d  h:m:s')
            ]);
        // kondisi 4
        }else{
            $file2 = $request->file('logo_pemda_hp');
            $file = $request->file('logo_pemda_warna');
            $new_logo       = 'logo_pemda'.'.'.$file->getClientOriginalExtension();
            $new_logo_hp    = 'logo_pemda_hp'.'.'.$file2->getClientOriginalExtension();
            
            $image = public_path('/template/assets/images/'.$new_logo);
            unset($image);
            $image2 = public_path('/template/assets/images/'.$new_logo_hp);
            unlink($image2);

            $path = public_path('template/assets/images/');
            $file->move($path, $new_logo);
            
            $path2 = 'template/assets/images';
            $file2->move($path2, $new_logo_hp);



            DB::table('config_app')->where('id', 1)->update([
                'nm_pemda'          => $request['nm_pemda'],
                'nm_badan'          => $request['nm_badan'],
                'logo_pemda_warna'  => $new_logo,
                'logo_pemda_hp'     => $new_logo_hp,
                'updated_at'        => date('Y-m-d  h:m:s')
            ]);
        }
        

        return redirect()->route('setting.edit');
    }
}
