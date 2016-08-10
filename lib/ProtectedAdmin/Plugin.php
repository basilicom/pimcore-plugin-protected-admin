<?php
namespace ProtectedAdmin;

use Pimcore\Model\WebsiteSetting;
use Pimcore\API\Plugin as PluginApi;
use Pimcore\Db;
use Pimcore\Model\Property\Predefined as PropertyPredefined;

class Plugin extends PluginApi\AbstractPlugin implements PluginApi\PluginInterface
{
    const CONFIG_PROTECTED_ADMIN_ENABLED
        = 'protectedAdminEnabled';

    const CONFIG_PROTECTED_ADMIN_PASSWORD
        = 'protectedAdminPassword';

    const CONFIG_PROTECTED_ADMIN_USERNAME
        = 'protectedAdminUser';

    public function init()
    {
        \Pimcore::getEventManager()->attach("system.startup", function ($event) {

            $front = \Zend_Controller_Front::getInstance();

            $frontControllerPlugin = new FrontControllerPlugin();
            $front->registerPlugin($frontControllerPlugin);
        });
    }

    public static function install()
    {
        if (!self::isInstalled()) {

            $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_ENABLED);
            if (!is_object($setting)) {
                $setting = new WebsiteSetting();
                $setting->setName(self::CONFIG_PROTECTED_ADMIN_ENABLED);
                $setting->setType('bool');
                $setting->setValue('data', false);
                $setting->save();
            }

            $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_USERNAME);
            if (!is_object($setting)) {
                $setting = new WebsiteSetting();
                $setting->setName(self::CONFIG_PROTECTED_ADMIN_USERNAME);
                $setting->setType('text');
                $setting->setValue('data', 'admin');
                $setting->save();
            }

            $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_PASSWORD);
            if (!is_object($setting)) {
                $setting = new WebsiteSetting();
                $setting->setName(self::CONFIG_PROTECTED_ADMIN_PASSWORD);
                $setting->setType('text');
                $setting->setValue('data', 'admin');
                $setting->save();
            }

        }

        return 'Successfully installed plugin protectedAdmin.';
    }

    public static function uninstall()
    {
        
        $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_USERNAME);
        if (is_object($setting)) {
            $setting->delete();
        }
        
        $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_PASSWORD);
        if (is_object($setting)) {
            $setting->delete();
        }

        $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_ENABLED);
        if (is_object($setting)) {
            $setting->delete();
        }

        return 'Successfully removed plugin protectedAdmin.';
    }

    public static function isInstalled()
    {
        $setting = WebsiteSetting::getByName(self::CONFIG_PROTECTED_ADMIN_ENABLED);
        return (is_object($setting));
    }

    public static function needsReloadAfterInstall()
    {
        return false; // backend only functionality!
    }

}