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

defined('MOODLE_INTERNAL') || die();

/**
 * Theme settings for theme_inteb
 *
 * @package   theme_inteb
 * @copyright 2025, You Name <your@email.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/classes/admin_settingspage_tabs.php');
require_once(__DIR__ . '/../remui/settings.php');
require_once(__DIR__ . '/lib.php');

$parent_tabs = null;
if (isset($settings) && method_exists($settings, 'get_tabs')) {
    $parent_tabs = $settings->get_tabs();
}

unset($settings);
$settings = null;

// Create settings category
$ADMIN->add('appearance', new admin_category('theme_inteb', get_string('configtitle', 'theme_inteb')));

$asettings = new theme_inteb_admin_settingspage_tabs(
    'themesettinginteb',
    get_string('themesettings', 'theme_inteb'),
    'moodle/site:config'
);

if ($ADMIN->fulltree) {
    $page = new admin_settingpage('themesettings', get_string('themesettings', 'theme_inteb'));

    // Dynamic image variables
    $a = new stdClass;
    $a->example_banner = (string)$OUTPUT->image_url('example_banner', 'theme_inteb');
    $a->cover_remui = (string)$OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string)$OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string)$OUTPUT->image_url('login_bg', 'theme');

    // General Settings
    $page->add(new admin_setting_heading(
        'themesettingsgeneral',
        get_string('themesettingsgeneral', 'theme_inteb'),
        ''
    ));

    // Theme info text
    $name = 'theme_inteb/themeinfotext';
    $title = '';
    $description = get_string('themeinfotext', 'theme_inteb');
    $page->add(new admin_setting_heading($name, $title, $description));

    // General notice mode
    $name = 'theme_inteb/generalnoticemode';
    $title = get_string('generalnoticemode', 'theme_inteb');
    $description = get_string('generalnoticemodedesc', 'theme_inteb');
    $choices = [
        'off' => get_string('generalnoticemode_off', 'theme_inteb'),
        'info' => get_string('generalnoticemode_info', 'theme_inteb'),
        'danger' => get_string('generalnoticemode_danger', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, 'off', $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // General notice text
    $name = 'theme_inteb/generalnotice';
    $title = get_string('generalnotice', 'theme_inteb');
    $description = get_string('generalnoticedesc', 'theme_inteb');
    $default = '<strong>Estamos trabajando</strong> para mejorar...';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hide/Show frontpage sections
    $name = 'theme_inteb/hidefrontpagesections';
    $title = get_string('hidefrontpagesections', 'theme_inteb');
    $description = get_string('hidefrontpagesections_desc', 'theme_inteb');
    $choices = [
        '0' => get_string('show', 'theme_inteb'),
        '1' => get_string('hide', 'theme_inteb')
    ];
    $page->add(new admin_setting_configselect($name, $title, $description, 0, $choices));

    // Hide/Show footer sections
    $name = 'theme_inteb/hidefootersections';
    $title = get_string('hidefootersections', 'theme_inteb');
    $description = get_string('hidefootersections_desc', 'theme_inteb');
    $choices = [
        '0' => get_string('show', 'theme_inteb'),
        '1' => get_string('hide', 'theme_inteb')
    ];
    $page->add(new admin_setting_configselect($name, $title, $description, 0, $choices));

    // Personal area header image settings
    $name = 'theme_inteb/showpersonalareaheader';
    $title = get_string('showpersonalareaheader', 'theme_inteb');
    $description = get_string('showpersonalareaheader_desc', 'theme_inteb');
    $choices = [
        '1' => get_string('show', 'theme_inteb'),
        '0' => get_string('hide', 'theme_inteb')
    ];
    $page->add(new admin_setting_configselect($name, $title, $description, '0', $choices));

    $name = 'theme_inteb/personalareaheader';
    $title = get_string('personalareaheader', 'theme_inteb');
    $description = get_string('personalareaheaderdesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'personalareaheader', 0, [
        'subdirs' => 0,
        'accepted_types' => ['web_image']
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // My courses header image settings
    $name = 'theme_inteb/showmycoursesheader';
    $title = get_string('showmycoursesheader', 'theme_inteb');
    $description = get_string('showmycoursesheader_desc', 'theme_inteb');
    $choices = [
        '1' => get_string('show', 'theme_inteb'),
        '0' => get_string('hide', 'theme_inteb')
    ];
    $page->add(new admin_setting_configselect($name, $title, $description, '0', $choices));

    $name = 'theme_inteb/mycoursesheader';
    $title = get_string('mycoursesheader', 'theme_inteb');
    $description = get_string('mycoursesheaderdesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'mycoursesheader', 0, [
        'subdirs' => 0,
        'accepted_types' => ['web_image']
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login Settings
    $page->add(new admin_setting_heading(
        'themesettingslogin',
        get_string('themesettingslogin', 'theme_inteb'),
        ''
    ));
    // About title
    $name = 'theme_inteb/abouttitle';
    $title = get_string('abouttitle', 'theme_inteb');
    $description = get_string('abouttitledesc', 'theme_inteb');
    $default = get_string('abouttitle_default', 'theme_inteb');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // About text
    $name = 'theme_inteb/abouttext';
    $title = get_string('abouttext', 'theme_inteb');
    $description = get_string('abouttextdesc', 'theme_inteb');
    $setting = new admin_setting_confightmleditor($name, $title, $description, get_string('abouttext_default', 'theme_inteb'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Carousel Settings
    $page->add(new admin_setting_heading(
        'theme_inteb_carousel',
        get_string('carouselsettings', 'theme_inteb'),
        ''
    ));

    // Number of slides
    $name = 'theme_inteb/login_numberofslides';
    $title = get_string('numberofslides', 'theme_inteb');
    $description = get_string('numberofslides_desc', 'theme_inteb');
    $choices = range(1, 10);
    $page->add(new admin_setting_configselect($name, $title, $description, 1, array_combine($choices, $choices)));

    // Settings for each slide
    $numslides = get_config('theme_inteb', 'login_numberofslides') ?: 1;
    for ($i = 1; $i <= $numslides; $i++) {
        // Slide title
        $name = 'theme_inteb/login_slidetitle' . $i;
        $title = get_string('slidetitle', 'theme_inteb', $i);
        $description = get_string('slidetitle_desc', 'theme_inteb', $i);
        $page->add(new admin_setting_configtext($name, $title, $description, ''));

        // Slide image
        $name = 'theme_inteb/login_slideimage' . $i;
        $title = get_string('slideimage', 'theme_inteb', $i);
        $description = get_string('slideimage_desc', 'theme_inteb', $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'login_slideimage' . $i, 0, [
            'subdirs' => 0,
            'accepted_types' => ['web_image']
        ]);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide URL
        $name = 'theme_inteb/login_slideurl' . $i;
        $title = get_string('slideurl', 'theme_inteb', $i);
        $description = get_string('slideurldesc', 'theme_inteb', $i);
        $page->add(new admin_setting_configtext($name, $title, $description, ''));
    }

    // Carousel interval
    $name = 'theme_inteb/login_carouselinterval';
    $title = get_string('carouselinterval', 'theme_inteb');
    $description = get_string('carouselintervaldesc', 'theme_inteb');
    $setting = new admin_setting_configtext($name, $title, $description, '5000');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Chat Settings
    $page->add(new admin_setting_heading(
        'theme_inteb_chat',
        get_string('themesettingschat', 'theme_inteb'),
        get_string('themesettingschatdesc', 'theme_inteb')
    ));

    // Enable chat
    $name = 'theme_inteb/enable_chat';
    $title = get_string('enable_chat', 'theme_inteb');
    $description = get_string('enable_chatdesc', 'theme_inteb');
    $page->add(new admin_setting_configcheckbox($name, $title, $description, 0));

    // Tawk.to URL
    $name = 'theme_inteb/tawkto_embed_url';
    $title = get_string('tawkto_embed_url', 'theme_inteb');
    $description = get_string('tawkto_embed_urldesc', 'theme_inteb');
    $page->add(new admin_setting_configtext($name, $title, $description, ''));

    // Copy/Paste Settings
    $page->add(new admin_setting_heading(
        'theme_inteb_copypaste',
        get_string('themesettingscopypaste', 'theme_inteb'),
        get_string('themesettingscopypaste_desc', 'theme_inteb')
    ));

    // Copy/Paste prevention
    $name = 'theme_inteb/copypaste_prevention';
    $title = get_string('copypaste_prevention', 'theme_inteb');
    $description = get_string('copypaste_preventiondesc', 'theme_inteb');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Copy/Paste roles
    require_once($CFG->libdir . '/accesslib.php');
    $roles = role_get_names(null, ROLENAME_ORIGINAL);
    $roles_array = [];
    foreach ($roles as $role) {
        $roles_array[$role->id] = $role->localname;
    }

    $name = 'theme_inteb/copypaste_roles';
    $title = get_string('copypaste_roles', 'theme_inteb');
    $description = get_string('copypaste_rolesdesc', 'theme_inteb');
    $setting = new admin_setting_configmultiselect($name, $title, $description, [5], $roles_array);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $asettings->add($page);

    if ($parent_tabs !== null) {
        $all_tabs = array_merge($asettings->get_tabs(), $parent_tabs);
        $asettings->set_tabs($all_tabs);
    }
}

$ADMIN->add('theme_inteb', $asettings);
