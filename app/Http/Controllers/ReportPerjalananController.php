<?php

namespace App\Http\Controllers;

use App\Models\ReportPerjalanan;
use App\Models\User;
use App\Models\Perjalanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;

class ReportPerjalananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->can('Kelola Report Perjalanan')) {
                $data = ReportPerjalanan::with('user', 'perjalanan')->get();
            } else {
                $data = ReportPerjalanan::with('user', 'perjalanan')
                    ->where('id_user', auth()->user()->id)
                    ->get();
            };
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('perjalanan', function($row){
                    $ruteArray = $row->perjalanan->rute->pluck('nama')->toArray();
                    $full = implode(', ', $ruteArray);
                    $display = implode(', ', array_slice($ruteArray, 0, 3));
                    if (count($ruteArray) > 3) {
                        $display .= '...';
                    }
                    return '<span title="'.e($full).'">'.$display.'</span>';
                })
  
                ->addColumn('bukti', function($row){
                    if ($row->bukti) {
                        $url = asset('storage/' . $row->bukti);
                       return '<img src="'.$url.'" alt="Bukti" class="bukti-thumbnail" data-full="'.$url.'" style="max-width: 100px; cursor: pointer;">';
                    }
                    return '-';
                }) 
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-info btn-sm edit-btn" data-id="'.$row->id.'">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>';
                    return $btn;
                })
                ->addColumn('status', function($row){
                    $status = $row->status == 'confirmed' ? 'Terkonfirmasi' : 'Tidak Terkonfirmasi';
                    return '<span class="badge badge-'.($row->status == 'confirmed' ? 'success' : 'danger').'">'.$status.'</span>';
                })
                ->rawColumns(['action', 'status', 'perjalanan','bukti'])
                ->make(true);
        }
        if (auth()->user()->can('Kelola Report Perjalanan')) {
            $data['perjalanan'] = Perjalanan::with('rute', 'kendaraan', 'user')->get();
            $data['user'] = User::all();
        } else {
            $data['perjalanan'] = Perjalanan::with('rute', 'kendaraan', 'user')
                ->where('id_user', auth()->user()->id)
                ->get();
            $data['user'] = User::where('id', auth()->user()->id)->get();
        };
        return view ('report_perjalanan.index', $data);
    }

    public function exportPdf()
    {
        $reports = ReportPerjalanan::with('user', 'perjalanan.rute')->get();

        $pdf = Pdf::loadView('report_perjalanan.pdf', compact('reports'));
        return $pdf->download('Report Perjalanan.pdf');
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
            'id_user' => 'Driver',
            'id_perjalanan' => 'Perjalanan',
        ];

        $rules = [
            'id_perjalanan' => 'required|exists:tb_perjalanan,id',
            'tanggal' => 'required|date',
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|in:confirmed,not_confirmed',
        ];

        if (auth()->user()->can('Kelola Perjalanan')) {
            $rules['id_user'] = 'required|exists:users,id';
        }

        $validate = Validator::make($request->all(), $rules, [], $attributes)->validate();

        $filePath = $request->file('bukti')->store('uploads', 'public');

        $report = ReportPerjalanan::create([
            'id_user' => $validate['id_user'] ?? auth()->id(),
            'id_perjalanan' => $validate['id_perjalanan'],
            'tanggal' => $validate['tanggal'],
            'bukti' => $filePath,
            'status' => $validate['status'] ?? 'not_confirmed',
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Report berhasil ditambahkan!',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportPerjalanan  $reportPerjalanan
     * @return \Illuminate\Http\Response
     */
    public function show(ReportPerjalanan $perjalanan)
    {
        return response()->json($perjalanan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportPerjalanan  $reportPerjalanan
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportPerjalanan $reportPerjalanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportPerjalanan  $reportPerjalanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportPerjalanan $perjalanan)
    {
        $attributes = [
            'id_user' => 'Driver',
            'id_perjalanan' => 'Perjalanan',
        ];

       $rules = [
            'id_perjalanan' => 'required|exists:tb_perjalanan,id',
            'tanggal' => 'required|date',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|in:confirmed,not_confirmed',
        ];

        if (auth()->user()->can('Kelola Perjalanan')) {
            $rules['id_user'] = 'required|exists:users,id';
        }

        $validate = Validator::make($request->all(), $rules, [], $attributes)->validate();

        $perjalanan->id_perjalanan = $validate['id_perjalanan'];
        $perjalanan->id_user = $validate['id_user'] ?? auth()->id();
        $perjalanan->tanggal = $validate['tanggal'];
        if ($request->hasFile('bukti')) {
            $filePath = $request->file('bukti')->store('uploads', 'public');
            $perjalanan->bukti = $filePath;
        }
        $perjalanan->status = $validate['status'] ?? 'not_confirmed';
        $perjalanan->save();
        return response()->json([
            'status' => "success",
            'message' => 'Report berhasil diupdate!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportPerjalanan  $reportPerjalanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportPerjalanan $perjalanan)
    {
        $perjalanan->delete();
        return response()->json([
            'status' => "success",
            'message' => 'Report berhasil dihapus!',
        ], 200);
    }
}
