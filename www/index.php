<?php
require '../vendor/autoload.php';
require '../lib/i18n/i18n.php';

$app = new \Slim\Slim(array(
  'view' => new \Slim\Views\Twig(),
  'mode' => 'development',
  'log.enabled' => true,
  'log.level' => \Slim\Log::DEBUG,
  'templates.path' => './templates'
));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache'
);

$view->parserExtensions = array(
  new \Slim\Views\TwigExtension(),
);

\Slim\Route::setDefaultConditions(array(
    'lang' => 'en|mr|hi'
));

$setLanguage = function ($lang) use ($app) {
  return function () use ($lang, $app) {
    $route = $app->router()->getCurrentRoute();
    $params = $route->getParams();
    $langCode = isset($params[$lang]) ? $params[$lang] : 'mr';
    $i18n = new \i18n\i18n($langCode, dirname(__FILE__) . '/locale');
    $view = $app->view();
    $twig = $view->getEnvironment();
    $twig->addGlobal('trans', $i18n);
    error_log("----------LANG--------".$langCode);
  };
};

$app->get('(/:lang)/', $setLanguage('lang'), function ($lang = 'mr') use ($app, $i18n) {
  return $app->render('pages/home.html', array(
    'lang' => $lang,
    'page' => 'home'
  ));
})->name('home');

$app->get('(/:lang)/about', $setLanguage('lang'), function ($lang = 'mr') use ($app, $i18n) {
  return $app->render('pages/about.html', array(
    'lang' => $lang,
    'page' => 'about'
  ));
})->name('about');

$app->get('(/:lang)/activities', $setLanguage('lang'), function ($lang = 'mr') use ($app) {
  return $app->render('pages/activities.html', array(
    'lang' => $lang,
    'page' => 'activities'
  ));
})->name('activities');

$app->get('(/:lang)/festivals', $setLanguage('lang'), function ($lang = 'mr') use ($app) {
  return $app->render('pages/festivals.html', array(
    'lang' => $lang,
    'page' => 'festivals'
  ));
})->name('festivals');

$app->get('(/:lang)/trust', $setLanguage('lang'), function ($lang = 'mr') use ($app) {
  return $app->render('pages/trust.html', array(
    'lang' => $lang,
    'page' => 'trust'
  ));
})->name('trust');

$app->get('(/:lang)/photos', $setLanguage('lang'), function ($lang = 'mr') use ($app) {
  return $app->render('pages/photos.html', array(
    'lang' => $lang,
    'page' => 'photos'
  ));
})->name('photos');

$app->get('(/:lang)/spots-in-akole', $setLanguage('lang'), function ($lang = 'mr') use ($app) {
  return $app->render('pages/spots-in-akole.html', array(
    'lang' => $lang,
    'page' => 'spots-in-akole'
  ));
})->name('spots-in-akole');

$app->run();
?>
