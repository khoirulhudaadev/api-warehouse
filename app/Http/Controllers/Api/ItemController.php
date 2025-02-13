<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ItemRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// Mengambil hanya kolom 'type_name' dari database
// $typeNames = Type::pluck('type_name');

// $types = $result->map(function ($type) {
//     return [
//         'id' => $type->type_id,
//         'name' => $type->type_name,
//         'description' => 'Description for ' . $type->type_name
//     ];
// });

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $itemRepository;

    // Constructor for dependency injection
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        try {
            $result = $this->itemRepository->getAll();

            if($result->count() > 0) {
                return $this->sendApiResponse('Barang berhasil didapatkan!', $result);    
            }
            
            return $this->sendApiError('Barang tidak ditemukan!', $result, 422);  
            
        } catch (ValidationException $e) {
            // Jika ada error validasi
            return $this->sendApiError('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            // Jika ada error lainnya (misalnya database atau lainnya)
            return $this->sendApiError('Terjadi kesalahan internal', $e->getMessage(), 500);
        }
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|max:20|string',
                'unit_id' => 'required|integer',
                'type_id' => 'required|integer',
                'amount' => 'required|integer',
                'image' => 'required|mimes:jpg,jpeg,png|file|max:50000',
            ]);

            if($validator->fails()) 
            {
                return $this->sendApiError($validator->errors(), $request, 422);
            }

            $image = $request->file('image');
            $uploadImage = $image->storeOnCloudinaryAs('items', time() . '-' . $image->getClientOriginalName());
            
            // Ambil public_id dan secure_url dari hasil upload
            $securePath = $uploadImage->getSecurePath();
            $publicId = $uploadImage->getPublicId();
            
            $validatedData = $validator->validate();
            // // Update URL gambar pada data item
            $validatedData['image'] = $securePath;
            $validatedData['image_public_id'] = $publicId;

            // $type = Type::create($validator->validate());
            $type = $this->itemRepository->create($validatedData);
            return $this->sendApiResponse('Barang berhasil ditambahkan!', $type, 201);

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
        $type = $this->itemRepository->getById($id);
        if(!$type) {
            return $this->sendApiError('Barang tidak ada!', $id, 422);
        }
        return $this->sendApiResponse('Barang ditemukan!', $type,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)    
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_name' => 'required|max:20|string',
                'unit_id' => 'required|integer',
                'type_id' => 'required|integer',
                'amount' => 'required|integer',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|file|max:50000'
            ]);
            
            if ($validator->fails()) {
                return $this->sendApiError($validator->errors(), $request, 422);
            }

            $itemFind = $this->itemRepository->getById($id);
            if(!$itemFind) {
                return $this->sendApiError('Barang tidak ditemukan!', $id, 422);
            }

            $validatedData = $validator->validated();

            // Upload gambar ke Cloudinary (misalnya, jika ada gambar)
            if ($request->hasFile('image')) {

                // Hapus data gambar lama dari cloudinary
                // $fileName = pathinfo($itemFind->image_public_id, PATHINFO_FILENAME);
                Cloudinary::destroy($itemFind->image_public_id);

                $newFile = $request->file('image');
                $uploadImage = $newFile->storeOnCloudinaryAs('items', time() . '-' . $newFile->getClientOriginalName());
                
                // Ambil public_id dan secure_url dari hasil upload
                $securePath = $uploadImage->getSecurePath();
                $publicId = $uploadImage->getPublicId();
                
                // // Update URL gambar pada data item
                $validatedData['image'] = $securePath;
                $validatedData['image_public_id'] = $publicId;
            }

            $item = $this->itemRepository->update($id, $validatedData);
            if($item) {
                Cache::forget('item_key');
                return $this->sendApiResponse('Berhasil perbarui data barang!', $item, 201);
            }
            
            return $this->sendApiError('Gagal perbarui data barang!', $id, 400);
        } catch (ValidationException $e) {
            // Jika ada error validasi
            return $this->sendApiError('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            // Jika ada error lainnya (misalnya database atau lainnya)
            return $this->sendApiError('Terjadi kesalahan internal', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = $this->itemRepository->delete($id);
        if(!$type) {
            return $this->sendApiError('Data dengan id ' .$id. ' tidak ditemukan!', $type, 422);
        }       
        Cache::forget('item_key'); 
        return $this->sendApiResponse('Hapus data berhasil!', $id, 201);
    }
}
