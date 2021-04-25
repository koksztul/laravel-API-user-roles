<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Revision Model
    |--------------------------------------------------------------------------
    */
    'model' => Venturecraft\Revisionable\Revision::class,

    'additional_fields' => ['fname', 'lname', 'login', 'email', 'type', 'password'],

];
