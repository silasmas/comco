
/**
 * Empêche la navigation directe vers le fichier image dans la galerie COMCO.
 */
document.addEventListener('DOMContentLoaded', function () {
  const gallery = document.getElementById('comco-gallery');

  if (!gallery) {
    return;
  }

  gallery.querySelectorAll('[data-bp]').forEach(function (item) {
    item.addEventListener('click', function (event) {
      event.preventDefault();
    });
  });
});
