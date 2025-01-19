<?php

namespace theme_inteb\util;

use theme_config;

/**
 * Utility class for theme settings specifically for handling footer settings.
 */
class settings {
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

        // Retrieve 'my_credit' from the theme settings or use a default value if not set.
        $templatecontext['my_credit'] = get_string('credit', 'theme_inteb');

        // Retrieve 'abouttext' from the theme settings or use a default value if not set.
        $templatecontext['abouttext'] = !empty($this->theme->settings->abouttext) ? $this->theme->settings->abouttext : get_string('abouttext_default', 'theme_inteb');

        return $templatecontext;
    }
}
