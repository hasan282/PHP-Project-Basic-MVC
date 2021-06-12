<?php

class upload
{
    private $file_extension = array();
    private $FileLocation;
    private $FileSizeLimit;
    private $ErrorMessage;
    private $UploadResult;

    public function __construct($location = IMG_LOCATION, $extension = FILE_EXTENSION)
    {
        $this->FileLocation = $location . '/';
        $this->file_extension = explode('|', $extension);
        $this->FileSizeLimit = LIMIT_FILESIZE;
    }

    public function upload($File)
    {
        $FileSize = $this->_FileSize($File);
        $file_extension = $this->_CheckExtension($File);
        if ($FileSize == 0) {
            $this->UploadResult = array('status' => 1, 'file' => null);
        } else {
            $NewName = 'IMG_' . date('YmdHis') . '.' . $file_extension['ext'];
            $FileTarget = $this->FileLocation . $NewName;
            $ErrorCheck = $this->_UploadErrorCheck($FileTarget, $FileSize, $file_extension['status']);
            if ($ErrorCheck) {
                $this->UploadResult = array('status' => 0, 'error' => $this->ErrorMessage);
            } else {
                if (move_uploaded_file($File['tmp_name'], $FileTarget)) {
                    $this->UploadResult = array('status' => 1, 'file' => $NewName);
                } else {
                    $this->UploadResult = array('status' => 0, 'error' => 'failed');
                }
            }
        }
        return $this->UploadResult;
    }

    private function _UploadErrorCheck($FileLoc, $FileSize, $FileExt)
    {
        $UploadError = false;
        if (file_exists($FileLoc)) {
            $this->ErrorMessage = 'exist';
            $UploadError = true;
        }
        if ($FileSize > $this->FileSizeLimit) {
            $this->ErrorMessage = 'over';
            $UploadError = true;
        }
        if (!$FileExt) {
            $this->ErrorMessage = 'unmatch';
            $UploadError = true;
        }
        return $UploadError;
    }

    public function DeleteFile($File)
    {
        unlink($this->FileLocation . $File);
    }

    public function ReplaceFile($OldFile, $NewFile)
    {
        $UpResult = $this->upload($NewFile);
        if ($UpResult['status'] > 0) {
            if ($UpResult['file'] != null) {
                $ReplaceResult = array('status' => 1, 'file' => $UpResult['file']);
                $this->DeleteFile($OldFile);
            } else {
                $ReplaceResult = array('status' => 1, 'file' => null);
            }
        } else {
            $ReplaceResult = array('status' => 0, 'error' => $UpResult['error']);
        }
        return $ReplaceResult;
    }

    private function _FileSize($File)
    {
        return $File['size'];
    }

    private function _CheckExtension($File)
    {
        $name_split = explode('.', $File['name']);
        $extension = strtolower(end($name_split));
        $is_true_ext = false;
        foreach ($this->file_extension as $ext) {
            if ($ext == $extension) {
                $is_true_ext = true;
            }
        }
        $result = array('status' => $is_true_ext, 'ext' => $extension);
        return $result;
    }
}
