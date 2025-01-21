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
 * A drawer based layout for the remui theme.
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $COURSE, $USER;

require_once($CFG->dirroot . '/theme/remui/layout/common.php');
require_once($CFG->libdir . '/behat/lib.php');

$coursecontext = context_course::instance($COURSE->id);

// Ejemplo de condición específica de RemUI para mostrar estadísticas en el dashboard:
if (
    !is_guest($coursecontext, $USER) &&
    \theme_remui\toolbox::get_setting('enabledashboardcoursestats') &&
    $PAGE->pagelayout == 'mydashboard' &&
    $PAGE->pagetype == 'my-index'
) {
    $templatecontext['isdashboardstatsshow'] = true;
}

// Debe llamarse antes de renderizar la plantilla para añadir clases de body, etc.
require_once($CFG->dirroot . '/theme/remui/layout/common_end.php');

// =================== INICIO: Cambios para integrar con theme_inteb ===================
use theme_inteb\util\settings as inteb_settings;

// Instanciamos la clase del tema "inteb" para obtener sus configuraciones.
$themesettings = new inteb_settings();

// Mezclamos los datos del footer que provee nuestra clase:
$templatecontext = array_merge($templatecontext, $themesettings->footer());

// ============================================
// Mostrar cabecera del Área personal (Personal Área)
// solo si se cumplen:
// 1) Estamos en la página "my-index" con contexto de usuario
// 2) El ajuste showpersonalareaheader está habilitado (valor "1")
// ============================================
if ($PAGE->pagetype === 'my-index' && $PAGE->context->contextlevel == CONTEXT_USER) {
    $showpersonalareaheader = !empty($themesettings->theme->settings->showpersonalareaheader)
        && $themesettings->theme->settings->showpersonalareaheader === '1';
    if ($showpersonalareaheader) {
        $templatecontext = array_merge(
            $templatecontext,
            $themesettings->personal_area_header()
        );
    }
}

// ============================================
// Mostrar cabecera de "Mis cursos"
// solo si se cumplen:
// 1) Estamos en la página "my-index" con contexto de sistema
// 2) El ajuste showmycoursesheader está habilitado (valor "1")
// ============================================
if ($PAGE->pagetype === 'my-index' && $PAGE->context->contextlevel == CONTEXT_SYSTEM) {
    $showmycoursesheader = !empty($themesettings->theme->settings->showmycoursesheader)
        && $themesettings->theme->settings->showmycoursesheader === '1';
    if ($showmycoursesheader) {
        $templatecontext = array_merge(
            $templatecontext,
            $themesettings->my_courses_header()
        );
    }
}
// =================== FIN: Cambios para integrar con theme_inteb ===================

// Finalmente, renderizamos la plantilla base de RemUI, pasándole nuestro $templatecontext.
echo $OUTPUT->render_from_template('theme_remui/drawers', $templatecontext);
