<?php

namespace App\Http\Controllers;

use App\Models\TipeKendaraan;
use App\Models\MerkKendaraan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kendaraan::with('tipe', 'merk')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-info btn-sm edit-btn" data-id="'.$row->id.'">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $status = $row->status == 'active' ? 'Active' : 'Inactive';
                    return '<span class="badge badge-'.($row->status == 'active' ? 'success' : 'danger').'">'.$status.'</span>';
                })
                ->addColumn('konsumsi', function($row){
                    return $row->konsumsi . ' km/l';
                })
                ->rawColumns(['action', 'status', 'konsumsi'])
                ->make(true);
        }
        $data['tipe_kendaraan'] = TipeKendaraan::all();
        $data['merk_kendaraan'] = MerkKendaraan::all();
        return view ('kendaraan.index', $data);
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
        $attributes = [
            'id_tipe' => 'Tipe Kendaraan',
            'id_merk' => 'Merk Kendaraan',
            'nama' => 'Nama Kendaraan',
            'no_polisi' => 'No Polisi',
            'warna' => 'Warna',
            'bahan_bakar' => 'Bahan Bakar',
            'konsumsi' => 'Konsumsi',
            'status' => 'Status'
        ];

        $validate = Validator::make($request->all(),[
            'id_tipe' => 'required|max:255',
            'id_merk' => 'required|max:255',
            'nama' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'bahan_bakar' => 'required|string|max:255',
            'konsumsi' => 'required|integer|max:255',
            'status' => 'required|string|in:active,inactive',
        ],[], $attributes)->validate();

        $kendaraan = Kendaraan::create([
            'id_tipe' => $validate['id_tipe'],
            'id_merk' => $validate['id_merk'],
            'nama' => $validate['nama'],
            'no_polisi' => $validate['no_polisi'],
            'warna' => $validate['warna'],
            'bahan_bakar' => $validate['bahan_bakar'],
            'konsumsi' => $validate['konsumsi'],
            'status' => $validate['status'],
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Kendaraan berhasil ditambahkan!',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function show(Kendaraan $kendaraan)
    {
        return response()->json($kendaraan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kendaraan $kendaraan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $attributes = [
            'id_tipe' => 'Tipe Kendaraan',
            'id_merk' => 'Merk Kendaraan',
            'nama' => 'Nama Kendaraan',
            'no_polisi' => 'No Polisi',
            'warna' => 'Warna',
            'bahan_bakar' => 'Bahan Bakar',
            'konsumsi' => 'Konsumsi',
            'status' => 'Status'
        ];

        $validate = Validator::make($request->all(),[
            'id_tipe' => 'required|max:255',
            'id_merk' => 'required|max:255',
            'nama' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'bahan_bakar' => 'required|string|max:255',
            'konsumsi' => 'required|integer|max:255',
            'status' => 'required|string|in:active,inactive',
        ],[], $attributes)->validate();

        $kendaraan->id_tipe = $validate['id_tipe'];
        $kendaraan->id_merk = $validate['id_merk'];
        $kendaraan->nama = $validate['nama'];
        $kendaraan->no_polisi = $validate['no_polisi'];
        $kendaraan->warna = $validate['warna'];
        $kendaraan->bahan_bakar = $validate['bahan_bakar'];
        $kendaraan->konsumsi = $validate['konsumsi'];
        $kendaraan->status = $validate['status'];

        $kendaraan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil dihapus!',
        ]);
    }
}
