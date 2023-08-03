@if ($hide_badge)
<style>.grecaptcha-badge { visibility: collapse !important }</style>
@endif

@if ($invisible)
<script>
  function initCaptcha() {
    var captchas = Array.prototype.slice.call(document.querySelectorAll('.g-recaptcha[data-size=invisible]'), 0);

    captchas.forEach(function (captcha, index) {
      var form = captcha.parentNode;
      while (form.tagName !== 'FORM') {
        form = form.parentNode;
      }

      // create custom callback
      window['recaptchaSubmit' + index] = function () { form.submit(); };
      captcha.setAttribute('data-callback', 'recaptchaSubmit' + index);

      form.addEventListener('submit', function (event) {
        event.preventDefault();
        grecaptcha.reset(index);
        grecaptcha.execute(index);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', initCaptcha);
</script>
@endif

@include('captcha::base-script', ['url' => $url])
