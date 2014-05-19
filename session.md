Session Class
=============

#### session->get(string $key)
    $session->get($key=null);
returns key from $_SESSION if it exists, falls back to $_COOKIE, else false
> To return all session data as an array leave the argument blank. `session->get()` or `session->get(null)`

#### session->set(string $key, mixed $value)
    $session->set($key, $value);
sets value to $_SESSION key

#### session->setcookie()
#### session->remove()
#### session->removeAllCookies()

#### session->reset()
#### session->start()
#### session->destroy()

#### session->exists()
#### session->is_empty()

#### session->_session_exists()
#### sesssion->_cookie_exists()
