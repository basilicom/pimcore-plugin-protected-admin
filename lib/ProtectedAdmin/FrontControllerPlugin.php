<?php
namespace ProtectedAdmin;

use Pimcore\Config;
use Pimcore\Model\User;

use Pimcore\Tool\Authentication;
use Pimcore\Tool\Frontend;

class FrontControllerPlugin extends \Zend_Controller_Plugin_Abstract
{
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        parent::preDispatch($request);

        $config = Frontend::getWebsiteConfig();

        $user = Authentication::authenticateSession();

        if ($this->isRequestToAdminBackend($request) 
        && !$this->isAuthenticationValid()
        && ($config->get(Plugin::CONFIG_PROTECTED_ADMIN_ENABLED, false) == true)
        && (!($user instanceof \Pimcore\Model\User))
        ) {
            $this->sendHttpBasicAuthResponse();
            exit;
        }
    }

    /**
     * @return bool
     */
    private function isAuthenticationValid()
    {

        $config = Frontend::getWebsiteConfig();

        $username = $config->get(Plugin::CONFIG_PROTECTED_ADMIN_USERNAME, '');
        $password = $config->get(Plugin::CONFIG_PROTECTED_ADMIN_PASSWORD, '');

        if (trim($password) == '') {
            // empty password - this is not good; Deny access!
            return false;
        }

        if (($_SERVER['PHP_AUTH_USER'] === $username) && ($_SERVER['PHP_AUTH_PW'] === $password)) {
            return true;
        }

        return false;
    }

    private function sendHttpBasicAuthResponse()
    {
        $config = Frontend::getWebsiteConfig();
        $password = $config->get(Plugin::CONFIG_PROTECTED_ADMIN_PASSWORD, null);

        if (($password === null) || (trim($password) == '')) {

            $notice = 'Missing or empty Website Property '
                . Plugin::CONFIG_PROTECTED_ADMIN_PASSWORD;

        } else {

            $notice = 'Authentication required';
        }

        /** @var $response \Zend_Controller_Response_Http */
        $response = $this->getResponse();

        $response->setHeader('Cache-Control', 'max-age=0');
        $response->setHttpResponseCode(401);
        $response->setHeader(
            'WWW-Authenticate',
            'Basic realm="' . $notice . '"'
        );

        $response->setBody('Unauthorized.');
        $response->sendResponse();
    }
    
    /**
     * @param \Zend_Controller_Request_Http $request
     * @return bool
     */
    private function isRequestToAdminBackend($request)
    {
        return preg_match("/^\\/admin/", $request->getRequestUri()) === 1;
    }
}
