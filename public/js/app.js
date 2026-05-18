// app.js — Library Management System

document.addEventListener('DOMContentLoaded', function () {

  // Auto-dismiss flash alerts after 4 seconds
  document.querySelectorAll('.alert-dismissible').forEach(function (el) {
    setTimeout(function () {
      var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
      if (bsAlert) bsAlert.close();
    }, 4000);
  });

  // Confirm delete buttons (generic)
  document.querySelectorAll('.btn-confirm-delete').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (!confirm('Are you sure? This cannot be undone.')) e.preventDefault();
    });
  });

  // Highlight current nav link based on URL query param
  var urlParams = new URLSearchParams(window.location.search);
  var currentPage = urlParams.get('page') || '';
  document.querySelectorAll('.navbar-nav .nav-link').forEach(function (link) {
    var href = link.getAttribute('href') || '';
    var hrefPage = new URLSearchParams(href.split('?')[1] || '').get('page') || '';
    if (hrefPage && currentPage.startsWith(hrefPage)) {
      link.classList.add('active');
    }
  });
});
