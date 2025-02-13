<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UnitRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;


class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $unitRepository;

    // Constructor for dependency injection
    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function index()
    {
        $result = $this->unitRepository->getAll();

        if($result->count() > 0) {
            return $this->sendApiResponse( 'Data berhasil didapatkan!', $result);    
        }
        return $this->sendApiError( 'Data tidak ditemukan!', $result, 422);    
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required | max:20 | string | unique:units'
        ]);

        if($validator->fails()) 
        {
            return $this->sendApiError( $validator->errors(), $request->unit_name, 422);
        }
        
        // $unit = Type::create($validator->validate());
        $unit = $this->unitRepository->create($validator->validate());

        if($unit) {
            return $this->sendApiResponse( 'Satuan berhasil dibuat!', $unit, 201);
        }
        return $this->sendApiError( 'Satuan gagal dibuat!', $unit, 403);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $unit = $this->unitRepository->getById($id);
        if(!$unit) {
            return $this->sendApiError('Satuan tidak ada!', $id, 422);
        }
        return $this->sendApiResponse('Satuan ditemukan!', $unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = $this->unitRepository->delete($id);
        if(!$unit) {
            return $this->sendApiError('Hapus satuan gagal!', $id, 403);
        }        
        Cache::forget('unit_key');
        return $this->sendApiResponse('Hapus satuan berhasil!', $id, 201);
    }
}
