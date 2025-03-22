<?php
/**
 * Lib functions for theme_inteb
 *
 * @package   theme_inteb
 * @copyright (c) 2025 IngeWeb <soporte@ingeweb.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Pedro Arias <soporte@ingeweb.co>
 */

defined('MOODLE_INTERNAL') || die();

// Load our license override autoloader
require_once(__DIR__ . '/classes/license_autoload.php');

require_once(__DIR__ . '/../remui/lib.php');

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_inteb_get_extra_scss($theme) {
    $scss = '';
    // Cargando SCSS existente de variables y estilos personalizados
    if (file_exists(__DIR__ . '/scss/_variables.scss')) {
        $scss .= file_get_contents(__DIR__ . '/scss/_variables.scss');
    }
    if (file_exists(__DIR__ . '/scss/custom_variables.scss')) {
        $scss .= file_get_contents(__DIR__ . '/scss/custom_variables.scss');
    }
    if (file_exists(__DIR__ . '/scss/inteb.scss')) {
        $scss .= file_get_contents(__DIR__ . '/scss/inteb.scss');
    }

    // Añadiendo el contenido de custom.css
    if (file_exists(__DIR__ . '/style/custom.css')) {
        $customCss = file_get_contents(__DIR__ . '/style/custom.css');
        $scss .= $customCss;
    }

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_inteb_get_pre_scss($theme)
{
    $scss = theme_remui_get_extra_scss($theme);

    return $scss;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_inteb_get_main_scss_content($theme) {
    global $CFG;

    // Primero, cargar el SCSS del tema padre (RemUI) directamente, ya que no podemos 
    // confiar en method_exists en este caso específico. Utilizamos la lógica original
    // de theme_remui_get_main_scss_content.
    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    $context = context_system::instance();

    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_remui', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Fallback de seguridad a default.scss si no se encuentra el preset especificado.
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/default.scss');
    }

    // Luego, cargar las personalizaciones SCSS de inteb.
    $intebVariables = '';
    $customVariables = '';
    $intebScss = '';

    if (file_exists($CFG->dirroot . '/theme/inteb/scss/_variables.scss')) {
        $intebVariables = file_get_contents($CFG->dirroot . '/theme/inteb/scss/_variables.scss');
    }
    if (file_exists($CFG->dirroot . '/theme/inteb/scss/custom_variables.scss')) {
        $customVariables = file_get_contents($CFG->dirroot . '/theme/inteb/scss/custom_variables.scss');
    }
    if (file_exists($CFG->dirroot . '/theme/inteb/scss/inteb.scss')) {
        $intebScss = file_get_contents($CFG->dirroot . '/theme/inteb/scss/inteb.scss');
    }

    // Cargar cualquier CSS personalizado desde 'custom.css'.
    $customCss = '';
    if (file_exists($CFG->dirroot . '/theme/inteb/style/custom.css')) {
        $customCss = file_get_contents($CFG->dirroot . '/theme/inteb/style/custom.css');
    }

    // Combinar todos los estilos en el orden correcto.
    $combinedScssContent = $scss . "\n" . $intebVariables . "\n" . $customVariables . "\n" . $intebScss . "\n" . $customCss;

    return $combinedScssContent;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return mixed
 */
function theme_inteb_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    $theme = theme_config::load('inteb');

    if ($context->contextlevel == CONTEXT_SYSTEM) {
        // Serve theme files with prefixed names
        if ($filearea === 'ib_personalareaheader') {
            return $theme->setting_file_serve('ib_personalareaheader', $args, $forcedownload, $options);
        }
        if ($filearea === 'ib_mycoursesheader') {
            return $theme->setting_file_serve('ib_mycoursesheader', $args, $forcedownload, $options);
        }

        // Check if the file area corresponds to the carousel images.
        if (strpos($filearea, 'ib_login_slideimage') === 0) {
            // Extract the slide number from the file area name.
            $slide_number = substr($filearea, strlen('ib_login_slideimage'));
            // Serve the slide image.
            return $theme->setting_file_serve("ib_login_slideimage{$slide_number}", $args, $forcedownload, $options);
        }
    }

    return theme_remui_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options);
}

