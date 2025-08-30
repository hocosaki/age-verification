<?php

if(!defined('ABSPATH')) {
  exit;
}

// 設定画面の登録
function age_verification_add_settings_page() {
  add_options_page(
    'Age Verification Settings', // ページのタイトル
    'Age Verification',          // メニュータイトル
    'manage_options',            // 必要な権限
    'age-verification',          // スラッグ
    'age_verification_settings_page_html' // 設定画面を表示する関数
  );
}
add_action( 'admin_menu', 'age_verification_add_settings_page' );

// 設定の登録とセクション、フィールドの追加
function age_verification_settings_init() {
  register_setting( 'age_verification_group', 'age_verification_settings', 'age_verification_sanitize_options' );

  add_settings_section(
    'age_verification_section_appearance', // セクションID
    '外観設定',                          // セクションタイトル
    'age_verification_section_appearance_callback', // セクション説明コールバック関数
    'age-verification'                   // ページスラッグ
  );

  add_settings_field(
    'age_verification_background_color', // フィールドID
    '背景色',                           // フィールドタイトル
    'age_verification_background_color_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_background_opacity', // フィールドID
    '背景の透明度',                         // フィールドタイトル
    'age_verification_background_opacity_callback', // フィールド表示コールバック関数
    'age-verification',                   // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_content_bg_color', // フィールドID
    'コンテンツ枠 背景色',               // フィールドタイトル
    'age_verification_content_bg_color_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_content_border_color', // フィールドID
    'コンテンツ枠 ボーダー色',             // フィールドタイトル
    'age_verification_content_border_color_callback', // フィールド表示コールバック関数
    'age-verification',                   // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  // ボタンデザイン統一設定
  add_settings_field(
    'age_verification_same_button_design', // フィールドID
    'ボタンデザインを統一する',               // フィールドタイトル
    'age_verification_same_button_design_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  // 統一デザイン時のボタン色設定
  add_settings_field(
    'age_verification_button_bg_color_unified', // フィールドID
    '統一ボタン 背景色',               // フィールドタイトル
    'age_verification_button_bg_color_unified_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_button_border_color_unified', // フィールドID
    '統一ボタン ボーダー色',             // フィールドタイトル
    'age_verification_button_border_color_unified_callback', // フィールド表示コールバック関数
    'age-verification',                   // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );


   // ボタンの色設定フィールド (個別設定用)
  add_settings_field(
    'age_verification_yes_button_bg_color', // フィールドID
    '「はい」ボタン 背景色',               // フィールドタイトル
    'age_verification_yes_button_bg_color_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_yes_button_border_color', // フィールドID
    '「はい」ボタン ボーダー色',             // フィールドタイトル
    'age_verification_yes_button_border_color_callback', // フィールド表示コールバック関数
    'age-verification',                   // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_no_button_bg_color', // フィールドID
    '「いいえ」ボタン 背景色',               // フィールドタイトル
    'age_verification_no_button_bg_color_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );

  add_settings_field(
    'age_verification_no_button_border_color', // フィールドID
    '「いいえ」ボタン ボーダー色',             // フィールドタイトル
    'age_verification_no_button_border_color_callback', // フィールド表示コールバック関数
    'age-verification',                   // ページスラッグ
    'age_verification_section_appearance' // セクションID
  );


  add_settings_section(
    'age_verification_section_content', // セクションID
    'コンテンツ設定',                          // セクションタイトル
    'age_verification_section_content_callback', // セクション説明コールバック関数
    'age-verification'                   // ページスラッグ
  );

  add_settings_field(
    'age_verification_content_title', // フィールドID
    'コンテンツ タイトル',               // フィールドタイトル
    'age_verification_content_title_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_content' // セクションID
  );

  add_settings_field(
    'age_verification_content_text', // フィールドID
    'コンテンツ 内容',                // フィールドタイトル
    'age_verification_content_text_callback', // フィールド表示コールバック関数
    'age-verification',                  // ページスラッグ
    'age_verification_section_content' // セクションID
  );

  add_settings_field(
    'age_verification_yes_button_text', // フィールドID
    '「はい」ボタン テキスト',           // フィールドタイトル
    'age_verification_yes_button_text_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_content' // セクションID
  );

  add_settings_field(
    'age_verification_no_button_text', // フィールドID
    '「いいえ」ボタン テキスト',           // フィールドタイトル
    'age_verification_no_button_text_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_content' // セクションID
  );

  add_settings_field(
    'age_verification_no_redirect_url', // フィールドID
    '「いいえ」リンク先URL',           // フィールドタイトル
    'age_verification_no_redirect_url_callback', // フィールド表示コールバック関数
    'age-verification',                 // ページスラッグ
    'age_verification_section_content' // セクションID
  );
}
add_action( 'admin_init', 'age_verification_settings_init' );

function age_verification_sanitize_options( $input ) {
  $sanitized_output = array();
  foreach ( $input as $key => $value ) {
    // 各フィールドの型に合わせてサニタイズ関数を使い分ける
    if ( $key === 'content_text' ) {
      $sanitized_output[$key] = wp_kses_post( $value );
    } elseif ( $key === 'no_redirect_url' ) {
      $sanitized_output[$key] = esc_url_raw( $value );
    } elseif ( in_array( $key, array('content_title', 'yes_button_text', 'no_button_text') ) ) {
      $sanitized_output[$key] = sanitize_text_field( $value );
    } else {
      // その他（色、不透明度、チェックボックスなど）は適切にサニタイズ
      // 例: sanitize_hex_color(), floatval()など
      $sanitized_output[$key] = sanitize_text_field( $value ); // 例として
    }
  }
  return $sanitized_output;
}

// セクションの説明コールバック関数
function age_verification_section_appearance_callback() {
  echo '<p>年齢認証モーダルの表示に関する設定を行います。</p>';
}

function age_verification_section_content_callback() {
  echo '<p>年齢認証モーダルのコンテンツに関する設定を行います。</p>';
}

// フィールドの表示コールバック関数 (既存)
function age_verification_background_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['background_color'] ) ? $options['background_color'] : '#ce5858'; // 初期値
  echo '<input type="text" name="age_verification_settings[background_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ce5858">';
}

function age_verification_background_opacity_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['background_opacity'] ) ? $options['background_opacity'] : '0.4'; // 初期値
  echo '<input type="number" name="age_verification_settings[background_opacity]" value="' . esc_attr( $value ) . '" min="0" max="1" step="0.1">';
  echo '<p class="description">0 (完全透明) から 1 (不透明) の間で設定します。0.1刻み。</p>';
}

