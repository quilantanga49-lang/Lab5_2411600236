<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'brand',
        'quantity',
        'reorder_level',
        'unit_price',
        'supplier',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reorder_level' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class)->latest();
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<=', 'reorder_level');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->quantity === 0;
    }

    public function getStockValueAttribute(): float
    {
        return round($this->quantity * (float) $this->unit_price, 2);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_out_of_stock) {
            return 'Out of stock';
        }

        if ($this->is_low_stock) {
            return 'Low stock';
        }

        return 'In stock';
    }

    /**
     * Record a stock movement and update the product quantity in one place.
     */
    public function adjustStock(
        string $type,
        int $quantity,
        ?int $userId = null,
        ?string $reference = null
    ): void {
        $this->quantity = $type === 'in'
            ? $this->quantity + $quantity
            : max(0, $this->quantity - $quantity);

        $this->save();

        $this->transactions()->create([
            'type' => $type,
            'quantity' => $quantity,
            'reference' => $reference,
            'user_id' => $userId,
        ]);
    }
}
