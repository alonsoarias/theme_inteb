<?php
// Este archivo forma parte de Moodle - http://moodle.org/
//
// Moodle es software libre: puedes redistribuirlo y/o modificarlo
// bajo los términos de la Licencia Pública General GNU como publicada por
// la Free Software Foundation, ya sea la versión 3 de la Licencia, o
// (a tu elección) cualquier versión posterior.
//
// Moodle se distribuye con la esperanza de que sea útil,
// pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implícita de
// COMERCIABILIDAD o IDONEIDAD PARA UN PROPÓSITO PARTICULAR. Consulta la
// Licencia Pública General GNU para más detalles.
//
// Deberías haber recibido una copia de la Licencia Pública General GNU
// junto con Moodle. Si no, consulta <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Ajustes para el tema theme_inteb
 *
 * @package   theme_inteb
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Incluimos la clase para admin_settingspage_tabs y settings del tema padre
require_once(__DIR__ . '/classes/admin_settingspage_tabs.php');
require(__DIR__ . '/../remui/settings.php');

// Guardamos las tabs del padre
$parent_tabs = null;
if (isset($settings) && method_exists($settings, 'get_tabs')) {
    $parent_tabs = $settings->get_tabs();
}

// Creamos la categoría propia en "appearance"
$ADMIN->add('appearance', new admin_category('theme_inteb', get_string('configtitle', 'theme_inteb')));

