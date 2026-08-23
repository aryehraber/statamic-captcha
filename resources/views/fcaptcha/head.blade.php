<script>
  document.addEventListener('fcaptcha:verified', function (event) {
    var responseField = event.target.getAttribute('data-response-field');
    var input = responseField ? document.getElementById(responseField) : null;

    if (input) {
      input.value = event.detail.token;
    }
  });
</script>
<script src="{{ rtrim($serverUrl, '/') }}/fcaptcha.js" async defer></script>
