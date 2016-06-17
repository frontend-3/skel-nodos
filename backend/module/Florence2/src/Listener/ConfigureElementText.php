<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

use Zend\Stdlib\ArrayUtils;

/**
 * Configure Text Element Listener
 * 
 * @package Florence2
 * @version v1.0.1
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureElementText
{
    /**
     * Configure
     * 
     * @return void
     * @since v1.0.0
     */
    static function configure($e)
    {
        $definition = $e->getTarget();
        
        $defaults = [
            'filters'  => [
                'StripTags',
                'StringTrim',
            ],
        ];
        
        // Acts as
        
        // In case we need to update the element's maxlength attribute, we
        // first set this variable as false and check at the end.
        $maxLength = false;
        
        try {
            $actsAs = $definition->get('actsAs');
        }
        catch (\OutOfRangeException $e) {
            $actsAs = false;
        }
        
        if ($actsAs) {
            switch (strtolower($actsAs)) {
                case 'name':
                    $maxLength = 40;
                    $defaults['validators'][] = [
                        'Alpha' => [
                            // Because "De la Cruz"
                            'allowWhitespace' => true,
                        ],
                        'StringLength' => [
                            'min' => 3,
                            // Because "Wolfeschlegelsteinhausenbergerdorff" ;)
                            'max' => $maxLength,
                        ]
                    ];
                    break;
                
                case 'number':
                    $defaults['validators'][] = 'Digits';
                    break;
                
                case 'email':
                    $defaults['validators'][] = 'EmailAddress';
                    break;
                
                case 'dni':
                    $maxLength = 8;
                    $defaults['validators'][] = [
                        'StringLength' => [
                            'min' => 8,
                            'max' => $maxLength,
                        ]
                    ];
                    $defaults['validators'][] = 'Digits';
                    break;
                
                case 'telephone':
                    $maxLength = 9;
                    $defaults['validators'][] = [
                        'StringLength' => [
                            'min' => 6,
                            'max' => $maxLength,
                        ]
                    ];
                    $defaults['validators'][] = 'Digits';
                    break;
            }
            
            if ($maxLength) {
                $specs = $definition->getElementSpecification();
                $specs['attributes']['maxlength'] = $maxLength;
                $definition->setElementSpecification($specs);
            }
        }
        
        $definition->merge($defaults);
    }
}


