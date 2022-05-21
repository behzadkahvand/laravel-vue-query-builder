<?php

if (!function_exists('remove_white_spaces')) {
    function remove_white_spaces(string $string): string
    {
        return preg_replace("/\s+/", "", $string);
    }
}

if (!function_exists('sanitize_input')) {
    function sanitize_input(string $string): string
    {
        return strip_tags(str_replace(['"',"'"], "", $string));
    }
}

if (!function_exists('object_to_array')) {
    function object_to_array(object $entity): array
    {
        $reflectionClass = new ReflectionClass(get_class($entity));
        $array           = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->isInitialized($entity)) {
                $array[$property->getName()] = $property->getValue($entity);
            }
            $property->setAccessible(false);
        }

        return $array;
    }
}
