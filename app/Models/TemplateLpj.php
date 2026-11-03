<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemplateLpj extends Model
{
    use HasFactory;

    protected $table = 'template_lpjs';

    protected $fillable = [
        'nama',
        'kategori',
        'tipe',
        'url_link',
        'file_path',
        'file_name',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get downloadable destination URL or route.
     */
    public function getDownloadUrlAttribute(): string
    {
        if ($this->tipe === 'link' && !empty($this->url_link)) {
            return $this->url_link;
        }

        if ($this->tipe === 'file' && !empty($this->file_path)) {
            return route('template-lpj.download', $this->id);
        }

        return !empty($this->url_link) ? $this->url_link : route('template-lpj.download', $this->id);
    }

    /**
     * Scope to get the currently active template for a category.
     */
    public static function getActiveTemplate(?string $kategori = 'Prestasi Mandiri'): ?self
    {
        $query = static::where('is_active', true);
        if ($kategori) {
            $matched = (clone $query)->where('kategori', $kategori)->latest()->first();
            if ($matched) {
                return $matched;
            }
        }
        return $query->latest()->first();
    }

    /**
     * Get all active templates for a category.
     */
    public static function getActiveTemplates(?string $kategori = 'Prestasi Mandiri')
    {
        $query = static::where('is_active', true);
        if ($kategori) {
            $matched = (clone $query)->where('kategori', $kategori)->latest()->get();
            if ($matched->isNotEmpty()) {
                return $matched;
            }
        }
        return $query->latest()->get();
    }
}
