<?php

namespace App\Http\Controllers\Tendik;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Tendik;
use Illuminate\Support\Facades\Auth;

class TendikDashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:tendik');
    }

    public function index(){
        $about = Tendik::leftJoin('jabatans','jabatans.id','tendiks.jabatan_id')->where('nip',Auth::guard('tendik')->user()->nip)->first();
        $jabatans = Jabatan::get();
        $harga = Tendik::leftJoin('jabatans','jabatans.id','tendiks.jabatan_id')->select('remunerasi')->where('nip',Auth::guard('tendik')->user()->nip)->first();
        $absensi = Tendik::leftJoin('jabatans','jabatans.id','tendiks.jabatan_id')->select('remunerasi')->where('nip',Auth::guard('tendik')->user()->nip)->first();
        return view('tendik/dashboard', compact('about','jabatans'));
    }

    public function ubahPassword(Request $request){
        if ($request->password != $request->ulangi_password) {
            return redirect()->route('tendik.dashboard')->with(['error'  =>  'Password yang anda masukan tidak sama']);
        }
        else{
            Tendik::where('id',Auth::guard('tendik')->user()->id)->update([
                'password'  =>  bcrypt($request->password), 
            ]);

            return redirect()->route('tendik.dashboard')->with(['success'  =>  'Password berhasil diubah']);
        }
    }
    
    public function ubahData(Request $request){
        $this->validate($request,[
            'nm_lengkap' =>  'required',
            'nip'  =>  "required",
            'pangkat'  =>  "required",
            'golongan'  =>  "required",
            'jabatan'  =>  "required",
            'jenis_kepegawaian'  =>  "required",
            'jenis_kelamin'  =>  "required",
            'no_rekening'  =>  "required",
            'no_npwp'  =>  "required",
        ]);
        $jabatan = Jabatan::select('nm_jabatan','remunerasi')->where('id',$request->jabatan)->firstOrFail();
        Tendik::where('id',Auth::guard('tendik')->user()->id)->update([
            'nm_lengkap'    =>  $request->nm_lengkap,
            'nip'   =>  $request->nip,
            'pangkat'   =>  $request->pangkat,
            'golongan'  =>  $request->golongan,
            'jabatan_id'   =>  $request->jabatan,
            'jenis_kepegawaian' =>  $request->jenis_kepegawaian,
            'jenis_kelamin' =>  $request->jenis_kelamin,
            'no_rekening'   =>  $request->no_rekening,
            'no_npwp'   =>  $request->no_npwp,
        ]);

        return redirect()->route('tendik.dashboard')->with(['success'  =>  'Data anda berhasil diubah']);
    }
}
