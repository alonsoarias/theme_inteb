<?php

/**
 * Provides the core rendering functionality for the theme_inteb, aligning Moodle's HTML with Bootstrap expectations.
 *
 * This core renderer class extends theme_remui's core renderer, adding specific modifications to enhance and customize
 * the user interface for theme_inteb. Key functionalities include customized login forms, theme settings integration,
 * and dynamic handling of UI elements like carousels and notices based on theme configurations.
 *
 * @package    theme_inteb
 * @category   output
 * @author     Pedro Alonso Arias Balcucho
 * @copyright  2024 Soporte IngeWeb <soporte@ingeweb.co>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_inteb\output;

use theme_config;
use moodle_url;

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__ . '/../../../remui/classes/output/core_renderer.php');
require_once(__DIR__ . '/../util/theme_settings.php');

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 */
class core_renderer extends \theme_remui\output\core_renderer
{

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form)
    {
        global $SITE, $OUTPUT;

        $context = $form->export_for_template($this);

        // Cargar la configuración actual del tema
        $theme = theme_config::load('inteb');

        // Manejar la imagen de fondo o el color para la página de login
        $loginimageurl = $theme->setting_file_url('loginimage', 'loginimage');
        if (!empty($loginimageurl)) {
            $context->loginimageurl = $loginimageurl;
            $context->loginbackground = "background-image: url('{$loginimageurl}'); background-size: cover;";
        } else {
            $loginbgcolor = $theme->settings->loginbg_color ?? '#b2cdea';
            $context->loginbackground = "background-color: {$loginbgcolor};";
        }

        // Obtener el nombre del sitio de la configuración global
        $context->sitename = format_string($SITE->fullname, true, ['context' => \context_course::instance(SITEID)]);
        $context->my_credit = get_string('credit', 'theme_inteb');

        // Recuperar la URL del logo de los ajustes del tema
        $context->logourl = $this->get_theme_logo_url('logo');

        // Preparar las imágenes del carrusel
        $context->carouselimages = [];
        $numslides = (int)$theme->settings->numberofslides;
        for ($i = 1; $i <= $numslides; $i++) {
            $imageurl = $theme->setting_file_url("slideimage{$i}", "slideimage{$i}");
            if (!empty($imageurl)) {
                $slidetitle = $theme->settings->{"slidetitle{$i}"} ?? 'Default Title'; // Usar un título predeterminado si no está configurado
                $context->carouselimages[] = [
                    'url' => $imageurl,
                    'link' => $theme->settings->{"slideurl{$i}"},
                    'title' => $slidetitle,
                    'first' => ($i === 1) ? true : false
                ];
            }
        }

        // Si no hay imágenes en el carrusel, añadir una imagen por defecto
        if (empty($context->carouselimages)) {
            $defaultImage = $OUTPUT->image_url('slide0', 'theme'); // Usando slide0 como imagen por defecto
            $context->carouselimages[] = [
                'url' => (string)$defaultImage,
                'link' => '',
                'title' => 'Default Image Title',
                'first' => true
            ];
        }

        // Establecer el intervalo del carrusel desde los ajustes del tema
        $carouselInterval = $theme->settings->carouselinterval ?? 5000; // Predeterminado a 5000ms si no está configurado
        $context->carouselinterval = $carouselInterval;

        // Renderizar la página de login usando la plantilla específica si existe
        if (file_exists(__DIR__ . "/../../templates/core/login-custom.mustache")) {
            return $this->render_from_template('core/login-custom', $context);
        }

        // De lo contrario, usar la plantilla estándar de login
        return $this->render_from_template('core/login', $context);
    }

    /**
     * Get the welcome image.
     *
     * @return string
     */
    public function get_theme_img_url($img)
    {
        $theme = theme_config::load('inteb');
        return $theme->setting_file_url($img, $img);
    }

    public function get_theme_logo_url($img)
    {
        $theme = theme_config::load('remui');
        return $theme->setting_file_url($img, $img);
    }

    public function standard_footer_html()
    {
        global $CFG, $USER;
        //hack by @pabloapico - Perfdebug for our user...
        //TODO: Make this a setting...
        // Turn perf info on for admins
        /*if ( $USER->id == 2 ) {
			$CFG->perfdebug = 8;
		}*/

        $output = parent::standard_footer_html();
        if ($this->page->theme->settings->copypaste_visibility == 1) {
            $this->do_jquery_prevent_copy();
        }

        // $output .= $this->my_add_chat_html();
        return $output;
    }

    public function full_header()
    {
        global $CFG, $USER, $PAGE;

        $theme = theme_config::load('inteb');
        $output = '';

        // Check if the frontpage sections should be hidden
        if (!empty($theme->settings->hidefrontpagesections)) {
            $output .= '<style>.frontpage-sections { display: none; }</style>';
        }

        // Generate a unique ID for the popup each time the page loads
        $popup_id = bin2hex(random_bytes(8));

        // General notice settings
        if (!empty(trim($theme->settings->generalnotice))) {
            $mode = $theme->settings->generalnoticemode;
            $alert_type = ($mode == 'info') ? 'alert-info' : (($mode == 'danger') ? 'alert-danger' : '');
            if ($alert_type) {
                $output .= '<div class="alert ' . $alert_type . ' mt-4"><strong><i class="fa fa-info-circle"></i></strong> ' . $theme->settings->generalnotice . '</div>';
            }
        }

        // Admin notice for configuration
        if (is_siteadmin() && $theme->settings->generalnoticemode == 'off') {
            $output .= '<div class="alert mt-4"><a href="' . $CFG->wwwroot . '/admin/settings.php?section=themesettingingeweb#theme_inteb"><strong><i class="fa fa-edit"></i></strong> ' . get_string('generalnotice_create', 'theme_inteb') . '</a></div>';
        }

        if (!$this->check_allowed_urls()) {
            $output .= '<style>
            #' . $popup_id . ' {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.75) !important;
                z-index: 10000 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                pointer-events: auto !important;
            }
            html, body {
                overflow: hidden !important; // Prevent scrolling on the whole page
            }
            </style>';

            $output .= '<div id="' . $popup_id . '">';
            $output .= '<div style="padding: 20px; background: white; border: 1px solid #ccc; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">';
            $output .= '<h2 style="color: red;">' . get_string('unauthorized_access', 'theme_inteb') . '</h2>';
            $output .= '<p>' . get_string('unauthorized_access_msg', 'theme_inteb') . '</p>';
            $output .= '</div>';
            $output .= '</div>';

            // Add JavaScript to block access to development tools and prevent all interaction on the page
            $output .= '<script type="text/javascript">
            document.addEventListener("keydown", function(event) {
                if (event.keyCode == 123 || (event.ctrlKey && event.shiftKey && (event.keyCode == 73 || event.keyCode == 74))) {
                    event.preventDefault();
                    alert("' . get_string('devtools_access_disabled', 'theme_inteb') . '");
                    return false;
                }
            });
            setInterval(function() {
                if ((window.outerHeight - window.innerHeight) > 200 || (window.outerWidth - window.innerWidth) > 200) {
                    alert("' . get_string('devtools_access_disabled', 'theme_inteb') . '");
                }
            }, 1000);
            // Prevent any interaction on the underlying page
            // Prevent any interaction on the underlying page
            document.body.style.pointerEvents = \'none\';  // Disable all interactions with the page
            document.addEventListener(\'contextmenu\', event => event.preventDefault());
            document.body.addEventListener(\'click\', function(e) {
                e.stopPropagation(); // Stop the event from propagating
                return false;
            }, true); // Capture phase
            </script>';
        }

        // Continue with the normal full header
        $output .= parent::full_header();
        return $output;
    }

    protected function my_add_chat_html()
    {
        global $USER;
        if (!isloggedin()) return '';

        $codembed = "
        <!--Start of Chat Script-->
        <script type=\"text/javascript\">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        Tawk_API.visitor = {
            name  : '" . $USER->firstname . " " . $USER->lastname . "',
            email : '" . $USER->email . "',
            username : '" . $USER->username . "',
            idnumber : '" . $USER->idnumber . "',
        };
        Tawk_API.onLoad = function(){
            Tawk_API.setAttributes({
              name  : '" . $USER->firstname . " " . $USER->lastname . "',
              email : '" . $USER->email . "',
              username : '" . $USER->username . "',
              idnumber : '" . $USER->idnumber . "',
            }, function(error){});
        };
        (function(){
        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];
        s1.async=true;
    	s1.src='https://embed.tawk.to/XXXXXXXXXXXXXXXXXXXXXXXXXXXX/default';
    	s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Chat Script-->
        ";
        //return $codembed;
        return ''; //TODO:: This scripts shall be configurable

    }
    protected function do_jquery_prevent_copy()
    {
        global $CFG, $USER, $PAGE;
        $puede_copiar = false;

        if (is_siteadmin() || !user_has_role_assignment($USER->id, 5)) {
            $puede_copiar = true;
        }
        if (isloggedin() && !$puede_copiar) {

            //$PAGE->requires->jquery();
            $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/theme/inteb/js/prevent-copy-paste.js'));
        }
    }

    /**
     * Comprueba si la URL actual está en la lista de URLs permitidas.
     * @return bool True si la URL está permitida, False en caso contrario.
     */
    protected function check_allowed_urls()
    {
        global $CFG;
        $allowed_urls = [
            'https://udes.moodlesoporte.net',
            'http://udes.moodlesoporte.net',
            'https://virtual.udes.edu.co',
            'http://virtual.udes.edu.co',
            'https://moodle45.localhost.com',
            'http://moodle45.localhost.com'
        ];

        return in_array($CFG->wwwroot, $allowed_urls);
    }
}
