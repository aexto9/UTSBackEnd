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
                'message' => 'Resource is added successfully',
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
            
            //jika id yg dicari adalah valid/ada
            if ($employees) {
                $data = [
                    'message' => 'Get Detail Resource',
                    'data' => $employees
                ];
            } else {
                //jika data yg dicari null/tidak ada
                $data = [
                    'message' => 'Resource not found',
                    'data' => null
                ];
    
                $statusCode = 404;
            }
    
            //output yang diterima user
            return response()->json($data, $statusCode);
        }
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
                'message' => 'Resource is Update Succesfully',
                'data' => $employees
            ];
        } else {
            $data = [
                'message' => 'Resource not found',
                'data' => null
            ];
        }

        //output yang diterima user
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $statusCode = 200;
        //mengambil data dati database sesuai dengan id
        $employees = employees::find($id);

        //percabangan jika data yg dicari ditemukan
        if ($employees) {
            $employees->delete();

            $data = [
                'message' => 'Resource is delete successfully',
            ];
        } else {
            //percabangan jika data yg dicari ditemukan
            $data = [
                'message' => 'Resource not found',
            ];

            $statusCode = 404;
        }

        //output yang diterima user
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
                'message' => 'Resource not Found',
                'code' => 404,
            ]);
        }

        //jika menemukan
        return response()->json([
            'message' => 'Get Searched resource',
            'data' => $employees,
            'code' => 200,
        ]);
    }

    public function status(string $status)
    {
        //mengambil data dari database sesuai status yg diminta
        $employees = employees::where('status', $status)
            ->get();
        $count = $employees->count();

        //permisalan jika status yang dicari tidak ada datanya
        if($employees->isEmpty()) {
            return response()->json([
                'message' => 'Resource not Found',
                'code' => 404,
            ]);
        }
        
        if ($status=="Active") {
            //status pekerja adalah aktif
            return response()->json([
                'message' => 'Get Active resource',
                'total'=> $count,
                'data' => $employees,
                'code' => 200,
            ]);
        }elseif ($status=="InActive") {
            //status pekerja adalah tidak aktif
            return response()->json([
                'message' => 'Get InActive Resource',
                'total'=> $count,
                'data' => $employees,
                'code' => 200,
            ]);
        }elseif ($status=="Terminated") {
            //status pekerja alah disingkirkan/ dipecat
            return response()->json([
                'message' => 'Get Terminated Resource',
                'total'=> $count,
                'data' => $employees,
                'code' => 200,
            ]);
        }
    }
}
