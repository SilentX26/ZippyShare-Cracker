<?php
namespace App\Library;

class ZippyShareCracker
{
    private $url;
    private $html;
    private $downloadId;
    private $urlDownload;

    function __construct($url)
    {
        $this->url = $url;
        $this->crack();
    }

    private function _getHtml()
    {
        $this->html = curl($this->url);
        if(!$this->html) {
            echo outputColor("Resource {$this->url} gagal didapatkan.", 'error');
            die;
        }
    }

    private function _getElementUrl()
    {
        $this->_getHtml();

        $elementUrl = substr($this->html, strpos($this->html, "document.getElementById('dlbutton').href"));
        $elementUrl = substr($elementUrl, 0, strpos($elementUrl, ';'));

        $this->html = $elementUrl;
        echo outputColor("Resource {$this->url} berhasil didapatkan!\n", 'alertSuccess');
    }

    private function _getDownloadID()
    {
        $this->_getElementUrl();

        preg_match('/\s\([\s\S]+\)/', $this->html, $downloadId);
        eval('$downloadId = ' .$downloadId[0]. ';');

        $this->downloadId = $downloadId;
    }

    private function _getUrlDownload()
    {
        $this->_getDownloadID();

        $elementUrlDownload = preg_replace('/\s\([\s\S]+\)/', $this->downloadId, $this->html);
        $urlDownload = preg_replace('/document.getElementById\(\'dlbutton\'\).href|["=\s\+]+/', '', $elementUrlDownload);

        $urlParsed = parse_url($this->url);
        $this->urlDownload = "{$urlParsed['scheme']}://{$urlParsed['host']}{$urlDownload}";
    }

    private function _getFileName()
    {
        $arrName = explode('/', $this->urlDownload);
        return end($arrName);
    }

    function crack()
    {
        $this->_getUrlDownload();

        echo outputColor("Mendownload resource..\n", 'alertSuccess');
        $fileName = $this->_getFileName();
        shell_exec("curl -o downloads/{$fileName} {$this->urlDownload}");
    }
}