@unless(config('captcha.altcha_disable_cdn', false))
<script src="https://cdn.jsdelivr.net/npm/altcha/dist/altcha.min.js" defer async type="module"></script>
@endunless
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('altcha-widget')
    const captcha = document.createElement('altcha-widget')

    wrapper.getAttributeNames().forEach((name) => {
      captcha.setAttribute(name, wrapper.getAttribute(name))
    })
    
    wrapper.append(captcha)
    const form = captcha.closest('form')
    const hiddenInput = document.createElement('input')
    hiddenInput.setAttribute('type', 'hidden')
    hiddenInput.setAttribute('name', 'altcha-payload')

    // Append the hidden input to the form
    form.appendChild(hiddenInput)

    captcha.addEventListener('statechange', (ev) => {
      if (ev.detail.state === 'verified') {
        hiddenInput.setAttribute('value', ev.detail.payload)
      }
    })
  })
</script>
