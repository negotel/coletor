<?php

namespace Source\Support;

class ScanDirFile
{

    private $dir;
    protected $totalFile;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function scanneArquivo()
    {

        $result = array();
        $cdir = scandir($this->dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($this->dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }
        $this->totalFile = $result;
        return $result;
    }

    public function totalArquivo(){
        return count($this->totalFile);
    }
}
