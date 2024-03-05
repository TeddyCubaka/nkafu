<?php

class Utils_functions
{
    public function BonneDate($date)
    {
        $dt = date_create($date);
        $bonnedat = date_format($dt, 'd/m/Y');
        return $bonnedat;
    }

    public function to_db_date($date)
    {

        $dt = date_create($date);
        if (!$dt) return false;
        $new_date = date_format($dt, 'Y-m-d h:m:s');
        return $new_date;
    }

    public function createId($cod)
    {
        $alphabet1 = array('ap', 'je', 're', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'me', 'apx', 'bb', 'cg', 'ub', 'ri', 'sq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'ax', 'mie', 'xf', 'gj', 'a', 'bn', 'M5', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'df', 'is', 'kl', 'se', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'i_', '_9', 'klh', 'af', 'df', 'Mvx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'as', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'si', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');
        return $cod . '_' . $st . '_' . rand($m * 30, $s);
    }

    public function is_valid_mail($val)
    {
        if (!empty($val) && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}/i', $val)) {
            return true;
        } else {
            return false;
        }
    }

    public function is_valid_url($url)
    {
        $url = trim($url);
        if (empty($url)) {
            return false;
        }
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'http://' . $url;
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        if (!checkdnsrr(parse_url($url, PHP_URL_HOST))) {
            return false;
        }
        return true;
    }

    public function validate_etablissement($array)
    {
        return array_key_exists('logo', $array) && array_key_exists('libele', $array) && array_key_exists('abreviation', $array) && array_key_exists('contact', $array) && array_key_exists('Id_adresse', $array) && array_key_exists('Type', $array) && array_key_exists('Id_user_created_at', $array);
    }

    public function autoNumber($taille, $user)
    {
        $characts = $user;
        $characts   .= '1234567890';
        $characts   .= '130819871308199327062016';
        $code_aleatoire      = '';

        for ($i = 0; $i < $taille; $i++) {
            $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
        }
        return $code_aleatoire;
    }

    function GenRefTrans($cod)
    {
        $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'axc', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'dfi', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'if', '09', 'kii4', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');
        $c = substr($cod . '0' . $st . '0' . rand($m * 30, $s), 0, 20);
        $diff = 20 - strlen($c);
        if ($diff == 0) {
            return $c;
        } else {
            $code = '';
            $code .= $c;
            //echo '++' .'\n';
            for ($i = 0; $i < $diff; $i++) {

                $code .= $i;
            }
            return $code;
        }
        //return $cod . '0' . $st . '0' . rand( $m * 30, $s );
    }

