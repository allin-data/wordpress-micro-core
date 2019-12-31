<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Helper;

/**
 * Class MethodUtil
 * @package AllInData\Micro\Core\Helper
 */
class MethodUtil
{
    /**
     * Maps camel_case to CamelCase
     * @param string $attributeName
     * @return string
     */
    static public function canonicalizeAttributeName(string $attributeName): string
    {
        $attributeNameParts = explode(' ', ucwords(str_replace(['-','_'], ' ', $attributeName)));
        foreach ($attributeNameParts as $idx => $part) {
            $attributeNameParts[$idx] = ucfirst(strtolower($part));
        }
        if (0 === count($attributeNameParts)) {
            return '';
        }
        $attributeNameParts[0] = strtolower($attributeNameParts[0]);

        return implode('', $attributeNameParts);
    }

    /**
     * @param string $methodName
     * @return string
     */
    static public function canonicalizeMethodName(string $methodName): string
    {
        $methodNameParts = explode(' ', ucwords(str_replace(['-', '_'], ' ', $methodName)));
        foreach ($methodNameParts as $idx => $part) {
            $methodNameParts[$idx] = ucfirst(strtolower($part));
        }

        return implode('', $methodNameParts);
    }

    /**
     * Maps CamelCase to camel_case
     * @param string $attributeName
     * @return string
     */
    static public function decanonicalizeAttributeName(string $attributeName): string
    {
        $attributeNameParts = preg_split('/(?=[A-Z])/', $attributeName);
        foreach ($attributeNameParts as $idx => $part) {
            $attributeNameParts[$idx] = strtolower($part);
        }

        return implode('_', $attributeNameParts);
    }
}