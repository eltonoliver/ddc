<?php

$sess_name = session_name();
if (session_start()) {
    setcookie($sess_name, session_id(), null, '/', null, null, true);
    session_regenerate_id();
}
$arrayReferer = explode("/", $_SERVER['HTTP_REFERER']);
$arrayReferer = array_reverse($arrayReferer);
$referer = $arrayReferer[0];
if (!$_SESSION['ID'] or ($referer != "cad-arquivos" and substr($referer, 0, 27) != "cad-arquivos?noTopoRodape=1")) {
    echo "Você não tem permissão para acessar este arquivo!";
    exit;
} else {
    /*
     * jQuery File Upload Plugin PHP Example 5.2.9
     * https://github.com/blueimp/jQuery-File-Upload
     *
     * Copyright 2010, Sebastian Tschan
     * https://blueimp.net
     *
     * Licensed under the MIT license:
     * http://creativecommons.org/licenses/MIT/
     */

    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', false);

    class UploadHandler {

        private $options;

        function __construct($options = null) {
            $this->options = array(
                'script_url' => $_SERVER['PHP_SELF'],
                'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/temp/',
                'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/temp/',
                'param_name' => 'files',
                // The php.ini settings upload_max_filesize and post_max_size
                // take precedence over the following max_file_size setting:
                'max_file_size' => null,
                'min_file_size' => 1,
                //'accept_file_types' => '/.(jpg|png|gif|jpge|mp3|ogg|flv|wmv|mp4|mpge|zip|rar|pdf)/', //'/.+$/i',
                'accept_file_types' => array('jpg', 'png', 'gif', 'jpge', 'mp3', 'ogg', 'flv', 'wmv', 'mp4', 'mpge', 'zip', 'rar', 'pdf'),
                'max_number_of_files' => null,
                'discard_aborted_uploads' => true,
                'image_versions' => array(
                    //gera versoes
                    'image' => array(
                        'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/image/',
                        'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/image/',
                        'max_width' => 800,
                        'max_height' => 600,
                        'crop' => false
                    ),
                    /* 'galeria' => array(
                      'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/galeria/',
                      'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/galeria/',
                      'max_width' => 350,
                      'max_height' => 350,
                      'crop' => true
                      ),
                      'home' => array(
                      'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/home/',
                      'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/home/',
                      'max_width' => 350,
                      'max_height' => 350,
                      'crop' => true
                      ),
                      'miniatura' => array(
                      'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/miniaturas/',
                      'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/miniaturas/',
                      'max_width' => 200,
                      'max_height' => 200,
                      'crop' => true
                      ),
                      'desaparecidos_grande' => array(
                      'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/desaparecidos/big/',
                      'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/desaparecidos/big/',
                      'max_width' => 230,
                      'max_height' => 310,
                      'crop' => true
                      ),
                      'desaparecidos_pequeno' => array(
                      'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/desaparecidos/small/',
                      'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/desaparecidos/small/',
                      'max_width' => 140,
                      'max_height' => 190,
                      'crop' => true
                      ), */
                    'thumbnail' => array(
                        'upload_dir' => dirname(__FILE__) . '/arquivos/enviados/thumbnails/',
                        'upload_url' => dirname($_SERVER['PHP_SELF']) . '/arquivos/enviados/thumbnails/',
                        'max_width' => 80,
                        'max_height' => 80,
                        'crop' => true
                    )
                )
            );
            if ($options) {
                $this->options = array_replace_recursive($this->options, $options);
            }
        }

        private function get_file_object($file_name) {
            $file_path = $this->options['upload_dir'] . $file_name;
            if (is_file($file_path) && $file_name[0] !== '.') {
                $file = new stdClass();
                $file->name = $file_name;
                $file->size = filesize($file_path);
                $file->url = $this->options['upload_url'] . rawurlencode($file->name);
                foreach ($this->options['image_versions'] as $version => $options) {
                    if (is_file($options['upload_dir'] . $file_name)) {
                        $file->{$version . '_url'} = $options['upload_url']
                                . rawurlencode($file->name);
                    }
                }
                $file->delete_url = $this->options['script_url']
                        . '?file=' . rawurlencode($file->name);
                $file->delete_type = 'DELETE';
                return $file;
            }
            return null;
        }

        private function get_file_objects() {
            return array_values(array_filter(array_map(
                                    array($this, 'get_file_object'), scandir($this->options['upload_dir'])
            )));
        }

        private function create_scaled_image($file_name, $options) {
            $file_path = $this->options['upload_dir'] . $file_name;
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
            /* $success = $src_img && @imagecopyresampled(
              $new_img,
              $src_img,
              0, 0, 0, 0,
              $new_width,
              $new_height,
              $img_width,
              $img_height
              ) && $write_image($new_img, $new_file_path); */
            // Free up memory (imagedestroy does not delete files):
            @imagedestroy($src_img);
            @imagedestroy($new_img);
            return $success;
        }

        private function has_error($uploaded_file, $file, $error) {
            if ($error) {
                return $error;
            }
            /* if (!preg_match($this->options['accept_file_types'], strtolower($file->name))) {
              return 'acceptFileTypes';
              } */
            $nomeArquivo = explode(".", strtolower($file->name));
            $nomeArquivo = array_reverse($nomeArquivo);
            $extensaoArquivo = $nomeArquivo[0];
            if (!in_array($extensaoArquivo, $this->options['accept_file_types'])) {
                return 'acceptFileTypes';
            }
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                $file_size = filesize($uploaded_file);
            } else {
                $file_size = $_SERVER['CONTENT_LENGTH'];
            }
            if ($this->options['max_file_size'] && (
                    $file_size > $this->options['max_file_size'] ||
                    $file->size > $this->options['max_file_size'])
            ) {
                return 'maxFileSize';
            }
            if ($this->options['min_file_size'] &&
                    $file_size < $this->options['min_file_size']) {
                return 'minFileSize';
            }
            if (is_int($this->options['max_number_of_files']) && (
                    count($this->get_file_objects()) >= $this->options['max_number_of_files'])
            ) {
                return 'maxNumberOfFiles';
            }
            return $error;
        }

        private function trim_file_name($name, $type) {
            // Remove path information and dots around the filename, to prevent uploading
            // into different directories or replacing hidden system files.
            // Also remove control characters and spaces (\x00..\x20) around the filename:
            $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");
            // Add missing file extension for known image types:
            if (strpos($file_name, '.') === false &&
                    preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
                $file_name .= '.' . $matches[1];
            }
            return $file_name;
        }

        private function limpaStringArquivo($string) {

            $novaString = str_replace(' ', '_', $string);
            $novaString = str_replace(',', '', $novaString);
            $novaString = str_replace('/', '', $novaString);
            $novaString = str_replace('\\', '', $novaString);
            $novaString = str_replace('-', '', $novaString);
            $novaString = str_replace('(', '', $novaString);
            $novaString = str_replace(')', '', $novaString);
            $novaString = str_replace(',', '', $novaString);
            $novaString = str_replace('+', '', $novaString);
            //$novaString = str_replace('.','',$novaString);
            $novaString = str_replace(';', '', $novaString);

            return $novaString;
        }

        private function renomearArquivo($string) {
            $string = $this->limpaStringArquivo($string);
            $busca = array('Á', 'À', 'Ã', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ú', 'Ç', 'á', 'à', 'ã', 'é', 'ê', 'í', 'ó', 'õ', 'ú', 'ç');
            $retira = array('A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'U', 'C', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'u', 'c');
            $string = str_replace($busca, $retira, $string);
            setlocale(LC_CTYPE, "pt_BR");
            $novaString = trim(strtolower($string));
            return $novaString;
        }

        private function handle_file_upload($uploaded_file, $name, $size, $type, $error) {
            $file = new stdClass();
            $file->name = md5(uniqid()) . '_' . $this->trim_file_name($this->renomearArquivo($name), $type); //upgrade para fazer upload com nome unico
            $file->size = intval($size);
            $file->type = $type;
            $error = $this->has_error($uploaded_file, $file, $error);
            if (!$error && $file->name) {
                $file_path = $this->options['upload_dir'] . $file->name;
                $append_file = !$this->options['discard_aborted_uploads'] &&
                        is_file($file_path) && $file->size > filesize($file_path);
                clearstatcache();
                if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                    // multipart/formdata uploads (POST method uploads)
                    if ($append_file) {
                        file_put_contents(
                                $file_path, fopen($uploaded_file, 'r'), FILE_APPEND
                        );
                    } else {
                        move_uploaded_file($uploaded_file, $file_path);
                    }
                } else {
                    // Non-multipart uploads (PUT method support)
                    file_put_contents(
                            $file_path, fopen('php://input', 'r'), $append_file ? FILE_APPEND : 0
                    );
                }
                $file_size = filesize($file_path);
                if ($file_size === $file->size) {
                    $file->url = $this->options['upload_url'] . rawurlencode($file->name);
                    foreach ($this->options['image_versions'] as $version => $options) {
                        if ($this->create_scaled_image($file->name, $options)) {
                            $file->{$version . '_url'} = $options['upload_url']
                                    . rawurlencode($file->name);
                        }
                    }
                } else if ($this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = 'abort';
                }
                $file->size = $file_size;
                $file->delete_url = $this->options['script_url']
                        . '?file=' . rawurlencode($file->name);
                $file->delete_type = 'DELETE';
            } else {
                $file->error = $error;
            }
            return $file;
        }

        public function get() {
            $file_name = isset($_REQUEST['file']) ?
                    basename(stripslashes($_REQUEST['file'])) : null;
            if ($file_name) {
                $info = $this->get_file_object($file_name);
            } else {
                $info = $this->get_file_objects();
            }
            header('Content-type: application/json');
            echo json_encode($info);
        }

        public function post() {
            $upload = isset($_FILES[$this->options['param_name']]) ?
                    $_FILES[$this->options['param_name']] : null;
            $info = array();
            if ($upload && is_array($upload['tmp_name'])) {
                foreach ($upload['tmp_name'] as $index => $value) {
                    $info[] = $this->handle_file_upload(
                            $upload['tmp_name'][$index], isset($_SERVER['HTTP_X_FILE_NAME']) ?
                                    $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index], isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                                    $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index], isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                                    $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index], $upload['error'][$index]
                    );
                }
            } elseif ($upload) {
                $info[] = $this->handle_file_upload(
                        $upload['tmp_name'], isset($_SERVER['HTTP_X_FILE_NAME']) ?
                                $_SERVER['HTTP_X_FILE_NAME'] : $upload['name'], isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                                $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'], isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                                $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'], $upload['error']
                );
            }
            header('Vary: Accept');
            if (isset($_SERVER['HTTP_ACCEPT']) &&
                    (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-type: application/json');
            } else {
                header('Content-type: text/plain');
            }
            echo json_encode($info);
        }

        public function delete() {
            $file_name = isset($_REQUEST['file']) ?
                    basename(stripslashes($_REQUEST['file'])) : null;
            $file_path = $this->options['upload_dir'] . $file_name;
            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
            if ($success) {
                foreach ($this->options['image_versions'] as $version => $options) {
                    $file = $options['upload_dir'] . $file_name;
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            header('Content-type: application/json');
            echo json_encode($success);
        }

    }

    $upload_handler = new UploadHandler();

    header('Pragma: no-cache');
    header('Cache-Control: private, no-cache');
    header('Content-Disposition: inline; filename="files.json"');
    header('X-Content-Type-Options: nosniff');

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'HEAD':
        case 'GET':
            $upload_handler->get();
            break;
        case 'POST':
            $upload_handler->post();
            break;
        case 'DELETE':
            $upload_handler->delete();
            break;
        case 'OPTIONS':
            break;
        default:
            header('HTTP/1.0 405 Method Not Allowed');
    }
}
?>