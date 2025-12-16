<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    private $api = "http://localhost:8000/api/buku";

    public function index()
    {
        $client = new Client();

        $response = $client->get($this->api);
        $content = json_decode($response->getBody()->getContents(), true);

        return view('buku.index', [
            'mode'      => 'create',
            'data'      => $content['data'] ?? [],
            'editData'  => null
        ]);
    }

    // ============================
    // STORE : Tambah data
    // ============================
    public function store(Request $request)
    {
        $client = new Client();

        try {
            $response = $client->post($this->api, [
                'headers' => ['Content-Type' => 'application/json'],
                'body'    => json_encode([
                    'judul' => $request->judul,
                    'pengarang' => $request->pengarang,
                    'tanggal_publikasi' => $request->tanggal_publikasi,
                ])
            ]);

            $content = json_decode($response->getBody()->getContents(), true);

            if (($content['status'] ?? false) !== true) {
                return back()->withErrors($content['data'])->withInput();
            }

            return redirect('buku')->with('success', 'Berhasil menambah data');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $resp = json_decode($e->getResponse()->getBody()->getContents(), true);
            return back()->withErrors($resp['data'] ?? 'Kesalahan API')->withInput();
        }
    }

    // ============================
    // EDIT : Mode edit di index
    // ============================
    public function edit($id)
    {
        $client = new Client();

        // Ambil semua data untuk tabel
        $all = json_decode($client->get($this->api)->getBody()->getContents(), true)['data'];

        // Ambil 1 data untuk form edit
        $single = json_decode($client->get($this->api . '/' . $id)->getBody()->getContents(), true);

        if (($single['status'] ?? false) !== true) {
            return redirect('buku')->withErrors('Data tidak ditemukan');
        }

        return view('buku.index', [
            'mode'      => 'edit',
            'data'      => $all,
            'editData'  => $single['data']
        ]);
    }

    // ============================
    // UPDATE : Proses edit data
    // ============================
    public function update(Request $request, $id)
    {
        $client = new Client();

        try {
            $response = $client->put($this->api . '/' . $id, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'judul' => $request->judul,
                    'pengarang' => $request->pengarang,
                    'tanggal_publikasi' => $request->tanggal_publikasi,
                ])
            ]);

            $content = json_decode($response->getBody()->getContents(), true);

            if (($content['status'] ?? false) !== true) {
                return back()->withErrors($content['data'])->withInput();
            }

            return redirect('buku')->with('success', 'Berhasil mengupdate data');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $resp = json_decode($e->getResponse()->getBody()->getContents(), true);
            return back()->withErrors($resp['data'] ?? 'Kesalahan API')->withInput();
        }
    }

    // ============================
    // DELETE : Hapus data
    // ============================
    public function destroy($id)
    {
        $client = new Client();

        try {
            $response = $client->delete($this->api . '/' . $id);
            return redirect('buku')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            return redirect('buku')->withErrors('Gagal menghapus data');
        }
    }
}
