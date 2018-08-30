<?php
require_once 'SimpleImage.php';

if (!function_exists("getMessage")) {

    function getMessage($sub = '')
    {
        $msg = "";
        if (!empty($sub) && !empty($_SESSION[$sub]['message'])) {
            $msg = '<div class="alert alert-success  fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button> ' . $_SESSION[$sub]['message'] . '</div>';
            $_SESSION[$sub]['message'] = "";
        }// if end.
        if (empty($sub) && !empty($_SESSION['message'])) {
            $msg = '<div class="alert alert-success fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button> ' . $_SESSION['message'] . '</div>';
            $_SESSION['message'] = "";
        }// if end.
        if (!empty($sub) && !empty($_SESSION[$sub]['error'])) {
            $msg = '<div class="alert alert-danger fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button> ' . $_SESSION[$sub]['error'] . '</div>';
            $_SESSION[$sub]['error'] = "";
        }// if end.
        if (empty($sub) && !empty($_SESSION['error'])) {
            $msg = '<div class="alert alert-danger fade in"> <button type="button" class="close" data-dismiss="alert">&times;</button> ' . $_SESSION['error'] . '</div>';
            $_SESSION['error'] = "";
        }// if end.
        return $msg;
    }

}
if (!function_exists("generatePassword")) {

    function generatePassword($length = 8)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        //'0123456789``-=~!@#$%^&*()_+,./<>?;:[]{}\|';
        $str = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++)
            $str .= $chars[rand(0, $max)];
        return $str;
    }

}

if (!function_exists("generateOTP")) {

    function generateOTP($length = 6)
    {
        $chars = '0123456789';
        $str = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++)
            $str .= $chars[rand(0, $max)];
        return $str;
    }

}

if (!function_exists("dateDB2SHOW")) {

    function dateDB2SHOW($db_date = "", $format = "", $display = "")
    {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            if (!empty($format)) {
                return date($format, $db_date);
            }
            return date("d/m/Y", $db_date);
        }
        return $display;
    }
}
if (!function_exists("dateRangeDB2SHOW")) {

    function dateRangeDB2SHOW($db_date = "", $format = "", $display = "")
    {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            if (!empty($format)) {
                return date($format, $db_date);
            }
            return date("d-m-Y", $db_date);
        }
        return $display;
    }
}
if (!function_exists("dateNameDB2SHOW")) {
    function dateNameDB2SHOW($db_date = "", $display = "")
    {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            return date("d M Y", $db_date);
        }
        return $display;
    }
}
if (!function_exists("timeDB2SHOW")) {

    function timeDB2SHOW($db_date = "", $display = "")
    {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            return date("h:m A", $db_date);
        }
        return $display;
    }
}
if (!function_exists("dateTimeDB2SHOW")) {
    function dateTimeDB2SHOW($db_date = "", $format = "", $display = "")
    {
        if (!empty($db_date) && $db_date != "0000-00-00" && $db_date != "0000-00-00 00:00:00") {
            $db_date = strtotime($db_date);
            if (!empty($format)) {
                return date($format, $db_date);
            } else {
                return date("d/m/Y h:i A", $db_date);
            }
        }
        return $display;
    }

}
if (!function_exists("dateForm2DB")) {

    function dateForm2DB($frm_date)
    {
        $frm_date = explode("/", $frm_date);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            return $frm_date[2] . "-" . $frm_date[1] . "-" . $frm_date[0];
        } else {
            return '';
        }
    }

}
if (!function_exists("dateRangeForm2DB")) {

    function dateRangeForm2DB($frm_date)
    {
        $frm_date = explode("-", $frm_date);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            return $frm_date[2] . "-" . $frm_date[1] . "-" . $frm_date[0];
        } else {
            return '';
        }
    }
}
if (!function_exists("dateTimeForm2DB")) {

    function dateTimeForm2DB($frm_date)
    {
        $frm_date_time = explode(" ", $frm_date);
        $frm_date = explode("/", $frm_date_time[0]);
        $frm_time = explode(":", $frm_date_time[1]);
        if (!empty($frm_date[0]) && !empty($frm_date[1]) && !empty($frm_date[2])) {
            if (!isset($frm_time[0]))
                $frm_time[0] = "00";
            if (!isset($frm_time[1]))
                $frm_time[1] = "00";
            if (!isset($frm_time[2]))
                $frm_time[2] = "00";
            if (!empty($frm_date_time[2]) && $frm_date_time[2] == "AM" && $frm_time[0] == 12) {
                $frm_time[0] = "00";
            } else if (!empty($frm_date_time[2]) && $frm_date_time[2] == "PM" && $frm_time[0] < 12) {
                $frm_time[0] = (int)$frm_time[0];
                $frm_time[0] = $frm_time[0] + 12;
            }
            return $frm_date[2] . "-" . $frm_date[1] . "-" . $frm_date[0] . " " . $frm_time[0] . ":" . $frm_time[1] . ":" . $frm_time[2];
        } else {
            return "";
        }
    }

}
if (!function_exists("priceFormat")) {

    function priceFormat($price)
    {
        return number_format($price, 2);
    }

}

