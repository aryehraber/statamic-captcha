<script>
  document.addEventListener('statamic:nocache.replaced', (event) => {
    var script = document.createElement('script');

    script.src = '{{ $url }}';
    script.async = false;

    document.head.appendChild(script);
  });
</script>
