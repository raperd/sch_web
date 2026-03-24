<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table         = 'artikel';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'kategori_id', 'user_id', 'judul', 'slug', 'ringkasan', 'konten',
        'thumbnail', 'status', 'is_featured', 'view_count', 'tags', 'published_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    /**
     * Hanya artikel berstatus published.
     */
    public function published(): static
    {
        return $this->where('artikel.status', 'published')
                    ->where('artikel.published_at <=', date('Y-m-d H:i:s'));
    }

    /**
     * Artikel dengan join ke kategori dan user.
     */
    public function withRelations(): static
    {
        return $this->select('artikel.*, kategori_artikel.nama as kategori_nama, kategori_artikel.slug as kategori_slug, users.nama as penulis')
                    ->join('kategori_artikel', 'kategori_artikel.id = artikel.kategori_id', 'left')
                    ->join('users', 'users.id = artikel.user_id', 'left');
    }

    /**
     * Increment view count.
     */
    public function incrementView(int $id): void
    {
        $this->db->query('UPDATE artikel SET view_count = view_count + 1 WHERE id = ?', [$id]);
    }

    /**
     * Ambil artikel featured untuk beranda.
     */
    public function getFeatured(int $limit = 3): array
    {
        return $this->withRelations()
                    ->published()
                    ->where('artikel.is_featured', 1)
                    ->orderBy('artikel.published_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Ambil artikel terbaru.
     */
    public function getTerbaru(int $limit = 6): array
    {
        return $this->withRelations()
                    ->published()
                    ->orderBy('artikel.published_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->withRelations()->where('artikel.slug', $slug)->first();
    }
}