if ($ADMIN->fulltree) {
    // Crear nueva instancia
    $settings = new theme_inteb_admin_settingspage_tabs('themesettinginteb', get_string('configtitle', 'theme_inteb'));

    // Crear nuestra página de configuración
    $page = new admin_settingpage('themesettings', get_string('themesettings', 'theme_inteb'));

    // Variables para strings dinámicos
    $a = new stdClass;
    $a->example_banner = (string)$OUTPUT->image_url('example_banner', 'theme_inteb');
    $a->cover_remui = (string)$OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string)$OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string)$OUTPUT->image_url('login_bg', 'theme');

    // =========================
    // Sección: Opciones generales
    // =========================
    $page->add(new admin_setting_heading(
        'themesettingsgeneral',
        get_string('themesettingsgeneral', 'theme_inteb'),
        ''
    ));

    // Intro Text
    $name = 'theme_inteb/themeinfotext';
    $title = '';
    $description = get_string('themeinfotext', 'theme_inteb');
    $setting = new admin_setting_heading($name, $title, $description);
    $page->add($setting);

    // General Notice Mode
    $name = 'theme_inteb/generalnoticemode';
    $title = get_string('generalnoticemode', 'theme_inteb');
    $description = get_string('generalnoticemodedesc', 'theme_inteb');
    $default = 'off';
    $choices = [
        'off'    => get_string('generalnoticemode_off', 'theme_inteb'),
        'info'   => get_string('generalnoticemode_info', 'theme_inteb'),
        'danger' => get_string('generalnoticemode_danger', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // General Notice Text
    $name = 'theme_inteb/generalnotice';
    $title = get_string('generalnotice', 'theme_inteb');
    $description = get_string('generalnoticedesc', 'theme_inteb');
    $default = '<strong>Estamos trabajando</strong> para mejorar. Es posible que por momentos la plataforma experimente comportamientos extraños.';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mostrar/Ocultar secciones de la front page
    $name = 'theme_inteb/hidefrontpagesections';
    $title = get_string('hidefrontpagesections', 'theme_inteb');
    $description = get_string('hidefrontpagesections_desc', 'theme_inteb');
    $default = 0;
    $choices = [
        '0' => get_string('show', 'theme_inteb'),
        '1' => get_string('hide', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Mostrar/Ocultar secciones del footer
    $name = 'theme_inteb/hidefootersections';
    $title = get_string('hidefootersections', 'theme_inteb');
    $description = get_string('hidefootersections_desc', 'theme_inteb');
    $default = 0;
    $choices = [
        '0' => get_string('show', 'theme_inteb'),
        '1' => get_string('hide', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Imagen de cabecera para Área personal
    $name = 'theme_inteb/personalareaheader';
    $title = get_string('personalareaheader', 'theme_inteb');
    $description = get_string('personalareaheaderdesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'personalareaheader', 0, [
        'subdirs' => 0,
        'accepted_types' => ['web_image']
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mostrar/Ocultar imagen de Área personal
    $name = 'theme_inteb/showpersonalareaheader';
    $title = get_string('showpersonalareaheader', 'theme_inteb');
    $description = get_string('showpersonalareaheader_desc', 'theme_inteb');
    $default = '0';
    $choices = [
        '1' => get_string('show', 'theme_inteb'),
        '0' => get_string('hide', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Imagen de cabecera para "Mis cursos"
    $name = 'theme_inteb/mycoursesheader';
    $title = get_string('mycoursesheader', 'theme_inteb');
    $description = get_string('mycoursesheaderdesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'mycoursesheader', 0, [
        'subdirs' => 0,
        'accepted_types' => ['web_image']
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Mostrar/Ocultar imagen de "Mis cursos"
    $name = 'theme_inteb/showmycoursesheader';
    $title = get_string('showmycoursesheader', 'theme_inteb');
    $description = get_string('showmycoursesheader_desc', 'theme_inteb');
    $default = '0';
    $choices = [
        '1' => get_string('show', 'theme_inteb'),
        '0' => get_string('hide', 'theme_inteb')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // =========================
    // Sección: Login
    // =========================
    $page->add(new admin_setting_heading(
        'themesettingslogin',
        get_string('themesettingslogin', 'theme_inteb'),
        ''
    ));

    // Login Welcome Image
    $name = 'theme_inteb/loginimage';
    $title = get_string('loginimage', 'theme_inteb');
    $description = get_string('loginimagedesc', 'theme_inteb', $a);
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'loginimage',
        0,
        ['subdirs' => 0, 'accepted_types' => ['web_image']]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login Background Color
    $name = 'theme_inteb/loginbg_color';
    $title = get_string('loginbg_color', 'theme_inteb');
    $description = get_string('loginbg_colordesc', 'theme_inteb');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#b2cdea');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // About text
    $name = 'theme_inteb/abouttext';
    $title = get_string('abouttext', 'theme_inteb');
    $description = get_string('abouttextdesc', 'theme_inteb');
    $setting = new admin_setting_confightmleditor(
        $name,
        $title,
        $description,
        get_string('abouttext_default', 'theme_inteb')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // =========================
    // Sección: Carrusel
    // =========================
    $page->add(new admin_setting_heading(
        'theme_inteb_carousel',
        get_string('carouselsettings', 'theme_inteb'),
        ''
    ));

    // Número de slides
    $name = 'theme_inteb/numberofslides';
    $title = get_string('numberofslides', 'theme_inteb');
    $description = get_string('numberofslides_desc', 'theme_inteb');
    $default = 1;
    $choices = range(1, 10);
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        $default,
        array_combine($choices, $choices)
    );
    $page->add($setting);

    // Configuración para cada slide
    $numslides = get_config('theme_inteb', 'numberofslides');
    if (empty($numslides)) {
        $numslides = $default;
    }

    for ($i = 1; $i <= $numslides; $i++) {
        // Título del slide
        $name = 'theme_inteb/slidetitle' . $i;
        $title = get_string('slidetitle', 'theme_inteb', $i);
        $description = get_string('slidetitle_desc', 'theme_inteb', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $page->add($setting);

        // Imagen del slide
        $name = 'theme_inteb/slideimage' . $i;
        $title = get_string('slideimage', 'theme_inteb', $i);
        $description = get_string('slideimage_desc', 'theme_inteb', $i);
        $setting = new admin_setting_configstoredfile(
            $name,
            $title,
            $description,
            'slideimage' . $i,
            0,
            ['subdirs' => 0, 'accepted_types' => ['web_image']]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // URL del slide
        $name = 'theme_inteb/slideurl' . $i;
        $title = get_string('slideurl', 'theme_inteb', $i);
        $description = get_string('slideurldesc', 'theme_inteb', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $page->add($setting);
    }

    // Intervalo del carrusel
    $name = 'theme_inteb/carouselinterval';
    $title = get_string('carouselinterval', 'theme_inteb');
    $description = get_string('carouselintervaldesc', 'theme_inteb');
    $default = '5000';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // =========================
    // Sección: Chat
    // =========================
    $page->add(new admin_setting_heading(
        'theme_inteb_chat',
        get_string('themesettingschat', 'theme_inteb'),
        get_string('themesettingschatdesc', 'theme_inteb')
    ));

    // Habilitar/Deshabilitar Chat
    $name = 'theme_inteb/enable_chat';
    $title = get_string('enable_chat', 'theme_inteb');
    $description = get_string('enable_chatdesc', 'theme_inteb');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // URL de Tawk.to
    $name = 'theme_inteb/tawkto_embed_url';
    $title = get_string('tawkto_embed_url', 'theme_inteb');
    $description = get_string('tawkto_embed_urldesc', 'theme_inteb');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // =========================
    // Sección: Copy/Paste
    // =========================
    $page->add(new admin_setting_heading(
        'theme_inteb_copypaste',
        get_string('themesettingscopypaste', 'theme_inteb'),
        get_string('themesettingscopypaste_desc', 'theme_inteb')
    ));

    // Prevención de Copy/Paste
    $name = 'theme_inteb/copypaste_prevention';
    $title = get_string('copypaste_prevention', 'theme_inteb');
    $description = get_string('copypaste_preventiondesc', 'theme_inteb');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Roles para restricción de Copy/Paste
    require_once($CFG->libdir . '/accesslib.php');
    $roles = role_get_names(null, ROLENAME_ORIGINAL);
    $roles_array = [];
    foreach ($roles as $role) {
        $roles_array[$role->id] = $role->localname;
    }

    $name = 'theme_inteb/copypaste_roles';
    $title = get_string('copypaste_roles', 'theme_inteb');
    $description = get_string('copypaste_rolesdesc', 'theme_inteb');
    $default = [5]; // Típicamente "student" = ID 5
    $setting = new admin_setting_configmultiselect($name, $title, $description, $default, $roles_array);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // 5. Agregar nuestra página como una nueva tab
    $settings->add($page);

    // =============================================
    // 6) Reordenamos las tabs para que las nuestras
    //    aparezcan primero
    // =============================================
    $my_tabs = $settings->get_tabs();
    if ($parent_tabs !== null) {
        $all_tabs = array_merge($my_tabs, $parent_tabs);  // Primero las nuestras, luego las del padre
        $settings->set_tabs($all_tabs);
    }
}

// 7. Agregar la configuración a la categoría
$ADMIN->add('theme_inteb', $settings);