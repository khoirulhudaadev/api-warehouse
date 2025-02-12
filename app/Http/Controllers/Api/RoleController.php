<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $roleRepository;

    // Constructor for dependency injection
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $result = $this->roleRepository->getAll();

        if($result->count() > 0) {
            return $this->sendApiResponse( 'Role berhasil didapatkan!', $result);    
        }
        return $this->sendApiError( 'Role tidak ditemukan!', $result, 404);    
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_name' => 'required|max:20|string|unique:roles'
            ]);

            if($validator->fails()) 
            {
                return $this->sendApiError( 'Data tidak sesuai!', $request->role_name, 422);
            }
            
            // $role = Type::create($validator->validate());
            $role = $this->roleRepository->create($validator->validate());
            return $this->sendApiResponse( 'Role berhasil dibuat!', $role, 201);

        } catch (ValidationException $e) {
            // Jika ada error validasi
            return $this->sendApiError('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            // Jika ada error lainnya (misalnya database atau lainnya)
            return $this->sendApiError('Terjadi kesalahan internal', $e->getMessage(), 500);
        }
    }   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $role = $this->roleRepository->getById($id);
        if(!$role) {
            return $this->sendApiError('Role tidak ada!', $id, 404);
        }
        return $this->sendApiResponse('Role ditemukan!', $role);
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
        $role = $this->roleRepository->delete($id);
        if(!$role) {
            return $this->sendApiError('Hapus role gagal!', $id, 403);
        }        
        return $this->sendApiResponse('Hapus role berhasil!', $id, 201);
    }
}