    public function createIdTRS($cod)
    {
        $cod = $cod . 'TAC';
        //$taille = 15;
        $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'ax', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'df', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'i_', '_9', 'k+', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');

        $c = substr($cod . '0' . $st . '0' . rand($m * 30, $s), 0, 20);
        $diff = 20 - strlen($c);
        //echo $diff.'\n';

        if ($diff == 0) {
            function GenRefTrans($cod)
            {
                $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
                $alphabet2 = array('ag', 'axc', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'dfi', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
                $alphabet3 = array('va', 'ax', 'if', '09', 'kii4', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
                $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
                $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
                $s = date('s');
                $m = date('i');
                $c = substr($cod . '0' . $st . '0' . rand($m * 30, $s), 0, 20);
                $diff = 20 - strlen($c);
                if ($diff == 0) {
                    return $c;
                } else {
                    $code = '';
                    $code .= $c;
                    //echo '++' .'\n';
                    for ($i = 0; $i < $diff; $i++) {

                        $code .= $i;
                    }
                    return $code;
                }
                //return $cod . '0' . $st . '0' . rand( $m * 30, $s );
            }
        }
    }


    public function createPWD($cod)
    {
        //$taille = 15;
        $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'ax', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'df', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'i_', '_9', 'k+', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');

        $c = substr($cod . '0' . $st . '0' . rand($m * 30, $s), 0, 20);
        $diff = 4 - strlen($c);
        //echo $diff.'\n';

        if ($diff == 0) {
            return $c;
        } else {
            $code = '';
            $code .= $c;
            //echo '++' .'\n';
            for ($i = 0; $i < $diff; $i++) {
                $code .= $i;
            }
            return $code;
        }
        //return $cod . '0' . $st . '0' . rand( $m * 30, $s );
    }

    public function createIdAM($cod)
    {
        //$taille = 15;
        $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'ax', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'df', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'ij', '39', 'k2', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');

        $c = substr($cod . '0' . $st . '0' . rand($m * 30, $s), 0, 20);
        $diff = 10 - strlen($c);
        //echo $diff.'\n';

        if ($diff == 0) {
            return $c;
        } else {
            $code = '';
            $code .= $c;
            //echo '++' .'\n';
            for ($i = 0; $i < $diff; $i++) {
                $code .= $i;
            }
            return $code;
        }
        //return $cod . '0' . $st . '0' . rand( $m * 30, $s );
    }

    public function createCodeVerif($code)
    {
        //$taille = 15;
        $alphabet1 = array('ap', 'je', 'reXi', 'uh', 'ei', 'sq', 'wc', 'hf', 'is', 'go', 'se', 'pm', 'df', 'qs', 'vg', '0l', 'gu', 'vu', 'mee', 'ax', 'bb', 'cg', 'ub', 'ri', 'ssq', 'ws', 'hf', 'do', 'go', 'so', 'pq', 'hf', 'ds', 'vp', 'al', 'lu', 'ku', 'ue');
        $alphabet2 = array('ag', 'ax', 'mi', 'xef', 'gj', 'a', 'bn', 'M521', 'kQ', 'js', 'bk', 'yt', 'pf', 'zl', 'rw', 'Je', 're', 'mie', 'ap', 'bx', 'cg', 'db', 'ei', 'ip', 'wx', 'df', 'is', 'kl', 'sez', 'pm', 'df', 'qs', 'vg', 'ml', 'gi', 'vs', 'ie');
        $alphabet3 = array('va', 'ax', 'i_', '_9', 'k+', 'afHSA', 'df', 'Mx', 'Qs', 'ui', 'Lo', 'di', 'om', 'at', 'ok', 'ass', 'qp', 'cx', 'gg', 'dj', 'li', 'qk', 'wo', 'hf', 'is', 'jo', 'sia', 'um', 'ys', 'vs', 'yg', 'zl', 'zu', 'vl', 'oe');
        $nbr = (date('i') * rand(10, 2)) / rand(5, 2);
        $st = rand($nbr, 100) . $alphabet1[rand(0, count($alphabet1) - 1)] . $alphabet2[rand(0, count($alphabet2) - 1)] . $alphabet3[rand(0, count($alphabet3) - 1)] . date('i');
        $s = date('s');
        $m = date('i');

        $c = substr($code . '0' . $st . '0' . rand($m * 30, $s), 0, 18);
        $diff = 6 - strlen($c);
        //echo $diff.'\n';

        if ($diff == 0) {
            return $c;
        } else {
            $code = '';
            $code .= $c;
            //echo '++' .'\n';
            for ($i = 0; $i < $diff; $i++) {
                $code .= $i;
            }
            return $code;
        }
        //return $cod . '0' . $st . '0' . rand( $m * 30, $s );
    }

    public function get_header($code)
    {
        $codes = [
            '200' => 'HTTP/1.1 200 CREATED',
            '400' => 'HTTP/1.1 400 BAD REQUEST',
            '403' => 'HTTP/1.1 403 FORBIDEN',
            '404' => 'HTTP/1.1 400 NOT FOUND',
            '405' => 'HTTP/1.1 400 METHOD NOT ALLOW',
            '500' => 'HTTP/1.1 500 SERVER ERROR',
        ];
        return $codes[$code];
    }

    public function set_header($code)
    {
        header($this->get_header($code));
    }

    public function json_response($code, $message, $body)
    {
        header($this->get_header($code));
        return json_encode(array(
            'code' => $code,
            'message' => $message,
            'data' => $body
        ));
    }

    public function SMS_Send($SMS_Login, $SMS_Password, $MSISDN, $Message, $SMS_URL)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$SMS_URL",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "username=$SMS_Login&password=$SMS_Password&msisdn=$MSISDN&msg=$Message",
            CURLOPT_HTTPHEADER => array(
                'content-type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'cURL Error #:' . $err;
        } else {
            return $response;
        }
    }

    public function SMS_FORMATAGE($service, $Name_clt, $OTP = '')
    {
        $Message = '';

        switch ($service) {
            case 'BUY-FORFAIT':
                $Message .= 'Cher client';
                break;
            case 'NOTIFICATION':

                break;
            case 'INSCRIPTION':
                $Message .= "Cher client votre code OTP d'activation du compte Trans-Kelasi est : $OTP";
                break;
        }

        return $Message;
    }

    public function generateOTP($length = 4)
    {
        $otp = '';
        $permittedDigits = '123456789';
        $permittedDigitsLength = strlen($permittedDigits);

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $permittedDigitsLength - 1);
            $otp .= $permittedDigits[$randomIndex];
        }

        return $otp;
    }
}
function Ajust($val)
{
    $modif = strip_tags($val);
    $modif = htmlentities($modif, ENT_QUOTES, 'UTF-8');
    addslashes($modif);
    return $modif;
}
