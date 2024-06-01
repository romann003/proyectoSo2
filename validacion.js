// (function () {
//   'use strict';

//   window.addEventListener('load', function () {
//     var form = document.getElementById('formularioderegistro');

//     form.addEventListener('submit', function (event) {
//       if (form.checkValidity() === false) {
//         event.preventDefault();
//         event.stopPropagation();
//         }
//         form.classList.add('was-validated');
//     }, false);
//   }, false);
// })();



document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("formularioderegistro").addEventListener('submit', function (event) {
      var password = document.getElementById("password").value;
      var cPassword = document.getElementById("cPassword").value;
      if (password !== cPassword) {
        document.getElementById("cPassword").classList.add("is-invalid");
        event.preventDefault();
      } else {
        document.getElementById("cPassword").classList.remove("is-invalid");
      }
    });
});

// function recargarPagina() {
//   setTimeout(function() {
//     window.location.reload();
//   }, 5000);
// }