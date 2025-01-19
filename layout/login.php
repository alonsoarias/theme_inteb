<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
/**
 * Cajasan.
 *
 * @package    theme_inteb
 * @copyright  Creado por Ing Pablo A Pico - @pabloapico exclusivamente para plataformas Moodle creadas y soportadas por ingeweb - Sistemas y Publicidad
 */

defined('MOODLE_INTERNAL') || die();

$extraclasses[] = 'inteb-login';
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
];

echo $OUTPUT->render_from_template('theme_inteb/login', $templatecontext);
