<?php
namespace Cloudonaut\Lib;

class Validator
{
    private $violationList;
    private $violationListByID;
    private $isViolated = false;

    //Can't be empty
    public function isNotEmpty($input, $errCode = '', $id = '')
    {
        if (trim($input) != "") {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    //No special characters used (Alpha numeric)
    public function isText($input, $errCode = '', $id = '')
    {
        if (ctype_alnum((string) $input)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    //Text Only
    public function isTextOnly($input, $errCode = '', $id = '')
    {
        if (ctype_alpha((string) $input)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    //Complex password check
    public function isComplex($input, $errCode = '', $id = '')
    {
        if (trim($input) != "") {
            #if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $input)) ==> Must have symbol to
            if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $input)) {
                return true;
            } else {
                $this->addViolation($errCode, $id);
                return false;
            }
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }
    //Complex password check
    public function isPassword($input, $errCode = '', $id = '')
    {
        if (trim($input) != "") {
            if (preg_match("#.*^(?=.{8,20})(?=.*[a-zA-Z])(?=.*[0-9]).*$#", $input)) {
                return true;
            } else {
                $this->addViolation($errCode, $id);
                return false;
            }
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }
    //Email validation
    public function isEmail($input, $errCode = '', $id = '')
    {
        //$result = preg_match('/^[A-z0-9_\-]+([.][A-z0-9_\-]+)*[@][A-z0-9_\-]+([.][A-z0-9_\-]+)*[.][A-z]{2,}$/', $input);
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    //A date
    public function isDate($input, $errCode = '', $id = '')
    {
        if ((strlen($input) < 10) or (strlen($input) > 10)) {
            $this->addViolation($errCode, $id);
            return false;
        }
        if ((substr_count($input, "-")) != 2) {
            $this->addViolation($errCode, $id);
            return false;
        }
        $day = substr($input, 8, 2);
        $month = substr($input, 5, 2);
        $year = substr($input, 0, 4);
        $dayresult = preg_match("/^[0-9]+$/", $day);
        $monthresult = preg_match("/^[0-9]+$/", $month);
        $yearresult = preg_match("/^[0-9]+$/", $year);
        if (!$dayresult || !$monthresult || !$yearresult) {
            $this->errors[] = $errCode;
            return false;
        }

        if ($month <= 0 || $month > 12) {
            $this->addViolation($errCode, $id);
            return false;
        }

        if ($day <= 0 || $day > 31) {
            $this->addViolation($errCode, $id);
            return false;
        }

        return true;
    }

    //Time
    public function isTime($input, $errCode = '', $id = '')
    {
        if ((strlen($input) < 8) or (strlen($input) > 8)) {
            $this->addViolation($errCode, $id);
            return false;
        }
        if ((substr_count($input, ":")) != 2) {
            $this->addViolation($errCode, $id);
            return false;
        }
        $uur = substr($input, 0, 2);
        $minuut = substr($input, 3, 2);
        $second = substr($input, 6, 2);
        $uurresult = preg_match("/^[0-9]+$/", $uur);
        $minuutresult = preg_match("/^[0-9]+$/", $minuut);
        $secondresult = preg_match("/^[0-9]+$/", $second);
        if (!$uurresult || !$minuutresult || !$secondresult) {
            $this->addViolation($errCode, $id);
            return false;
        }

        if ($uur < 0 || $uur > 23) {
            $this->addViolation($errCode, $id);
            return false;
        }

        if ($minuut < 0 || $minuut > 59) {
            $this->addViolation($errCode, $id);
            return false;
        }

        if ($second < 0 || $second > 59) {
            $this->addViolation($errCode, $id);
            return false;
        }

        return true;
    }
    //Numbers
    public function isNumber($input, $errCode = '', $id = '')
    {
        if (is_numeric($input)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isBetween($min, $max, $input, $errCode = '', $id = '')
    {
        if ((float) $input >= (float) $min && (float) $input <= (float) $max) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isDigital($input, $errCode = '', $id = '')
    {
        if (ctype_digit((string) $input)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isURL($input, $errCode = '', $id = '')
    {
        //$result = preg_match ('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', $input );
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isIP($input, $errCode = '', $id = '')
    {
        if (filter_var($input, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isFilename($input, $errCode = '', $id = '')
    {
        $result = preg_match('/^[^\\/\*\?\:\,]+$/', $input);
        if ($result) {
            return true;
        } else {
            $this->addViolation($errCode, $id);
            return false;
        }
    }

    public function isInList($input, $list, $errCode = '', $id = '')
    {
        if (is_array($list)) {
            foreach ($list as $l) {
                if ($l == $input) {
                    return true;
                }

            }
        }
        $this->addViolation($errCode, $id);
        return false;
    }

    public function addViolation($description, $id = '')
    {
        $this->isViolated = true;
        $this->violationList[] = $description;
        if ($id != '') {
            $this->violationListByID[$id][] = $description;
        }

    }

    public function hasViolations($ignore=false)
    {
        if (!$ignore) {
            if ($this->isViolated) {
                return true;
            }
        }
        return false;
    }

    public function getViolations()
    {
        return (isset($this->violationList) && is_array($this->violationList)) ? $this->violationList : array();
    }

    public function getViolationsById()
    {
        return (isset($this->violationListByID) && is_array($this->violationListByID)) ? $this->violationListByID : array();
    }
}
