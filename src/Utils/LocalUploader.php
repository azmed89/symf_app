<?php


namespace App\Utils;


use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class LocalUploader implements UploaderInterface
{
    private $targetDirectory;
    public $file;

    public function __construct($target_directory)
    {
        $this->targetDirectory = $target_directory;
    }


    public function upload($file)
    {
        $video_number = random_int(1, 10000000);
        $fileName = $video_number . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {

        }
        $oryg_file_name = $this->clear(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        return [$fileName, $oryg_file_name];
    }

    public function delete($path)
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->remove('.' . $path);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while deleting your file at " . $exception->getPath();
        }

        return true;
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