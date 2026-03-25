<?php

namespace Tests\Unit\Models;

use App\Models\ArtikelModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class ArtikelModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate      = true;
    protected $migrateOnce  = true;
    protected $seed         = 'App\Database\Seeds\DatabaseSeeder';
    protected $seedOnce     = true;
    protected $namespace    = 'App';

    private ArtikelModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new ArtikelModel();
    }

    public function testSoftDeleteIsEnabled(): void
    {
        $this->assertTrue($this->model->useSoftDeletes);
    }

    public function testAllowedFieldsContainRequired(): void
    {
        $required = ['kategori_id', 'user_id', 'judul', 'slug', 'konten', 'status'];
        foreach ($required as $field) {
            $this->assertContains($field, $this->model->allowedFields);
        }
    }

    public function testInsertAndFindBySlug(): void
    {
        $this->model->insert([
            'judul'     => 'Artikel Test',
            'slug'      => 'artikel-test',
            'konten'    => '<p>Isi konten</p>',
            'status'    => 'draft',
            'user_id'   => 1,
            'kategori_id' => 1,
        ]);

        $row = $this->model->findBySlug('artikel-test');
        $this->assertNotNull($row);
        $this->assertSame('Artikel Test', $row['judul']);
    }

    public function testPublishedScopeExcludesDraft(): void
    {
        $this->model->insert([
            'judul'     => 'Draft Artikel',
            'slug'      => 'draft-artikel',
            'konten'    => 'Isi',
            'status'    => 'draft',
            'user_id'   => 1,
            'kategori_id' => 1,
        ]);

        $results  = $this->model->published()->findAll();
        $slugs    = array_column($results, 'slug');
        $this->assertNotContains('draft-artikel', $slugs);
        foreach ($results as $r) {
            $this->assertSame('published', $r['status']);
        }
    }

    public function testSoftDeleteDoesNotAppearInFindAll(): void
    {
        $id = $this->model->insert([
            'judul'     => 'Hapus Ini',
            'slug'      => 'hapus-ini',
            'konten'    => 'Isi',
            'status'    => 'published',
            'user_id'   => 1,
            'kategori_id' => 1,
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        $this->model->delete($id);

        $row = $this->model->find($id);
        $this->assertNull($row);

        $rowWithDeleted = $this->model->withDeleted()->find($id);
        $this->assertNotNull($rowWithDeleted);
        $this->assertNotNull($rowWithDeleted['deleted_at']);
    }

    public function testIncrementView(): void
    {
        $id = $this->model->insert([
            'judul'     => 'View Count Test',
            'slug'      => 'view-count-test',
            'konten'    => 'Isi',
            'status'    => 'published',
            'user_id'   => 1,
            'kategori_id' => 1,
            'published_at' => date('Y-m-d H:i:s'),
        ]);

        $this->model->incrementView((int) $id);
        $this->model->incrementView((int) $id);

        $row = $this->model->find($id);
        $this->assertSame(2, (int) $row['view_count']);
    }
}
