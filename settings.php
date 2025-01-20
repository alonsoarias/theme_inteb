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

    // ===============
    // Dynamic strings
    // ===============
    $a = new stdClass;
    $a->example_banner = (string) $OUTPUT->image_url('example_banner', 'theme_inteb');
    $a->cover_remui = (string) $OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string) $OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string) $OUTPUT->image_url('login_bg', 'theme');

    // =========================
    // Sección de opciones generales
    // =========================
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
    $choices = array(
        'off'    => get_string('generalnoticemode_off', 'theme_inteb'),
        'info'   => get_string('generalnoticemode_info', 'theme_inteb'),
        'danger' => get_string('generalnoticemode_danger', 'theme_inteb')
    );
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

    // Toggle visibility of front page sections
    $name = 'theme_inteb/hidefrontpagesections';
    $title = get_string('hidefrontpagesections', 'theme_inteb');
    $description = get_string('hidefrontpagesections_desc', 'theme_inteb');
    $default = 0;  // Default to showing the sections
    $choices = array(
        '0' => get_string('show', 'theme_inteb'),
        '1' => get_string('hide', 'theme_inteb')
    );
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // =========================
    // Sección de login
    // =========================
    $page->add(new admin_setting_heading('themesettingslogin', get_string('themesettingslogin', 'theme_inteb'), ''));

    // Login Welcome Image.
    $name = 'theme_inteb/loginimage';
    $title = get_string('loginimage', 'theme_inteb');
    $description = get_string('loginimagedesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginimage', 0, [
        'subdirs' => 0, 'accepted_types' => 'web_image'
    ]);
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

    // =========================
    // Sección del carrusel
    // =========================
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
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'slideimage' . $i, 0, [
            'subdirs' => 0, 'accepted_types' => 'web_image'
        ]);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Slide URL
        $name = 'theme_inteb/slideurl' . $i;
        $title = get_string('slideurl', 'theme_inteb', $i);
        $description = get_string('slideurldesc', 'theme_inteb', $i);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $page->add($setting);
    }

    // =========================
    // SECCIÓN: Chat (copiada y adaptada de aeronova)
    // =========================
    $page->add(new admin_setting_heading(
        'theme_inteb_chat',
        get_string('themesettingschat', 'theme_inteb'),       // Definir en lang/theme_inteb.php
        get_string('themesettingschatdesc', 'theme_inteb')    // Definir en lang/theme_inteb.php
    ));

    // Habilitar/Deshabilitar Chat
    $name = 'theme_inteb/enable_chat';
    $title = get_string('enable_chat', 'theme_inteb');            // Definir en lang
    $description = get_string('enable_chatdesc', 'theme_inteb');  // Definir en lang
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // URL de Tawk.to (o cualquier otro chat)
    $name = 'theme_inteb/tawkto_embed_url';
    $title = get_string('tawkto_embed_url', 'theme_inteb');           // Definir en lang
    $description = get_string('tawkto_embed_urldesc', 'theme_inteb'); // Definir en lang
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // =========================
    // NUEVA SECCIÓN: Copy/Paste (reemplaza la versión sencilla)
    // =========================
    $page->add(new admin_setting_heading(
        'theme_inteb_copypaste',
        get_string('themesettingscopypaste', 'theme_inteb'),       // Definir en lang
        get_string('themesettingscopypaste_desc', 'theme_inteb')   // Definir en lang
    ));

    // Checkbox para prevenir Copy/Paste
    $name = 'theme_inteb/copypaste_prevention';
    $title = get_string('copypaste_prevention', 'theme_inteb');        // Definir en lang
    $description = get_string('copypaste_preventiondesc', 'theme_inteb'); // Definir en lang
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Roles a los que aplicar la restricción
    require_once($CFG->libdir . '/accesslib.php');
    $roles = role_get_names(null, ROLENAME_ORIGINAL);
    $roles_array = [];
    foreach ($roles as $role) {
        $roles_array[$role->id] = $role->localname;
    }

    $name = 'theme_inteb/copypaste_roles';
    $title = get_string('copypaste_roles', 'theme_inteb');          // Definir en lang
    $description = get_string('copypaste_rolesdesc', 'theme_inteb'); // Definir en lang
    $default = [5]; // Ej: 5 suele ser "estudiante"
    $setting = new admin_setting_configmultiselect($name, $title, $description, $default, $roles_array);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // =========================
    // Inserta la página completa en las tabs del theme
    // =========================
    $settings->insert_tab($page);
}
