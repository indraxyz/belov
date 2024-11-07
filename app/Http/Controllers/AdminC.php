<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Akun;
use App\Models\Tiket;
use App\Models\Tiket_progres;

class AdminC extends Controller
{
    // LOGIN
    public function Login()
    {
        return view('admin.login');
    }

    public function LoginSubmit(Request $request)
    {
        // cek user
        $admin = Akun::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if ($admin != null) {
            // store session
            // $user = json_encode($user);
            // var_dump($user);
            error_log($admin);
            $request->session()->put('admin', $admin);
            return redirect('admin/home')->with('true', 'Login berhasil');
        } else {
            return redirect('admin/login')->with('false', 'Maaf, data tidak sesuai silahkan login kembali');
        }
    }

    // LOGOUT
    public function Logout()
    {
        // logout
        Session::forget(['admin']);
        return redirect('admin/login')->with('logout', 'Terima Kasih , Logout Berhasil');
    }

    // HOME
    public function Home()
    {
        // total masing2 status_tiket
        // SELECT COUNT(id) as jumlah, status_tiket FROM tiket GROUP BY status_tiket HAVING COUNT(id)

        $awal = Carbon::yesterday();
        $akhir  = Carbon::today()->addDay();
        $datas = Tiket::selectRaw('status_tiket, count(id) as jumlah_status')
            ->groupBy('status_tiket')
            ->whereBetween('created_at', [$awal, $akhir])
            ->get();
        $total = Tiket::whereBetween('created_at', [$awal, $akhir])->count();   //OK

        $akhir->subDay();
        return view('admin.home', ['awal' => $awal, 'akhir' => $akhir, 'datas' => $datas, 'total' => $total]);
    }
    public function HomeFilter(Request $request)
    {
        $awal = new Carbon($request->tanggalAwal);
        $akhir = new Carbon($request->tanggalAkhir);
        $akhir->addDay();

        $datas = Tiket::selectRaw('status_tiket, count(id) as jumlah_status')
            ->groupBy('status_tiket')
            ->whereBetween('created_at', [$awal, $akhir])
            ->get();
        $total = Tiket::whereBetween('created_at', [$awal, $akhir])->count();

        $akhir->subDay();
        return view('admin.home', ['awal' => $awal, 'akhir' => $akhir, 'datas' => $datas, 'total' => $total]);
    }

    // PROFIL
    public function Profil(Request $request)
    {
        // get profil
        $admin = $request->session()->get('admin');
        $data = Akun::find($admin->id);
        // error_log($data);

        return view('admin.profil', ['admin' => $data]);
    }
    public function ProfilUpdate(Request $request)
    {

        $data = Akun::find($request->id);
        $data->username = $request->username;
        $data->nama = $request->nama;
        $data->save();

        error_log($data);
        $request->session()->put('admin', $data);

        return redirect('admin/profil')->with('true', 'Profil berhasil diperbarui.');
    }
    public function ProfilUpdatePassword(Request $request)
    {
        $admin = $request->session()->get('admin');

        // cek old password
        if ($admin->password !== $request->oldPassword) {
            return redirect('admin/profil')->with('false', '-');
        }

        // update
        $data = Akun::find($request->id);
        $data->password = $request->password;
        $data->save();
        error_log($data);
        $request->session()->put('admin', $data);

        return redirect('admin/profil')->with('true', 'Password berhasil diperbarui.');
    }



    // -------

    // KELOLA TIKET
    public function Tiket()
    {
        return view('admin.tiket.index');
    }
    // API KELOLA TIKET
    public function GetTikets()
    {
        $tikets = Tiket::latest()->with('akun')->limit(10)->get();
        error_log($tikets);
        return response()->json($tikets);
    }
    public function MoreTikets($skip = 0)
    {
        $tikets = Tiket::latest()->with('akun')->offset($skip)->limit(10)->get();
        return response()->json($tikets);
    }
    public function FilterTikets(Request $request, $skip = 0)
    {
        // fungsi ini juga untuk button more saat filter aktif, parameter opsional = $skip
        error_log($skip);
        error_log($request->key);
        error_log($request->status_peserta);
        error_log($request->status_tiket);

        $key = $request->key;
        $status_peserta = $request->status_peserta;
        $status_tiket = $request->status_tiket;

        $tikets = Tiket::where(function ($q) use ($key) {
            $q->where('no_tiket', $key)
                ->orWhere('perusahaan', 'like', '%' . $key . '%')
                ->orWhere('nama_lengkap', 'like', '%' . $key . '%')
                ->orWhere('no_kartu_peserta', $key)
                ->orWhere('nik', $key);
        })
            ->where(function ($q) use ($status_peserta, $status_tiket) {
                $q->where('status_peserta', 'like', $status_peserta . '%')
                    ->where('status_tiket', 'like', $status_tiket . '%');
            })
            ->latest()->with('akun')->offset($skip)->limit(10)->get();
        // SELECT * FROM `tiket` WHERE (tiket.perusahaan like 'kode%' OR tiket.no_tiket like '42%') AND (status_peserta like '1%' AND status_tiket like '0%')

        return response()->json($tikets);
    }
    public function FilesTiket($id)
    {
        $files = Tiket::find($id, ['file_kartu_bpjs', 'file_ktp', 'file_foto', 'file_formulir']);
        return response()->json($files);
    }
    public function DetailTiket($id)
    {
        $tiket = Tiket::with('akun')->find($id);
        return response()->json($tiket);
    }
    public function VerifikasiTiket(Request $request)
    {

        // update tiket
        $tiket = Tiket::find($request->id);
        $tiket->status_tiket = $request->status_tiket;
        $tiket->id_admin = session('admin')->id;
        $tiket->save();

        // add new di tabel tiket_progres
        $progres = new Tiket_progres([
            'id_tiket' => $request->id,
            'progres' => $request->status_tiket,
            'catatan' => $request->catatan,
        ]);
        $progres->save();


        // get tiket
        $tiket = Tiket::where('id', $request->id)
            ->with('akun')
            ->first();

        return response()->json(['tiket' => $tiket]);
    }
    public function RiwayatTiket($id = null)
    {
        $riwayat = Tiket_progres::where('id_tiket', $id)->get();
        return response()->json($riwayat);
    }
    public function HapusTiket($id = null)
    {
        $tiket = Tiket::find($id);

        // delete files
        if ($tiket->file_kartu_bpjs != null) {
            unlink(public_path('assets/file/' . $tiket->file_kartu_bpjs));
        }
        if ($tiket->file_ktp != null) {
            unlink(public_path('assets/file/' . $tiket->file_ktp));
        }
        if ($tiket->file_foto != null) {
            unlink(public_path('assets/file/' . $tiket->file_foto));
        }
        if ($tiket->file_formulir != null) {
            unlink(public_path('assets/file/' . $tiket->file_formulir));
        }

        return response()->json($tiket->delete());
    }
}
