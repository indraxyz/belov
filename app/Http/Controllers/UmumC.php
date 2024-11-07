<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tiket;
use App\Models\Tiket_progres;

class UmumC extends Controller
{
    public function Home()
    {
        return view('home');
    }


    // submit form
    public function SubmitPeserta(Request $req)
    {

        // CEK REQUIRED
        if ($req->status_peserta==0 || $req->pemohon==0 || $req->perusahaan==''|| $req->nik==''|| $req->nama_lengkap==''|| $req->no_kartu_peserta==''|| $req->tgl_lahir==''|| $req->nohp==''|| $req->data_perbaikan==''|| $req->email==''|| $req->file('file_ktp')==null || $req->file('file_kartu_bpjs')==null || $req->file('file_formulir')==null ) {
            return response()->json('wajib diisi',500);
        }

        // build no_tiket , strlen
        $no_tiket = Tiket::count()+1;
        $nol_tiket = 6-strlen($no_tiket);
        for ($x = 1; $x<=$nol_tiket; $x++) {
            $no_tiket = '0'.$no_tiket;
        }
        $no_tiket = 'B'.$no_tiket;

        // build data perbaikan
        $data_perbaikan = array();
        foreach ($req->data_perbaikan as $perbaikan) {
            array_push($data_perbaikan,$perbaikan);
        }

        // build file (cek type, set name, upload)
        // nama perusahaan_nama peserta_tgl pengajuan_no tiket
        $nama_file = $req->perusahaan.'_'.$req->nama_lengkap.'_'.Carbon::now()->format('d-m-Y').'_'.$no_tiket;
        $file_ktp = $this->filePengajuan($req->file('file_ktp'),$nama_file.'_ktp','assets/file');
        $file_kartu_bpjs = $this->filePengajuan($req->file('file_kartu_bpjs'),$nama_file.'_kartubpjs','assets/file');;
        $file_foto = $this->filePengajuan($req->file('file_foto'),$nama_file.'_foto','assets/file');;
        $file_formulir = $this->filePengajuan($req->file('file_formulir'),$nama_file.'_formulir','assets/file');;

        $tiket = new Tiket;
        $tiket->no_tiket = $no_tiket;
        $tiket->status_peserta = $req->status_peserta;
        $tiket->pemohon = $req->pemohon;
        $tiket->perusahaan = $req->perusahaan;
        $tiket->pic_hrd = $req->pic_hrd;
        $tiket->nik = $req->nik;
        $tiket->nama_lengkap = $req->nama_lengkap;
        $tiket->no_kartu_peserta = $req->no_kartu_peserta;
        $tiket->tgl_lahir = $req->tgl_lahir;
        $tiket->nohp = $req->nohp;
        $tiket->data_perbaikan = implode("-",$data_perbaikan);;
        $tiket->email = $req->email;
        $tiket->status_tiket = 0;
        $tiket->file_ktp = $file_ktp;
        $tiket->file_kartu_bpjs = $file_kartu_bpjs;
        $tiket->file_foto = $file_foto;
        $tiket->file_formulir = $file_formulir;
        $tiket->save();

        $progres = new Tiket_progres;
        $progres->progres = 0;
        $progres->catatan = '-';
        $progres->id_tiket = $tiket->id;
        $progres->save();

        return response()->json($tiket);
    }

    // handle file pengajuan
    public function filePengajuan( $file = null, $nama='', $direktori='')
    {
        if ($file != null) {
            // cek tipe 
            error_log($file->getMimeType()); 

            // cek size, max 2mb
            error_log($file->getSize()); // satuan kb, dibag 1000

            // cek extention
            error_log($file->getClientOriginalExtension());

            // set nama & direktori
            $nama = $nama.'.'.$file->getClientOriginalExtension(); //nama perushaan_nama tenaga kerja_tgl pengajuan_no tiket
            // upload
            $file->move(public_path($direktori),$nama);
        } else {
            $nama = null;
        }

        // return nama file
        return $nama;
    }


    // lacak
    public function Lacak(Request $req)
    {
        
        $tiket = null;
        $progres = null;

        // cek no_tiket
        if (isset($req->no_tiket)) {
            $tiket = Tiket::where('no_tiket', $req->no_tiket)->with('akun')->first();
            error_log('tiket');
            error_log($tiket);

            // progres tiket
            if (isset($tiket)) {
                $progres = Tiket_progres::where('id_tiket',$tiket->id)->get();
                error_log($progres);
            }
        }

        return view('lacak', ['tiket' => $tiket, 'progres'=> $progres]);
    }


}
