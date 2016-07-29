<?php

namespace Me\Validation;

use Illuminate\Validation\Factory;
use Symfony\Component\Translation\TranslatorInterface;

//use Illuminate\Contracts\Validation\Validator;

class Validator
{
    protected static $messagesBag = array();

    protected $data;

    protected $vules;

    protected $messages;

    public function __construct(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        if (empty(static::$messagesBag)) {
            static::$messagesBag = require_once './messages.php';
        }

        $this->messages = array_merge(static::$messagesBag, (array) $messages);

        $validator = new Factory( );

        ddd($validator);

    }

    public function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {

    }

}