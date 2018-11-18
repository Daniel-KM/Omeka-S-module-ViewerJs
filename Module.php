<?php
/**
 * @author Daniel Berthereau
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2.1-en.txt
 * @copyright Daniel Berthereau, 2017-2018
 *
 * Copyright 2017-2018 Daniel Berthereau
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software. You can use, modify and/or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software’s author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user’s attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software’s suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */
namespace ViewerJs;

use Omeka\Module\AbstractModule;
use Omeka\Module\Exception\ModuleCannotInstallException;
use ViewerJs\Form\SettingsFieldset;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module extends AbstractModule
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $js = __DIR__ . '/asset/vendor/viewerjs/viewer.js';
        if (!file_exists($js)) {
            $t = $serviceLocator->get('MvcTranslator');
            throw new ModuleCannotInstallException(
                $t->translate('The ViewerJS library should be installed.') // @translate
                    . ' ' . $t->translate('See module’s installation documentation.')); // @translate
        }

        $this->manageMainSettings($serviceLocator, 'install');
        $this->manageSiteSettings($serviceLocator, 'install');
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $this->manageMainSettings($serviceLocator, 'uninstall');
        $this->manageSiteSettings($serviceLocator, 'uninstall');
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            \Omeka\Form\SettingForm::class,
            'form.add_elements',
            [$this, 'handleMainSettings']
        );
        $sharedEventManager->attach(
            \Omeka\Form\SiteSettingsForm::class,
            'form.add_elements',
            [$this, 'handleSiteSettings']
        );
    }

    public function handleMainSettings(Event $event)
    {
        $services = $this->getServiceLocator();
        $fieldset = $services->get('FormElementManager')->get(SettingsFieldset::class);
        $this->handleAnySettings($event, 'settings', $fieldset);
    }

    public function handleSiteSettings(Event $event)
    {
        $services = $this->getServiceLocator();
        $fieldset = $services->get('FormElementManager')->get(SettingsFieldset::class);
        $this->handleAnySettings($event, 'site_settings', $fieldset);
    }

    protected function manageMainSettings(ServiceLocatorInterface $services, $process)
    {
        $settings = $services->get('Omeka\Settings');
        $this->manageAnySettings($settings, 'settings', $process);
    }

    protected function manageSiteSettings(ServiceLocatorInterface $services, $process)
    {
        $settingsType = 'site_settings';
        $settings = $services->get('Omeka\Settings\Site');
        $api = $services->get('Omeka\ApiManager');
        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $settings->setTargetId($site->id());
            $this->manageAnySettings($settings, $settingsType, $process);
        }
    }

    protected function manageAnySettings($settings, $process, $settingsType)
    {
        $config = require __DIR__ . '/config/module.config.php';
        $space = strtolower(__NAMESPACE__);
        $defaultSettings = $config[$space][$settingsType];
        foreach ($defaultSettings as $name => $value) {
            switch ($process) {
                case 'install':
                    $settings->set($name, $value);
                    break;
                case 'uninstall':
                    $settings->delete($name);
                    break;
            }
        }
    }

    protected function handleAnySettings(Event $event, $settingsType, $fieldset)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');

        $settingsTypes = [
            // 'config' => 'Omeka\Settings',
            'settings' => 'Omeka\Settings',
            'site_settings' => 'Omeka\Settings\Site',
            'user_settings' => 'Omeka\Settings\User',
        ];
        $settings = $services->get($settingsTypes[$settingsType]);

        $space = strtolower(__NAMESPACE__);
        $defaultSettings = $config[$space][$settingsType];
        $data = [];
        foreach ($defaultSettings as $name => $value) {
            $data[$name] = $settings->get($name, $value);
        }

        $fieldset->setName($space);
        $form = $event->getTarget();
        $form->add($fieldset);
        $form->get($space)->populateValues($data);
    }
}
