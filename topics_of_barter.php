<?php

if(!empty($_FILES) && !empty($_FILES['my-file'])) {

    //path to load
    $path = 'img/';
    $tmp_path = 'tmp/';

    function resize($file)
    {
        global $tmp_path;

        $max_size = 600;
        if ($file['type'] == 'image/jpeg') {
            $source = imagecreatefromjpeg($file['tmp_name']);
            $extension = ".jpeg";
        }
        elseif ($file['type'] == 'image/png') {
            $source = imagecreatefrompng($file['tmp_name']);
            $extension = ".png";
        }
        elseif ($file['type'] == 'image/gif') {
            $source = imagecreatefromgif($file['tmp_name']);
            $extension = ".gif";
        }
        else
            return false;

        $w_src = imagesx($source);
        $h_src = imagesy($source);

        if ($w_src > $max_size)
        {
            // proportions
            $ratio = $w_src/$max_size;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);

            // create empty image
            $dest = imagecreatetruecolor($w_dest, $h_dest);

            // copy to empty image
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            imagejpeg($dest, $tmp_path.$file['name'], 75);
            imagedestroy($dest);
            imagedestroy($source);

            return $extension;
        }
        else
        {
            imagejpeg($source, $tmp_path . $file['name'], 75);
            imagedestroy($source);
            return $extension;
        }
    }

    $ext= resize( $_FILES['my-file']);

    if (!@copy($tmp_path.$_FILES['my-file']['name'], $path.count(scandir($path)).$ext))
        echo json_encode(array(
            'status' => "error",
        ));
    else
        echo json_encode(array(
            'status' => "ok",
        ));
    unlink($tmp_path.$name);
    // Sending JSON-encoded response

}

