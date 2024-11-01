<?php
if (!defined('ABSPATH')) {
  exit;
}

require_once(__DIR__ . '/../CrawlerDetect/init.php');

use Jaybizzle\CrawlerDetect\CrawlerDetect;

// Admin panel VUE app init sources
function vmv_admin_frontend()
{
  if ((is_admin()) && (array_key_exists('page', $_GET)) && $_GET['page'] == VMV_ONE__ADMIN_PAGE) {
    wp_register_script('vmv_preloader_frontend_appjs', VMV_ONE__PLUGIN_URL . VMV_ONE__BASE . 'front-end-vue3/dist/assets/index.js', array(), VMV_ONE__PLUGIN_VERSION, false);
    wp_register_script('vmv_preloader_frontend_vendors', VMV_ONE__PLUGIN_URL . VMV_ONE__BASE . 'front-end-vue3/dist/assets/vendor.js', array(), VMV_ONE__PLUGIN_VERSION, false);
    wp_register_style('vmv_preloader_css', VMV_ONE__PLUGIN_URL . VMV_ONE__BASE . 'front-end-vue3/dist/assets/index.css', array(), VMV_ONE__PLUGIN_VERSION, 'screen');
    wp_enqueue_script('vmv_preloader_frontend_appjs');
    wp_enqueue_script('vmv_preloader_frontend_vendors');
    wp_enqueue_style('vmv_preloader_css');
  }
}

function set_scripts_type_attribute($tag, $handle, $src)
{
  if ('vmv_preloader_frontend_appjs' === $handle) {
    $tag = '<script type="module" crossorigin src="' . $src . '"></script>';
  }
  if ('vmv_preloader_frontend_vendors' === $handle) {
    $tag = '<link rel="modulepreload" href="' . $src . '">';
  }

  return $tag;
}

add_filter('script_loader_tag', 'set_scripts_type_attribute', 10, 3);

function vmv_admin_plugin_links($links, $file)
{
  $plugin_file = explode(VMV_ONE__PLUGIN_DIR_NAME . '/', VMV_ONE__PLUGIN_FILE);
  $plugin_file = VMV_ONE__PLUGIN_DIR_NAME . '/' . $plugin_file[1];
  if ($file == $plugin_file) {
    $settings = '<a href="' . VMV_ONE__PLUGIN_ADMIN_URL . '">Settings</a>';
    array_unshift($links, $settings);
  }

  return $links;
}

// End Admin panel VUE app init sources

