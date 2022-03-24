@extends('layouts.layout')
@section('title', 'Manajemen Data Tendik')
@section('login_as', 'Kepegawaian')
@section('user-login')
    @if (Auth::check())
    {{ Auth::user()->nama_lengkap }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::check())
    {{ Auth::user()->nama_lengkap }}
    @endif
@endsection
@section('sidebar-menu')
    @include('kepegawaian/sidebar')
@endsection
@push('styles')
    <style>
        .tendik {
            color: #007bff !important;
            background: white;
        }

        .tendik:hover,
        .tendik:focus,
        .tendik:active {
            background-color: white !important;
            color: #007bff !important;
        }
    </style>
@endpush
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Remunerasi Tenaga Kependidikan Universitas Bengkulu
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message   = Session::get('success'))
                        <div class="alert alert-success alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut semua data tendik yang tersedia, silahkan tambahkan manual jika diperlukan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:10px;">
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm " onclick="tambahTendik()">
                          <i class="fa fa-plus"></i>&nbsp;Tambah Data Tendik
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" id="generate-password" onclick="generatePassword()" class="btn btn-danger btn-sm" >
                          <i class="fa fa-refresh"></i>&nbsp;Generate Password
                        </button>
                    </div>
                </div>
                <div class="col-md-12 form-tambah" id="form-tambah" style="display:none;">
                    <hr style="width:50%;">
                    <form action="{{ route('kepegawaian.tendik.post') }}" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Nama Lengkap :</label>
                                <input type="text" name="nm_lengkap" value="{{ old('nm_lengkap') }}" class="form-control @error('nm_lengkap') is-invalid @enderror" placeholder=" masukan nama lengkap">
                                @error('nm_lengkap')
                                    <small class="form-text text-danger">{{ $errors->first('nm_lengkap') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nip :</label>
                                <input type="number" name="nip" value="{{ old('nip') }}" class="form-control  @error('nip') is-invalid @enderror" placeholder=" masukan nip">
                                @error('nip')
                                    <small class="form-text text-danger">{{ $errors->first('nip') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Unit Kerja :</label>
                                <select name="unit_id" class="form-control  @error('unit_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- pilih nama jabatan --</option>
                                    @foreach ($units as $unit)
                                        <option {{ $unit->id == old('unit_id') ? 'selected' : '' }} value="{{ $unit->id }}">{{ $unit->nm_unit }} </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <small class="form-text text-danger">{{ $errors->first('unit_id') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pangkat :</label>
                                <input type="text" name="pangkat" value="{{ old('pangkat') }}" class="form-control  @error('pangkat') is-invalid @enderror" placeholder=" masukan pangkat">
                                @error('pangkat')
                                    <small class="form-text text-danger">{{ $errors->first('pangkat') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Golongan :</label>
                                <input type="text" name="golongan" value="{{ old('golongan') }}" class="form-control  @error('golongan') is-invalid @enderror" placeholder=" masukan golongan">
                                @error('golongan')
                                    <small class="form-text text-danger">{{ $errors->first('golongan') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nama Jabatan :</label>
                                <select name="jabatan_id" class="form-control  @error('jabatan_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- pilih nama jabatan --</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option {{ $jabatan->id == old('jabatan_id') ? 'selected' : '' }} value="{{ $jabatan->id }}">(kelas jabatan : {{ $jabatan->kelas_jabatan }}) - {{ $jabatan->nm_jabatan }} </option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')
                                    <small class="form-text text-danger">{{ $errors->first('jabatan_id') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Jenis Kepegawaian :</label>
                                <input type="text" name="jenis_kepegawaian" value="{{ old('jenis_kepegawaian') }}" class="form-control  @error('jenis_kepegawaian') is-invalid @enderror" placeholder=" masukan jenis kepegawaian">
                                @error('jenis_kepegawaian')
                                    <small class="form-text text-danger">{{ $errors->first('jenis_kepegawaian') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Jenis Kelamin :</label>
                                <select name="jenis_kelamin" class="form-control  @error('jenis_kelamin') is-invalid @enderror">
                                    <option value="" selected disabled>-- pilih jenis kelamin --</option>
                                    <option {{ old('jenis_kelamin') == "L" ? 'selected' : '' }} value="L">Laki-Laki</option>
                                    <option {{ old('jenis_kelamin') == "P" ? 'selected' : '' }} value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <small class="form-text text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Kedekatan Hukum :</label>
                                <input type="text" name="kedekatan_hukum" class="form-control  @error('kedekatan_hukum') is-invalid @enderror" value="PNS">
                                @error('kedekatan_hukum')
                                    <small class="form-text text-danger">{{ $errors->first('kedekatan_hukum') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nomor Rekening :</label>
                                <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" class="form-control  @error('no_rekening') is-invalid @enderror" placeholder="masukan nomor rekening">
                                @error('no_rekening')
                                    <small class="form-text text-danger">{{ $errors->first('no_rekening') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nomor NPWP :</label>
                                <input type="text" name="no_npwp" value="{{ old('no_npwp') }}" class="form-control  @error('no_npwp') is-invalid @enderror" placeholder="masukan nomor npwp">
                                @error('no_npwp')
                                    <small class="form-text text-danger">{{ $errors->first('no_npwp') }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>User ID Aplikasi Absensi :</label>
                                <input type="text" name="user_id_absensi" value="{{ old('user_id_absensi') }}" class="form-control  @error('user_id_absensi') is-invalid @enderror" placeholder="user id dari aplikasi absensi   ">
                                @error('user_id_absensi')
                                    <small class="form-text text-danger">{{ $errors->first('user_id_absensi') }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12" style="text-align:center;">
                            <a onclick="batalkan()" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-close"></i>&nbsp; Batalkan</a>
                            <button type="reset" class="btn btn-warning btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan Data Tendik</button>
                        </div>
                    </form>
                    <hr style="width:50%;">
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Nip</th>
                                <th>Pangkat</th>
                                <th>Golongan</th>
                                <th>Jabatan</th>
                                <th>Jenis Kelamin</th>
                                <th>No. Rekening</th>
                                <th>No. NPWP</th>
                                <th>User ID Absensi</th>
                                <th>Ubah Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($tendiks as $tendik)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $tendik->nm_lengkap }} </td>
                                    <td> {{ $tendik->nip }} </td>
                                    <td>
                                        @if ($tendik->pangkat == null)
                                            <label class="badge badge-danger"><i class="fa fa-minus"></i></label>
                                            @else
                                            {{ $tendik->pangkat }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tendik->golongan == null)
                                            <label class="badge badge-danger"><i class="fa fa-minus"></i></label>
                                            @else
                                            {{ $tendik->golongan }}
                                        @endif
                                    </td>
                                    <td> {{ $tendik->nm_jabatan }} </td>
                                    <td>
                                        @if ($tendik->jenis_kelamin == "L")
                                            <label class="badge badge-primary"><i class="fa fa-male"></i>&nbsp; Laki-Laki</label>
                                            @else
                                            <label class="badge badge-success"><i class="fa fa-female"></i>&nbsp; Perempuan</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tendik->no_rekening == null)
                                            <label class="badge badge-danger"><i class="fa fa-minus"></i></label>
                                            @else
                                            {{ $tendik->no_rekening }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tendik->no_npwp == null)
                                            <label class="badge badge-danger"><i class="fa fa-minus"></i></label>
                                            @else
                                            {{ $tendik->no_npwp }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $tendik->user_id_absensi }}
                                    </td>
                                    <td>
                                        <a onclick="ubahPassword({{ $tendik->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-key"></i></a>
                                    </td>
                                    <td>
                                        <a onclick="ubahTendik({{ $tendik->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusTendik({{ $tendik->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- modal ubah-->
                    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <p class="modal-title" style="font-size:17px;"><i class="fa fa-edit"></i>&nbsp;Form Ubah Data Tendik</p>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="{{ route('kepegawaian.tendik.update') }}" method="POST">
                                <div class="modal-body">
                                        {{ csrf_field() }} {{ method_field('PATCH') }}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    <strong>Perhatian :</strong> Silahkan ubah data Tenaga Kependidikan (Tendik) Jika terdapat kesalahan data !!
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_ubah" id="id_ubah">
                                            <div class="form-group col-md-12">
                                                <label>Nama Lengkap :</label>
                                                <input type="text" name="nm_lengkap" id="nm_lengkap" required class="form-control" placeholder=" masukan nama lengkap">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Nip :</label>
                                                <input type="number" name="nip" id="nip" required class="form-control" placeholder=" masukan nip">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Pangkat :</label>
                                                <input type="text" name="pangkat" id="pangkat" required class="form-control" placeholder=" masukan pangkat">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Golongan :</label>
                                                <input type="text" name="golongan" id="golongan" required class="form-control" placeholder=" masukan golongan">
                                            </div>
                                        <div class="form-group col-md-12">
                                            <label>Nama Jabatan : </label>
                                            <select name="jabatan_id" id="jabatan_id" required class="form-control">
                                                <option value="" selected disabled>-- pilih nama jabatan --</option>
                                                @foreach ($jabatans as $jabatan)
                                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nm_jabatan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Jenis Kepegawaian :</label>
                                            <input type="text" name="jenis_kepegawaian" id="jenis_kepegawaian" required class="form-control" placeholder=" masukan jenis kepegawaian">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Jenis Kelamin :</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" required class="form-control">
                                                <option value="" selected disabled>-- pilih jenis kelamin --</option>
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Kedekatan Hukum :</label>
                                            <input type="text" name="kedekatan_hukum" id="kedekatan_hukum" required class="form-control" value="PNS">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Nomor Rekening :</label>
                                            <input type="number" name="no_rekening" id="no_rekening" required class="form-control" placeholder="masukan nomor rekening">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Nomor NPWP :</label>
                                            <input type="text" name="no_npwp" id="no_npwp" required class="form-control" placeholder="masukan nomor npwp">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>User ID Aplikasi Absensi :</label>
                                            <input type="text" name="user_id_absensi" id="user_id_absensi" required value="{{ old('user_id_absensi') }}" class="form-control  @error('user_id_absensi') is-invalid @enderror" placeholder="user id dari aplikasi absensi   ">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Simpan Perubahan</button>
                                </div>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Hapus-->
                <div class="modal fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action=" {{ route('kepegawaian.tendik.delete') }} " method="POST">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <div class="modal-header">
                                    <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-key"></i>&nbsp;Form Konfirmasi Hapus Data</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="id" id="id_hapus">
                                            Apakah anda yakin ingin menghapus data? klik hapus jika iya !!
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalgeneratepassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action=" {{ route('kepegawaian.tendik.generate_password') }} " method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="modal-header">
                                <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-key"></i>&nbsp;Form Generate Password Tendik</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <i class="fa fa-info-circle"></i>&nbsp;Form generate password untuk seluruh tendik aktif
                                        </div>
                                        <div class="form-group">
                                            <label for="">Masukan Password</label>
                                            <input type="password" name="password" id="password" class="form-control password">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Konfirmasi Password</label>
                                            <input type="password" name="password2" id="password2" class="form-control password2">
                                        </div>
                                        <div>
                                            <a class="password_sama" style="color: green; font-size:12px; font-style:italic; display:none;"><i class="fa fa-check-circle"></i>&nbsp;Password Sama!!</a>
                                            <a class="password_tidak_sama" style="color: red; font-size:12px; font-style:italic; display:none;"><i class="fa fa-close"></i>&nbsp;Password Tidak Sama!!</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                <button type="submit" class="btn btn-primary btn-sm" id="btn-submit" disabled><i class="fa fa-check-circle"></i>&nbsp; Generate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalubahpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action=" {{ route('kepegawaian.tendik.ubah_password') }} " method="POST">
                        {{ csrf_field() }} {{ method_field('PATCH') }}
                        <div class="modal-header">
                            <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-key"></i>&nbsp;Form Ubah Password AKun Tendik</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <i class="fa fa-info-circle"></i>&nbsp;Form ubah password akun tendik
                                    </div>
                                    <input type="hidden" name="id" id="id">
                                    <div class="form-group">
                                        <label for="">Masukan Password</label>
                                        <input type="password" name="password_ubah" id="password_ubah" class="form-control password">
                                    </div>

                                    <div class="form-group">
                                        <label for="">Konfirmasi Password</label>
                                        <input type="password" name="password_ubah2" id="password_ubah2" class="form-control password2">
                                    </div>
                                    <div>
                                        <a class="password_ubah_sama" style="color: green; font-size:12px; font-style:italic; display:none;"><i class="fa fa-check-circle"></i>&nbsp;Password Sama!!</a>
                                        <a class="password_ubah_tidak_sama" style="color: red; font-size:12px; font-style:italic; display:none;"><i class="fa fa-close"></i>&nbsp;Password Tidak Sama!!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="btn-submit-ubah" disabled><i class="fa fa-check-circle"></i>&nbsp; Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

        function tambahTendik(){
            $('#form-tambah').show(300);
            $('#alert-generate').hide(300);
            $('#sedang-generate').hide(300);
            $('#generate-jabatan').hide(300);
            $('#generate-password').hide(300);
        }

        function ubahPassword(id){
            $('#modalubahpassword').modal('show');
            $('#id_password').val(id);
            $('#id').val(id);
        }

        function batalkan(){
            $('#form-tambah').hide(300);
            $('#generate').show(300);
        }

        function generatePassword(){
            $('#modalgeneratepassword').modal('show');
        }

        $(document).ready(function(){
            $("#password, #password2").keyup(function(){
                var password = $("#password").val();
                var ulangi = $("#password2").val();
                if($("#password").val() == $("#password2").val()){
                    $('.password_sama').show(200);
                    $('.password_tidak_sama').hide(200);
                    $('#btn-submit').attr("disabled",false);
                }
                else{
                    $('.password_sama').hide(200);
                    $('.password_tidak_sama').show(200);
                    $('#btn-submit').attr("disabled",true);
                }
            });
        });

        $(document).ready(function(){
            $("#password_ubah, #password_ubah2").keyup(function(){
                var password_ubah = $("#password_ubah").val();
                var ulangi = $("#password_ubah2").val();
                if($("#password_ubah").val() == $("#password_ubah2").val()){
                    $('.password_ubah_sama').show(200);
                    $('.password_ubah_tidak_sama').hide(200);
                    $('#btn-submit-ubah').attr("disabled",false);
                }
                else{
                    $('.password_ubah_sama').hide(200);
                    $('.password_ubah_tidak_sama').show(200);
                    $('#btn-submit-ubah').attr("disabled",true);
                }
            });
        });

        function ubahTendik(id){
            $.ajax({
                url: "{{ url('kepegawaian/manajemen_data_tendik') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#form-tambah').hide(300);
                    $('#modalubah').modal('show');
                    $('#id_ubah').val(id);
                    $('#nm_lengkap').val(data.nm_lengkap)
                    $('#nip').val(data.nip)
                    $('#pangkat').val(data.pangkat)
                    $('#golongan').val(data.golongan)
                    $('#jabatan_id').val(data.jabatan_id)
                    $('#jenis_kepegawaian').val(data.jenis_kepegawaian)
                    $('#jenis_kelamin').val(data.jenis_kelamin)
                    $('#kedekatan_hukum').val(data.kedekatan_hukum)
                    $('#no_rekening').val(data.no_rekening)
                    $('#no_npwp').val(data.no_npwp)
                    $('#user_id_absensi').val(data.user_id_absensi)
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusTendik(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

        @if (count($errors) > 0)
            $('#form-tambah').show(300);
        @endif
    </script>
@endpush
