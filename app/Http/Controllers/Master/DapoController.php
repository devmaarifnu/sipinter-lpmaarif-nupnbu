<?php

namespace App\Http\Controllers\Master;

use App\Exceptions\CatchErrorException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DapoController extends Controller
{
    public function index(Request $request) {
        $dapo = null;
        if ($request->has('npsn')) {
            $response = Http::withToken(config('services.dapo.token'))->get(config('services.dapo.url').'/'.$request->npsn);
            $dapo = $response->json();
        }
        return view('admin.master.dapo-lpmaarif', compact('dapo'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'npsn' => 'required',
                'nama_sekolah' => 'required',
                'bentuk_pendidikan' => 'required',
                'nama_yayasan' => 'required',
                'propinsi' => 'required',
                'kabupaten' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'alamat' => 'required',
            ]);

            $response = Http::withToken(config('services.dapo.token'))->asForm()->post(config('services.dapo.url'), [
                'npsn' => $request->npsn,
                'nama' => $request->nama_sekolah,
                'nama_yayasan' => $request->nama_yayasan,
                'npyp' => '-',
                'alamat_jalan' => $request->alamat,
                'kecamatan' => $request->kelurahan,
                'kabupaten' => '-',
                'provinsi' => '-',
                'prov' => 'Prov. '.$request->provinsi,
                'kab' => 'Kab. '.$request->kabupaten,
                'kec' => 'Kec. '.$request->kecamatan,
                'bentuk_pendidikan' => $request->bentuk_pendidikan,
                'brand' => '-',
                'q' => '-',
                'akreditasi' => '-',
                'lintang' => '-',
                'bujur' => '-',
            ]);

            $data = $response->json();
            if ($response->successful()) {
                return redirect()->route('dapo.index', ['npsn' => $request->npsn])->with('success', $data['message']);
            } else {
                return back()->with('error', $data['message']);
            }

        } catch (\Exception $e) {
            throw new CatchErrorException("[DAPO STORE] has error ". $e);

        }
    }

    public function destroy($npsn) {
        try {
            $response = Http::withToken(config('services.dapo.token'))->delete(config('services.dapo.url').'/'.$npsn);
            $response = $response->json();
            return redirect()->route('dapo.index')->with('success', $response['message']);

        } catch (\Exception $e) {
            throw new CatchErrorException("[DAPO DESTROY DESTROY] has error ". $e);

        }
    }
}
