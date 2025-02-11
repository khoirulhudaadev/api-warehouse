<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TypeRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $typeRepository;

    // Constructor for dependency injection
    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function index()
    {
        $result = $this->typeRepository->getAll();

        if($result->count() > 0) {
            return $this->sendApiResponse('semua jenis berhasil didapatkan!', $result);    
        }
        return $this->sendApiError('Jenis tidak ditemukan!', $result);    
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'type_name' => 'required | max:20 | string | unique:types'
        ]);

        if($validator->fails()) 
        {
            return $this->sendApiError('Data tidak sesuai!', $request->type_name);
        }
        
        // $type = Type::create($validator->validate());
        $type = $this->typeRepository->create($validator->validate());
        if($type) {
            return $this->sendApiResponse( 'Jenis berhasil dibuat!', $type);
        }
        return $this->sendApiError( 'Jenis gagal dibuat!', $type);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $type = $this->typeRepository->getById($id);
        if(!$type) {
            return $this->sendApiError('Jenis tidak ada!', $id);
        }
        return $this->sendApiResponse('Jenis ditemukan!', $type);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = $this->typeRepository->delete($id);
        if(!$type) {
            return $this->sendApiError('Hapus jenis gagal!', $id);
        }        
        return $this->sendApiResponse('Hapus jenis berhasil!', $id);
    }
}
