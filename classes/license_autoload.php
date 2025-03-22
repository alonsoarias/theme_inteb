<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Autoload hook for license override
 *
 * @package   theme_inteb
 * @copyright (c) 2025 IngeWeb <soporte@ingeweb.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Pedro Arias <soporte@ingeweb.co>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This function is called early in the Moodle bootstrap process to ensure
 * our license overrides are loaded before the original RemUI classes.
 */
function theme_inteb_license_autoload() {
    global $CFG;
    
    // Make sure theme is installed and these files exist
    if (!file_exists($CFG->dirroot . '/theme/inteb/classes/controller/LicenseController.php') || 
        !file_exists($CFG->dirroot . '/theme/inteb/classes/controller/RemUIController.php')) {
        return;
    }
    
    // Load our classes first
    require_once($CFG->dirroot . '/theme/inteb/classes/controller/LicenseController.php');
    require_once($CFG->dirroot . '/theme/inteb/classes/controller/RemUIController.php');
    
    // Define license constants if not already defined
    if (!defined("PLUGINSHORTNAME")) {
        // Plugins short name appears on the License Menu Page.
        define('PLUGINSHORTNAME', 'Edwiser RemUI');
        // This slug is used to store the data in db.
        // License is checked using two options viz edd_<slug>_license_key and edd_<slug>_license_status.
        define('PLUGINSLUG', 'remui');
        // Current Version of the plugin. This should be similar to Version tag mentioned in Plugin headers.
        define('PLUGINVERSION', '4.5.0');
        // Under this Name product should be created on WisdmLabs Site.
        define('PLUGINNAME', 'Edwiser RemUI');
        // URL local para evitar solicitudes externas
        define('STOREURL', '/theme/inteb/fakecheckurl.php');
        // Author Name.
        define('AUTHORNAME', 'WisdmLabs');

        define('EDD_LICENSE_ACTION', 'licenseactionperformed');
        define('EDD_LICENSE_KEY', 'edd_' . PLUGINSLUG . '_license_key');
        define('EDD_LICENSE_DATA', 'edd_' . PLUGINSLUG . '_license_data');
        define('EDD_PURCHASE_FROM', 'edd_' . PLUGINSLUG . '_purchase_from');
        define('EDD_LICENSE_STATUS', 'edd_' . PLUGINSLUG . '_license_status');
        define('EDD_LICENSE_ACTIVATE', 'edd_' . PLUGINSLUG . '_license_activate');
        define('EDD_LICENSE_DEACTIVATE', 'edd_' . PLUGINSLUG . '_license_deactivate');
        define('WDM_LICENSE_TRANS', 'wdm_' . PLUGINSLUG . '_license_trans');
        define('WDM_LICENSE_PRODUCTSITE', 'wdm_' . PLUGINSLUG . '_product_site');
    }
    
    // Set valid license status
    if (get_config('theme_remui', EDD_LICENSE_STATUS) !== 'valid') {
        set_config(EDD_LICENSE_STATUS, 'valid', 'theme_remui');
    }
}

// Load our overrides immediately
theme_inteb_license_autoload();