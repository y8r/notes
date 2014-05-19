<?php

namespace DTC; 

class Log
{
    public function __construct()
    {
        // $this->var_dump(is_null(session_id()), 'session_set?');
        $sessid = session_id();
        if(empty($sessid) === true)
        {
            session_start();
            $this->log('[debug] DTC\Log->__construct(): session_start()');
            // $this->var_dump($_SESSION, "DTC\Log Session");
            // $this->var_dump(debug_backtrace(), "DTC\Log Backtrace");
        }

        // $this->var_dump($_SESSION);
        // $this->var_dump(array_key_exists('logId', $_SESSION));

        if(array_key_exists('logId', $_SESSION) === false)
        {
            $id = $this->id();
            $_SESSION['logId'] = $id;
            $this->log(sprintf("set logId: %s", $id));
        }
    }
    public function log($log='')
    {
        $backtrace = debug_backtrace();

        $log = sprintf("[%s:%d] %s", $backtrace[1]['file'], $backtrace[1]['line'], $log);

        if(session_id() !== '' && array_key_exists('logId', $_SESSION) === true)
        {
            $log = sprintf("[%s] %s", $_SESSION['logId'], $log);
        }

        error_log(sprintf("%s\n", $log));
        // $this->var_dump($backtrace);
    }

    public function var_dump($var, $msg=null)
    {
        ob_start();
        if(is_null($msg) === false)
        {
            printf("%s: ", $msg);
        }
        var_dump($var);
        error_log(ob_get_contents());
        ob_end_clean();
    }

    public function toString($mixed)
    {
        if(is_array($mixed) === false && is_object($mixed) === false)
        {
            $this->log(sprintf("[error] Log->toString(): %s is not an object or array.", $mixed));
            return $mixed;
        }

        if(is_object($mixed))
        {
            $mixed = get_object_vars($mixed);
        }

        return json_encode($mixed);
        /*
        $string = urldecode(http_build_query($mixed));

        return preg_replace(
            array(
                '/&/',
                '/=/',
                '/[ ]+/',
                '/\s+(?=[,])/'
            ),
            array(
                ', ',
                ': ',
                ' ',
                ''
            ),
            $string
        );
        */
    }

    protected function id()
    {
        return (array_key_exists('logId', $_SESSION) === false) ? strtoupper(uniqid().rand(0,9)) : $_SESSION['logId'];
    }
}
