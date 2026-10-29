<?php
namespace App\Helpers;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Illuminate\Support\Str;
use Throwable;

class GenerateQr {

    public static function make(string $content, $imagePath) {
        try {
            // Generate QR code image using endroid/qr-code library
            $writer = new PngWriter();
            // Create QR code
            $qrCode = QrCode::create($content)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
                ->setSize(130)
                ->setMargin(10)
                ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));

            // Create generic logo
//            $logo = Logo::create(public_path('assets/images/logos/rounded-logo.png'))
//                ->setResizeToWidth(60)
//                ->setPunchoutBackground(true);

//            $result = $writer->write($qrCode, $logo);
            $result = $writer->write($qrCode);
            $result->saveToFile($imagePath);

            return true;
        } catch (Throwable $e) {
            logger()->error('[QR] Gagal membuat QR code: ' . $e->getMessage());
            return false;
        }

    }

    public static function encodeQr() {
        return route('verify', str_replace("-", "", Str::uuid()));
    }


}
