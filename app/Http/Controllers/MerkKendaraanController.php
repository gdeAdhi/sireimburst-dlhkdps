<?php

namespace App\Http\Controllers;

use App\Models\MerkKendaraan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use DataTables;

class MerkKendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MerkKendaraan::all();
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
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view ('merk_kendaraan.index');
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
            'nama' => 'Merk Kendaraan',
            'status' => 'Status',
        ];

        $validate = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ], [], $attributes)->validate();

        $merkKendaraan = MerkKendaraan::create([
            'nama' => $validate['nama'],
            'status' => $validate['status'],
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Merk kendaraan berhasil ditambahkan!',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MerkKendaraan  $merkKendaraan
     * @return \Illuminate\Http\Response
     */
    public function show(MerkKendaraan $kendaraan)
    {
        return response()->json([
            'id' => $kendaraan->id,
            'nama' => $kendaraan->nama,
            'status' => $kendaraan->status,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MerkKendaraan  $merkKendaraan
     * @return \Illuminate\Http\Response
     */
    public function edit(MerkKendaraan $merkKendaraan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MerkKendaraan  $merkKendaraan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MerkKendaraan $kendaraan)
    {
        $attributes = [
            'nama' => 'Merk Kendaraan',
            'status' => 'Status',
        ];
        
        $validate = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ], [], $attributes)->validate();

        $kendaraan->nama = $validate['nama'];
        $kendaraan->status = $validate['status'];

        $kendaraan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Merk kendaraan berhasil diperbarui!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MerkKendaraan  $merkKendaraan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerkKendaraan $kendaraan)
    {
        $kendaraan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Merk kendaraan berhasil dihapus!',
        ]);
    }
}
