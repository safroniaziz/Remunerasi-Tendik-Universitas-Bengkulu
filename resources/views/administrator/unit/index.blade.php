@extends('layouts.layout')
@section('title', 'Manajemen unit')
@section('login_as', 'administrator')
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
    @include('administrator/sidebar')
@endsection
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Remunerasi Tenaga Kependidikan Universitas Bengkulu
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            <strong>Berhasil :</strong>{{ $message }}
                        </div>
                        @elseif ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>Gagal :</strong>{{ $message }}
                            </div>
                            @else
                            <div class="alert alert-success alert-block" id="keterangan">
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut semua unit yang tersedia, data berikut didapatkan dari Sistem Informasi administrator (SIMPEG), silahkan tambahkan manual jika diperlukan !!
                            </div>
                    @endif
                </div>
                <div class="col-md-12" id="form-unit">
                    <hr style="width:50%;">
                    <form action="{{ route('administrator.unit.post') }}" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nama unit :</label>
                                <input type="text" name="nm_unit" value="{{ old('nm_unit') }}" class="form-control @error('nm_unit') is-invalid @enderror" placeholder=" masukan nama unit">
                                <div>
                                    @if ($errors->has('nm_unit'))
                                        <small class="form-text text-danger">{{ $errors->first('nm_unit') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tingkatan :</label>
                                <select name="tingkatan" class="form-control  @error('tingkatan') is-invalid @enderror">
                                    <option value="" selected disabled>-- pilih Tingkatan --</option>
                                    <option {{ old('tingkatan') == "universitas" ? 'selected' : '' }} value="universitas">universitas</option>
                                    <option {{ old('tingkatan') == "lembaga" ? 'selected' : '' }} value="lembaga">lembaga</option>
                                    <option {{ old('tingkatan') == "fakultas" ? 'selected' : '' }} value="fakultas">fakultas</option>

                                </select>                                
                                @error('tingkatan')
                                    <small class="form-text text-danger">{{ $errors->first('tingkatan') }}</small>
                                @enderror
                            </div>
                         
                        <div class="col-md-12" style="text-align:center;">
                            <a onclick="batalkan()" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-close"></i>&nbsp; Batalkan</a>
                            <button type="reset" class="btn btn-warning btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan unit</button>
                        </div>
                    </form>
                    <hr style="width:50%;">
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama unit</th>
                                <th>Tingkatan unit</th>
                       
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($units as $unit)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                 
                                    <td> {{ $unit->nm_unit }} </td>
                                    <td> {{ $unit->tingkatan }} </td>
              
                                    <td>
                                        <a onclick="editUnit({{ $unit->id }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusunit({{ $unit->id }})" class="btn btn-danger btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                     <!-- Modal Ubah -->
                     <div class="modal fade" id="modalubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action=" {{ route('administrator.unit.update') }} " method="POST">
                                    {{ csrf_field() }} {{ method_field('PATCH') }}
                                    <div class="modal-header">
                                        <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-suitcase"></i>&nbsp;Form Ubah Data unit</p>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="id" id="id_ubah">
                                                <div class="form-group">
                                                    <label for="">Nama unit :</label>
                                                    <input type="text" name="nm_unit" id="nm_unit" class="form-control @error('nm_unit') is-invalid @enderror" required placeholder="masukan nama unit">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tingkatan :</label>
                                                    <select name="tingkatan" class="form-control  @error('tingkatan') is-invalid @enderror">
                                                        <option value="" selected disabled>-- pilih Tingkatan --</option>
                                                        <option {{ old('tingkatan') == "universitas" ? 'selected' : '' }} value="universitas">universitas</option>
                                                        <option {{ old('tingkatan') == "lembaga" ? 'selected' : '' }} value="lembaga">lembaga</option>
                                                        <option {{ old('tingkatan') == "fakultas" ? 'selected' : '' }} value="fakultas">fakultas</option>
                    
                                                    </select>                                
                                                    @error('tingkatan')
                                                        <small class="form-text text-danger">{{ $errors->first('tingkatan') }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Hapus-->
                <div class="modal fade modal-danger" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action=" {{ route('administrator.unit.delete') }} " method="POST">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <div class="modal-header">
                                    <p style="font-size:15px; font-weight:bold;" class="modal-title"><i class="fa fa-trash"></i>&nbsp;Form Konfirmasi Hapus Data</p>
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
                                    <button type="button" style="border: 1px solid #fff;background: transparent;color: #fff;" class="btn btn-sm btn-outline pull-left" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                    <button type="submit" style="border: 1px solid #fff;background: transparent;color: #fff;" class="btn btn-sm btn-outline"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
        
        function editUnit(id){
            $.ajax({
                url: "{{ url('administrator/unit') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalubah').modal('show');
                    $('#id_ubah').val(id);
                    $('#tingkatan').val(data.tingkatan);
                    $('#nm_unit').val(data.nm_unit);
                 
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusunit(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

    </script>
@endpush
