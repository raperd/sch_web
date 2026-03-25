<?php

namespace Tests\Unit\Models;

use App\Models\GuruStafModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class GuruStafModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate  = true;
    protected $refresh  = true;
    protected $namespace = 'App';

    private GuruStafModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new GuruStafModel();
    }

    public function testSoftDeleteIsEnabled(): void
    {
        $this->assertTrue($this->model->useSoftDeletes);
    }

    public function testInsertAndFindGuru(): void
    {
        $id = $this->model->insert([
            'nama'      => 'Budi Santoso',
            'jabatan'   => 'Guru Matematika',
            'tipe'      => 'guru',
            'is_active' => 1,
            'urutan'    => 1,
        ]);

        $row = $this->model->find($id);
        $this->assertNotNull($row);
        $this->assertSame('Budi Santoso', $row['nama']);
        $this->assertSame('guru', $row['tipe']);
    }

    public function testGetByTipeReturnsOnlyMatchingTipe(): void
    {
        $this->model->insert(['nama' => 'Guru A', 'jabatan' => 'Wali Kelas', 'tipe' => 'guru', 'is_active' => 1, 'urutan' => 1]);
        $this->model->insert(['nama' => 'Staf B', 'jabatan' => 'TU', 'tipe' => 'staf', 'is_active' => 1, 'urutan' => 2]);

        $guruList = $this->model->getByTipe('guru');

        foreach ($guruList as $g) {
            $this->assertSame('guru', $g['tipe']);
        }
    }

    public function testAktifScopeExcludesInactive(): void
    {
        $this->model->insert(['nama' => 'Aktif',   'jabatan' => 'Guru', 'tipe' => 'guru', 'is_active' => 1, 'urutan' => 1]);
        $this->model->insert(['nama' => 'Nonaktif','jabatan' => 'Guru', 'tipe' => 'guru', 'is_active' => 0, 'urutan' => 2]);

        $results = $this->model->aktif()->findAll();
        foreach ($results as $r) {
            $this->assertSame(1, (int) $r['is_active']);
        }
    }

    public function testSoftDelete(): void
    {
        $id = $this->model->insert([
            'nama'      => 'Hapus Saya',
            'jabatan'   => 'Guru',
            'tipe'      => 'guru',
            'is_active' => 1,
            'urutan'    => 99,
        ]);

        $this->model->delete($id);
        $this->assertNull($this->model->find($id));
        $this->assertNotNull($this->model->withDeleted()->find($id));
    }
}
