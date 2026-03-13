<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryMetadata extends Model
{
    public $incrementing = false; // Define que o ID não é auto-incrementável
    protected $keyType = 'string'; // Define o tipo do ID como string
    protected $table = 'category_metadata'; // Especifica o nome da tabela
    protected $fillable = [
        'id',
        'name',
        'category_id'
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function values(): HasMany
    {
        return $this->hasMany(MetadataValue::class);
    }
}
