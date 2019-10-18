# Freshmail plugin for Craft CMS 3.x

Connect your freshmail account to Craft CMS.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require pleodigital/freshmail

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Freshmail.

## Freshmail Overview

This plugin allows quick and easy integration with the Freshmail tool. All you have to do is complete your access data and display the field responsible for entering your email address.

## Configuring Freshmail

In the Control Panel, go to Settings → Plugins -> Freshmail and enter your Freshmail Api Key and Freshmail Secret Key. 

## Using Freshmail

{{ freshmailInput('p2sis8er7d', { inputClass: 'email-input email-sub', placeholder: item.inputPlacecholder }) }}

## Freshmail Roadmap

If you have an idea how to develop this plugin, create PR and send it to us. Thanks!

Brought to you by [Pleo Digital](https://pleodigital.com/)
