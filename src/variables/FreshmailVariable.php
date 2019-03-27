<?php
/**
 * Freshmail plugin for Craft CMS 3.x
 *
 * Connect your freshmail account to Craft CMS.
 *
 * @link      https://pleodigital.com/
 * @copyright Copyright (c) 2019 Pleo Digital
 */

namespace pleodigitalfreshmail\freshmail\variables;

use pleodigitalfreshmail\freshmail\Freshmail;

use Craft;

/**
 * Freshmail Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.freshmail }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Pleo Digital
 * @package   Freshmail
 * @since     1.0.0
 */
class FreshmailVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.freshmail.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.freshmail.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    // public function exampleVariable($optional = null)
    // {
    //     $result = "And away we go to the Twig template...";
    //     if ($optional) {
    //         $result = "I'm feeling optional today...";
    //     }
    //     return $result;
    // }
}
