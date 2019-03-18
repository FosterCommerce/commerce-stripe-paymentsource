<?php

namespace fostercommerce\commercestripepaymentsource;

use fostercommerce\commercestripepaymentsource\services\Service as Service;
use fostercommerce\commercestripepaymentsource\variables\Variable;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

class Plugin extends BasePlugin
{
    public static $plugin;

    public $schemaVersion = '1.0.0';

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        if (
            !Craft::$app->plugins->isPluginEnabled('commerce') ||
            !Craft::$app->plugins->isPluginEnabled('commerce-stripe')
        ) {
            throw new \Exception('commerce-stripe-paymentsource requires Craft Commerce 2 and the Stripe gateway to be installed.');
        }

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('commercestripepaymentsource', Variable::class);
            }
        );
    }
}
