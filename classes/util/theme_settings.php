<?php

namespace theme_inteb\util;

use theme_config;

/**
 * Utility class for theme settings specifically for handling footer settings and personal area header.
 *
 * @package   theme_inteb
 * @copyright (c) 2025 IngeWeb <soporte@ingeweb.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Pedro Arias <soporte@ingeweb.co>
 */
class theme_settings {
    /**
     * @var stdClass The theme configuration object.
     */
    protected $theme;

    /**
     * Constructor that loads the current theme configuration.
     */
    public function __construct() {
        $this->theme = theme_config::load('inteb');
    }

    /**
     * Retrieves footer settings for the theme.
     *
     * This method gathers the 'my_credit' and 'abouttext' settings from the theme configuration
     * and prepares them for use in the footer template.
     *
     * @return array Context for the footer template with settings data.
     */
    public function footer() {
        $templatecontext = [];
        
        // Retrieve 'my_credit' from the theme settings
        $templatecontext['my_credit'] = get_string('credit', 'theme_inteb');
        
        // Comprobar y cargar correctamente abouttitle
        $templatecontext['abouttitle'] = isset($this->theme->settings->ib_abouttitle) && $this->theme->settings->ib_abouttitle
            ? $this->theme->settings->ib_abouttitle 
            : get_string('abouttitle_default', 'theme_inteb');
        
        // Comprobar y cargar correctamente abouttext
        $templatecontext['abouttext'] = isset($this->theme->settings->ib_abouttext) && $this->theme->settings->ib_abouttext
            ? $this->theme->settings->ib_abouttext 
            : get_string('abouttext_default', 'theme_inteb');
        
        return $templatecontext;
    }

    /**
     * Retrieves personal area header settings for the theme.
     *
     * @return array Context for the personal area header template with settings data.
     */
    public function personal_area_header() {
        $templatecontext = [];

        // Check if personal area header should be shown
        $showheader = !empty($this->theme->settings->ib_show_personalareaheader) && 
            $this->theme->settings->ib_show_personalareaheader == '1';
        
        if ($showheader) {
            $personalareaheader = $this->theme->setting_file_url('ib_personalareaheader', 'ib_personalareaheader');
            if (!empty($personalareaheader)) {
                $templatecontext['headerimage'] = [
                    'url' => $personalareaheader,
                    'title' => get_string('personalareaheader', 'theme_inteb'),
                    'show' => true
                ];
            } else {
                $templatecontext['headerimage'] = [
                    'url' => '',
                    'title' => get_string('defaultheader', 'theme_inteb'),
                    'show' => false
                ];
            }
        } else {
            $templatecontext['headerimage'] = [
                'show' => false
            ];
        }

        return $templatecontext;
    }

    /**
     * Retrieves my courses header settings for the theme.
     *
     * @return array Context for the my courses header template with settings data.
     */
    public function my_courses_header() {
        $templatecontext = [];

        // Check if my courses header should be shown
        $showheader = !empty($this->theme->settings->ib_show_mycoursesheader) && 
            $this->theme->settings->ib_show_mycoursesheader == '1';
        
        if ($showheader) {
            $mycoursesheader = $this->theme->setting_file_url('ib_mycoursesheader', 'ib_mycoursesheader');
            if (!empty($mycoursesheader)) {
                $templatecontext['headerimage'] = [
                    'url' => $mycoursesheader,
                    'title' => get_string('mycoursesheader', 'theme_inteb'),
                    'show' => true
                ];
            } else {
                $templatecontext['headerimage'] = [
                    'url' => '',
                    'title' => get_string('defaultheader', 'theme_inteb'),
                    'show' => false
                ];
            }
        } else {
            $templatecontext['headerimage'] = [
                'show' => false
            ];
        }

        return $templatecontext;
    }
}