<?php
defined('MOODLE_INTERNAL') || die();

// Obtener atributos del cuerpo y cargar la configuración del tema.
$bodyattributes = $OUTPUT->body_attributes();
$theme = theme_config::load('inteb');

// Configurar contexto para la plantilla.
$templatecontext = [
    'sitename' => format_string($SITE->fullname, true, ['context' => context_course::instance(SITEID)]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'loginbackground' => '', // Inicializar como vacío.
    'carouselimages' => [],  // Inicializar lista de imágenes del carrusel.
    'carouselinterval' => $theme->settings->carouselinterval ?? 5000, // Intervalo predeterminado: 5000 ms.
    'my_credit' => get_string('credit', 'theme_inteb'),
];

// Configurar imagen de fondo o color.
$loginimageurl = $theme->setting_file_url('loginimage', 'loginimage');
if (!empty($loginimageurl)) {
    $templatecontext['loginbackground'] = "background-image: url('{$loginimageurl}'); background-size: cover;";
} else {
    $loginbgcolor = $theme->settings->loginbg_color ?? '#b2cdea';
    $templatecontext['loginbackground'] = "background-color: {$loginbgcolor};";
}

// Preparar imágenes del carrusel.
$numslides = (int)($theme->settings->numberofslides ?? 0);
for ($i = 1; $i <= $numslides; $i++) {
    $imageurl = $theme->setting_file_url("slideimage{$i}", "slideimage{$i}");
    if (!empty($imageurl)) {
        $slidetitle = $theme->settings->{"slidetitle{$i}"} ?? 'Default Title';
        $slideurl = $theme->settings->{"slideurl{$i}"} ?? '#'; // URL del botón.
        $templatecontext['carouselimages'][] = [
            'url' => $imageurl,
            'link' => $slideurl,
            'title' => $slidetitle,
            'first' => ($i === 1) ? true : false,
        ];
    }
}

// Añadir imagen por defecto si no hay imágenes configuradas.
if (empty($templatecontext['carouselimages'])) {
    $defaultImage = $OUTPUT->image_url('slide0', 'theme'); // slide0 como imagen predeterminada.
    $templatecontext['carouselimages'][] = [
        'url' => (string)$defaultImage,
        'link' => '',
        'title' => 'Default Image Title',
        'first' => true,
    ];
}

// Renderizar desde la plantilla.
echo $OUTPUT->render_from_template('theme_inteb/core/login-custom', $templatecontext);

// Incluir $OUTPUT->main_content() para cumplir con los requisitos de Moodle.
// echo $OUTPUT->main_content();
