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