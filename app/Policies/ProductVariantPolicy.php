<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductVariant;

class ProductVariantPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('product-variants.index');
    }

    public function update(User $user, ProductVariant $variant)
    {
        return $user->hasPermissionTo('product-variants.edit');
    }

    public function duplicate(User $user, ProductVariant $variant)
    {
        // Quyền mặc định: cho phép admin hoặc user có quyền 'product-variant.duplicate'
        return $user->hasRole('admin') || $user->hasPermissionTo('product-variant.duplicate');
    }
}