if (!function_exists("stdURLFormat")) {

    function stdURLFormat($str, $space = '-')
    {
        $str = strtolower($str);
        $str = preg_replace('/[^a-zA-Z0-9\-\ ]/i', '', $str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", $space, $str);
        return $str;
    }

}

if (!function_exists("imgUpload")) {

    function imgUpload($field, $folder = '/data/', $file_name = '', $overwrite = true)
    {
        $allowed_types = 'gif|jpg|jpeg|png';
        if (!empty($_FILES[$field]['name'])) {
            if (!file_exists(FCPATH . $folder)) {
                mkdir(FCPATH . $folder, 0775);
            }
            $upload_path = FCPATH . $folder;
            if ($_FILES[$field]['error'] === 0) {
                $file_info = pathinfo($_FILES[$field]['name']);
                $file_name = $file_name . "." . $file_info['extension'];
                $file_path = $upload_path . $file_name;
                if (@move_uploaded_file($_FILES[$field]["tmp_name"], $file_path)) {
                    return $file_name;
                }
            }
        }
        return false;
    }

}

if (!function_exists("uploadFiles")) {

    function uploadFiles($folder = '/data/', $field_name)
    {

        $sent_file_name = $field_name;
        if (!empty($_FILES[$field_name]['name'])) {
            $tmp_files = $_FILES[$sent_file_name]['tmp_name'];

            if (!file_exists(FCPATH . $folder)) {
                mkdir(FCPATH . $folder, 0775);
            }
            $upload_path = FCPATH . $folder;
            foreach ($tmp_files as $key => $tmp_name) {
                if ($_FILES[$field_name]['error'][0] === 0) {
                    $files = $_FILES[$sent_file_name]['name'][$key];
                    @move_uploaded_file($tmp_name, $upload_path . '/' . $files);
                }
            }
        }
    }
}

if (!function_exists("uploadThumbFiles")) {
    function uploadThumbFiles($folder = '/data/', $field_name, $width, $height = '')
    {

        $sent_file_name = $field_name;
        if (!empty($_FILES[$field_name]['name'])) {
            $tmp_files = $_FILES[$sent_file_name]['tmp_name'];

            if (!file_exists(FCPATH . $folder)) {
                mkdir(FCPATH . $folder, 0775);
            }
            $upload_path = FCPATH . $folder;

            foreach ($tmp_files as $key => $tmp_name) {
                if ($_FILES[$field_name]['error'][0] === 0) {
                    //$files = $_FILES[$sent_file_name]['name'][$key];
                    $files = $_FILES[$sent_file_name]['name'][$key];
                    $file_path = $upload_path . '/' . $files;
                    $file_thumb_path = $upload_path . '/thumb_' . $files;
                    if (@move_uploaded_file($tmp_name, $file_path)) {
                        if (@copy($file_path, $file_thumb_path)) {
                            $img = new abeautifulsite\SimpleImage($file_thumb_path);
                            $img->fit_to_width($width)->fit_to_width($height)->save($file_thumb_path);
                        }
                    }
                }
            }
        }
    }
}

if (!function_exists("getSmallTitle")) {
    function getSmallTitle($string = '')
    {
        $words = explode(' ', $string);
        if (!empty($words)) {
            $count = count($words);
            $fc = '';
            for ($i = 0; $i < $count; $i++) {
                $fc .= !empty($words[$i][0]) ? $words[$i][0] : '';
            }
            return $fc;
        }
    }
}
if (!function_exists("uploadImage")) {
    function uploadImage($field, $folder_name = '/data/', $file_name = '', $width, $height = '')
    {
        if (!empty($_FILES[$field]['name'])) {
            if (!file_exists(FCPATH . $folder_name)) {
                mkdir(FCPATH . $folder_name, 0775);
            }
            $upload_path = FCPATH . $folder_name;
            if ($_FILES[$field]['error'] === 0) {
                $file_info = pathinfo($_FILES[$field]['name']);
                $tmp = $_FILES[$field]['tmp_name'];
                $file_name = $file_name . "." . $file_info['extension'];
                $file_path = $upload_path . $file_name;
                $file_thumb_path = $upload_path . "thumb_" . $file_name;
                try {
                    if (@move_uploaded_file($tmp, $file_path)) {
                        if (@copy($file_path, $file_thumb_path)) {
                            $img = new abeautifulsite\SimpleImage($file_thumb_path);
                            $img->fit_to_width($width)->save($file_thumb_path);
                        }
                        return $file_name;
                    }

                } catch (Exception $e) {
                }
            }
        }
        return false;
    }
}
if (!function_exists("createFolder")) {

    function createFolder($folder = '/data/')
    {
        if (!file_exists(FCPATH . $folder)) {
            //mkdir(DOC_ROOT_PATH . $folder, 0755, true);
            mkdir(FCPATH . $folder, 0777, true);
        }
    }

}
if (!function_exists("shortDesc")) {

    function shortDesc($str, $len = 300)
    {
        $str = substr($str, 0, $len);
        return $str;
    }

}
if (!function_exists("curl_get_contents")) {
    function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

if (!function_exists("getIPInfo")) {

    function getIPInfo($ip = '')
    {
        if (empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return json_decode(curl_get_contents("http://ipinfo.io/{$ip}/json"));
    }

}
if (!function_exists("numToMonth")) {
    function numToMonth($number)
    {
        $monthNum = $number;
        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
        return $monthName;
    }
}
if (!function_exists("timeAgo")) {
    function timeAgo($date)
    {
        $minutes_to_add = 725;

        $time = new DateTime($date);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

        $date = $time->format('Y-m-d H:i:s');
        $timestamp = strtotime($date);

        $strTime = array("second", "minute", "hour", "day", "month", "year");
        $length = array("60", "60", "24", "30", "12", "10");

        $currentTime = time();
        if ($currentTime >= $timestamp) {
            $diff = time() - $timestamp;
            for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
                $diff = $diff / $length[$i];
            }

            $diff = round($diff);
            if (($strTime[$i] == "hour") || ($strTime[$i] == "minute") || ($strTime[$i] == "second")) {
                return $diff . " " . $strTime[$i] . "(s) ago ";
            } else {
                return dateDB2SHOW($date, "M d, Y");
            }

        }
    }

    if (!function_exists("dummyLogo")) {

        function dummyLogo()
        {
            return base_url() . "data/avatar.jpeg";
        }

    }

    if (!function_exists("slugify")) {

        function slugify($text)
        {
            $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
            $text = trim($text, '-');
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = strtolower($text);
            $text = preg_replace('~[^-\w]+~', '', $text);
            if (empty($text)) {
                return 'n-a';
            }
            return $text;
        }

    }
}

if (!function_exists("financialYear")) {
    function financialYear()
    {
        $financialYear = '';
        $prev_year = substr(date('Y'), 2, 2) - 1;
        $current_year = substr(date('Y'), 2, 2);
        $next_year = substr(date('Y'), 2, 2) + 1;
        if (date('m') >= '04') {
            $financialYear = $current_year . $next_year;
        }
        if (date('m') <= '03') {
            $financialYear = $prev_year . $current_year;
        }
        return $financialYear;
    }
}

if (!function_exists("transactionFormat")) {
    function transactionFormat($number)
    {
        $finance_year = financialYear();
        $serial = str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        return $finance_year . $serial;
    }
}


if (!function_exists("getFiles")) {
    function getFiles($path)
    {
        $path = FCPATH . $path;
        $thumbs = glob($path . "[!thumb_]*[.jpg,.jpeg,.JPG,.JPEG,.png,.PNG,.gif,.GIF]");
        return $thumbs;
    }
}

if (!function_exists("getThumbs")) {
    function getThumbs($path)
    {
        $path = FCPATH . $path;
        $thumbs = glob($path . "thumb_*[.jpg,.jpeg,.JPG,.JPEG,.png,.PNG,.gif,.GIF]");
        return $thumbs;
    }
}
function _success($msg = '', $result_key, $result_value, $status = 200)
{
    return json_encode(array("msg" => $msg,"status" => $status,$result_key => $result_value));
}

function _error($error, $status = 404)
{
    return json_encode(array("msg" => $error,"status" => $status));
}

function requestIsNotFromMobile()
{
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)))
        return true;
    else
        return false;
}

function ConvertArrayToObject($data = array())
{
    return (object)$data;
}

function ConvertObjectToArray($data)
{
    return (array)$data;
}


function validPostHeader()
{
    $rawData = file_get_contents("php://input");
    if (!empty($rawData)) {
        $data = array(
            'status' => true,
            'data' => json_decode($rawData)
        );
        return ConvertArrayToObject($data);
    } else {
        $data = array(
            'status' => false,
            'data' => null
        );
        return ConvertArrayToObject($data);
    }
}

function base64ToImg($string, $path, $file_name, $mime_type = 'jpg')
{
    if (!is_dir($path))
        mkdir($path, 0777, true);
    $uploadFile = $path . '/' . $file_name . '.' . $mime_type;
    file_put_contents($uploadFile, base64_decode($string));
    return $uploadFile;
}

function removeFolder($dirname)
{
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                removeFolder($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

?>
