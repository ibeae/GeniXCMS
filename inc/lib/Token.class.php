<?php
/**
 * GeniXCMS - Content Management System.
 *
 * PHP Based Content Management System and Framework
 *
 * @since 0.0.2 build date 20150309
 *
 * @version 1.1.5
 *
 * @link https://github.com/semplon/GeniXCMS
 * @link http://genix.id
 *
 * @author Puguh Wijayanto <psw@metalgenix.com>
 * @copyright 2014-2017 Puguh Wijayanto
 * @license http://www.opensource.org/licenses/mit-license.php MIT
*/

/**
 * Token Class.
 *
 * @author Puguh Wijayanto <psw@metalgenix.com>
 *
 * @since 0.0.2
 */
class Token
{
    public function __construct()
    {
        self::create();
    }

    public static function create()
    {
        $length = '80';
        $token = '';
        $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
        $codeAlphabet .= '0123456789';
        // $codeAlphabet.= "!@#$%^&*()[]\/{}|:\<>";
        //$codeAlphabet.= SECURITY_KEY;
        for ($i = 0; $i < $length; ++$i) {
            $token .= $codeAlphabet[Typo::crypto_rand_secure(0, strlen($codeAlphabet))];
        }
        $url = $_SERVER['REQUEST_URI'];
        $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $ip = $_SERVER['REMOTE_ADDR'];
        $time = time();
        define('TOKEN', $token);
        define('TOKEN_URL', $url);
        define('TOKEN_IP', $ip);
        define('TOKEN_TIME', $time);
        $json = self::json();
        Options::update('tokens', $json);

        return $token;
    }

    /**
     * Json Token Function.
     *
     * $token = [{'time','ip','url',token'},]
     */
    public static function json()
    {
        $token = Options::v('tokens');
        $token = json_decode(Typo::Xclean($token), true);
        $newtoken = array(
                        TOKEN => array(
                            'time' => TOKEN_TIME,
                            'ip' => TOKEN_IP,
                            'url' => TOKEN_URL,
                            ),
                    );
        if (is_array($token)) {
            $newtoken = array_merge($token, $newtoken);
        }
        $newtoken = self::ridOld($newtoken);
        $newtoken = json_encode($newtoken);

        return $newtoken;
    }

    public static function isExist($token)
    {
        $json = Options::get('tokens');
        $tokens = json_decode(Typo::Xclean($json), true);
//        print_r($tokens);
        if (!is_array($tokens) || $tokens == '') {
            $tokens = array();
        }
        if (array_key_exists($token, $tokens)) {
            $call = true;
        } else {
            $call = false;
        }

        return $call;
    }

    public static function remove($token)
    {
        $json = Options::get('tokens');
        $tokens = json_decode(Typo::Xclean($json), true);
        unset($tokens[$token]);
        $tokens = json_encode($tokens);
        if (Options::update('tokens', $tokens)) {
            return true;
        } else {
            return false;
        }
    }

    public static function ridOld($tokens)
    {
        $time = time();
        // echo $time;
        foreach ($tokens as $token => $value) {
            if ($time - $value['time'] > 3600) {
                unset($tokens[$token]);
            }
        }

        return $tokens;
    }

    public static function validate($token)
    {
        if (!self::isExist($token) && !self::urlMatch($token)) {
            $valid = false;
        } else {
            $valid = true;
        }

        return $valid;
    }

    public static function urlMatch($token)
    {
        $tokens = json_decode(Typo::Xclean(Options::v('tokens')), true);
        $urlLive = $_SERVER['REQUEST_URI'];
        $urlToken = in_array($token, $tokens) ? $tokens[$token]['url']: '';
        if ($urlToken == $urlLive) {
            return true;
        } else {
            return false;
        }
    }
}
