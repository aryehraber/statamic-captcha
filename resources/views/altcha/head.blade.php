<script src="https://cdn.jsdelivr.net/npm/altcha/dist/altcha.min.js" async defer type="module"></script>
<script>
  function initAltchaWidget() {
    const wrapper = document.getElementById('altcha-widget')

    if (!wrapper || wrapper.querySelector('altcha-widget')) return

    const captcha = document.createElement('altcha-widget')

    wrapper.getAttributeNames().forEach((name) => {
      if (name !== 'id') {
        captcha.setAttribute(name, wrapper.getAttribute(name))
      }
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
  }

  // Initialize on DOMContentLoaded (non-cached pages)
  document.addEventListener('DOMContentLoaded', initAltchaWidget)

  // Initialize after nocache regions are replaced (statically cached pages)
  document.addEventListener('statamic:nocache.replaced', initAltchaWidget)
</script>
