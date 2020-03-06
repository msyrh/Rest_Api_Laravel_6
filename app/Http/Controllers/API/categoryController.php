<?php

namespace App\Http\Controllers\API;

use App\category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category=category::all();
        $data=$category->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data category berhasil diambil'
        ];
        return response()->json($response,200);
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
        $input = $request->all();
        $validator=Validator::make($input,[
            'nama'=>'required',
        ]);
        if ($validator->fails()){
            $response=[
            'success'=>false,
            'data'=>'Gagal Validasi',
            'message'=>$validator->errors()
            ];
            return response()->json($response,404);
        }
        
        $category=category::create($input);
        $data = $category->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data Category Berhasil disimpan'
        ];
        return response()->json($response,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=category::find($id);
        $data=$category->toArray();

        if(is_null($category)){
            $response=[
                'success'=>false,
                'data'=>'Kosong',
                'message'=>'Data Category Tidak Ditemukan.'
            ];
            return response()->json($response,404);
        }
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data Category ditemukan.'
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        $input = $request->all();
        $validator=Validator::make($input,[
            'nama'=>'required'
        ]);
        if ($validator->fails()){
            $response=[
                'success'=>false,
                'data'=>'Gagal Validasi',
                'message'=>$validator->errors()
            ];
            return response()->json($response,404);
        }
        $category->nama=$input['nama'];
        $category->save();
        $data=$category->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data Category telah diupdate.'
        ];
        return response()->json($response,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $category->delete();
        $data=$category->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data Category Berhasil Dihapus.'
        ];
        return response()->json($response,200);
    }
}
