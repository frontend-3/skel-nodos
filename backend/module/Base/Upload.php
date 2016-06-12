<?php
namespace Base;


class Upload
{
    private $rdata = array();
    protected $sizes = array();
    private $dest_folder = NULL;

    public function getData(){
        return $this->rdata;
    }

    private function createPathUpload($absolute_path){
        if (!file_exists($absolute_path)) {
            mkdir($absolute_path, 0777, true);
        }
    }

    public function setFolder($dest_folder)
    {
        $this->dest_folder = $dest_folder;
    }

    public function getFolder()
    {
        return $this->dest_folder;
    }

    private function getExt($filename){
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function move($data_file, $dest_name) 
    {

        $id = uniqid();
        $dest_name = $dest_name.'-'.$id;

        $path = sprintf('upload/%s/', $this->getFolder());
        $absolute_path = sprintf('%s/public/%s', ROOT_PATH, $path);
        $this->createPathUpload($absolute_path);
        $file_ext = $this->getExt($data_file['name']);
        $new_name = sprintf('%s.%s', $dest_name, $file_ext);
        $to = sprintf('%s/%s', $absolute_path, $new_name);
        $upload_path = $path . $new_name;

        if (move_uploaded_file($data_file['tmp_name'], $to)) 
        {
            $data_img['path'] = sprintf('%s%s', $path, $new_name);
            list($width, $height) = getimagesize($to);
            $data_img['width'] = $width;
            $data_img['height'] = $height;
            $data_img['ext'] = $file_ext;
            $data_img['name'] = $dest_name;
            $data_img['size'] = filesize($to);
            $data_img['mime_type'] = $data_file['type'];
            $this->rdata = $data_img;
        }
    }

    public function cut()
    {
        $new_name = 'list.' . $file_ext;
        $save_path = $upload_path;
        $upload_path = $path . $new_name;
        @resize($save_path, $thumb[0], $thumb[1], $upload_path);
        list($width, $height) = getimagesize($upload_path);
        $data_img['thumb'] = array(
            'ext' => $file_ext, 
            'path' => $path_save . $new_name, 
            'height' => $height, 
            'width' => $width,
            'mime_type'=>$mime
        );
    }

    private function resize($img, $w, $h, $newfilename) 
    {
        //Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
            //trigger_error("GD is not loaded", E_USER_WARNING);
            return false;
        }
        //Get Image size info
        $imgInfo = getimagesize($img);
        switch ($imgInfo[2]) {
            case 1: $im = imagecreatefromgif($img);
                break;
            case 2: $im = imagecreatefromjpeg($img);
                break;
            case 3: $im = imagecreatefrompng($img);
                break;
            default:
                trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }
        $nWidth = round($w);
        $nHeight = round($h);
        $newImg = imagecreatetruecolor($nWidth, $nHeight);
        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($imgInfo[2] == 1) OR ( $imgInfo[2] == 3)) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
        switch ($imgInfo[2]) {
            case 1: imagegif($newImg, $newfilename, 100);
                break;
            case 2: imagejpeg($newImg, $newfilename, 100);
                break;
            case 3: imagepng($newImg, $newfilename);
                break;
            default: trigger_error('Failed resize image!', E_USER_WARNING);
                break;
        }
        return $newfilename;
    }
}

?>