<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\Upload;


class Upload
{
    /**@var string $uploadPath*/
    private $uploadPath;
    /**@var array $allowedExtensions*/
    private $allowedTypes = [];
    /**@var array $maxSize*/
    private $maxSize = [
        'height' => 100,
        'width' => 100,
    ];
    
    /**
     * @param string $uploadPath
     * @param array|null $allowedTypes = null
     * @param array|null $maxSize = null
     */
    public function __construct($uploadPath, $allowedTypes = null, $maxSize = null)
    {
        $this->uploadPath = $uploadPath;
        
        if ($allowedTypes) {
            $this->allowedTypes = $allowedTypes;
        }
        
        if ($maxSize) {
            $this->maxSize = $maxSize;
        }
    }
    
    public function upload($file, $maxSize = null) 
    {
        $tmpName = $file['tmp_name'];

        if (count($this->allowedTypes) > 0) {
            if (!in_array($file['type'], $this->allowedTypes)) {
                return false;
            }
        }
        
        $newFileName = md5($file['name'] . time());
        
        $ext = substr($file['name'], strrpos($file['name'], '.'));
        
        $newFileName .= $ext;
        
        if (!$maxSize) {
            $maxSize = $this->maxSize;
        }
        
        $fileFullPath = APP_PATH . $this->uploadPath . $newFileName;
        
        if (move_uploaded_file($tmpName, $fileFullPath)) {
            list($width, $height) = getimagesize($fileFullPath);
            
            if ($width > $maxSize['width'] || $height > $maxSize['height']) {
                $this->resize($fileFullPath, $maxSize['width'], $maxSize['height'], $file['type']);
            }

            return $newFileName;
        } else {
            return false;
        }
    }
    
    public function remove($file)
    {
        $fullPath = APP_PATH . $this->uploadPath . $file;
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    
    /**
     * @param string $file
     * @param int $w
     * @param int $h
     * @param string $type
     * 
     * @return bool
     */
    private function resize($file, $w, $h, $type) {
        list($width, $height) = getimagesize($file);
        
        switch ($type) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $src = imagecreatefrompng($file);
                break;
            case 'image/gif':
                $src = imagecreatefromgif($file);
                break;
            default:
                return false;
        }
        
        $dst = imagecreatetruecolor($w, $h);
        
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        switch ($type) {
            case 'image/jpeg':
                imagejpeg($dst, $file, 100);
                break;
            case 'image/png':
                imagepng($dst, $file);
                break;
            case 'image/gif':
                imagegif($dst, $file);
                break;
            default:
                return false;
        }
        
        return true;
    }
}