<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\RAbsen;
use App\Models\RCapaianSkp;
use App\Models\RIntegritas;
use App\Models\Tendik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class RekapitulasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','isKepegawaian']);
    }

    public function index($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','nm_periode','slug')->first();
        // $datas = Remunerasi::select('nm_lengkap','nip','pangkat','golongan','kelas_jabatan','nm_jabatan',
        //                             'remunerasi_per_bulan','jumlah_bulan','no_rekening')
        //                     ->where('periode_id',$periode_aktif->id)        
        //                     ->get();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        return view('kepegawaian/rekapitulasi.index',compact('periode_id','periode_aktif','table'));
    }

    public function generateTable($periode_id){
        // return $periode_id;
        $periode = Periode::select('slug')->where('id',$periode_id)->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode->slug);
        if (Schema::hasTable($table)) {
            $notification = array(
                'message' => 'Gagal, tanel rekapitulasi sudah tersedia!',
                'alert-type' => 'error'
            );
            return redirect()->route('kepegawaian.rekapitulasi',[$periode_id])->with($notification);
        } else {
            $query = "create table ".$table."(
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT KEY,
                tendik_id bigint(20) UNSIGNED NOT NULL,
                periode_id bigint(20) UNSIGNED NOT NULL,
                nm_lengkap varchar(255)  DEFAULT NULL,
                nip varchar(255)  DEFAULT NULL,
                pangkat varchar(255)  DEFAULT NULL,
                golongan varchar(255)  DEFAULT NULL,
                kelas_jabatan varchar(255)  DEFAULT NULL,
                nm_jabatan varchar(255)  DEFAULT NULL,
                remunerasi_per_bulan varchar(255)  DEFAULT NULL,
                remunerasi_30 varchar(255)  DEFAULT NULL,
                remunerasi_70 varchar(255)  DEFAULT NULL,
                jumlah_bulan varchar(255)  DEFAULT NULL,
                jumlah_remun_30 varchar(255)  DEFAULT NULL,
                jumlah_remun_70 varchar(255)  DEFAULT NULL,
                total_remun varchar(255)  DEFAULT NULL,
                potongan_pph varchar(255)  DEFAULT NULL,
                laporan_lhkpn_lhkasn varchar(255)  DEFAULT NULL,
                sanksi_disiplin varchar(255)  DEFAULT NULL,
                nominal_lhkpn_lhkasn varchar(255)  DEFAULT NULL,
                nominal_sanksi_disiplin varchar(255)  DEFAULT NULL,
                potongan_integritas_satu_bulan varchar(255)  DEFAULT NULL,
                nilai_skp varchar(255)  DEFAULT NULL,
                potongan_skp varchar(255)  DEFAULT NULL,
                nominal_potongan varchar(255)  DEFAULT NULL,
                persen_absen_bulan_satu varchar(255)  DEFAULT NULL,
                persen_absen_bulan_dua varchar(255)  DEFAULT NULL,
                persen_absen_bulan_tiga varchar(255)  DEFAULT NULL,
                persen_absen_bulan_empat varchar(255)  DEFAULT NULL,
                persen_absen_bulan_lima varchar(255)  DEFAULT NULL,
                persen_absen_bulan_enam varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_satu varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_dua varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_tiga varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_empat varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_lima varchar(255)  DEFAULT NULL,
                nominal_absen_bulan_enam varchar(255)  DEFAULT NULL,
                total_absensi varchar(255)  DEFAULT NULL,
                total_integritas varchar(255)  DEFAULT NULL,
                total_skp varchar(255)  DEFAULT NULL,
                total_akhir_remun varchar(255)  DEFAULT NULL,
                no_rekening varchar(255)  DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
            )";
            $create = DB::statement($query);
            $notification = array(
                'message' => 'Berhasil, tabel rekapitulasi berhasil digenerate!',
                'alert-type' => 'success'
            );
            return redirect()->route('kepegawaian.rekapitulasi',[$periode_id])->with($notification);
        }
        
    }

    public function dataTendik($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','nm_periode','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = DB::table($table)->select('nm_lengkap','nip','pangkat','golongan','kelas_jabatan','nm_jabatan',
                                    'remunerasi_per_bulan','jumlah_bulan','no_rekening')
                            ->where('periode_id',$periode_aktif->id)        
                            ->get();
        return view('kepegawaian/rekapitulasi.data_tendik',compact('periode_id','periode_aktif','table','datas'));
    }

    public function generateDataTendik($periode_id){
        $datas = Tendik::leftJoin('jabatans','jabatans.id','tendiks.jabatan_id')
                        ->select('tendiks.id','nm_lengkap','nip','pangkat','golongan','no_rekening','kelas_jabatan','jabatans.nm_jabatan','remunerasi')
                        ->get();
        $jumlah_bulan = Periode::where('id',$periode_id)->select('jumlah_bulan')->first();
        $array = [];
        for ($i=0; $i <count($datas) ; $i++) { 
            $array[]    =   [
                'periode_id'    =>  $periode_id,
                'tendik_id'    =>  $datas[$i]->id,
                'nm_lengkap'    =>  $datas[$i]->nm_lengkap,
                'nip'    =>  $datas[$i]->nip,
                'pangkat'    =>  $datas[$i]->pangkat,
                'golongan'    =>  $datas[$i]->golongan,
                'kelas_jabatan'    =>  $datas[$i]->kelas_jabatan,
                'nm_jabatan'    =>  $datas[$i]->nm_jabatan,
                'remunerasi_per_bulan'    =>  $datas[$i]->remunerasi,
                'no_rekening'    =>  $datas[$i]->no_rekening,
                'jumlah_bulan'    =>  $jumlah_bulan->jumlah_bulan,
            ];
        }
        $periode_aktif = Periode::where('id',$periode_id)->select('id','nm_periode','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        DB::table($table)->insert($array);
        $notification = array(
            'message' => 'Berhasil, data tenaga kependidikan berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.data_tendik',[$periode_id])->with($notification);
    }

    public function totalRemun($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','nm_periode','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = DB::table($table)->select('nm_lengkap','remunerasi_per_bulan','remunerasi_30','remunerasi_70','jumlah_bulan',
                                                        'jumlah_remun_30','jumlah_remun_70','total_remun')
                            ->where('periode_id',$periode_id)
                            ->get();
        $cek = DB::table($table)->select('remunerasi_30')->first();
        if ($cek->remunerasi_30 != null) {
            $a = "sudah";
        }
        else{
            $a = "belum";
        }
        return view('kepegawaian/rekapitulasi.total_remun', compact('datas','periode_aktif','periode_id','a'));
    }

    public function generateTotalRemun($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','nm_periode','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = Tendik::select('id')->get();
        // $array = [];
        for ($i=0; $i <count($datas) ; $i++) {
            $data = RIntegritas::where('tendik_id',$datas[$i]->id)->where('periode_id',$periode_id)->first();
            DB::table($table)->where('tendik_id',$datas[$i]->id)->update([
                // $array[] = [
                'remunerasi_30' =>  $data->remun_30,
                'remunerasi_70' =>  $data->remun_70,
                'jumlah_remun_30' =>  $data->total_remun_30,
                'jumlah_remun_70' =>  $data->total_remun_70,
                'total_remun' =>  $data->total_remun,
            ]);
            
        }
        $notification = array(
            'message' => 'Berhasil, data total remunerasi berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.total_remun',[$periode_id])->with($notification);
    }

    public function integritas($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = DB::table($table)->select('nm_lengkap','potongan_pph','laporan_lhkpn_lhkasn','sanksi_disiplin',
                                    'nominal_lhkpn_lhkasn','nominal_sanksi_disiplin','potongan_integritas_satu_bulan','total_integritas')
                            ->where('periode_id',$periode_id)
                            ->get();
        $cek = DB::table($table)->select('potongan_pph')->first();
        
        if ($cek->potongan_pph != null) {
            $a = "sudah";
        }
        else{
            $a = "belum";
        }
        return view('kepegawaian/rekapitulasi.integritas', compact('datas','periode_aktif','periode_id','a'));
    }

    public function generateIntegritas($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = Tendik::select('id')->get();
        $array = [];
        for ($i=0; $i <count($datas) ; $i++) {
            $data = RIntegritas::where('tendik_id',$datas[$i]->id)->where('periode_id',$periode_id)->first();
            DB::table($table)->where('tendik_id',$datas[$i]->id)->update([
                'potongan_pph' =>  $data->pajak_pph,
                'laporan_lhkpn_lhkasn' =>  $data->laporan_lhkpn_lhkasn,
                'sanksi_disiplin' =>  $data->sanksi_disiplin,
                'nominal_lhkpn_lhkasn' =>  $data->potongan_lhkpn_lhkasn,
                'nominal_sanksi_disiplin' =>  $data->potongan_sanksi_disiplin,
                'potongan_integritas_satu_bulan' =>  $data->integritas_satu_bulan,
                'total_integritas' =>  $data->total_integritas,
            ]);
            
        }
        $notification = array(
            'message' => 'Berhasil, rubrik integritas berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.integritas',[$periode_id])->with($notification);
    }

    public function skp($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas =  DB::table($table)->select('nm_lengkap','nilai_skp','potongan_skp',
                                    'nominal_potongan')
                            ->where('periode_id',$periode_id)
                            ->get();
        $cek = DB::table($table)->select('nilai_skp')->first();
        if ($cek->nilai_skp != null) {
            $a = "sudah";
        }
        else{
            $a = "belum";
        }
        return view('kepegawaian/rekapitulasi.skp', compact('datas','periode_id','periode_aktif','a'));
    }

    public function generateSkp($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = Tendik::select('id')->get();
        $jumlah_bulan = Periode::where('id',$periode_id)->select('jumlah_bulan')->first();
        // $array = [];
        for ($i=0; $i <count($datas) ; $i++) {
            $data = RCapaianSkp::where('tendik_id',$datas[$i]->id)->where('periode_id',$periode_id)->first();
            DB::table($table)->where('tendik_id',$datas[$i]->id)->update([
                'nilai_skp' =>  $data->nilai_skp,
                'potongan_skp' =>  $data->potongan_skp,
                'nominal_potongan' =>  $data->nominal_potongan,
                'total_skp' =>  $data->nominal_potongan * $jumlah_bulan->jumlah_bulan,
            ]);
        }
        $notification = array(
            'message' => 'Berhasil, rubrik skp berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.skp',[$periode_id])->with($notification);
    }

    public function persentaseAbsen($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug','jumlah_bulan')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas =  DB::table($table)->select('id','nm_lengkap','persen_absen_bulan_satu','persen_absen_bulan_dua','persen_absen_bulan_tiga',
                                    'nominal_absen_bulan_satu','nominal_absen_bulan_dua','nominal_absen_bulan_tiga','total_absensi')
                            ->where('periode_id',$periode_id)
                            ->get();
        $cek = DB::table($table)->select('persen_absen_bulan_satu')->first();
        if ($cek->persen_absen_bulan_satu != null) {
            $a = "sudah";
        }
        else{
            $a = "belum";
        }
        return view('kepegawaian/rekapitulasi.persentase_absen',[$periode_aktif], compact('datas','periode_aktif','periode_id','a'));
    }

    public function generateAbsensi($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = Tendik::select('id')->get();

        for ($i=0; $i <count($datas) ; $i++) {
            $data = RAbsen::where('tendik_id',$datas[$i]->id)->where('periode_id',$periode_id)->first();
            DB::table($table)->where('tendik_id',$datas[$i]->id)->update([
                'persen_absen_bulan_satu' =>  $data->potongan_bulan_1 == null ? "0" : $data->potongan_bulan_1,
                'persen_absen_bulan_dua' =>  $data->potongan_bulan_2 == null ? "0" : $data->potongan_bulan_2,
                'persen_absen_bulan_tiga' =>  $data->potongan_bulan_3 == null ? "0" : $data->potongan_bulan_3,
                'persen_absen_bulan_empat' =>  $data->potongan_bulan_4 == null ? "0" : $data->potongan_bulan_4,
                'persen_absen_bulan_lima' =>  $data->potongan_bulan_5 == null ? "0" : $data->potongan_bulan_5,
                'persen_absen_bulan_enam' =>  $data->potongan_bulan_6 == null ? "0" : $data->potongan_bulan_6,
                'nominal_absen_bulan_satu' =>  $data->nominal_bulan_1 == null ? "0" : $data->nominal_bulan_1,
                'nominal_absen_bulan_dua' =>  $data->nominal_bulan_2 == null ? "0" : $data->nominal_bulan_2,
                'nominal_absen_bulan_tiga' =>  $data->nominal_bulan_3 == null ? "0" : $data->nominal_bulan_3,
                'nominal_absen_bulan_empat' =>  $data->nominal_bulan_4 == null ? "0" : $data->nominal_bulan_4,
                'nominal_absen_bulan_lima' =>  $data->nominal_bulan_5 == null ? "0" : $data->nominal_bulan_5,
                'nominal_absen_bulan_enam' =>  $data->nominal_bulan_6 == null ? "0" : $data->nominal_bulan_6,
                'total_absensi' =>  $data->nominal_bulan_1+$data->nominal_bulan2+$data->nominal_bulan_3+$data->nominal_bulan_4+$data->nominal_bulan_5,
            ]);
        }
        $notification = array(
            'message' => 'Berhasil, rubrik absensi berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.persentase_absen',[$periode_id])->with($notification);
    }

    public function totalAkhir($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug','jumlah_bulan')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas =  DB::table($table)->where('periode_id',$periode_id)
                            ->get();
        $cek = DB::table($table)->select('total_akhir_remun')->first();
        if ($cek->total_akhir_remun != null) {
            $a = "sudah";
        }
        else{
            $a = "belum";
        }
        return view('kepegawaian/rekapitulasi.total_akhir',[$periode_aktif], compact('datas','periode_aktif','periode_id','a'));
    }

    public function generateTotalAkhir($periode_id){
        $periode_aktif = Periode::where('id',$periode_id)->select('id','slug')->first();
        $table = "rekapitulasi_".str_replace('-', '_', $periode_aktif->slug);
        $datas = Tendik::select('id')->get();

        for ($i=0; $i <count($datas) ; $i++) {
            $data = DB::table($table)->select('id','nm_lengkap','total_remun','potongan_pph','total_absensi','total_integritas','total_skp')
                            ->where('periode_id',$periode_id)
                            ->where('tendik_id',$datas[$i]->id)
                            ->first();
            DB::table($table)->where('tendik_id',$datas[$i]->id)->update([
                'total_akhir_remun' =>  $data->total_remun - $data->potongan_pph - $data->total_absensi - $data->total_integritas - $data->total_skp ,
            ]);
        }
        $notification = array(
            'message' => 'Berhasil, total akhir berhasil digenerate!',
            'alert-type' => 'success'
        );
        return redirect()->route('kepegawaian.rekapitulasi.total_akhir_remun',[$periode_id])->with($notification);
    }
}