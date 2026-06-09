<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class HtmlToImageService
{
    public function convert(string $html, string $prefix = 'html', int $width = 794, int $height = 1123): string
    {
        $directory = storage_path('app/booking-pdf');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $tempPath = $directory . '/' . $prefix . '_' . time() . '.png';

        $chromePath = $this->findChromePath();
        if (! $chromePath) {
            throw new \RuntimeException('Chrome/Chromium browser not found. Please install Google Chrome.');
        }

        Browsershot::html($html)
            ->setChromePath($chromePath)
            ->setNodeModulePath(base_path('node_modules'))
            ->windowSize($width, $height)
            ->deviceScaleFactor(1)
            ->waitUntilNetworkIdle()
            ->delay(500)
            ->clip(0, 0, $width, $height)
            ->save($tempPath);

        Log::info('HTML converted to image', ['file' => $tempPath]);

        return $tempPath;
    }

    public function toDataUri(?string $path): string
    {
        if (! $path || ! file_exists($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    private function findChromePath(): ?string
    {
        $possiblePaths = [
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Chromium\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Chromium\\Application\\chrome.exe',
            '/usr/bin/chromium-browser',
            '/usr/bin/google-chrome',
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path) || is_executable($path)) {
                return $path;
            }
        }

        return null;
    }
}
