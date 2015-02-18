<?php
namespace Zone\Core\Component\Archive;

class Zipper extends \ZipArchive
{
    public function addDir($path, $base)
    {
        $nodes = glob($path . '/*');
        foreach ($nodes as $node) {
            if (is_dir($node)) {
                $this->addDir($node, $base);
            } else if (is_file($node)) {
                $vpath = str_replace($base, '', $node);
                $this->addFile($node, substr($vpath, 1, strlen($vpath)));
            }
        }
    }
}