/**
 * Callback called immediately after the theme is initially set in $PAGE.
 * Used to ensure we're using our overridden license controller.
 */
function theme_inteb_page_init() {
    // Apply our license override
    theme_inteb_license_autoload();
    
    // Force a valid license status
    set_config(EDD_LICENSE_STATUS, 'valid', 'theme_remui');
}

/**
 * Function to register necessary event handlers to automatically 
 * activate license on every page load.
 */
function theme_inteb_before_footer() {
    // Ensure license is valid on every page
    theme_inteb_page_init();
    return '';
}

/**
 * This function is called when outputting a URL and makes sure Moodle
 * links have license override applied.
 *
 * @param moodle_url $url
 * @param array $options
 * @return string URL
 */
function theme_inteb_get_url($url, $options = array()) {
    // Activate license on URL output
    theme_inteb_page_init();
    return $url;
}

/**
 * Hook that activates the license when theme settings are loaded.
 */
function theme_inteb_pre_settings_load() {
    // Apply our license override
    theme_inteb_license_autoload();
    
    // Force a valid license status
    set_config(EDD_LICENSE_STATUS, 'valid', 'theme_remui');
}

/**
 * Ensures the theme license is validated when rendering CSS.
 * 
 * @param string $css The final CSS.
 * @return string The processed CSS.
 */
function theme_inteb_process_css($css) {
    // Apply license override before processing CSS
    theme_inteb_page_init();
    
    // Return the CSS as is, no additional processing needed here
    return $css;
}

/**
 * Helper function to convert old configuration names to new prefixed ones.
 * 
 * @return void
 */
function theme_inteb_migrate_settings() {
    $oldtonew = [
        'generalnoticemode' => 'ib_generalnoticemode',
        'generalnotice' => 'ib_generalnotice',
        'enable_chat' => 'ib_enable_chat',
        'tawkto_embed_url' => 'ib_tawkto_embed_url',
        'copypaste_prevention' => 'ib_copypaste_prevention',
        'copypaste_roles' => 'ib_copypaste_roles',
        'login_numberofslides' => 'ib_login_numberofslides',
        'login_carouselinterval' => 'ib_login_carouselinterval',
        'showpersonalareaheader' => 'ib_showpersonalareaheader',
        'personalareaheader' => 'ib_personalareaheader',
        'showmycoursesheader' => 'ib_showmycoursesheader',
        'mycoursesheader' => 'ib_mycoursesheader',
        'hidefrontpagesections' => 'ib_hidefrontpagesections',
        'hidefootersections' => 'ib_hidefootersections',
        'abouttitle' => 'ib_abouttitle',
        'abouttext' => 'ib_abouttext'
    ];
    
    // Slide specific settings
    for ($i = 1; $i <= 10; $i++) {
        $oldtonew["login_slidetitle$i"] = "ib_login_slidetitle$i";
        $oldtonew["login_slideurl$i"] = "ib_login_slideurl$i";
        // Las imágenes se migrarán cuando se guarden los nuevos settings
    }
    
    foreach ($oldtonew as $old => $new) {
        $value = get_config('theme_inteb', $old);
        if ($value !== false) {
            set_config($new, $value, 'theme_inteb');
            // Opcional: eliminar el valor antiguo
            // unset_config($old, 'theme_inteb');
        }
    }
}

/**
 * Function to get theme settings with prefixed names
 *
 * @param string $setting Name of the setting
 * @param mixed $default Default value if setting is not found
 * @return mixed The setting value or default
 */
function theme_inteb_get_setting($setting) {
    // Always try with the ib_ prefix first
    $value = get_config('theme_inteb', 'ib_' . $setting);
    
    // If not found, try the non-prefixed version (for backward compatibility)
    if ($value === false) {
        $value = get_config('theme_inteb', $setting);
    }
    
    return $value;
}