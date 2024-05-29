/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.bootstrap_subtheme = {
    attach: function(context, settings) {

      // Custom code here
      // Narrow main navbar once it attached to top
      once('once-id', 'body').forEach(function () {
        const el = document.querySelector("#navbar-main")
        const observer = new IntersectionObserver(
          ([e]) => {
            console.log(e.intersectionRatio)
            return e.target.classList.toggle("is-pinned", e.intersectionRatio < 1)
          },
          { threshold: [1] }
        );

        observer.observe(el);
      })

    }
  };

})(jQuery, Drupal);
