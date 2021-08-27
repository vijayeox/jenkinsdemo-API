<?php
namespace Oxzion\FormEngine;

interface Engine
{
    public function parseForm($form, $fieldReference, &$errors);
}
