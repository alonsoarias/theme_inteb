<?php

defined('MOODLE_INTERNAL') || die();

require(__DIR__ . '/../remui/settings.php');
require_once(__DIR__ . '/classes/admin_settingspage_tabs.php');

if ($ADMIN->fulltree) {
    $tabs = $settings->get_tabs();
    $settings = new theme_inteb_admin_settingspage_tabs('themesettinginteb', get_string('configtitle', 'theme_inteb'));
    $settings->set_tabs($tabs);
    // Creamos una sola página de configuración con múltiples secciones
    $page = new admin_settingpage('themesettings', get_string('themesettings', 'theme_inteb'));

    //Dynamic strings
    $a = new stdClass;
    $a->example_banner = (string) $OUTPUT->image_url('example_banner', 'theme_inteb');
    $a->cover_remui = (string) $OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string) $OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string) $OUTPUT->image_url('login_bg', 'theme');


    // Sección de opciones generales
    $page->add(new admin_setting_heading('themesettingsgeneral', get_string('themesettingsgeneral', 'theme_inteb'), ''));

    // Intro Text.
    $name = 'theme_inteb/themeinfotext';
    $title = '';
    $description = get_string('themeinfotext', 'theme_inteb');
    $setting = new admin_setting_heading($name, $title, $description);
    $page->add($setting);

    // General Notice Mode.
    $name = 'theme_inteb/generalnoticemode';
    $title = get_string('generalnoticemode', 'theme_inteb');
    $description = get_string('generalnoticemodedesc', 'theme_inteb');
    $default = 'off';

    // These are the built-in presets.
    $choices = array();
    $choices['off'] = get_string('generalnoticemode_off', 'theme_inteb');
    $choices['info'] = get_string('generalnoticemode_info', 'theme_inteb');
    $choices['danger'] = get_string('generalnoticemode_danger', 'theme_inteb');

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // General Notice text setting
    $name = 'theme_inteb/generalnotice';
    $title = get_string('generalnotice', 'theme_inteb');
    $description = get_string('generalnoticedesc', 'theme_inteb');
    $default = '<strong>Estamos trabajando</strong> para mejorar. Es posible que por momentos la plataforma experimente comportamientos extraños.';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Enable or disable copy & paste for students
    $setting = new admin_setting_configselect(
        'theme_inteb/copypaste_visibility',
        get_string('config_copypaste', 'theme_inteb'),
        get_string('config_copypaste_desc', 'theme_inteb'),
        0,
        array(
            '0' => get_string('disable', 'theme_inteb'), // Disable option
            '1' => get_string('enable', 'theme_inteb')  // Enable option
        )
    );
    $page->add($setting);

    // Toggle visibility of front page sections
    $name = 'theme_inteb/hidefrontpagesections';
    $title = get_string('hidefrontpagesections', 'theme_inteb');
    $description = get_string('hidefrontpagesections_desc', 'theme_inteb');
    $default = 0;  // Default to showing the sections
    $choices = array(
        '0' => get_string('show', 'theme_inteb'),  // Option to show the sections
        '1' => get_string('hide', 'theme_inteb')   // Option to hide the sections
    );
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Sección de login
    $page->add(new admin_setting_heading('themesettingslogin', get_string('themesettingslogin', 'theme_inteb'), ''));

    // Login Welcome Image.
    $name = 'theme_inteb/loginimage';
    $title = get_string('loginimage', 'theme_inteb');
    $description = get_string('loginimagedesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginimage', 0, array(
        'subdirs' => 0, 'accepted_types' => 'web_image'
    ));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login Background Color
    $name = 'theme_inteb/loginbg_color';
    $title = get_string('loginbg_color', 'theme_inteb');
    $description = get_string('loginbg_colordesc', 'theme_inteb');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#b2cdea');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // About text setting
    $name = 'theme_inteb/abouttext';
    $title = get_string('abouttext', 'theme_inteb');
    $description = get_string('abouttextdesc', 'theme_inteb');
    $setting = new admin_setting_confightmleditor($name, $title, $description, get_string('abouttext_default', 'theme_inteb'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Sección del carrusel
    $page->add(new admin_setting_heading('theme_inteb_carousel', get_string('carouselsettings', 'theme_inteb'), ''));

    // Number of slides in the carousel
    $name = 'theme_inteb/numberofslides';
    $title = get_string('numberofslides', 'theme_inteb');
    $description = get_string('numberofslides_desc', 'theme_inteb');
    $default = 1;
    $choices = range(1, 10);
    $setting = new admin_setting_configselect($name, $title, $description, $default, array_combine($choices, $choices));
    $page->add($setting);

    // Settings for each slide
    $numslides = get_config('theme_inteb', 'numberofslides');
    for ($i = 1; $i <= $numslides; $i++) {
        // Slide Title
        $name = 'theme_inteb/slidetitle' . $i;
        $title = get_string('slidetitle', 'theme_inteb', $i);
        $description = get_string('slidetitle_desc', 'theme_inteb', $i);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);
        // Slide image
        $name = 'theme_inteb/slideimage' . $i;
        $title = get_string('slideimage', 'theme_inteb', $i);
        $description = get_string('slideimage_desc', 'theme_inteb', $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slideimage' . $i, 0, array(
            'subdirs' => 0, 'accepted_types' => 'web_image'
        ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_inteb/slideurl' . $i;
        $title = get_string('slideurl', 'theme_inteb', $i);
        $description = get_string('slideurldesc', 'theme_inteb', $i);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);
    }

    // Añadir al archivo settings.php del tema
    $settingname = 'theme_inteb/carouselinterval';
    $title = get_string('carouselinterval', 'theme_inteb');
    $description = get_string('carouselintervaldesc', 'theme_inteb');
    $default = '5000';  // Default a 5000 milisegundos (5 segundos)
    // Añadir la página al admin tree

    $settings->insert_tab($page);
}
