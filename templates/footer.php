<?php
/**
 * The template for displaying the footer
 *
 * @package Smores
 * @since Smores 2.0
 */
?>
  <!-- Asynchronous Javascript Loacing Example -->
  <!-- <script type="text/javascript">
      (function(d, t) {
          var g = d.createElement(t),
              s = d.getElementsByTagName(t)[0];
          g.src = '[path/to/script]';
          s.parentNode.insertBefore(g, s);
      }(document, 'script'));
  </script>-->


  <?php wp_footer(); ?>

  <script src="https://cdn.jsdelivr.net/npm/emoji-js@3.4.1/lib/emoji.min.js" type="text/javascript"></script>
  <script src="https://unicodey.com/js-emoji/lib/jquery.emoji.js" type="text/javascript"></script>

<script>
    var previous = null;
    var current = null;
    setInterval(function() {
        $.getJSON("/wp-json/acf/v2/options/", function(json) {
            current = JSON.stringify(json);
            if (previous && current && previous !== current) {
                console.log('refresh');
                location.reload();
            }
            previous = current;
        });
    }, 5000);
</script>
  </body>
</html>
