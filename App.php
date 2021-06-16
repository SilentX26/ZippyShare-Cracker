<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/
namespace App;

class App
{
    // property yang akan menampung data resource sesuai yang diinput user
    private $resource;

    function __construct()
    {
        // Menampilkan output "welcome"
        echo outputColor("ZippyShare Cracker\n", 'warning');
        echo outputColor("version: 1.0\n", 'warning');
        echo outputColor("Created by Muhammad Randika Rosyid\n", 'success');

        // Memanggil method init() yang selanjutnya akan mengeksekusi permintaan user
        $this->init();
    }

    /*
        * method yang akan memvalidasi resource yang diinput user
        * guna memastikan resource yang diinput ialah resource untuk domain zippyshare
        * @ Parameter: URL resource
        * @ Return type: Mixed
    */
    private function _resourceIsZippy($url)
    {
        $parsed_resource = parse_url($url);
        return preg_match('/zippyshare.com/i', $parsed_resource['host']);
    }

    /*
        * method yang akan meminta input resource kepada user
        * @ Return type: Void
    */
    private function _inputResource()
    {
        echo "\nMasukkan URL ataupun nama file anda\n";
        echo "input = ";
        $this->resource = input();
    }

    /*
        * method yang akan memproses resource yang diinput user
        * @ Return type: Void
    */
    private function _getResource()
    {
        // Memanggil method _inputResource() untuk meminta resource kepada user
        $this->_inputResource();
        
        // Mengecek apakah resource yang diinput berupa url?
        if(!validUrl($this->resource)) {
            // Mengecek apakah yang diinput user ini berupa nama file? (jika dalam kasus multi download)
            if(file_exists($this->resource)) {
                // Jika file ditemukan, maka load seluruh resource yang terdapat pada file
                $this->resource = file_get_contents($this->resource);
                $this->resource = explode("\n", $this->resource);

                $totalResource = count($this->resource);
                echo "\n{$totalResource} resource didapatkan.\n";

            } else {
                // Jika file tidak ditemukan, maka hentikan eksekusi script
                echo outputColor("File resource anda tidak dapat ditemukan!", 'error');
                die;
            }

        } else {
            // Jika iya, maka resource akan ditetapkan ke property "resource"
            $this->resource = array($this->resource);
            echo "\n1 resource didapatkan.\n";
        }
    }

    /*
        * method yang akan menangani alur berjalannya tools
        * method ini dipanggil oleh method constructor
        * @ Return type: Void
    */
    function init()
    {
        // Memanggil method _getResource() untuk mendapatkan resource dari user
        $this->_getResource();   
        echo outputColor("Memproses resource..", 'alertWarning');

        // Lakukan looping resource yang sudah valid
        foreach($this->resource as $key => $value) {
            echo "\n\n";
            // Membersihkan spasi maupun space kosong pada resource
            $value = trim($value);

            // Memastikan kembali, apakah resource yang diinput oleh user ialah domain zippyshare
            if($this->_resourceIsZippy($value) == 1) {
                // Jika valid, panggil class ZippyShareCracker
                callClass('App\Library\ZippyShareCracker', $value);
            } else {
                // Jika tidak valid, tampilkan output error
                echo outputColor("Resource {$value} tidak dapat diproses, pastikan URL yang anda masukkan ialah URL dari domain zippyshare.", 'error');
            }
        }
    }
}