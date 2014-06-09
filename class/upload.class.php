<?php
/*
Upload Class 0.2 By ming0070913
Capable with both <input type="file" name="file1" /> or <input type="file" name="file[]" />
Functions:
    add($files):
        add files, usually by add($_FILES)
    upload($destination, $overwrite=true):
        files with errors will not be uploaded
        upload all to $destination
        overwrite if $overwrite true
    checkSize($max, $min=0):
        check files that size>$max or size<$min
        set $min to negative if you want to neglect the lower limit
    checkMIME($mime, $allow=true, $allow_no_type = false):
        check files' mime for:
            $allow=true: allow mime typse = $mime only
            $allow=false: allow mime types NOT = $mime only
        allow files don't have mime type if $allow_no_type true
    checkExt($ext, $allow=true, $allow_no_ext = false):
        check files' extension for:
            $allow=true: allow extensions = $ext only
            $allow=false: allow extensions NOT = $ext only
        allow files don't have extension if $allow_no_ext true
    getName():
        get all the files' id and name
    getSize():
        get all the files' id and size
    getType():
        get all the files' id and type
    getTotalSize():
        get the total size of all files
    markError($ids, $type):
        mark a error on one or more than one files that id in $ids(array/int)
    get($error=NULL):
        get all the infomation of all files that waiting to upload for:
            $error=NULL: all files
            $error=true: all files contain error
            $error=false: all files don't contain any error
    getUploaded($error=NULL):
        get all the uploaded files' infomation for:
            $error=NULL: all files
            $error=true: all files contain error
            $error=false: all files don't contain any error
    show($error=NULL):
        show all the upload files' infomation for:
            $error=NULL: all files
            $error=true: all files contain error
            $error=false: all files don't contain any error
        It's usually used for developing only
Error types:
    -Default in PHP(REF: http://php.net/manual/en/features.file-upload.errors.php)
        0: There is no error, the file uploaded with success.
        1: The uploaded file exceeds the upload_max_filesize directive in php.ini.
        2: The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
        3: The uploaded file was only partially uploaded.
        4: No file was uploaded.
        6: Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
        7: Failed to write file to disk. Introduced in PHP 5.1.0.
        8: A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.
    -upload
        10: file already exists and $overwrite = false
        11: removing exist file failed
        12: upload failed
    -checkSize
        13: file is too small
        14: file is empty
        15: file is too large
    -checkMIME
        16: file has no mime type
        17: file's mime type forbidden
    -checkExt
        18: file has no extension
        19: file's extension forbidden
*/
Class UploadClass {
    protected $files, $uploaded, $encode;

    function __construct($encode="BIG5") {
        $this->encode = $encode;
    }

    public function add($files) {
        if(!$files) return false;
        $ok = true;
        foreach($files as $name=>$file) {
            if(is_array($file['name'])) {
                $i = 0;
                while($i<count($file['name'])) {
                    $c = count($this->files);
                    preg_match("/\.([^\.]+)$/i", $file['name'][$i], $e);
                    $this->files[$c]['name'] = $file['name'][$i];
                    $this->files[$c]['type'] = $file['type'][$i];
                    $this->files[$c]['extension'] = strtolower($e[1]);
                    $this->files[$c]['tmp_name'] = $file['tmp_name'][$i];
                    $this->files[$c]['error'] = array($file['error'][$i]);
                    $this->files[$c]['size'] = $file['size'][$i];
                    $i++;
                }
            } else {
                $c = count($this->files);
                preg_match("/\.([^\.]+)$/i", $file['name'], $e);
                $this->files[$c] = $file;
                $this->files[$c]['extension'] = $e[1];
            }
        }
        return $ok;
    }

    public function upload($destination, $overwrite=true) {
        if(substr($destination, -1)!="/" || substr($destination, -1)!="\\") $destination .= "/";
        $ok = true;
        foreach($this->files as $id=>$file){
            if($file['error'][0]!=0) continue;
            $path = $destination.$file["name"];
            if(file_exists($path)){
                if(!$overwrite){
                    $this->addError($id, 10);
                    $ok = false;
                    continue;
                }elseif(!@unlink($path)){
                    $this->addError($id, 11);
                    $ok = false;
                    continue;
                }
            }
            if(!@move_uploaded_file($file['tmp_name'], $path)){
                $this->addError($id, 12);
                continue;
            }
            $c = count($this->uploaded);
            $this->uploaded[$c] = $file;
            $this->uploaded[$c]['path'] = $path;
            @unlink($file['tmp_name']);
        }
        return $ok;
    }

    public function checkSize($max, $min=0) {
        //$min = -1 to ignore the lower limit
        $ok = true;
        foreach($this->files as $id=>$file) {
            if($file['size']<$min){
                $this->addError($id, 13);
                $ok = false;
                continue;
            } elseif($min==0 && $file['size']==0) {
                $this->addError($id, 14);
                $ok = false;
                continue;
            } elseif($file['size']>$max) {
                $this->addError($id, 15);
                $ok = false;
                //continue;
            }
        }
        return $ok;
    }

    public function checkMIME($mime, $allow=true, $allow_no_type = false) {
        //$allow true: allow $mime only, else, forbid $mime only
        if(!$mime) return true;
        $ok = true;
        foreach($this->files as $id=>$file) {
            if(!$allow_no_type && $file['type']==NULL) {
                $this->addError($id, 16);
                $ok = false;
                continue;
            }
            if((($allow?1:0)+(in_array(strtolower($file['type']), $mime)?1:0))==1) {
                $this->addError($id, 17);
                $ok = false;
                //continue;
            }
        }
        return $ok;
    }

    public function checkExt($ext, $allow=true, $allow_no_ext = false) {
        //$allow true: allow $ext only, else, forbid $ext only
        if(!$ext) return true;
        $ok = true;
        foreach($this->files as $id=>$file){
            if(!$allow_no_ext && $file['extension']==NULL) {
                $this->addError($id,18);
                $ok = false;
                continue;
            }
            if((($allow?1:0)+(in_array($file['extension'], $ext)?1:0))==1) {
                $this->addError($id,19);
                $ok = false;
                //continue;
            }
        }
        return $ok;
    }

    public function getName() {
        foreach($this->files as $id=>$file) $t[$id] = $file['name'];
        return $t;
    }

    public function getExt() {
        foreach($this->files as $id=>$file) $t[$id] = $file['extension'];
        return $t;
    }

    public function getType() {
        foreach($this->files as $id=>$file) $t[$id] = $file['type'];
        return $t;
    }

    public function getSize() {
        foreach($this->files as $id=>$file) $t[$id] = $file['size'];
        return $t;
    }

    public function getTotalSize() {
        foreach($this->files as $id=>$file) $t += $file['size'];
        return $t;
    }

    public function markError($ids, $type) {
        if(!is_array($ids)) $ids = array($ids);
        $t = $this->files;
        foreach($ids as $id) $this->addError($id, $type);
        if($this->files==$t) return false;//nothing changed
        return true;
    }

    public function get($error=NULL) {
        if($error==NULL) return $this->files;
        foreach($this->files as $id=>$file){
            if($error===true && $file['error'][0]==0) continue;//error only
            elseif($error===false && $file['error']!=0) continue;
            $t[$id] = $file;
        }
        return $t;
    }

    public function getUploaded($error=NULL) {
        if($error==NULL) return $this->uploaded;
        foreach($this->uploaded as $id=>$file){
            if($error===true && $file['error'][0]==0) continue;//error only
            elseif($error===false && $file['error']!=0) continue;
            $t[$id] = $file;
        }
        return $t;
    }

    public function show($error=NULL) {
        foreach($this->files as $file){
            if($error===true && $file['error'][0]==0) continue;//error only
            elseif($error===false && $file['error'][0]!=0) continue;
            $t .= "Upload: ".$file["name"]."\n";
            $t .= "Type: ".$file["type"]."\n";
            $t .= "Extension: ".$file["extension"]."\n";
            $t .= "Size: ".$file["size"]." b\n";
            $t .= "Stored in: ".$file["tmp_name"]." \n";
        }
        return $t;
    }

    private function addError($id, $type) {
        if($this->files[$id]['error'][0]==0) $this->files[$id]['error'] = array($type);
        else $this->files[$id]['error'][] = $type;
    }
} ?>