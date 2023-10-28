<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    private $base64String = null; // base64String: data:image/jpeg;base64,/9j/4AAQSkZ/4gHYS...

    public function __construct($base64String = "")
    {
        $this->base64String = $base64String;
    }

    public function getRandomName($prefix = '')
    {
        $carbon = new Carbon();
        $date = $carbon->now()->format('Ymd');
        $time = $carbon->now()->format('His');
        $randomId = Str::random(10);
        $extFile = $this->getExt($this->base64String);

        if ($prefix !== '') {
            return "$prefix-$date-$time-$randomId.$extFile";
        } else {
            return "$date-$time-$randomId.$extFile";
        }
    }

    public function getExt()
    {
        $extension = explode('/', explode(':', substr($this->base64String, 0, strpos($this->base64String, ';')))[1])[1];
        return $extension; // jpg
    }

    public function getSizeInMB()
    {
        $kb = (int) strlen(base64_decode($this->base64String)) / 1024;
        $mb = $kb / 1024;
        return $mb;
    }

    public function getFileType()
    {
        $file_data = base64_decode($this->getFileString($this->base64String));
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $file_data, FILEINFO_MIME_TYPE);
        // $extension = explode('/', $mime_type)[1];
        $file_type = explode('/', $mime_type)[0];
        return $file_type; // image
    }

    public function getFileString()
    {
        // find substring from replace here eg: data:image/png;base64,
        $replace = substr($this->base64String, 0, strpos($this->base64String, ',') + 1);
        $base64stringfile = str_replace($replace, '', $this->base64String);
        $fileString = str_replace(' ', '+', $base64stringfile);
        return $fileString;
    }

    // store file into storage
    public function store($filePath)
    {
        $file = base64_decode($this->getFileString());
        Storage::disk('local')->put($filePath, $file);
    }

    public function delete($filePath)
    {
        Storage::disk('local')->delete($filePath);
    }

    public function fileExists($filePath)
    {
        return Storage::disk('local')->exists($filePath);
    }
}
