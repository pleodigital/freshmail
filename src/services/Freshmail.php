<?php
/**
 * Freshmail plugin for Craft CMS 3.x
 *
 * Connect your freshmail account to Craft CMS.
 *
 * @link      https://pleodigital.com/
 * @copyright Copyright (c) 2019 Pleo Digital
 */

namespace pleodigitalfreshmail\freshmail\services;

use pleodigitalfreshmail\freshmail\Freshmail;

use Craft;
use craft\base\Component;

/**
 * Freshmail Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Pleo Digital
 * @package   Freshmail
 * @since     1.0.0
 */
class Freshmail extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Freshmail::$plugin->freshmail->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {

        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Freshmail::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    
    }
}