function age_verification_content_bg_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['content_bg_color'] ) ? $options['content_bg_color'] : '#ECE7DC'; // 初期値
  echo '<input type="text" name="age_verification_settings[content_bg_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ECE7DC">';
}

function age_verification_content_border_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['content_border_color'] ) ? $options['content_border_color'] : '#ce5858'; // 初期値
  echo '<input type="text" name="age_verification_settings[content_border_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ce5858">';
}

// ボタンデザイン統一チェックボックスのコールバック関数 (新規)
function age_verification_same_button_design_callback() {
  $options = get_option( 'age_verification_settings' );
  $checked = isset( $options['same_button_design'] ) ? checked( $options['same_button_design'], 1, false ) : '';
  echo '<input type="checkbox" id="age_verification_same_button_design" name="age_verification_settings[same_button_design]" value="1" ' . $checked . '>';
  echo '<label for="age_verification_same_button_design">「はい」と「いいえ」のボタンデザインを統一する</label>';
}

// 統一デザイン時のボタン色設定コールバック関数 (新規)
function age_verification_button_bg_color_unified_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['button_bg_color_unified'] ) ? $options['button_bg_color_unified'] : '#f1f1f1'; // 初期値
  echo '<div class="age-verification-unified-button-settings">';
  echo '<input type="text" name="age_verification_settings[button_bg_color_unified]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#f1f1f1">';
  echo '</div>';
}

function age_verification_button_border_color_unified_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['button_border_color_unified'] ) ? $options['button_border_color_unified'] : '#ce5858'; // 初期値
  echo '<div class="age-verification-unified-button-settings">';
  echo '<input type="text" name="age_verification_settings[button_border_color_unified]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ce5858">';
  echo '</div>';
}


// ボタンの色のフィールド表示コールバック関数 (個別設定用 - 既存)
function age_verification_yes_button_bg_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['yes_button_bg_color'] ) ? $options['yes_button_bg_color'] : '#f1f1f1'; // 初期値
  echo '<div class="age-verification-individual-button-settings">';
  echo '<input type="text" name="age_verification_settings[yes_button_bg_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#f1f1f1">';
  echo '</div>';
}

function age_verification_yes_button_border_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['yes_button_border_color'] ) ? $options['yes_button_border_color'] : '#ce5858'; // 初期値
  echo '<div class="age-verification-individual-button-settings">';
  echo '<input type="text" name="age_verification_settings[yes_button_border_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ce5858">';
  echo '</div>';
}

