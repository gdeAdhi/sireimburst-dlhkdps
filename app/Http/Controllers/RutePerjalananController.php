<?php

namespace App\Http\Controllers;

use App\Models\RutePerjalanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DataTables;

class RutePerjalananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RutePerjalanan::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-info btn-sm edit-btn" data-id="'.$row->id.'">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>';
                    $btn .= ' <button type="button" class="btn btn-secondary btn-sm maps-btn" data-lat="'.$row->latitude.'" data-nama="'.$row->nama.'" data-lng="'.$row->longitude.'">Maps</button>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $status = $row->status == 'active' ? 'Active' : 'Inactive';
                    return '<span class="badge badge-'.($row->status == 'active' ? 'success' : 'danger').'">'.$status.'</span>';
                })
                ->addColumn('alamat', function($row){
                    $alamat = $row->alamat ?? '-';
                    $short = strlen($alamat) > 50 ? substr($alamat, 0, 50) . '...' : $alamat;
                    return '<span title="'.e($alamat).'">'.$short.'</span>';
                })

                ->rawColumns(['action', 'status', 'alamat'])
                ->make(true);
        }

        $routes = RutePerjalanan::all();

        foreach ($routes as $route) {
            $originalName = $route->nama;

            // Skip if already starts with IN - or OUT -
            if (Str::startsWith($originalName, 'IN - ') || Str::startsWith($originalName, 'OUT - ')) {
                continue;
            }

            $inName = "IN - {$originalName}";
            $outName = "OUT - {$originalName}";

            // Insert OUT - record if it doesn't exist
            if (!RutePerjalanan::where('nama', $outName)->exists()) {
                $outCopy = $route->replicate();
                $outCopy->nama = $outName;
                $outCopy->save();
            }

            // Update current record to IN - ...
            $route->nama = $inName;
            $route->save();
        }
        return view ('rute_perjalanan.index');
    }

    /**
     * Show the data for the maps.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMaps(Request $request)
    {
        if ($request->ajax()) {
            $data = RutePerjalanan::select('nama as label', 'latitude as lat', 'longitude as lng')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            return response()->json($data);
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
        $attributes = [
            'nama' => 'Rute Perjalanan',
            'alamat' => 'Alamat',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'status' => 'Status',
        ];

        $validate = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
                'regex:/^-?\d{1,2}(\.\d{1,6})?$/'
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
                'regex:/^-?\d{1,3}(\.\d{1,6})?$/'
            ],
            'status' => 'required|string|in:active,inactive',
        ],[], $attributes)->validate();

        $baseName = $validate['nama'];

        $inRoute = RutePerjalanan::create([
            'nama' => "IN - {$baseName}",
            'alamat' => $validate['alamat'],
            'longitude' => $validate['longitude'],
            'latitude' => $validate['latitude'],
            'status' => $validate['status'],
        ]);

        $outRoute = RutePerjalanan::create([
            'nama' => "OUT - {$baseName}",
            'alamat' => $validate['alamat'],
            'longitude' => $validate['longitude'],
            'latitude' => $validate['latitude'],
            'status' => $validate['status'],
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Rute Perjalanan IN dan OUT berhasil ditambahkan!',
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RutePerjalanan  $rutePerjalanan
     * @return \Illuminate\Http\Response
     */
    public function show(RutePerjalanan $perjalanan)
    {
        return response()->json($perjalanan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RutePerjalanan  $rutePerjalanan
     * @return \Illuminate\Http\Response
     */
    public function edit(RutePerjalanan $rutePerjalanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RutePerjalanan  $rutePerjalanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RutePerjalanan $perjalanan)
    {
        $attributes = [
            'nama' => 'Rute Perjalanan',
            'alamat' => 'Alamat',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'status' => 'Status',
        ];

        $validate = Validator::make($request->all(),[
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
                'regex:/^-?\d{1,2}(\.\d{1,6})?$/'
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
                'regex:/^-?\d{1,3}(\.\d{1,6})?$/'
            ],
            'status' => 'required|string|in:active,inactive',
        ],[], $attributes)->validate();

        $perjalanan->nama = $validate['nama'];
        $perjalanan->alamat = $validate['alamat'];
        $perjalanan->longitude = $validate['longitude'];
        $perjalanan->latitude = $validate['latitude'];
        $perjalanan->status = $validate['status'];

        $perjalanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Rute Perjalanan berhasil diperbarui!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RutePerjalanan  $rutePerjalanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(RutePerjalanan $perjalanan)
    {
        $perjalanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Rute Perjalanan berhasil dihapus!',
        ]);
    }
}
