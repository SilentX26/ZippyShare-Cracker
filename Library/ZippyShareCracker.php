<?php
/*
    * @ ZippyShare Cracker
    * @ Version 1.0
    * @ Created by Muhammad Randika Rosyid
*/
namespace App\Library;

class ZippyShareCracker
{
    // Property yang akan menampung URL resource
    private $url;

    // Property yang akan menampung source berupa HTML dari web zippyshare
    private $html;

    // Property yang akan menampung download ID
    private $downloadId;

    // Property yang akan menampung URL download yang sudah valid
    private $urlDownload;

    function __construct($url)
    {
        // Menetapkan resouce ke property url
        $this->url = $url;

        // Memanggil method crack() yang selanjutnya akan memproses resource
        $this->crack();
    }

    /*
        * method yang akan mendapatkan source HTML dari web zippyshare
        * @ Return type: Void
    */
    private function _getHtml()
    {
        $this->html = curl($this->url);
        // Mengecek apakah ada kesalahan pada saat mengirim permintaan?
        if(!$this->html) {
            // Jika ada, maka hentikan eksekusi script dengan menampilkan output error
            echo outputColor("Resource {$this->url} gagal didapatkan.", 'error');
            die;
        }
    }

    /*
        * method yang akan memproses source HTML yang udah didapatkan dari web zippyshare
        * method ini akan mencari element yang menyimpan URL download file dari source HTML yang sudah didapatkan
        * @ Return type: Void
    */
    private function _getElementUrl()
    {
        // Memanggil method _getHtml() untuk mendapatkan source HTML dari web zippyshare
        $this->_getHtml();

        // Mendapatkan element yang dicari, lalu menetapkannya pada property html
        $elementUrl = substr($this->html, strpos($this->html, "document.getElementById('dlbutton').href"));
        $elementUrl = substr($elementUrl, 0, strpos($elementUrl, ';'));

        $this->html = $elementUrl;
        echo outputColor("Resource {$this->url} berhasil didapatkan!\n", 'alertSuccess');
    }

    /*
        * method yang akan mendapatkan download ID dari web zippyshare
        * download ID ini yang akan berfungsi agar file dari web zippyshare dapat di download
        * download ID bersifat unik, maka tiap permintaan yang dikirim ke web zippyshare
        * akan menghasilkan download ID yang berbeda-beda
        * @ Return type: Void
    */
    private function _getDownloadID()
    {
        // Memanggil method _getElementUrl() untuk mendapatkan element yang menampung URL download
        $this->_getElementUrl();

        // Mencari download ID, lalu menetapkannya pada property downloadId
        preg_match('/\s\([\s\S]+\)/', $this->html, $downloadId);
        eval('$downloadId = ' .$downloadId[0]. ';');

        $this->downloadId = $downloadId;
    }

    /*
        * method yang akan memproses download ID yang sudah didapatkan
        * selanjutnya akan dibentuk menjadi URL download yang sudah fix
        * @ Return type: Void
    */
    private function _getUrlDownload()
    {
        // Memanggil method _getDownloadID() yang akan mencari download ID
        $this->_getDownloadID();
        
        // Merangkai URL download file, dan akan menetapkannya pada property urlDownload
        $elementUrlDownload = preg_replace('/\s\([\s\S]+\)/', $this->downloadId, $this->html);
        $urlDownload = preg_replace('/document.getElementById\(\'dlbutton\'\).href|["=\s\+]+/', '', $elementUrlDownload);

        $urlParsed = parse_url($this->url);
        $this->urlDownload = "{$urlParsed['scheme']}://{$urlParsed['host']}{$urlDownload}";
    }

    /*
        * method yang akan menghasilkan nama file dari URL resource yang dimiliki
        * @ Return type: String
    */
    private function _getFileName()
    {
        $arrName = explode('/', $this->urlDownload);
        return end($arrName);
    }

    /*
        * method ini akan dipanggil oleh constructor dan akan meneruskan permintaan
        * @ Return type: Void
    */
    function crack()
    {
        // Memanggil method _getUrlDownload() untuk memproses resource
        $this->_getUrlDownload();

        // Jika URL download yang sudah fix berhasil didapatkan
        // Maka selanjutnya ialah menjalankan perintah curl
        // Guna mendownload file dari web zippyshare
        echo outputColor("Mendownload resource..\n", 'alertSuccess');
        $fileName = $this->_getFileName();
        shell_exec("curl -o downloads/{$fileName} {$this->urlDownload}");
    }
}