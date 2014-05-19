session->get(string $key)
```
$session->get($key=null);
```
returns key from $_SESSION if it exists, falls back to $_COOKIE, else false

session->set(string $key, mixed $value)
```
$session->set($key, $value);
```
sets value to $_SESSION key
