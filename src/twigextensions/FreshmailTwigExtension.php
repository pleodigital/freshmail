<?php
/**
 * Freshmail plugin for Craft CMS 3.x
 *
 * Connect your freshmail account to Craft CMS.
 *
 * @link      https://pleodigital.com/
 * @copyright Copyright (c) 2019 Pleo Digital
 */

namespace pleodigitalfreshmail\freshmail\twigextensions;

use pleodigitalfreshmail\freshmail\Freshmail;
use craft\helpers\Template as TemplateHelper;

use Craft;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Pleo Digital
 * @package   Freshmail
 * @since     1.0.0
 */
class FreshmailTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Freshmail';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    // public function getFilters()
    // {
    //     return [
    //         new \Twig_SimpleFilter('someFilter', [$this, 'someInternalFunction']),
    //     ];
    // }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('freshmailInput', [$this, 'freshmailInputFunction']),
        ];
    }

    public function freshmailInputFunction($listId, $options = null) 
    {

        $action = 'freshmail/freshmail';

        $listIdFormName = 'freshmailListId';
        $emailIdFormName = 'freshmailEmail';

        $inputClass = $options && isset( $options['inputClass'] ) ? $options['inputClass'] : '';
        $inputPlaceholder = $options && isset( $options['placeholder'] ) ? $options['placeholder'] : '';

        return TemplateHelper::raw('<input type="hidden" name="action" value="' . $action . '"><input type="hidden" name="' . $listIdFormName . '" value="' . $listId . '"><input type="email" name="' . $emailIdFormName . '" class="' . $inputClass . '" placeholder="' . $inputPlaceholder . '">');
    
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return string
     */
    // public function someInternalFunction($text = null)
    // {
    //     $result = $text . " in the way";

    //     return $result;
    // }
}
