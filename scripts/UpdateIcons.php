<?php

class UpdateIcons
{
    private const VARIANTS = [
        'outlined' => 'o-',
        'rounded' => 'r-',
        'sharp' => 's-'
    ];

    public function __construct()
    {
        $this->log('-- Updating icons...');

        $this->remove_old_icons();
        $this->copy_new_icons();
        $this->adapt_new_icons();

        $this->log('-- Done.');
    }

    private function remove_old_icons()
    {
        $this->log('-- -- Removing old icons...');

        $icons = glob(__DIR__ . '/../resources/svg/*.svg');
        foreach ($icons as $icon) {
            unlink($icon);
        }
    }

    private function copy_new_icons()
    {
        $this->log('-- -- Copying new icons...');

        foreach (self::VARIANTS as $variant => $prefix) {
            $icons = glob(__DIR__ . "/../node_modules/@material-symbols/svg-400/{$variant}/*.svg");
            foreach ($icons as $icon) {
                $newName = $prefix . basename($icon);
                copy($icon, __DIR__ . '/../resources/svg/' . $newName);
            }
        }
    }

    private function adapt_new_icons()
    {
        $this->log('-- -- Adapt new icons...');

        $icons = glob(__DIR__ . '/../resources/svg/*.svg');
        foreach ($icons as $icon) {
            // Get icon content
            $svgString = file_get_contents($icon);

            // Parse icon content
            $dom = new DOMDocument;
            $dom->loadXML($svgString);

            // Get svg element
            $svg = $dom->getElementsByTagName('svg')->item(0);

            $svg->setAttribute('fill', 'currentColor');
            // Supprimer les attributs width, height 
            $svg->removeAttribute('width');
            $svg->removeAttribute('height');

            // Save icon svg without namespace
            $newSvgString = $dom->saveXML($svg, LIBXML_NOXMLDECL);

            // Save icon
            file_put_contents($icon, $newSvgString);
        }
    }

    private function log($message)
    {
        echo $message . PHP_EOL;
    }
}

(new UpdateIcons);
