<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Support\Facades\Log;

class DiscountService
{
    public function getComputedDiscount(Item $item)
    {
        $itemDiscount = $this->getItemDiscount($item);

        if ($itemDiscount) {
            return $itemDiscount;
        }
        $categoryDiscount = $this->getCategoryDiscount($item->category);

        if ($categoryDiscount) {
            return $categoryDiscount;
        }

        $allMenuDiscount = $this->getAllMenuDiscount();

        return $allMenuDiscount;
    }

    protected function getItemDiscount(Item $item)
    {

        return $item->discounts->last();
    }

    protected function getCategoryDiscount(Category $category)
    {
        while ($category) {
            $categoryDiscount = $category->discounts->last();

            if ($categoryDiscount) {
                return $categoryDiscount;
            }

            $category = $category->parent;
        }

        return null;
    }

    protected function getAllMenuDiscount()
    {
        return Discount::where('type', 'all_menu')->latest()->first();
    }

    public function calculateDiscountedAmount(Item $item)
    {
        $originalAmount = $item->amount;
        $discount = $this->getComputedDiscount($item);

        if ($discount) {
            $discountPercentage = $discount->value;
            $discountedAmount = $originalAmount - ($originalAmount * ($discountPercentage / 100));

            //   Log::info($discountedAmount);

            return max(0, $discountedAmount);
        }

        return null;
    }
}
