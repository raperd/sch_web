<?php

namespace Tests\Unit\Models;

use App\Models\PengaturanModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class PengaturanModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate  = true;
    protected $refresh  = true;
    protected $namespace = 'App';

    private PengaturanModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new PengaturanModel();
    }

    public function testGetByKeyReturnsNullWhenMissing(): void
    {
        $value = $this->model->getByKey('key_yang_tidak_ada');
        $this->assertNull($value);
    }

    public function testGetByKeyReturnsDefaultWhenMissing(): void
    {
        $value = $this->model->getByKey('key_tidak_ada', 'default_value');
        $this->assertSame('default_value', $value);
    }

    public function testSetByKeyInsertsNew(): void
    {
        $this->model->setByKey('test_key', 'test_value');
        $this->assertSame('test_value', $this->model->getByKey('test_key'));
    }

    public function testSetByKeyUpdatesExisting(): void
    {
        $this->model->setByKey('update_key', 'nilai_awal');
        $this->model->setByKey('update_key', 'nilai_baru');
        $this->assertSame('nilai_baru', $this->model->getByKey('update_key'));
    }

    public function testGetAllGroupedReturnsByGrup(): void
    {
        $this->model->insert([
            'setting_key'   => 'site_name',
            'setting_value' => 'Sekolah Test',
            'label'         => 'Nama Situs',
            'tipe'          => 'text',
            'grup'          => 'umum',
            'urutan'        => 1,
        ]);

        $grouped = $this->model->getAllGrouped();
        $this->assertArrayHasKey('umum', $grouped);
        $this->assertArrayHasKey('site_name', $grouped['umum']);
    }

    public function testGetAllFlatReturnsKeyValuePairs(): void
    {
        $this->model->insert([
            'setting_key'   => 'telepon',
            'setting_value' => '021-12345678',
            'label'         => 'Telepon',
            'tipe'          => 'text',
            'grup'          => 'umum',
            'urutan'        => 2,
        ]);

        $flat = $this->model->getAllFlat();
        $this->assertArrayHasKey('telepon', $flat);
        $this->assertSame('021-12345678', $flat['telepon']);
    }
}
