<?php
namespace Me\Contracts\Http;

interface Request
{

    public function method();

    public function get($key);

    public function has($key);

    public function all();
    
    /**
     * 获得客户端真实IP
     *
     * @return string|null
     */
    public function ip();
}