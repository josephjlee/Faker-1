<?php
namespace Faker;

interface ExtensionInterface
{
    
    public static function registerExtension($index,$namespace);
    
    public static function registerExtensions(array $extension);
    
}
/* End of File */