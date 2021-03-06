<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tendik;
use App\Models\Jabatan;
use Illuminate\Support\Str;

class AdministratorTendikController extends Controller
{
    
    public function index(){
        $jabatans = Jabatan::select('id','nm_jabatan')->get();
        $tendiks = Tendik::leftJoin('jabatans','jabatans.id','tendiks.jabatan_id')
                        ->select('tendiks.id','nm_lengkap','nip','pangkat','golongan','jabatan_id','user_id_absensi','jabatans.nm_jabatan','jenis_kelamin','no_rekening','no_npwp')
                        ->orderBy('tendiks.id','desc')
                        ->get();
        return view('administrator/tendik.index',compact('tendiks','jabatans'));
    }

    public function post(Request $request){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
            'jabatan_id'   =>  'Nama Jabatan',
            'nm_lengkap'    =>  'Nama Lengkap',
            'nip'   =>  'Nip',
            'pangkat'   =>  'Pangkat',
            'golongan'  =>  'Golongan',
            'jenis_administrator' =>  'Jenis administrator',
            'jenis_kelamin' =>  'Jenis Kelamin',
            'kedekatan_hukum'   =>  'Kedekatan Hukum',
            'no_rekening'   =>  'No Rekening',
            'no_npwp'   =>  'No NPWP',
            'user_id_absensi'   =>  'User Id Absensi',
        ];
        $this->validate($request, [
            'jabatan_id'    =>  'required',
            'nm_lengkap'    =>  'required',
            'nip'   =>  'required|numeric',
            'pangkat'   =>  'required',
            'golongan'  =>  'required',
            'jenis_administrator' =>  'required',
            'jenis_kelamin' =>  'required',
            'kedekatan_hukum'   =>  'required',
            'no_rekening'   =>  'required|numeric',
            'no_npwp'   =>  'required|numeric',
            'user_id_absensi'   =>  'required|numeric',
        ], $messages, $attributes);
        Tendik::create([
            'jabatan_id' => $request->jabatan_id,
            'nm_lengkap' => $request->nm_lengkap,
            'slug' => Str::slug($request->nm_lengkap),
            'nip' => $request->nip,
            'pangkat' => $request->pangkat,
            'golongan' => $request->golongan,
            'jenis_administrator' => $request->jenis_administrator,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kedekatan_hukum' => $request->kedekatan_hukum,
            'no_rekening' => $request->no_rekening,
            'no_npwp' => $request->no_npwp,
            'user_id_absensi' => $request->user_id_absensi,
        ]);

        $notification = array(
            'message' => 'Berhasil, data tendik berhasil ditambakan!',
            'alert-type' => 'success'
        );

        return redirect()->route('administrator.tendik')->with($notification);
    }

    public function edit($id){
        $tendik = Tendik::find($id);
        return $tendik;
    }

    public function update(Request $request){
        Tendik::where('id',$request->id_ubah)->update([
            'jabatan_id'    =>  $request->jabatan_id,
            'nm_lengkap'    =>  $request->nm_lengkap,
            'slug' => Str::slug($request->nm_lengkap),
            'nip'   =>  $request->nip,
            'pangkat'   =>  $request->pangkat,
            'golongan'  =>  $request->golongan,
            'jenis_administrator' =>  $request->jenis_administrator,
            'jenis_kelamin' =>  $request->jenis_kelamin,
            'kedekatan_hukum'   =>  $request->kedekatan_hukum,
            'no_rekening'   =>  $request->no_rekening,
            'no_npwp'   =>  $request->no_npwp,
            'user_id_absensi' => $request->user_id_absensi,
        ]);

        $notification = array(
            'message' => 'Berhasil, data tendik berhasil diupdate!',
            'alert-type' => 'success'
        );

        return redirect()->route('administrator.tendik')->with($notification);
    }

    public function delete(Request $request){
        $tendik = Tendik::find($request->id);
        $tendik->delete();

        return redirect()->route('administrator.tendik')->with(['success' =>  'Data tendik berhasil dihapus !']);
    }

    public function generatePassword(Request $request){
        Tendik::where('status','aktif')->update([
            'password'  =>  bcrypt($request->password),
        ]);

        return redirect()->route('administrator.tendik')->with(['success' =>  'Password berhasil di generate !']);
    }

    public function ubahPassword(Request $request){
        Tendik::where('id',$request->id)->update([
            'password'  =>  bcrypt($request->password_ubah),
        ]);

        return redirect()->route('administrator.tendik')->with(['success' =>  'Password berhasil di generate !']);
    }
   
}
