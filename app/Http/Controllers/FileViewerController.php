<?php

namespace App\Http\Controllers;

use App\Models\BHPNU;

class FileViewerController extends Controller
{
    public function viewOSSDoc(string $path, string $fileName) {
        $fileName = $path.'/'.$fileName;
        if ($fileName) {
            $filepath = storage_path("app/oss-doc/". $fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }

    public function viewBuktiPembayaran(string $fileName) {
        if ($fileName) {
            $filepath = storage_path("app/bhpnu-doc/bukti-bayar/". $fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }

    /**
     * Tampilkan hasil watermark akta BHPNU. Parameter berupa id_bhpnu (bukan nama
     * file) supaya admin tidak bisa menebak dokumen permohonan lain lewat URL.
     */
    public function viewAktaBhpnu(int $bhpnuId) {
        return $this->serveAktaBhpnu($bhpnuId, false);
    }

    public function downloadAktaBhpnu(int $bhpnuId) {
        return $this->serveAktaBhpnu($bhpnuId, true);
    }

    private function serveAktaBhpnu(int $bhpnuId, bool $download) {
        $bhpnu = BHPNU::find($bhpnuId);

        if (!$bhpnu || !$bhpnu->akta_file) {
            return response("File is Processing");
        }

        $filepath = storage_path("app/bhpnu-doc/akta/". $bhpnu->akta_file);

        if (!file_exists($filepath)) {
            return response("File is Processing");
        }

        return $download
            ? response()->download($filepath)
            : response()->file($filepath);
    }

    public function viewNpwpLama(string $fileName) {
        if ($fileName) {
            $filepath = storage_path("app/coretax-doc/npwp-lama/". $fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }

    public function viewSkPtk(string $path, string $fileName) {
        $fileName = $path.'/'.$fileName;
        if ($fileName) {
            $filepath = storage_path("app/ptk-doc/". $fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }

    public function pdfUploadViewer(string $fileName) {
        if ($fileName) {
            $filepath = storage_path("app/uploads/".$fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }

    public function pdfGeneratedViewer(string $type=null, string $fileName=null) {
        if ($fileName && $type) {
            $filepath = storage_path("app/generated/".$type."/".$fileName);

            if (!file_exists($filepath)) return response("File not found!");
            return response()->file($filepath);
        }
        return response("Invalid Document!");
    }
}
