<?php

require __DIR__ . '/../vendor/autoload.php';

use FontLib\Font;

$fontDir = realpath(__DIR__ . '/../resources/fonts');
$fonts = [
    'noto sans devanagari' => [
        'normal' => 'NotoSansDevanagari-Regular.ttf',
        'bold' => 'NotoSansDevanagari-Bold.ttf',
    ],
];

$installed = [];
if (is_readable($fontDir . '/installed-fonts.json')) {
    $installed = json_decode(file_get_contents($fontDir . '/installed-fonts.json'), true) ?: [];
}

foreach ($fonts as $family => $variants) {
    foreach ($variants as $variant => $ttfFile) {
        $ttfPath = $fontDir . DIRECTORY_SEPARATOR . $ttfFile;
        if (!is_readable($ttfPath)) {
            fwrite(STDERR, "Missing font file: {$ttfPath}\n");
            exit(1);
        }

        $hash = md5_file($ttfPath);
        $baseName = str_replace(' ', '_', $family) . "_{$variant}_{$hash}";
        $targetBase = $fontDir . DIRECTORY_SEPARATOR . $baseName;
        $ufmPath = $targetBase . '.ufm';

        if (!file_exists($ufmPath)) {
            $font = Font::load($ttfPath);
            $font->parse();
            $font->saveAdobeFontMetrics($ufmPath);
            $font->close();
            echo "Generated: {$ufmPath}\n";
        } else {
            echo "Exists: {$ufmPath}\n";
        }

        if (!file_exists($targetBase . '.ttf')) {
            copy($ttfPath, $targetBase . '.ttf');
            echo "Copied: {$targetBase}.ttf\n";
        }

        $installed[$family][$variant] = $baseName;
    }
}

file_put_contents(
    $fontDir . '/installed-fonts.json',
    json_encode($installed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo "Updated installed-fonts.json\n";
