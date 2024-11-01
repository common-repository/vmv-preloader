<?php
if (!defined('ABSPATH')) {
  exit;
}

function vmv_preloader_option_defaults()
{
  $path_preloader_sources = 'base-animations/';
  $theme_default = 'Signature.json';
  $f_json = VMV_ONE__PLUGIN_DIR . $path_preloader_sources . $theme_default;
  $arrayValues = json_decode(file_get_contents($f_json), true);
  $glyphs = get_glyphs($arrayValues['logoText'], 'SignatureGlyphs.json');

  $arrayValues += [
    'dataGlyphs' => $glyphs,
    'pluginActivated' => false,
    'delete_option_on_deactivate' => false,
    'viewEachLoad' => false,
    'timeStamp' => 1644573315,
    'timeDelay' => 1,
    'speedRatio' => 6,
    'themeFile' => 'Signature.json',
    'vmvBgIsActive' => false,
    'vmvBgData' => '',
    'logicView' => 'pageLoad',
    'isPremium' => false,
    'needEndCycle' => false,
  ];
  return $arrayValues;
}

function vmv_preloader_get_option()
{
  return get_option(VMV_ONE__OPTION);
}

// send only needed options
function vmv_preloader_get_option_front()
{
  $option = get_option(VMV_ONE__OPTION);
  unset($option['dataGlyphs']);
  unset($option['fontSpaceScale']);
  unset($option['fontBaseHeight']);
  unset($option['pulseElWidth']);
  unset($option['uniqCorrectSpeedAnimation']);
  unset($option['cycleCorrection']);
  unset($option['timeStamp']);
  unset($option['htmlBox']);
  unset($option['cssBox']);
  unset($option['preloaderWidth']);
  unset($option['preloaderHeight']);
  unset($option['pulseBottom']);
  unset($option['pulseLeft']);
  unset($option['pulseElement']);
  return $option;
}

function vmv_preloader_update_option($new_option, $option): bool
{
  $updated_option = array_merge($option, $new_option);
  $updated = update_option(VMV_ONE__OPTION, $updated_option);
  return $updated == 1;
}

function vmv_preloader_options_not_exists(): bool
{
  return (get_option(VMV_ONE__OPTION) === false);
}
