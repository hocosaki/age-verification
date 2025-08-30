<?php
/*
 * Plugin Name: Age Verification
 * Description: このプラグインをインストールして有効化するだけで、訪問者がどのページにアクセスしても、初めての訪問時に年齢認証が表示されるようになります。
 * Version: 1.0.0
 * Author: hocosaki
 */

if(!defined('ABSPATH')) {
  exit;
}

// アップデートチェッカーファイルを読み込む
require_once __DIR__ . '/age-verification-updater.php';

function age_verification_hide_body_until_modal() {
  if ( ! isset( $_COOKIE['age_verified'] ) ) {
    echo '<style>
      body { visibility: hidden; overflow: hidden; }
      #age-verification-modal { display: none; }
    </style>';
  }
}
add_action( 'wp_head', 'age_verification_hide_body_until_modal', 0 );

// 設定ファイルを含める
require_once plugin_dir_path( __FILE__ ) . 'age-verification-settings.php';

// プラグイン一覧に設定リンクを追加
function age_verification_settings_link( $links ) {
  $settings_link = '<a href="' . admin_url( 'options-general.php?page=age-verification' ) . '">設定</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'age_verification_settings_link' );


// CORS ヘッダーを追加
function add_cors_http_header() {
  ob_start();
  header( "Access-Control-Allow-Origin: " . esc_url_raw( site_url() ) );
  header( "Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE" );
  header( "Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept" );

  if ( 'OPTIONS' == $_SERVER['REQUEST_METHOD'] ) {
    status_header( 200 );
    exit();
  }
  ob_end_flush();
}
add_action( 'send_headers', 'add_cors_http_header' );

// エンキューするスクリプトとスタイル
function age_verification_enqueue_scripts() {
  // 設定を読み込む
  $options = get_option( 'age_verification_settings' );

  if ( ! isset( $_COOKIE['age_verified'] ) ) {
    $css_file_path = plugin_dir_path( __FILE__ ) . 'css/age-verification.css';
    if ( file_exists( $css_file_path ) ) {
      $css_content = file_get_contents( $css_file_path );
      echo '<style id="age-verification-styles-inline">' . $css_content . '</style>';
    }
  }
  //wp_enqueue_style( 'age-verification-style', plugins_url( 'css/age-verification.css', __FILE__ ) );
  wp_enqueue_script( 'age-verification-script', plugins_url( 'js/age-verification.js', __FILE__ ), array( 'jquery' ), null, true );

  // JavaScriptに設定を渡す
  wp_localize_script( 'age-verification-script', 'age_verification_settings', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce' => wp_create_nonce( 'age-verification-nonce' ),
    'redirect_url' => isset( $options['no_redirect_url'] ) ? esc_url_raw( $options['no_redirect_url'] ) : 'https://yahoo.co.jp', // 設定値を使用
    'background_color' => isset( $options['background_color'] ) ? sanitize_hex_color( $options['background_color'] ) : '#ce5858', // 設定値を使用
    'background_opacity' => isset( $options['background_opacity'] ) ? floatval( $options['background_opacity'] ) : 0.4, // 設定値を使用
    'content_bg_color' => isset( $options['content_bg_color'] ) ? sanitize_hex_color( $options['content_bg_color'] ) : '#ECE7DC', // 設定値を使用
    'content_border_color' => isset( $options['content_border_color'] ) ? sanitize_hex_color( $options['content_border_color'] ) : '#ce5858', // 設定値を使用
    'content_title' => isset( $options['content_title'] ) ? sanitize_text_field( $options['content_title'] ) : '年齢認証', // 設定値を使用
    'content_text' => isset( $options['content_text'] ) ? wp_kses_post( $options['content_text'] ) : 'このサイトは成人向けコンテンツを含んでいます。<br>あなたは18歳以上ですか？', // 設定値を使用
    'yes_button_text' => isset( $options['yes_button_text'] ) ? sanitize_text_field( $options['yes_button_text'] ) : 'はい', // 設定値を使用
    'no_button_text' => isset( $options['no_button_text'] ) ? sanitize_text_field( $options['no_button_text'] ) : 'いいえ', // 設定値を使用
    // ボタンの色の設定を追加
    'same_button_design' => isset( $options['same_button_design'] ) ? (bool)$options['same_button_design'] : false, // 新規：デザイン統一フラグ
    'button_bg_color_unified' => isset( $options['button_bg_color_unified'] ) ? sanitize_hex_color( $options['button_bg_color_unified'] ) : '#f1f1f1', // 新規：統一背景色
    'button_border_color_unified' => isset( $options['button_border_color_unified'] ) ? sanitize_hex_color( $options['button_border_color_unified'] ) : '#ce5858', // 新規：統一ボーダー色
    'yes_button_bg_color' => isset( $options['yes_button_bg_color'] ) ? sanitize_hex_color( $options['yes_button_bg_color'] ) : '#f1f1f1', // 既存：個別 はい 背景色
    'yes_button_border_color' => isset( $options['yes_button_border_color'] ) ? sanitize_hex_color( $options['yes_button_border_color'] ) : '#ce5858', // 既存：個別 はい ボーダー色
    'no_button_bg_color' => isset( $options['no_button_bg_color'] ) ? sanitize_hex_color( $options['no_button_bg_color'] ) : '#f1f1f1', // 既存：個別 いいえ 背景色
    'no_button_border_color' => isset( $options['no_button_border_color'] ) ? sanitize_hex_color( $options['no_button_border_color'] ) : '#ce5858', // 既存：個別 いいえ ボーダー色
  ) );
}
add_action( 'wp_enqueue_scripts', 'age_verification_enqueue_scripts' );

