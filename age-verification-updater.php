<?php
/**
 * Age Verification Plugin Updater
 * Handles custom plugin updates using the Plugin Update Checker library.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// v5以降のライブラリを使用する場合、名前空間が必要です。
// READMEの「Migrating from 4.x」を参照。
// use ステートメントはグローバルスコープの先頭、または名前空間宣言の直後で使用する必要があります。
// if ブロックの外、ファイルの先頭に移動しました。
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Plugin Update Checker ライブラリのパス。
// ライブラリをダウンロードし、プラグインディレクトリ内の 'plugin-update-checker'
// サブディレクトリに配置していることを想定しています。
// 配置場所が異なる場合は、このパスを修正してください。
$update_checker_path = plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';

// ライブラリファイルが存在し、読み込み可能かチェック
if ( file_exists( $update_checker_path ) ) {
  require $update_checker_path;

  // PucFactory クラスが存在するかチェックしてから使用する
  // これにより、ライブラリが正しく読み込まれなかった場合の致命的なエラーを防ぎます。
  // use ステートメントはファイルの先頭に移動したため、ここではクラス名の存在チェックのみを行います。
  if ( class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {

    // Instantiate the update checker.
    // Replace 'YOUR_UPDATE_JSON_URL' with the actual URL to your update.json file.
    // This JSON file will contain information about the latest version of your plugin.
    //$main_plugin_file_path = plugin_dir_path( __FILE__ ) . 'age-verification.php';
    $myUpdateChecker = PucFactory::buildUpdateChecker(
      'https://github.com/hocosaki/age-verification',
      __FILE__,               // Full path to the main plugin file.
      'age-verification' // Unique identifier for the plugin.
    );

    // Optional: Add a custom branch for beta/dev updates (if needed).
     $myUpdateChecker->setBranch('main');

    // Optional: Set the required WordPress version.
    // $myUpdateChecker->setRequiresWp('5.0');

    // Optional: Set the required PHP version.
    // $myUpdateChecker->setRequiresPhp('7.0');

    // Optional: Add support for specific tags in the update JSON (e.g., 'tested' tag).
    // $myUpdateChecker->add  ('tested', '5.8');

    // Optional: Configure automatic update checks.
    // By default, it checks daily. You can change the check interval if needed.
    // $myUpdateChecker->setCheckForUpdates('daily'); // 'hourly', 'daily', 'twicedaily', 'never'

    // Optional: Add query args to the update check request (if your server needs them).
    // $myUpdateChecker->addQueryArg('api_key', 'YOUR_API_KEY');

  } else {
    // PucFactory クラスが見つからない場合のログ出力 (デバッグ用)
    error_log( '[' . date( 'Y-m-d H:i:s', time() ) . '] Updater Error: PucFactory class (v5) not found after requiring library. Plugin Update Checker library might be incomplete, corrupted, or an incompatible version.' );
  }

} else {
  // ライブラリファイルが見つからない場合のログ出力 (デバッグ用)
  error_log( '[' . date( 'Y-m-d H:i:s', time() ) . '] Updater Error: Plugin Update Checker library file not found at expected path: ' . $update_checker_path );
}
?>
