<script>
  (function () {
    function responseInput(event) {
      var responseField = event.target.getAttribute('data-response-field');

      return responseField ? document.getElementById(responseField) : null;
    }

    // FCaptcha dispatches these on the widget container itself and the events
    // do not bubble, so these listeners have to run in the capture phase.
    document.addEventListener('fcaptcha:verified', function (event) {
      var input = responseInput(event);

      if (input) {
        input.value = event.detail.token;
      }
    }, true);

    document.addEventListener('fcaptcha:expired', function (event) {
      var input = responseInput(event);

      if (input) {
        input.value = '';
      }
    }, true);
  })();
</script>
<script src="{{ rtrim($serverUrl, '/') }}/fcaptcha.js" async defer></script>