// モーダルHTMLを追加
function age_verification_modal() {
  // クッキーがセットされていない場合のみモーダルを表示
  if ( ! isset( $_COOKIE['age_verified'] ) ) {
    // 設定を読み込む
    $options = get_option( 'age_verification_settings' );
    $content_title = isset( $options['content_title'] ) ? esc_html( $options['content_title'] ) : '年齢認証';
    // wp_kses_post を使用して安全にHTMLを出力
    $content_text = isset( $options['content_text'] ) ? wp_kses_post( $options['content_text'] ) : 'このサイトは成人向けコンテンツを含んでいます。<br>あなたは18歳以上ですか？';
    $yes_button_text = isset( $options['yes_button_text'] ) ? esc_html( $options['yes_button_text'] ) : 'はい';
    $no_button_text = isset( $options['no_button_text'] ) ? esc_html( $options['no_button_text'] ) : 'いいえ';

    ?>
    <div id="age-verification-modal" class="modal">
      <div class="modal-content">
        <h2><?php echo $content_title; ?></h2>
        <p><?php echo $content_text; ?></p>
        <div class="btn-wrap">
          <button id="age-verify-yes"><?php echo $yes_button_text; ?></button>
          <button id="age-verify-no"><?php echo $no_button_text; ?></button>
        </div>
      </div>
    </div>
<?php
  }
}
add_action( 'wp_body_open', 'age_verification_modal' );

function age_verification_ajax_handler() {
  check_ajax_referer( 'age-verification-nonce', 'nonce' );

  if ( isset( $_POST['verified'] ) && $_POST['verified'] === 'true' ) {
    $expiry = time() + ( 86400 * 30 ); // 30日間有効
    setcookie( 'age_verified', 'true', [
      'expires' => $expiry,
      'path' => '/',
      'domain' => $_SERVER['HTTP_HOST'],
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Strict'
    ] );
    wp_send_json_success();
  } else {
    wp_send_json_error();
  }
}
add_action( 'wp_ajax_age_verification', 'age_verification_ajax_handler' );
add_action( 'wp_ajax_nopriv_age_verification', 'age_verification_ajax_handler' );

// 年齢確認チェック用のAJAXハンドラーを追加
function check_age_verification_ajax_handler() {
  check_ajax_referer( 'age-verification-nonce', 'nonce' );

  if ( isset( $_COOKIE['age_verified'] ) && $_COOKIE['age_verified'] === 'true' ) {
    wp_send_json_success();
  } else {
    wp_send_json_error();
  }
}
add_action( 'wp_ajax_check_age_verification', 'check_age_verification_ajax_handler' );
add_action( 'wp_ajax_nopriv_check_age_verification', 'check_age_verification_ajax_handler' );

function add_defer_to_age_verification( $tag, $handle ) {
  if ( 'age-verification-script' === $handle ) {
    return str_replace( ' src', ' defer src', $tag );
  }
  return $tag;
}
add_filter( 'script_loader_tag', 'add_defer_to_age_verification', 10, 2 );

?>