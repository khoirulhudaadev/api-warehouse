<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }
     
    public function index()
    {
        //
        $result = $this->userRepository->getAll(); 
        if($result->count() > 0) {
            return $this->sendApiResponse( 'Akun berhasil didapatkan!', $result);    
        }
        return $this->sendApiError( 'Akun tidak ditemukan!', $result);  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|max:60|string|unique:users',
                'role_id' => 'required|integer|in:1,2,3',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]);
    
            if ($validator->fails()) {
                return $this->sendApiError('Data tidak sesuai!', $validator->errors());
            }
    
            // Ambil data yang tervalidasi
            $data = $validator->validate();
            // Hash password
            $data['password'] = Hash::make($data['password']);
    
            // Simpan ke repository
            $user = $this->userRepository->create($data);
    
            if($user) {
                Cache::forget('use_key');
                return $this->sendApiResponse('Akun berhasil dibuat!', $user);
            }
    
        } catch (ValidationException $e) {
            // Jika ada error validasi
            return $this->sendApiError('Validation error', $e->errors());
        } catch (Exception $e) {
            // Jika ada error lainnya (misalnya database atau lainnya)
            return $this->sendApiError('Terjadi kesalahan internal', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userRepository->getById($id);
        if(!$user) {
            return $this->sendApiError('Pengguna tidak ada!', $id, 422);
        }
        return $this->sendApiResponse('Pengguna ditemukan!', $user,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $checkAccount = $this->userRepository->getById($id);
        if(!$checkAccount) {
            return $this->sendApiError('Data pengguna tidak ditemukan!', $id, 422);
        }

        $update = $this->userRepository->update($id, $request);
        if(!$update) {
            return $this->sendApiError('Terjadi kesalahan saat perbarui data!', $request, 422);
        }
        Cache::forget('user_key');
        return $this->sendApiError('Berhasil perbarui data!', $request->all(), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $result = $this->userRepository->delete($id);
        if(!$result) {
            return $this->sendApiError( 'Gagal menghapus akun!', $result);              
        }
        Cache::forget('user_key');
        return $this->sendApiResponse( 'Berhasil hapus akun!', $result);              
    }
}
