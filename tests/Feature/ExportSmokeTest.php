<?php

namespace Tests\Feature;

use App\Exports\Cabang;
use App\Exports\Others;
use App\Exports\PDPTK;
use App\Exports\SatpenExport;
use App\Exports\Wilayah;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

/**
 * Smoke test export Excel terhadap maatwebsite/excel 4.x
 * (interface kini natively typed: collection(): Enumerable, styles(): ?array).
 */
class ExportSmokeTest extends TestCase
{
    private function simpanKeTemporary($export): string
    {
        $file = 'smoke-export-'.uniqid().'.xlsx';
        Excel::store($export, $file, 'local');

        return storage_path('app/'.$file);
    }

    public function test_export_wilayah_menghasilkan_file_xlsx(): void
    {
        $path = $this->simpanKeTemporary(new Wilayah());

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        @unlink($path);
    }

    public function test_export_satpen_menghasilkan_file_xlsx(): void
    {
        $filter = ['id_jenjang' => 1];
        $path = $this->simpanKeTemporary(
            new SatpenExport(['id_jenjang' => 1], ['setujui', 'expired', 'perpanjangan'], $filter)
        );

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        @unlink($path);
    }

    public function test_export_pdptk_menghasilkan_file_xlsx(): void
    {
        $filter = ['id_jenjang' => 1];
        $path = $this->simpanKeTemporary(
            new PDPTK(
                ['id_jenjang' => 1],
                ['SEKOLAH', 'MADRASAH'],
                '2025/2026',
                $filter,
                [['nm_satpen', 'like', '%']],
                ['0', '1']
            )
        );

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        @unlink($path);
    }

    public function test_export_others_menghasilkan_file_xlsx(): void
    {
        $filter = ['id_jenjang' => 1];
        $path = $this->simpanKeTemporary(
            new Others(
                ['id_jenjang' => 1],
                ['SEKOLAH', 'MADRASAH'],
                $filter,
                [['nm_satpen', 'like', '%']],
                ['Negeri', 'Swasta'],
                ['A', 'B', 'C']
            )
        );

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        @unlink($path);
    }

    public function test_export_cabang_menghasilkan_file_xlsx(): void
    {
        $path = $this->simpanKeTemporary(new Cabang(['id_prov' => 1], null));

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));

        @unlink($path);
    }

    public function test_semua_class_export_memenuhi_kontrak_interface_excel_v4(): void
    {
        $exports = [
            new Wilayah(),
            new SatpenExport(['id_jenjang' => 1], ['setujui'], ['id_jenjang' => 1]),
            new Cabang(['id_prov' => 1], null),
        ];

        foreach ($exports as $export) {
            $this->assertInstanceOf(
                \Maatwebsite\Excel\Concerns\FromCollection::class,
                $export,
                get_class($export).' tidak memenuhi FromCollection v4.'
            );
            $this->assertInstanceOf(
                \Maatwebsite\Excel\Concerns\WithStyles::class,
                $export,
                get_class($export).' tidak memenuhi WithStyles v4.'
            );
        }
    }

    public function test_halaman_export_dapat_diakses_admin(): void
    {
        $admin = User::whereIn('role', ['super admin', 'admin pusat'])
            ->where('status_active', 'active')
            ->first();

        if (!$admin) {
            $this->markTestSkipped('Tidak ada user admin aktif di database.');
        }

        // Endpoint rekap PTK nasional memakai anonymous export class (FromCollection).
        $this->actingAs($admin)
            ->get('/a/npyp/rekap-ptk')
            ->assertStatus(200);
    }
}
