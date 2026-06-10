<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ServiceCategory;
use App\Models\SubService;

final class ServiceManagementService
{
    public function __construct(
        private readonly ServiceCategory $categories = new ServiceCategory(),
        private readonly SubService $subServices = new SubService()
    ) {
    }

    public function saveCategory(?int $id, array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $isActive = isset($input['is_active']) ? 1 : 0;

        if ($name === '') {
            return ['success' => false, 'message' => 'Nama kategori wajib diisi.'];
        }

        $slug = $this->slug((string) ($input['slug'] ?? $name));

        if ($id === null) {
            $this->categories->create($name, $slug, $description !== '' ? $description : null, $isActive);
        } else {
            $this->categories->update($id, $name, $slug, $description !== '' ? $description : null, $isActive);
        }

        return ['success' => true, 'message' => 'Kategori layanan berhasil disimpan.'];
    }

    public function saveSubService(?int $id, array $input): array
    {
        $categoryId = (int) ($input['service_category_id'] ?? 0);
        $name = trim((string) ($input['name'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $price = (float) ($input['price'] ?? 0);
        $isActive = isset($input['is_active']) ? 1 : 0;

        if ($categoryId < 1 || $name === '' || $price < 0) {
            return ['success' => false, 'message' => 'Data sub layanan tidak valid.'];
        }

        $slug = $this->slug((string) ($input['slug'] ?? $name));

        if ($id === null) {
            $this->subServices->create($categoryId, $name, $slug, $description !== '' ? $description : null, $price, $isActive);
        } else {
            $this->subServices->update($id, $categoryId, $name, $slug, $description !== '' ? $description : null, $price, $isActive);
        }

        return ['success' => true, 'message' => 'Sub layanan berhasil disimpan.'];
    }

    public function updateAdminPrice(int $subServiceId, int $adminId, array $input): array
    {
        $price = (float) ($input['price'] ?? -1);

        if ($price < 0) {
            return ['success' => false, 'message' => 'Harga tidak valid.'];
        }

        if (!$this->subServices->updateAdminPrice($subServiceId, $adminId, $price)) {
            return ['success' => false, 'message' => 'Anda tidak memiliki akses ke sub layanan ini.'];
        }

        return ['success' => true, 'message' => 'Harga berhasil diperbarui.'];
    }

    private function slug(string $value): string
    {
        $slug = strtolower(trim($value));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?: '';
        $slug = trim($slug, '-');

        return $slug !== '' ? $slug : 'service-' . time();
    }
}
