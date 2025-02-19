<?php

namespace App\Http\Controllers\Api;
  
use App\Http\Controllers\Controller;
use App\Repositories\DeliveryRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeliveryController extends Controller
{
    use SoftDeletes;
    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;
    protected $deliveryRepository;

    // Constructor for dependency injection
    public function __construct(DeliveryRepository $deliveryRepository)
    {
        $this->deliveryRepository = $deliveryRepository;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $item = $this->deliveryRepository->getById($id);
        if(!$item) {
            return $this->sendApiError('Barang tidak ada!', $id, 422);
        }
        return $this->sendApiResponse('Barang ditemukan!', $item,200);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->deliveryRepository->getAll();
        
        if($result->count() > 0)
        {
            return $this->sendApiResponse('Data barang yang keluar berhasil didapatkan', $result);
        }
        return $this->sendApiError('Data barang yang keluar tidak ditemukan', $result, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $checkDelivery = $this->deliveryRepository->getById($id);

        if(!$checkDelivery) 
        {
            return $this->sendApiResponse('Data barang keluar tidak ditemukan', $checkDelivery, 422);
        }

        $checkDeliveryArray = $checkDelivery->toArray();
        $restore = $this->deliveryRepository->update($id, $checkDeliveryArray);

        if(!$restore) 
        {
            return $this->sendApiError('Gagal melakukan pemulihan data barang', $restore, 403);
        }
        Cache::forget('delivery_key');
        Cache::forget('item_key');
        $checkDelivery->delete();
        return $this->sendApiResponse('Berhasil melakukan pemulihan data barang', $restore, 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $checkDelivery = $this->deliveryRepository->delete($id);

        if($checkDelivery) 
        {
            Cache::forget('delivery_key');
            return $this->sendApiResponse('Data barang keluar berhasil dihapus', $checkDelivery, 201);
        }
        return $this->sendApiResponse('Data barang keluar gagal dihapus', $checkDelivery, 422);
    }
}
