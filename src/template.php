<?php

function template($templatePath, $data = [])
{
    extract($data);
    
    include dirname(__DIR__) . '/templates/' . ltrim($templatePath, '/');
}
