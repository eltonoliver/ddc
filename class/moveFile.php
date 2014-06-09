<?php

class moveFile {

    public function versao_image($file_name, $options, $file_path) {
        $file_path = $file_path . $file_name;
        $new_file_path = $options['upload_dir'] . $file_name;
        list($img_width, $img_height) = @getimagesize($file_path);
        if (!$img_width || !$img_height) {
            return false;
        }
        $new_width = $options['max_width'];
        $new_height = $options['max_height'];
        $zoom_crop = $options['crop'];
        //nao passar do tamanho original
        $new_width = min($new_width, $img_width);
        $new_height = min($new_height, $img_height);
        //se nao cortar, redimensionar proporcional
        if (!$zoom_crop) {
            $scale = min(
                    $options['max_width'] / $img_width, $options['max_height'] / $img_height
            );
            if ($scale > 1) {
                $scale = 1;
            }
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
        }

        $new_img = @imagecreatetruecolor($new_width, $new_height);
        switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
            case 'jpg':
            case 'jpeg':
                $src_img = @imagecreatefromjpeg($file_path);
                $write_image = 'imagejpeg';
                break;
            case 'gif':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                $src_img = @imagecreatefromgif($file_path);
                $write_image = 'imagegif';
                break;
            case 'png':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                @imagealphablending($new_img, false);
                @imagesavealpha($new_img, true);
                $src_img = @imagecreatefrompng($file_path);
                $write_image = 'imagepng';
                break;
            default:
                $src_img = $image_method = null;
        }
        if ($zoom_crop > 0) {
            $src_x = $src_y = 0;
            $src_w = $img_width;
            $src_h = $img_height;

            $cmp_x = $img_width / $new_width;
            $cmp_y = $img_height / $new_height;

            // calculate x or y coordinate and width or height of source
            if ($cmp_x > $cmp_y) {
                $src_w = round($img_width / $cmp_x * $cmp_y);
                $src_x = round(($img_width - ($img_width / $cmp_x * $cmp_y)) / 2);
            } else if ($cmp_y > $cmp_x) {
                $src_h = round($img_height / $cmp_y * $cmp_x);
                $src_y = round(($img_height - ($img_height / $cmp_y * $cmp_x)) / 2);
            }

            // positional cropping!
            if ($align) {
                if (strpos($align, 't') !== false) {
                    $src_y = 0;
                }
                if (strpos($align, 'b') !== false) {
                    $src_y = $img_height - $src_h;
                }
                if (strpos($align, 'l') !== false) {
                    $src_x = 0;
                }
                if (strpos($align, 'r') !== false) {
                    $src_x = $img_width - $src_w;
                }
            }
            $success = $src_img && @imagecopyresampled($new_img, $src_img, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h) && $write_image($new_img, $new_file_path);
        } else {
            $success = $src_img && @imagecopyresampled($new_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height) && $write_image($new_img, $new_file_path);
        }
        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($src_img);
        @imagedestroy($new_img);
        //return $success;
    }

    public function move($file, $to, $id = null) {

        if (!$file || $file['error'] != 0) {
            return false;
        }

        $name = $this->montarNomeFile($file['name']);
        $to = $this->verificarDestino($to);
        $to .= $this->criarSubDir($id, $to);

        if (@move_uploaded_file($file['tmp_name'], $to . $name)) {
            return $name;
        }
        return false;
    }

    public function montarNomeFile($n) {
        $name = md5(uniqid()) .
                '_' . $this->removerAcentos(pathinfo($n, PATHINFO_FILENAME)) .
                '.' . pathinfo($n, PATHINFO_EXTENSION);
        return $name;
    }

    public function verificarDestino($to) {
        $t = $to;
        return $t = $t . (($t{strlen($t) - 1} == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR);
    }

    public function criarSubDir($id = null, $to = null) {
        if (!$id || !$to) {
            return '';
        }
        $t = $to;
        $s = '';
        for ($i = 0; $i < strlen($id); $i++) {
            $s .= ($d = substr($id, $i, 1) . DIRECTORY_SEPARATOR);
            $t .= $d;
            mkdir($t, 0755);
        }

        return $s;
    }

    public function removerAcentos($string = '') {
        $array1 = array(
            "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç",
            "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç",
            " ", "~", "^", "´", "`", "@", "#", "$", "%", "¨", "&", "*", "+", "'", "\"");
        $array2 = array(
            "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c",
            "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C",
            "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_", "_");
        return str_replace($array1, $array2, $string);
    }

    public function remover($filename, $id = null) {
        @unlink($filename);
        if ($id) {
            $t = $filename;
            $l = strlen($id);
            for ($i = 0; $i < $l; $i++) {
                $t = dirname($t) . DIRECTORY_SEPARATOR;
                if (file_exists($t) && count(scandir($t)) > 2) {
                    return true;
                }
                @rmdir($t);
            }
        }

        return true;
    }

    public function montarSubDir($id = null) {
        $s = '';
        $s .= $id . DIRECTORY_SEPARATOR;
        /* for ($i = 0; $i < strlen($id); $i++) {
          $s .= substr($id, $i, 1) . DIRECTORY_SEPARATOR;
          } */

        return $s;
    }

    public function montarSubDirUrl($id = null) {
        $s = '';
        $s .= $id . '/';
        /* for ($i = 0; $i < strlen($id); $i++) {
          $s .= substr($id, $i, 1) . '/';
          } */

        return $s;
    }

    public static function download($from, $file, $name = null, $id = null) {

        $f = new self();
        $filename = $f->verificarDestino($from) . $f->montarSubDir($id) . $file;

        //verifica se o arquivo existe
        if (!file_exists($filename)) {
            throw new Exception('File not found!.');
        }

        set_time_limit(0);
        //obtem o nome que vai aparecer para download
        $novoNome = ($name ? $name : pathinfo($filename, PATHINFO_FILENAME)) . '.' . pathinfo($filename, PATHINFO_EXTENSION);

        //iniciando cabeçalho para download

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $novoNome);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));

        readfile($filename);
        exit;
    }

}

?>