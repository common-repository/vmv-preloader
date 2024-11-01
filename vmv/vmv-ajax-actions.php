<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function get_ajax_params() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );

  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $option = vmv_preloader_get_option_front();

  $response = [
    'success' => true,
    'options' => $option
  ];
  echo json_encode( $response );
  die;
}

function set_ajax_params() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );

  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $option     = vmv_preloader_get_option();
  $new_option = array();
  $update_option = 0;

  $arrayItemsBoolean = array(
    "pluginActivated",
    "delete_option_on_deactivate",
    "viewEachLoad",
    "isPremium",
    "vmvBgIsActive",
    "needEndCycle"
  );
  $arrayItemsNumber = array(
    "w1",
    "w2",
    "w3",
    "w4",
    "timeDelay",
    "speedRatio",
  );

  foreach ( $_POST as $key => $value ) {
    if ( in_array( $key, $arrayItemsBoolean ) ) {
      $new_option[ $key ] = filter_var( sanitize_text_field( $value ), FILTER_VALIDATE_BOOLEAN );
    } else if ( in_array( $key, $arrayItemsNumber )){
      $new_option[ $key ] = filter_var( sanitize_text_field( $value ), FILTER_VALIDATE_INT );
    } else {
      $new_option[ $key ] = sanitize_text_field( $value );
    }
  }

  $path_preloader_sources = $new_option['isPremium']=='true' ? 'premium-animations/':'base-animations/';
  $f_json = VMV_ONE__PLUGIN_DIR . $path_preloader_sources . $new_option['themeFile'];

  if ( $option['themeFile'] !== $new_option['themeFile'] ) {
    $rewrite_option = json_decode(file_get_contents($f_json), true);

    unset($rewrite_option['bg']);
    unset($rewrite_option['bgStop1']);
    unset($rewrite_option['bgStop2']);
    unset($rewrite_option['bgGradientType']);
    unset($rewrite_option['bgGradientDirection']);

    $rewrite_option += [
      'pluginActivated' => $new_option['pluginActivated'],
      'delete_option_on_deactivate' => $new_option['delete_option_on_deactivate'],
      'viewEachLoad' => $new_option['viewEachLoad'],
      'w1' =>  $new_option['w1'],
      'w2' =>  $new_option['w2'],
      'w3' =>  $new_option['w3'],
      'w4' =>  $new_option['w4'],
      'bg' =>  $new_option['bg'],
      'bgStop1' =>  $new_option['bgStop1'],
      'bgStop2' =>  $new_option['bgStop2'],
      'bgGradientType' =>  $new_option['bgGradientType'],
      'bgGradientDirection' =>  $new_option['bgGradientDirection'],
      'timeStamp' => $new_option['timeStamp'],
      'timeDelay' => $new_option['timeDelay'],
      'speedRatio' => $new_option['speedRatio'],
      'themeFile' => $new_option['themeFile'],
      'vmvBgIsActive' => $new_option['vmvBgIsActive'],
      'vmvBgData' => $new_option['vmvBgData'],
      'logicView' => $new_option['logicView'],
      'isPremium' => $new_option['isPremium'],
      'needEndCycle' => $new_option['needEndCycle'],
      'previewIdPage' => $option['previewIdPage'],
    ];



    if($new_option['themeFile'] ==='Signature.json'){
      $glyphs = get_glyphs($rewrite_option['logoText'], 'SignatureGlyphs.json');
      $rewrite_option += [
        'dataGlyphs' => $glyphs,
      ];
    }

    delete_option( VMV_ONE__OPTION );
    add_option(VMV_ONE__OPTION, $rewrite_option);

    $update_option = 1;
  } else {

    if($new_option['themeFile'] ==='Signature.json'){
      $new_option['dataGlyphs'] = get_glyphs($new_option['logoText'], 'SignatureGlyphs.json');
    }

    foreach ( $new_option as $key => $value ) {
      if ( $value !== $option[ $key ] ) {
        $update_option = 1;
      }
    }

    if ( $update_option == 1 ) {
      $update_option = vmv_preloader_update_option( $new_option, $option );
    }

  }

  $response = [
    'success' => $update_option == 1,
    'options' => $update_option == 1 ? vmv_preloader_get_option_front() : null
  ];

  echo json_encode( $response );
  die;
}


function vmv_list_base_animations() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );
  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $listNameFiles = array();
  $file_paths    = list_files( VMV_ONE__PLUGIN_DIR . "base-animations", 1 );
  foreach ( $file_paths as $index => $item ) {
    if ( ! empty( wp_basename( $item ) ) && isset( pathinfo( wp_basename( $item ) )['extension'] ) ) {
      array_push( $listNameFiles, wp_basename( $item ) );
    }
  }

  $response = [
    'success' => true,
    'data'    => [
      'list_files' => $listNameFiles,
    ]
  ];
  echo json_encode( $response );
  die;
}

function vmv_upload_file_premium_animations() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );

  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $path_folder_save = VMV_ONE__PLUGIN_DIR . VMV_ONE__UPLOAD_DIR;

  if ( ! file_exists( $path_folder_save ) ) {
    mkdir( $path_folder_save, 0777, true );
  }

  if ( $_FILES['file']['tmp_name'] != '' ) {
    $new_file_path = $path_folder_save . '/' . $_FILES['file']['name'];
    move_uploaded_file( $_FILES['file']['tmp_name'], $new_file_path );
  }

  $response['response'] = "SUCCESS";

  echo json_encode( $response );
  die;
}

function vmv_delete_file_premium_animations() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );

  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $file = VMV_ONE__PLUGIN_DIR . VMV_ONE__UPLOAD_DIR . '/' . sanitize_text_field( $_POST['filename_to_delete'] );

  if ( file_exists( $file ) ) {
    wp_delete_file( $file );
    $response['success'] = true;
  } else {
    $response['success'] = false;
    $response['file']    = $file;
    $response['error']   = 'File not found!';
  }
  echo json_encode( $response );
  die;
}

function vmv_list_premium_animations() {
  $nonce = check_ajax_referer( 'vmv_preloader_token', 'vmv_token', false );
  header( 'Content-Type: application/json' );
  if ( ! $nonce ) {
    $response = [
      'success' => false,
      'error'   => 'Security violation!'
    ];
    echo json_encode( $response );
    die;
  }

  $listNameFiles = array();

  $file_paths = list_files( VMV_ONE__PLUGIN_DIR . VMV_ONE__UPLOAD_DIR, 1 );
  foreach ( $file_paths as $index => $item ) {
    if ( ! empty( wp_basename( $item ) ) && isset( pathinfo( wp_basename( $item ) )['extension'] ) ) {
//      array_push( $listNameFiles, wp_basename( $item ) );
      $listNameFiles[] = wp_basename( $item );
    }
  }

  $response = [
    'success' => true,
    'data'    => [
      'list_files' => $listNameFiles,
    ]
  ];
  echo json_encode( $response );
  die;
}
