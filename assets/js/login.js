// Login page orb positions
window.AUTH_ORBS = [
  { x: 0.1,  y: 0.2, r: 0.35, color: 'rgba(27,94,32,0.4)'  },
  { x: 0.85, y: 0.7, r: 0.3,  color: 'rgba(46,125,50,0.25)' },
];

// tetap pertahankan function lama
function togglePassword(id, icon) {
  const input = document.getElementById(id);
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}

// TAMBAHAN (CSP-safe)
document.addEventListener('DOMContentLoaded', function () {
  const eye = document.querySelector('.auth-eye');
  const input = document.getElementById('password');

  if (eye && input) {
    eye.addEventListener('click', function () {
      togglePassword('password', this);
    });
  }
});