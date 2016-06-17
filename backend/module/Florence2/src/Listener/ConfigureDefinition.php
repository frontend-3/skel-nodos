<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

/**
 * Configure Definition Listener
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureDefinition
{
    /**
     * Configure
     * 
     * @return void
     * @since v1.0.1
     */
    static function configure($e)
    {
        $definition = $e->getTarget();
        
        $definitionValues = $definition->getAll();
        
        // MinLength & MaxLength
        try {
            $minLength = $definition->get('minLength');
        }
        catch (\OutOfRangeException $e) {
            $minLength = false;
        }
        
        try {
            $maxLength = $definition->get('maxLength');
        }
        catch (\OutOfRangeException $e) {
            $maxLength = false;
        }
        
        if ($minLength !== false || $maxLength !== false) {
            $stringLengthOptions = [];
            
            if ($minLength !== false) {
                $stringLengthOptions['min'] = $minLength;
            }
            if ($maxLength !== false) {
                $stringLengthOptions['max'] = $maxLength;
                $definitionValues['elementAttributes']['maxlength'] = $maxLength;
            }
            
            $definitionValues['validators'][] = [
                'StringLength' => $stringLengthOptions
            ];
        }
        
        // identicalTo
        try {
            $identicalTo = $definition->get('identicalTo');
            $definitionValues['validators'][] = [
                'Identical' => [
                    'token' => $identicalTo,
                ],
            ];
        }
        catch (\OutOfRangeException $e) {
        }
        
        $definition->setAll($definitionValues);
    }
}


