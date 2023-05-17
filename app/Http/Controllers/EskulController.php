<?php

namespace App\Http\Controllers;

use App\Models\Eskul;
use Illuminate\Http\Request;
use App\helpers\ApiFormatter;
use Exception;

class EskulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search_eskul;
        $limit = $request->limit;
        $eskuls = Eskul::where('eskul', 'LIKE', '%' . $search . '%')->limit($limit)->get();

        if ($eskuls)
        {
            return ApiFormatter::createAPI(200, 'success', $eskuls);
        } else {
            return ApiFormatter::createAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // validasi data
            $request->validate([
                'eskul' => 'required|min:5',
                'jumlah' => 'required|numeric',
                'hari' => 'required',
                'tanggal' => 'required',
            ]);
            // ngirim data baru ke table students lewat model Student
            $eskul = Eskul::create([
                'eskul' => $request->eskul,
                'jumlah' => $request->jumlah,
                'hari' => $request->hari,
                'tanggal' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d'),
            ]);
            // cari data baru yang berhasil di simpen, cari berdasarkan id lewat data id dari $student yg di atas
            $hasilTambahData = Eskul::where('id', $eskul->id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createAPI(200, 'success', $eskul);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            // munculin deskripsi error yg bakal tampil di property data jsonnya
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function createToken()
    {
        return csrf_token();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eskul  $eskul
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $eskulDetail = Eskul::find($id);

            if ($eskulDetail) {
                return ApiFormatter::createAPI(200, 'success', $eskulDetail);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eskul  $eskul
     * @return \Illuminate\Http\Response
     */
    public function edit(Eskul $eskul)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eskul  $eskul
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'eskul' => 'required|min:5',
                'jumlah' => 'required|numeric',
                'hari' => 'required',
                'tanggal' => 'required',
            ]);

            $eskul = Eskul::find($id);

            $eskul->update([
                'eskul' => $request->eskul,
                'jumlah' => $request->jumlah,
                'hari' => $request->hari,
                'tanggal' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d'),
            ]);

            $update = Eskul::where('id', $eskul->id)->first();

            if ($update) {
                return ApiFormatter::createAPI(200, 'success', $update);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eskul  $eskul
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $eskul = Eskul::findOrFail($id);
            $proses = $eskul->delete();

            if ($proses) {
                return ApiFormatter::createAPI(200, 'success delete data!');
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function trash()
    {
        try {
            $eskul = Eskul::onlyTrashed()->get();
            if ($eskul) {
                return ApiFormatter::createAPI(200, 'success', $eskul);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $eskul = Eskul::onlyTrashed()->where('id', $id);
            $eskul->restore();
            $restore = Eskul::where('id', $id)->first();
            if ($restore) {
                return ApiFormatter::createAPI(200, 'success', $restore);
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanent($id)
    {
        try {
            $eskul = Eskul::onlyTrashed()->where('id', $id);
            $proses = $eskul->forceDelete();
            if ($proses) {
                return ApiFormatter::createAPI(200, 'success', 'Data Berhasil Dihapus!');
            } else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}
