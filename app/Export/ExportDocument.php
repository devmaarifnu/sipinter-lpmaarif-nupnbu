<?php

namespace App\Export;
use App\Exceptions\CatchErrorException;
use App\Helpers\Date;
use App\Helpers\GenerateQr;
use App\Http\Controllers\Settings;
use App\Models\FileRegister;
use App\Models\Satpen;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportDocument
{
    public static function makePiagamDokumen(Satpen $satpenProfile)
    {
        try {
            $filePath = storage_path('app/templates/');
            $exportFilePath = storage_path('app/generated/piagam/');
            $templateName = $filePath . Settings::get("template_piagam");
            $exportFilename = $satpenProfile->file[0]->nm_file;
            $qrPath  = $filePath. "qrcode.png";

            $tempExportFilenameSplit = pathinfo($exportFilename);
            $tempFilename = $filePath. $tempExportFilenameSplit['filename'].".docx";

            $templateDocument = new TemplateProcessor($templateName);

            if ($satpenProfile) {

                if (GenerateQr::make($satpenProfile->file[0]->qrcode, $qrPath)) {
                    $templateDocument->setValue('kategori', $satpenProfile->kategori->nm_kategori);
                    $templateDocument->setValue('noregistrasi', $satpenProfile->no_registrasi);
                    $templateDocument->setValue('npsn', $satpenProfile->npsn);
                    $templateDocument->setValue('nama', $satpenProfile->nm_satpen);
                    $templateDocument->setValue('yayasan', $satpenProfile->yayasan);
                    $templateDocument->setValue('alamat', $satpenProfile->alamat);
                    $templateDocument->setValue('kecamatan', $satpenProfile->kecamatan);
                    $templateDocument->setValue('kabupaten', $satpenProfile->kabupaten->nama_kab);
                    $templateDocument->setValue('propinsi', $satpenProfile->provinsi->nm_prov);
                    $templateDocument->setValue('tahunberdiri', $satpenProfile->thn_berdiri);
                    $templateDocument->setValue('tahunberdiri', $satpenProfile->thn_berdiri);
                    $templateDocument->setValue('telp', $satpenProfile->telpon);
                    $templateDocument->setValue('fax', $satpenProfile->fax);
                    $templateDocument->setValue('email', $satpenProfile->email);
                    $templateDocument->setValue('tanggal', Date::tglMasehi($satpenProfile->file[0]->tgl_file));

                    // Replace the QR code placeholder with the actual QR code image in the template
                    $templateDocument->setImageValue('qrcode',  array('path' => $qrPath, 'width' => 150, 'height' => 150));

                    $templateDocument->saveAs($tempFilename);
//                    $templateDocument->saveAs($exportFilePath. $exportFilename);
                    //Convert to pdf
//                    $command = 'docx2pdf ' . escapeshellarg($tempFilename) . ' ' . escapeshellarg($exportFilePath. $exportFilename);
                    $command = "sudo /var/www/convertpdf.sh -i ". escapeshellarg($tempFilename) . ' -o ' . escapeshellarg($exportFilePath);

                    exec($command, $output, $returnCode);

                    if ($returnCode === 0) {
                        unlink($tempFilename);
                        unlink($qrPath);
                        return true;
                    } else {
                        echo 'Error converting Word document to PDF.';
                    }
                    return true;
                }
            }
            return false;

        } catch (\Exception $e) {
            throw new CatchErrorException("[MAKE PIAGAM DOKUMEN] has error ". $e);

        }

    }

    public static function makeSKDokumen(Satpen $satpenProfile)
    {
        try {
            $filePath = storage_path('app/templates/');
            $exportfilePath = storage_path('app/generated/sk/');
            $templateName = $filePath . Settings::get("template_sk");
            $exportFilename = $satpenProfile->file[1]->nm_file;
            $qrPath  = $filePath. "qrcode.png";

            $tempExportFilenameSplit = pathinfo($exportFilename);
            $tempFilename = $filePath. $tempExportFilenameSplit['filename'].".docx";

            $templateDocument = new TemplateProcessor($templateName);

            if ($satpenProfile) {

                if (GenerateQr::make($satpenProfile->file[1]->qrcode, $qrPath)) {
                    /**
                     * Documents are looked up by mapfile instead of row order.
                     * PC/PW recommendation data is now optional because those
                     * fields are disabled on the registration/revision form.
                     */
                    $filePermohonan = FileRegister::findByMapfile($satpenProfile->filereg, 'surat_permohonan');
                    $fileSrtAset = FileRegister::findByMapfile($satpenProfile->filereg, 'surat_aset');
                    // $fileRekomPC = FileRegister::findByMapfile($satpenProfile->filereg, 'rekom_pc');
                    // $fileRekomPW = FileRegister::findByMapfile($satpenProfile->filereg, 'rekom_pw');

                    $templateDocument->setValue('nomor', $satpenProfile->no_urut);
                    $templateDocument->setValue('tahuntop', date('Y'));
                    $templateDocument->setValue('bulanromawi', Date::bulanRomawi($satpenProfile->file[1]->tgl_file));
                    $templateDocument->setValue('namasekolah', $filePermohonan->nm_lembaga ?? '');
                    $templateDocument->setValue('nosrtsatpen', $filePermohonan->nomor_surat ?? '');
                    $templateDocument->setValue('tglsuratsatpen', $filePermohonan ? Date::tglMasehi($filePermohonan->tgl_surat) : '');

                    $templateDocument->setValue('nmlembaga', $fileSrtAset->nm_lembaga ?? '');
                    $templateDocument->setValue('daerah', $fileSrtAset->daerah ?? '');
                    $templateDocument->setValue('nosurataset', $fileSrtAset->nomor_surat ?? '');
                    $templateDocument->setValue('tglsurataset', $fileSrtAset ? Date::tglMasehi($fileSrtAset->tgl_surat) : '');

                    // $templateDocument->setValue('nmlembagapc', $fileRekomPC->nm_lembaga ?? '');
                    // $templateDocument->setValue('pc', $fileRekomPC->daerah ?? '');
                    // $templateDocument->setValue('nosrtpc', $fileRekomPC->nomor_surat ?? '');
                    // $templateDocument->setValue('tglsrtpc', $fileRekomPC ? Date::tglMasehi($fileRekomPC->tgl_surat) : '');

                    // $templateDocument->setValue('nmlembagapw', $fileRekomPW->nm_lembaga ?? '');
                    // $templateDocument->setValue('pw', $fileRekomPW->daerah ?? '');
                    // $templateDocument->setValue('nosrtpw', $fileRekomPW->nomor_surat ?? '');
                    // $templateDocument->setValue('tglsrtpw', $fileRekomPW ? Date::tglMasehi($fileRekomPW->tgl_surat) : '');

                    $templateDocument->setValue('namasatpen', $satpenProfile->nm_satpen);
                    $templateDocument->setValue('alamat', $satpenProfile->alamat);
                    $templateDocument->setValue('kelurahan', $satpenProfile->kelurahan);
                    $templateDocument->setValue('kecamatan', $satpenProfile->kecamatan);
                    $templateDocument->setValue('kabupaten', $satpenProfile->kabupaten->nama_kab);
                    $templateDocument->setValue('propinsi', $satpenProfile->provinsi->nm_prov);
                    $templateDocument->setValue('nomorregistrasi', $satpenProfile->no_registrasi);
                    $templateDocument->setValue('kategori', $satpenProfile->kategori->nm_kategori);
                    $templateDocument->setValue('ketkategori', $satpenProfile->kategori->keterangan);

                    $templateDocument->setValue('tglm', Date::tglMasehi($satpenProfile->file[1]->tgl_file));
                    $templateDocument->setValue('tglh', Date::tglHijriyah($satpenProfile->file[1]->tgl_file));

                    $templateDocument->setValue('tembusanlembagapw', $fileRekomPW->nm_lembaga ?? '');
                    $templateDocument->setValue('propinsipw', $fileRekomPW->daerah ?? '');
                    $templateDocument->setValue('tembusanlembagapc', $fileRekomPC->nm_lembaga ?? '');
                    $templateDocument->setValue('kabupatenpc', $fileRekomPC->daerah ?? '');

                    // Replace the QR code placeholder with the actual QR code image in the template
                    $templateDocument->setImageValue('qrcode',  array('path' => $qrPath, 'width' => 150, 'height' => 150));

                    $templateDocument->saveAs($tempFilename);
//                    $templateDocument->saveAs($exportfilePath. $exportFilename);
                    //Convert to pdf
//                    $command = 'docx2pdf ' . escapeshellarg($tempFilename) . ' ' . escapeshellarg($exportfilePath. $exportFilename);
                    $command = "sudo /var/www/convertpdf.sh -i ". escapeshellarg($tempFilename) . ' -o ' . escapeshellarg($exportfilePath);

                    exec($command, $output, $returnCode);
//
                    if ($returnCode === 0) {
                        unlink($tempFilename);
                        unlink($qrPath);
                        return true;
                    } else {
                        echo 'Error converting Word document to PDF.';
                    }
                    return true;
                }
            }
            return false;

        } catch (\Exception $e) {
            throw new CatchErrorException("[MAKE SK DOKUMEN] has error ". $e);

        }

    }

}
