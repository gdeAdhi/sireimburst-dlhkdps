<?php

namespace App\Http\Controllers;

use App\Models\Perjalanan;
use App\Models\RutePerjalanan;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PerjalananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->can('Kelola Perjalanan')) {
                $data = Perjalanan::with('rute', 'kendaraan', 'user')->get();
            } else {
                $data = Perjalanan::with('rute', 'kendaraan', 'user')
                    ->where('id_user', auth()->user()->id)
                    ->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rute', function($row){
                    $ruteArray = $row->rute->pluck('nama')->toArray();
                    $full = implode(', ', $ruteArray);
                    $display = implode(', ', array_slice($ruteArray, 0, 3));
                    if (count($ruteArray) > 3) {
                        $display .= '...';
                    }
                    return '<span title="'.e($full).'">'.$display.'</span>';
                })
                
                ->addColumn('jarak', function($row){
                    return $row->jarak." km";
                })                
                ->addColumn('kalkulasi', function($row){
                    return $row->kalkulasi." km/l";
                })                
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-info btn-sm edit-btn" data-id="'.$row->id.'">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>';
                    $btn .= ' <button type="button" class="btn btn-secondary btn-sm maps-btn" data-id="'.$row->id.'">Maps</button>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $status = $row->status == 'active' ? 'Active' : 'Inactive';
                    return '<span class="badge badge-'.($row->status == 'active' ? 'success' : 'danger').'">'.$status.'</span>';
                })
                ->rawColumns(['action', 'status', 'rute'])
                ->make(true);
        }
        $data['kendaraan'] = Kendaraan::where('status', 'active')->get();
        $data['rute'] = RutePerjalanan::where('status', 'active')->get();
        $data['user'] = User::all();
        return view ('perjalanan.index', $data);
    }

    public function getByUser($id)
    {
        $perjalanan = Perjalanan::with('rute', 'user')
            ->where('id_user', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->user->name . ' - ' . $item->rute->pluck('nama')->implode(', '),
                ];
            });

        return response()->json($perjalanan);
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
            'id_rute' => 'Rute',
            'id_kendaraan' => 'Kendaraan',
            'id_user' => 'User',
        ];

        $rules = [
            'id_rute' => 'required|array|min:1',
            'id_rute.*' => 'exists:tb_ruteperjalanan,id',
            'id_kendaraan' => 'required|exists:tb_kendaraan,id',
            'jarak' => 'required|string',
            'kalkulasi' => 'required|string',
        ];

        if (auth()->user()->can('Kelola Perjalanan')) {
            $rules['id_user'] = 'required|exists:users,id';
        }

        $validate = Validator::make($request->all(),$rules,[], $attributes)->validate();

        $perjalanan = Perjalanan::create([
            // 'id_rute' => $validate['id_rute'],
            'id_kendaraan' => $validate['id_kendaraan'],
            'id_user' => $validated['id_user'] ?? auth()->id(),
            'jarak' => $validate['jarak'],
            'kalkulasi' => $validate['kalkulasi'],
            // 'status' => $validate['status'],
        ]);

        $perjalanan->rute()->attach($validate['id_rute']);

        return response()->json([
            'status' => "success",
            'message' => 'Perjalanan berhasil ditambahkan!',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perjalanan  $perjalanan
     * @return \Illuminate\Http\Response
     */
    public function show(Perjalanan $perjalanan)
    {
        $perjalanan->load('rute', 'kendaraan', 'user');
        return response()->json($perjalanan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perjalanan  $perjalanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Perjalanan $perjalanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perjalanan  $perjalanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perjalanan $perjalanan)
    {
        $attributes = [
            'id_rute' => 'Rute',
            'id_kendaraan' => 'Kendaraan',
            'id_user' => 'User',
        ];

        $rules = [
            'id_rute' => 'required|array|min:1',
            'id_rute.*' => 'exists:tb_ruteperjalanan,id',
            'id_kendaraan' => 'required|exists:tb_kendaraan,id',
            'jarak' => 'required|string',
            'kalkulasi' => 'required|string',
        ];

        if (auth()->user()->can('Kelola Perjalanan')) {
            $rules['id_user'] = 'required|exists:users,id';
        }

        $validate = Validator::make($request->all(), $rules, [], $attributes)->validate();

        $perjalanan->id_kendaraan = $validate['id_kendaraan'];
        $perjalanan->id_user = $validate['id_user'] ?? auth()->id();
        $perjalanan->jarak = $validate['jarak'];
        $perjalanan->kalkulasi = $validate['kalkulasi'];
        $perjalanan->rute()->sync($validate['id_rute']);
        $perjalanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Perjalanan berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perjalanan  $perjalanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perjalanan $perjalanan)
    {
        $perjalanan->rute()->detach();
        $perjalanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perjalanan berhasil dihapus!',
        ]);
    }
}
