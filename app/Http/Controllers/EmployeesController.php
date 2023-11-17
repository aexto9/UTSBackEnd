<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\employees;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //mengambil data dari tabel employees
        $employees = employees::all();

        //percabangan jika data kosong
        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'Data is empty',
                'code' => 200,
            ]);
        }

        //output yang didapat user
        return response()->json([
            'message' => 'Get All Resource',
            'data' => $employees,
            'code' => 200,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $statusCode = 201;

        //input yang harus diisi user
        $input = [
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'status' => $request->status,
            'hired_on' => $request->hired_on
            
        ];

        //pesan output jika tidak valid
        $messages = [
            
            'name.required' => 'Nama harus diisi',
            'gender.required' => 'Jenis Kelamin harus diisi',
            'phone.required' => 'No. HP harus diisi',
            'address.required' => 'Alamat harus diisi',
            'email.required' => 'Email harus diisi',
            'status.required' => 'Status harus diisi',
            'hired_on.required' => 'Tanggal Pertama Kerja harus diisi'

        ];

        //validasi inputan user
        $validator = Validator::make($input, [
            'name' => 'required',
            'gender' => 'required|in:M,F',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'hired_on' => 'required|date'
        ], $messages);
    
        //percabangan jika gagal memvalidasi
        if ($validator->fails()) {
            $data = [
                'message' => 'Validasi Gagal',
                'data' => $validator->errors()
            ];

            $statusCode = 400;
        } else {
            //jika berhasil tervalidasi
            $employees = employees::create($validator->validate());
            $data = [
                'message' => 'Data Pegawai berhasil dibuat',
                'data' => $employees
            ];
        }
        
        //output yang diterima user
        return response()->json($data, $statusCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        {
            $statusCode = 200;
            $employees = employees::find($id);
    
            if ($employees) {
                $data = [
                    'message' => 'Data Pegawai ditemukan',
                    'data' => $employees
                ];
            } else {
                $data = [
                    'message' => 'Data Pegawai tidak ditemukan',
                    'data' => null
                ];
    
                $statusCode = 404;
            }
    
            return response()->json($data, $statusCode);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $statusCode = 200;
        $employees = employees::find($id);
        
        if ($employees){

            //input yang bisa diisi oleh user , bisa semua bisa hanya satu
            $input = [
                'name' => $request->name ?? $employees->name,
                'gender' => $request->gender ?? $employees->gender,
                'phone' => $request->phone ?? $employees->phone,
                'address' => $request->address ?? $employees->address,
                'email' => $request->email ?? $employees->email,
                'status' => $request->status ?? $employees->status,
                'hired_on' => $request->hired_on ?? $employees->hired_on
            ];
            
            $employees->update($input);

            $data = [
                'message' => 'Pembaruan data Pegawai Berhasil',
                'data' => $employees
            ];
        } else {
            $data = [
                'message' => 'Pegawai tidak ditemukan',
                'data' => null
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $statusCode = 200;
        $employees = employees::find($id);

        if ($employees) {
            $employees->delete();

            $data = [
                'message' => 'ID: ' . $id . ' Pegawai Berhasil dihapus',
            ];
        } else {
            $data = [
                'message' => 'Pegawai tidak ditemukan',
            ];

            $statusCode = 404;
        }


        return response()->json($data, $statusCode);
    }

    public function search(string $name)
    {
        //mengambil data sesuai dengan variabel $name
        $employees = employees::where('name', 'LIKE', '%' . $name . '%')
            ->get();

        //percabangan jika tidak menemukan
        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'Pegawai Tidak Ditemukan',
                'code' => 404,
            ]);
        }

        //jika menemukan
        return response()->json([
            'message' => 'Pegawai Ditemukan',
            'data' => $employees,
            'code' => 200,
        ]);
    }

    public function status(string $status)
    {
        
        $employees = employees::where('status', $status)
            ->get();

        
        if($employees->isEmpty()) {
            return response()->json([
                'message' => 'Data Pegawai Tidak Ditemukan',
                'code' => 404,
            ]);
        }
        
        if ($status=="Active") {
            return response()->json([
                'message' => 'Data Pegawai Aktif',
                'data' => $employees,
                'code' => 200,
            ]);
        }elseif ($status=="InActive") {
            return response()->json([
                'message' => 'Data Pegawai Tidak Aktif',
                'data' => $employees,
                'code' => 200,
            ]);
        }elseif ($status=="Terminated") {
            return response()->json([
                'message' => 'Data Pegawai Dipecar',
                'data' => $employees,
                'code' => 200,
            ]);
        }
    }
}
