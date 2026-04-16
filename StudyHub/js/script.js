document.addEventListener('DOMContentLoaded', function () {
  var yearSpan = document.getElementById('year');
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }

  var navToggle = document.getElementById('navToggle');
  var navLinks = document.querySelector('.nav-links');

  if (navToggle && navLinks) {
    navToggle.addEventListener('click', function () {
      navLinks.classList.toggle('open');
    });
  }

  // Course level filters on dashboard
  var filterButtons = document.querySelectorAll('.course-filter-btn');
  var courseCards = document.querySelectorAll('.course-card');

  if (filterButtons.length && courseCards.length) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var level = btn.getAttribute('data-filter');

        filterButtons.forEach(function (b) {
          b.classList.toggle('active', b === btn);
        });

        courseCards.forEach(function (card) {
          var minPlan = card.getAttribute('data-min-plan');
          if (level === 'all' || level === minPlan) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }
});

function toggleTest(button) {
  var index = button.getAttribute('data-course-index');
  var testBlock = document.querySelector('[data-course-test="' + index + '"]');
  if (!testBlock) return;

  var isVisible = testBlock.style.display === 'block';
  testBlock.style.display = isVisible ? 'none' : 'block';
  button.textContent = isVisible ? 'View Test Questions' : 'Hide Test Questions';
}

