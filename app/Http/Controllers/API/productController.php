<?php

namespace App\Http\Controllers\API;

use App\category;
use App\product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** 
         *  model 1
         */
        // $product=product::join('categories','products.id_category','=','categories.id')->select('products.*','categories.nama as nama_category')->get();
        // $data=$product->toArray();

        /** 
         *  model 2
         */
        $product=product::with(['category:id,nama'])->get();
        // $product['nama_category']=$product->category->nama;
        $data=$product->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'mesage'=>'Data Product Berhasil diambil'
        ];
        // return response()->json($response,200);
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
        $input =$request->all();
        $validator=Validator::make($input,[
            'id_category'=>'required',
            'nama'=>'required',
            'harga'=>'required',
            'image'=>'required',
            'qty'=>'required',
        ]);
        if ($validator->fails()){
            $response=[
                'success'=>false,
                'data'=>'Gagal Validasi',
                'message'=>$validator->errors()
            ];
            return response()->json($response,404);
        }
        $id=product::max('id');
        $id+=1;
        $input=$request->all();
        if ($request->hasFile('image')){
            $name=str_replace(' ','_',$input['nama']);
            $input['image']='/upload/products/'.$name.'_'.$id.'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'),$input['image']);
        }
        $product=product::create($input);
        $data=$product->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'message'=>'Data Product Berhasil disimpan'
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
        $product=product::with(['category:id,nama'])->where('id',$id)->get();
        $data=$product->toArray();
        $response=[
            'success'=>true,
            'data'=>$data,
            'mesage'=>'Data Product Berhasil diambil'
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
        //mengambil data yang akan diedit
        $product=product::find($id);
        $data=$product->toArray();
        $category=category::orderBy('nama','ASC')->get()->pluck('nama','id');
        
        if(is_null($product)){
            $response=[
                'success'=>false,
                'data'=>'Kosong',
                'message'=>'Data Product tidak ditemukan'
            ];
            return response()->json($response,404);
        }
        $response=[
            'success'=>true,
            'data'=>$data,
            'combo'=>$category,
            'message'=>'Data berhasil ditemukan',
        ];
        return response()->json($response,200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input=$request->all();
        $validator=Validator::make($input,[
            'id_category'=>'required',
            'nama'=>'required',
            'harga'=>'required',
            // 'image'=>'required',
            'qty'=>'required',
        ]);
        if ($validator->fails()){
            $response=[
                'success'=>false,
                'data'=>'Gagal Validasi',
                'message'=>$validator->errors()
            ];
            return response()->json($response,404);
        }
        $product=product::findorFail($id);
        if($request->hasFile('image')){
            if($product->image != NULL){
                unlink(public_path($product->image));
            }
            $input['image']='/upload/products/'.str_slug($input['nama'],'-').'_'.$input['id'].'.'.$request->image->getClientOriginalExtension();
            $request->move(public_path('/upload/products'),$input['image']); 
        }
        $product->update($input);
        $response=[
            'success'=>true,
            'data'=>$input,
            'message'=>'Data Produk Berhasil diupdate'
        ];
        return response()->json($response,200); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!$product->image == NULL){
            unlink(public_path($product->image));
        }
        Product::destroy($id);
        $response=[
            'success'=>true,
            'message'=>'Data Berhasil Dihapus'
        ];
        return response()->json($response,200);
    }
}
