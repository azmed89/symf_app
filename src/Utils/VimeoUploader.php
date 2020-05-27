<?php


namespace App\Utils;


use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Security\Core\Security;

class VimeoUploader implements UploaderInterface
{
    private $vimeoToken;
    public $file;

    public function __construct(Security $security)
    {
        $this->vimeoToken = $security->getUser()->getVimeoApiKey();
    }


    public function upload($file)
    {
        // Here you could for example call Vimeo API client from https://github.com/vimeo/vimeo.php and upload to
        // public folder videos stored on the same server as an application is

        // But in this case I'm using html form to upload videos
        // to Vimeo so I leave this method empty or return nothing
    }

    public function delete($path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.vimeo.com/videos/$path",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/vnd.vimeo.*+json;version=3.4",
                "Authorization: Bearer {$this->vimeoToken}",
                "Cache-control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err) {
            throw new ServiceUnavailableHttpException('Error. Try again later. Message: ' . $err);
        } else {
            return true;
        }
    }

    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    private function clear($string)
    {
        $string = preg_replace('/[^A-Za-z0-9- ]+/', '', $string);
        return $string;
    }

    
}