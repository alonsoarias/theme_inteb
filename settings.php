<?php
// This file is part of Moodle - http://moodle.org/
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for theme_inteb
 *
 * @package   theme_inteb
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// ===============================================
// 1) Limpiamos la variable $settings para evitar
//    colisiones con el tema padre (remui).
// ===============================================
unset($settings);
$settings = null;

// ===============================================================
// 2) Incluimos el settings del tema padre. Esto normalmente crea
//    una admin_settingpage('themesettingremui', ...) y la añade
//    a la sección "appearance". Renombraremos dicha página para 
//    no tener conflicto con nuestro propio "themesettinginteb".
// ===============================================================
require_once(__DIR__ . '/../remui/settings.php');

// Si el padre realmente creó algo en $settings, lo renombramos:
if (!empty($settings) && method_exists($settings, 'get_name')) {
    // Por ejemplo, si era "themesettingremui", lo cambiamos a otro nombre interno.
    $settings->set_name('themesettingremui_parent');
}

// ==================================================
// 3) Incluimos la clase para admin_settingspage_tabs
// ==================================================
require_once(__DIR__ . '/classes/admin_settingspage_tabs.php');

// ===================================================================
// 4) Creamos la categoría propia en "appearance" para nuestro tema.
// ===================================================================
$ADMIN->add('appearance', new admin_category('theme_inteb', get_string('configtitle', 'theme_inteb')));

// 4a) Si el padre retornó pestañas, las tomamos antes de sobrescribir $settings.
$parenttabs = [];
if (!empty($settings) && method_exists($settings, 'get_tabs')) {
    $parenttabs = $settings->get_tabs();
}

// 4b) Creamos nuestra propia instancia de admin_settingspage_tabs.
$intebsettings = new theme_inteb_admin_settingspage_tabs('themesettinginteb', get_string('themesettings', 'theme_inteb'));

// ============================================================
// 5) Definimos nuestros ajustes SOLO si $ADMIN->fulltree == true
//    (así es la forma estándar en Moodle).
// ============================================================
if ($ADMIN->fulltree) {
    // Creamos una sola página de ajustes (un tab).
    $page = new admin_settingpage('themesettings', get_string('themesettings', 'theme_inteb'));

    // -------------------------------------------------------------------
    // Ejemplo de variables dinámicas (strings, URLs de imagen, etc.)
    // -------------------------------------------------------------------
    $a = new stdClass;
    $a->example_banner = (string) $OUTPUT->image_url('example_banner', 'theme_inteb');
    $a->cover_remui    = (string) $OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string) $OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string) $OUTPUT->image_url('login_bg', 'theme');

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
        'danger' => get_string('generalnoticemode_danger', 'theme_inteb'),
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
        '1' => get_string('hide', 'theme_inteb'),
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
    $default = get_string('abouttext_default', 'theme_inteb');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
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

    // Number of slides
    $name = 'theme_inteb/numberofslides';
    $title = get_string('numberofslides', 'theme_inteb');
    $description = get_string('numberofslides_desc', 'theme_inteb');
    $default = 1;
    $choices = range(1, 10);
    $setting = new admin_setting_configselect($name, $title, $description, $default, array_combine($choices, $choices));
    $page->add($setting);

    // Determinar cuántos hay configurados realmente
    $numslides = get_config('theme_inteb', 'numberofslides');
    if (empty($numslides)) {
        $numslides = $default;
    }

    // Configuraciones para cada slide del carrusel
    for ($i = 1; $i <= $numslides; $i++) {
        // Título
        $name = 'theme_inteb/slidetitle' . $i;
        $title = get_string('slidetitle', 'theme_inteb', $i);
        $description = get_string('slidetitle_desc', 'theme_inteb', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $page->add($setting);

        // Imagen
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

        // URL (enlace) opcional
        $name = 'theme_inteb/slideurl' . $i;
        $title = get_string('slideurl', 'theme_inteb', $i);
        $description = get_string('slideurldesc', 'theme_inteb', $i);
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $page->add($setting);
    }

    // Nuevo setting para el intervalo del carrusel (en milisegundos)
    $settingname = 'theme_inteb/carouselinterval';
    $title       = get_string('carouselinterval', 'theme_inteb');
    $description = get_string('carouselintervaldesc', 'theme_inteb');
    $default     = '5000'; // 5 segundos
    $setting     = new admin_setting_configtext($settingname, $title, $description, $default);
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

    // Check para habilitar/deshabilitar Chat
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

    // Check para prevenir Copy/Paste
    $name = 'theme_inteb/copypaste_prevention';
    $title = get_string('copypaste_prevention', 'theme_inteb');
    $description = get_string('copypaste_preventiondesc', 'theme_inteb');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
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
    $title = get_string('copypaste_roles', 'theme_inteb');
    $description = get_string('copypaste_rolesdesc', 'theme_inteb');
    $default = [5]; // Ejemplo: usualmente "student" es el ID 5
    $setting = new admin_setting_configmultiselect($name, $title, $description, $default, $roles_array);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Finalmente, añadimos esta página a nuestra instancia de tabs:
    $intebsettings->add($page);
}

// =============================================
// 6) Insertamos nuestras pestañas antes de las
//    que vinieran del padre, si existen.
// =============================================
$mytabs  = $intebsettings->get_tabs();         // Lo que acabamos de añadir.
$alltabs = array_merge($mytabs, $parenttabs);  // Primero las nuestras, luego las del padre.
$intebsettings->set_tabs($alltabs);

// =====================================================
// 7) Agregamos nuestra configuración a la categoría
//    'theme_inteb' (creada en el paso 4).
// =====================================================
$ADMIN->add('theme_inteb', $intebsettings);
