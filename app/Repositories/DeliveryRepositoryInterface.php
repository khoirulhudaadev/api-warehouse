<?php

namespace App\Repositories;

interface DeliveryRepositoryInterface {
    public function getAll();
    public function getById($id);
    public function update($id, array $data);
    public function delete($id);
}