function age_verification_no_button_bg_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['no_button_bg_color'] ) ? $options['no_button_bg_color'] : '#f1f1f1'; // 初期値
  echo '<div class="age-verification-individual-button-settings">';
  echo '<input type="text" name="age_verification_settings[no_button_bg_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#f1f1f1">';
  echo '</div>';
}

function age_verification_no_button_border_color_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['no_button_border_color'] ) ? $options['no_button_border_color'] : '#ce5858'; // 初期値
  echo '<div class="age-verification-individual-button-settings">';
  echo '<input type="text" name="age_verification_settings[no_button_border_color]" value="' . esc_attr( $value ) . '" class="age-verification-color-picker" data-default-color="#ce5858">';
  echo '</div>';
}


// フィールドの表示コールバック関数 (既存)
function age_verification_content_title_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['content_title'] ) ? $options['content_title'] : '年齢認証'; // 初期値
  echo '<input type="text" name="age_verification_settings[content_title]" value="' . esc_attr( $value ) . '" class="regular-text">';
}

function age_verification_content_text_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['content_text'] ) ? $options['content_text'] : 'このサイトは成人向けコンテンツを含んでいます。<br>あなたは18歳以上ですか？';
  // 値をサニタイズして、許可されたタグのみを許可する
  $sanitized_value = wp_kses_post( $value );
  // textareaに表示する際は、エスケープ処理を行う
  echo '<textarea name="age_verification_settings[content_text]" rows="5" cols="50" class="large-text">' . esc_textarea( $sanitized_value ) . '</textarea>';
  echo '<p class="description">利用可能なタグは、a, abbr, blockquote, cite, code, del, em, i, ins, kbd, q, strike, strong, suo, time, var, br, p, span, div, iframe, img です。</p>';
}

function age_verification_yes_button_text_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['yes_button_text'] ) ? $options['yes_button_text'] : 'はい'; // 初期値
  echo '<input type="text" name="age_verification_settings[yes_button_text]" value="' . esc_attr( $value ) . '" class="regular-text">';
}

function age_verification_no_button_text_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['no_button_text'] ) ? $options['no_button_text'] : 'いいえ'; // 初期値
  echo '<input type="text" name="age_verification_settings[no_button_text]" value="' . esc_attr( $value ) . '" class="regular-text">';
}

function age_verification_no_redirect_url_callback() {
  $options = get_option( 'age_verification_settings' );
  $value = isset( $options['no_redirect_url'] ) ? $options['no_redirect_url'] : 'https://yahoo.co.jp'; // 初期値
  echo '<input type="url" name="age_verification_settings[no_redirect_url]" value="' . esc_attr( $value ) . '" class="regular-text">';
}

// 設定画面のHTML
function age_verification_settings_page_html() {
  // 権限チェック
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  // 設定更新時のエラーやメッセージを表示
  settings_errors();

?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields( 'age_verification_group' );
      do_settings_sections( 'age-verification' );
      submit_button( '設定を保存' );
      ?>
    </form>
  </div>
<?php
}

// カラーピッカー用のスクリプトとスタイルをエンキュー
function age_verification_enqueue_color_picker_assets( $hook_suffix ) {
  // 特定の管理画面でのみ実行
  if ( 'settings_page_age-verification' !== $hook_suffix ) {
    return;
  }
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'age_verification_enqueue_color_picker_assets' );

// カラーピッカーと設定表示切り替えを有効化するスクリプト
function age_verification_admin_footer_script() {
  // 現在の画面が年齢認証設定ページか確認
  $screen = get_current_screen();
  if ( $screen && 'settings_page_age-verification' === $screen->id ) {
?>
    <script type="text/javascript">
      jQuery(document).ready(function($){
        // カラーピッカーを有効化
        $('.age-verification-color-picker').wpColorPicker();

        // ボタン設定表示切り替えのロジック
        var sameDesignCheckbox = $('#age_verification_same_button_design');
        var unifiedSettings = $('.age-verification-unified-button-settings').closest('tr');
        var individualSettings = $('.age-verification-individual-button-settings').closest('tr');

        function toggleButtonSettingsVisibility() {
          if (sameDesignCheckbox.is(':checked')) {
            unifiedSettings.show();
            individualSettings.hide();
          } else {
            unifiedSettings.hide();
            individualSettings.show();
          }
        }

        // ロード時の状態に応じて表示を切り替え
        toggleButtonSettingsVisibility();

        // チェックボックスの状態が変更されたときに表示を切り替え
        sameDesignCheckbox.on('change', toggleButtonSettingsVisibility);
      });
    </script>
    <?php
  }
}
add_action( 'admin_footer', 'age_verification_admin_footer_script' );

?>