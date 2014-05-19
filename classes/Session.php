<?php

namespace DTC;

class Session
{
    protected $id = null;

    protected $log;

    public function __construct(
        Log $log
    )
    {
        $id = session_id();
        if(empty($id) === true)
        {
            $this->start();
            $id = session_id();
        }

        $this->id = $id;

        $this->log = $log;
    }

    public function get($key=null)
    {
        if(is_null($key) === true)
        {
            return $_SESSION;
        }

        if(array_key_exists($key, $_SESSION) === true && empty($_SESSION[$key]) === false)
        {
            return $_SESSION[$key];
        }
        else if(array_key_exists($key, $_COOKIE) === true && empty($_COOKIE[$key]) === false)
        {
            // $backtrace = debug_backtrace();
            
            $this->log->log(sprintf("[debug] DTC\Session->get('%s'): \$_COOKIE was accessed. Value was %s ", $key, var_export($_COOKIE[$key],1)));
            // $this->log->log(print_r($backtrace,1));
            return $_COOKIE[$key];
        }
        else
        {
            $this->log->log(sprintf("[debug] DTC\Session->get('%s'): value doesn't exist in \$_SESSION or \$_COOKIE.", $key));
            // $this->log->var_dump($_SESSION, '$_SESSION');
            // $this->log->var_dump($_COOKIE, '$_COOKIE');
            return null;
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        $this->log->log(sprintf("[debug] DTC\Session->set('%s', %s)", $key, var_export($value,1)));
        return $value;
    }

    public function setcookie($name, $value, $expire = 3600, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        $this->log->log(sprintf('[debug] DTC\Session->setcookie(%s, %s, %s, %s, %s, %s, %s);', var_export($name,1), var_export($value,1), var_export($expire,1), var_export($path,1), var_export($domain,1), var_export($secure,1), var_export($httponly,1)));

        if(is_null($value) === false && $expire !== -1)
        {
            $this->set($name, $value);
            $this->log->log(sprintf("[debug] DTC\Session->set('%s', %s)", $name, var_export($value,1)));
        }
        else
        {
            $this->log->log(sprintf("[debug] DTC\Session->set('%s', %s) failed", $name, var_export($value,1)));
        }
    }

    public function remove($key, $from='both')
    {
        if($this->_session_exists($key) === true && preg_match('/^both|session$/i', $from) === 1)
        {
            $this->log->log(sprintf("[debug] DTC\Session->remove('%s'): Removing from \$_SESSION.", $key));
            unset($_SESSION[$key]);
        }

        if($this->_cookie_exists($key) === true && preg_match('/^both|cookie$/i', $from) === 1)
        {
            $this->log->log(sprintf("[debug] DTC\Session->remove('%s'): Removing from \$_COOKIE.", $key));
            $this->setcookie($key, null, -1, '/');
            unset($_COOKIE[$key]);
        }
    }

    public function removeAllCookies()
    {
        // $this->log->var_dump($_COOKIE, '$_COOKIE');
        foreach($_COOKIE as $key => $value)
        {
            if(preg_match('/^phpsessid|_bcvm.*|bc_pv_end$/i', $key) === 0)
            {
                $this->setcookie($key, null, -1, '/');
            }
        }
    }

    public function reset($preserve=array())
    {
        if(in_array('logId', $preserve) === false)
        {
            array_push($preserve, 'logId');
        }

        $jar = array();
        foreach($preserve as $key)
        {
            if($this->exists($key) === true)
            {
                $jar[$key] = $this->get($key);
            }
        }

        // $this->log->var_dump($jar, "jar");

        $this->destroy();

        // $this->log->log('session_destroy()');

        $this->start();

        foreach($jar as $key => $value)
        {
            $this->set($key, $value);
        }

        $this->log->log('[debug] DTC\Session->reset();');
    }

    public function start()
    {
        session_start();

        // $this->log->log('[debug] DTC\Session->start()');
    }

    public function destroy()
    {
        session_destroy();
    }

    public function exists($key)
    {
        if($this->_session_exists($key) === true)
        {
            return true;
        }
        else if($this->_cookie_exists($key) === true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function is_empty($key)
    {
        if($this->_session_exists($key) === true)
        {
            return empty($_SESSION[$key]);
        }
        else if($this->_cookie_exists($key) === true)
        {
            return empty($_COOKIE[$key]);
        }
        else
        {
            return true;
        }
    }

    private function _session_exists($key)
    {
        return array_key_exists($key, $_SESSION) === true;
    }

    private function _cookie_exists($key)
    {
        return array_key_exists($key, $_COOKIE) === true;
    }
}
