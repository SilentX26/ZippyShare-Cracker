<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/
namespace App;

use App\Library\ClicoText;
use App\Library\ZippyShareCracker;

class App
{
    private $resource;

    function __construct()
    {
        echo outputColor("ZippyShare Cracker\n", 'warning');
        echo outputColor("version: 1.0\n", 'warning');
        echo outputColor("Created by Muhammad Randika Rosyid\n", 'success');

        $this->init();
    }

    private function _resourceIsZippy($url)
    {
        $parsed_resource = parse_url($url);
        return preg_match('/zippyshare.com/i', $parsed_resource['host']);
    }

    private function _inputResource()
    {
        echo "\nMasukkan URL ataupun nama file anda\n";
        echo "input = ";
        $this->resource = input();
    }

    private function _getResource()
    {
        $this->_inputResource();
        
        if(!validUrl($this->resource)) {
            if(file_exists($this->resource)) {
                $this->resource = file_get_contents($this->resource);
                $this->resource = explode("\n", $this->resource);

                $totalResource = count($this->resource);
                echo "\n{$totalResource} resource didapatkan.\n";

            } else {
                echo outputColor("File resource anda tidak dapat ditemukan!", 'error');
                die;
            }

        } else {
            $this->resource = array($this->resource);
            echo "\n1 resource didapatkan.\n";
        }
    }

    function init()
    {
        $this->_getResource();   
        echo outputColor("Memproses resource..", 'alertWarning');

        foreach($this->resource as $key => $value) {
            echo "\n\n";
            $value = trim($value);

            if($this->_resourceIsZippy($value) == 1) {
                callClass('App\Library\ZippyShareCracker', $value);
            } else {
                echo outputColor("Resource {$value} tidak dapat diproses, pastikan URL yang anda masukkan ialah URL dari domain zippyshare.", 'error');
            }
        }
    }
}