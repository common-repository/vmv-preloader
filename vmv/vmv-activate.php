<?php

if (!defined('ABSPATH')) {
  exit;
}
function get_glyphs($text, $source_file)
{
  $path_preloader_sources = 'preloader-sources/';
  $f_json = VMV_ONE__PLUGIN_DIR . $path_preloader_sources . $source_file;
  $jsonDecoded = json_decode(file_get_contents($f_json), true);
  $logoLetters = str_split($text);
  $arrayGlyphs = [];
  foreach ($logoLetters as $itemLetter) {
    $itemExist = in_array('G__' . ord($itemLetter), array_column($arrayGlyphs, 'id'));
    if ($itemExist === false) {
      $itemIndex = array_search('G__' . ord($itemLetter), array_column($jsonDecoded['glyphs'], 'id'));
      $arrayGlyphs[] = $jsonDecoded['glyphs'][$itemIndex];
    }
  }
  return json_encode($arrayGlyphs);
}

function vmv_preloader_activate()
{
  if (vmv_preloader_options_not_exists()) {
    $optionDefault = vmv_preloader_option_defaults();
    add_option(VMV_ONE__OPTION, $optionDefault);
  }

  $option = vmv_preloader_get_option();

  $needAddDemoPage = $option['previewIdPage'] === null;

  if ($needAddDemoPage) {
    $cur_user_id = get_current_user_id();

    $vmv_demo_preview = array(
      'post_title' => 'VMV Preloader DEMO view(don\'t change!!!)',
      'post_status' => 'draft',
      'post_author' => $cur_user_id,
      'post_type' => 'page',
    );

    $postId = wp_insert_post($vmv_demo_preview);

    $new_option['previewIdPage'] = $postId;
    vmv_preloader_update_option($new_option, $option);
  }
}