function put_base_code_to_template()
{
  global $post;
  $crawler = new CrawlerDetect;
  $option = vmv_preloader_get_option();

  $pageId = $option['previewIdPage'];
  $demoView = ($post->ID == $pageId);

  $isNotCrawler = !$crawler->isCrawler();
  $currentCookie = 'vmv_PredL_' . $option['timeStamp'];
  $isDemoPage = ($post->post_type == 'page' && $demoView);
  $isNotExistCookie = !isset($_COOKIE[$currentCookie]);
  $pluginActivated = $option['pluginActivated'];
  $needIncludeVMV = ($isNotExistCookie && $pluginActivated && $isNotCrawler || $isDemoPage);

  if ($needIncludeVMV) {
    $themeFileJs = $option['themeComponent'] ?? 'type1';
    $pathJsThemeFile = VMV_ONE__PLUGIN_URL . VMV_ONE__BASE . 'theme-js/' . $themeFileJs . '.js';
    $preloaderParams = [];
    $preloaderParams += [
      'pageId' => $option['previewIdPage'],
      'dataGlyphs' => $option['dataGlyphs'] ?? 'null',
      'logoText' => $option['logoText'] ?? 'null',
      'fontSpaceScale' => $option['fontSpaceScale'] ?? 'null',
      'fontBaseHeight' => $option['fontBaseHeight'] ?? 'null',
      'pulseElWidth' => $option['pulseElWidth'] ?? 'null',
      'uniqCorrectSpeedAnimation' => $option['uniqCorrectSpeedAnimation'] ?? 'null',
      'cycleCorrection' => $option['cycleCorrection'] ?? 'null',
      'speedRatio' => $option['speedRatio'] ?? 'null',
      'strokeColor1' => $option['strokeColor1'] ?? 'null',
      'strokeColor2' => $option['strokeColor2'] ?? 'null',
      'strokeColor3' => $option['strokeColor3'] ?? 'null',
      'strokeColor4' => $option['strokeColor4'] ?? 'null',
      'strokeColor5' => $option['strokeColor5'] ?? 'null',
      'strokeColor6' => $option['strokeColor6'] ?? 'null',
      'strokeColor7' => $option['strokeColor7'] ?? 'null',
      'strokeColor8' => $option['strokeColor8'] ?? 'null',
      'shadow' => $option['shadow'] ?? 'null',
      'bg' => $option['bg'] ?? 'null',
      'bgStop1' => $option['bgStop1'] ?? 'null',
      'bgStop2' => $option['bgStop2'] ?? 'null',
      'bgGradientType' => $option['bgGradientType'] ?? 'null',
      'bgGradientDirection' => $option['bgGradientDirection'] ?? 'null',
      'bgImage' => $option['vmvBgData'] && $option['vmvBgIsActive'] ? $option['vmvBgData'] : 'null',
      'w1' => $option['w1'] ?? 'null',
      'w2' => $option['w2'] ?? 'null',
      'w3' => $option['w3'] ?? 'null',
      'w4' => $option['w4'] ?? 'null',
      'timeStamp' => $option['timeStamp'] ?? 'null',
      'typeShow' => boolval($option['viewEachLoad']) ? 'session' : 'localStorage',
      'isDemoView' => boolval($isDemoPage) ? 'true' : 'false',
      'needEndCycle' => boolval($option['needEndCycle']) ? 'true' : 'false',
      'logicView' => $option['logicView'] ?? 'null',
      'maxDuration' => '15',
      'durationWaiting' => $option['timeDelay'] ?? 'null',
      'htmlGenerate' => $option['htmlGenerate'] ?? 'null',
      'htmlBox' => $option['htmlBox'] ?? 'null',
      'cssBox' => $option['cssBox'] ?? 'null',
      'preloaderWidth' => $option['preloaderWidth'] ?? 'null',
      'preloaderHeight' => $option['preloaderHeight'] ?? 'null',
      'pulseBottom' => $option['pulseBottom'] ?? 'null',
      'pulseLeft' => $option['pulseLeft'] ?? 'null',
      'pulseElement' => $option['pulseElement'] ?? 'null',
    ];

    $typeStore = $option['viewEachLoad']?'true':'false';

    echo '<style data-vmv-s0>body,html,div#_vmv_fade{background-color:' . $bg . ' !important}div#_vmv_fade{z-index:2147483638;position:fixed;top:0;left:0;height:100vh;width:100vw;transform:translateZ(2147483638em)}</style>';
    echo '<script data-vm1v-j0>(()=>{if("undefined"==typeof window)return!1;const e=' . $typeStore . '?window.sessionStorage:window.localStorage;var i=!e.getItem("'. $currentCookie .'")||' . $preloaderParams['isDemoView'] . ';window._vmv_$s0={isActive:i},i&&document.querySelector(":root").insertAdjacentHTML("afterbegin","<div id=\'_vmv_fade\'></div><vmv-preloader></vmv-preloader>")})();</script>';
    echo '<script data-vm1v-j1>window._vmv_$s1={';
    while (list($key, $val) = each($preloaderParams)) {
      if ($val == 'null' || $val == 'true' || $val == 'false' || gettype($val) == 'integer' || $key=='dataGlyphs') {
        echo "$key:$val,";
      } else {
        echo "$key:\"$val\",";
      }
    }
    echo '}</script><script rel="preload" src="' . $pathJsThemeFile . '"></script>';
  }
}

// Init demo template  
function vmv_add_custom_demo_template($template)
{
  global $post;
  $option = vmv_preloader_get_option();
  $pageId = $option['previewIdPage'];
  $demoView = ($post->ID == $pageId);
  $isDemoPage = ($post->post_type == 'page' && $demoView);

  if ($isDemoPage) {
    return VMV_ONE__PLUGIN_DIR . 'preview-template/vmv-preloader-preview.php';
  }
  return $template;
}

add_filter('page_template', 'vmv_add_custom_demo_template');

function vmv_preloader_head_section()
{
  put_base_code_to_template();
}
