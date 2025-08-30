jQuery(document).ready(function($) {
  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return match[2];
    return null; // クッキーが存在しない場合はnullを返す
  }

  function showModal() {
    $('#age-verification-modal').show();
    document.body.style.visibility = "visible";
  }

  function hideModal() {
    $('#age-verification-modal').hide();
  }

  // サーバーサイドでの検証を行うAJAX関数
  function checkAgeVerification() {
    $.ajax({
      url: age_verification_settings.ajax_url,
      type: 'POST',
      data: {
        action: 'check_age_verification',
        nonce: age_verification_settings.nonce
      },
      success: function(response) {
        if (!response.success) {
          // 設定を適用
          applySettings();
          showModal();
        }
      },
      error: function() {
        console.error('Age verification check failed');
        // エラー時はデフォルト設定でモーダルを表示
        applySettings();
        showModal();
      }
    });
  }

  // 設定をモーダルに適用する関数
  function applySettings() {
    var modal = $('#age-verification-modal');
    var modalContent = modal.find('.modal-content');
    var yesButton = $('#age-verify-yes');
    var noButton = $('#age-verify-no');

    // 背景色の設定
    var bgColor = age_verification_settings.background_color;
    var bgOpacity = age_verification_settings.background_opacity;
    // 16進数の色をRGBAに変換する関数（簡易版）
    function hexToRgba(hex, alpha) {
      var r = parseInt(hex.slice(1, 3), 16);
      var g = parseInt(hex.slice(3, 5), 16);
      var b = parseInt(hex.slice(5, 7), 16);
      return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    }
    modal.css('background-color', hexToRgba(bgColor, bgOpacity));

    // コンテンツ枠の背景色とボーダー色
    modalContent.css('background-color', age_verification_settings.content_bg_color);
    modalContent.css('border-color', age_verification_settings.content_border_color);

    // コンテンツ タイトルと内容
    modalContent.find('h2').html(age_verification_settings.content_title);
    modalContent.find('p').html(age_verification_settings.content_text); // HTMLを許可するため.text()ではなく.html()を使用

    // ボタン テキスト
    yesButton.text(age_verification_settings.yes_button_text);
    noButton.text(age_verification_settings.no_button_text);

    // ボタンのデザイン設定
    if (age_verification_settings.same_button_design) {
      // デザイン統一の場合
      yesButton.css('background-color', age_verification_settings.button_bg_color_unified);
      yesButton.css('border-color', age_verification_settings.button_border_color_unified);
      noButton.css('background-color', age_verification_settings.button_bg_color_unified);
      noButton.css('border-color', age_verification_settings.button_border_color_unified);
    } else {
      // 個別設定の場合
      yesButton.css('background-color', age_verification_settings.yes_button_bg_color);
      yesButton.css('border-color', age_verification_settings.yes_button_border_color);
      noButton.css('background-color', age_verification_settings.no_button_bg_color);
      noButton.css('border-color', age_verification_settings.no_button_border_color);
    }
  }


  // ページロード時に年齢確認を行う
  checkAgeVerification();

  $('#age-verify-yes').on('click', function() {
    $.ajax({
      url: age_verification_settings.ajax_url,
      type: 'POST',
      data: {
        action: 'age_verification',
        verified: 'true',
        nonce: age_verification_settings.nonce
      },
      success: function(response) {
        if (response.success) {
          hideModal();
          document.body.style.visibility = "visible";
          document.body.style.overflow = "auto";
        } else {
          console.error('Server responded with an error:', response);
          alert('サーバーからエラーレスポンスを受け取りました。');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX request failed:', textStatus, errorThrown);
        alert('エラーが発生しました。');
      }
    });
  });

  $('#age-verify-no').on('click', function() {
    window.location.href = age_verification_settings.redirect_url;
  });
});