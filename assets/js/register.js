// Register page orb positions
window.AUTH_ORBS = [
  { x: 0.85, y: 0.2, r: 0.35, color: 'rgba(27,94,32,0.4)'  },
  { x: 0.1,  y: 0.8, r: 0.3,  color: 'rgba(46,125,50,0.25)' },
];

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

function checkStrength(val) {
  const bar  = document.getElementById('strengthBar');
  const hint = document.getElementById('strengthHint');
  let score  = 0;

  if (val.length >= 6)           score++;
  if (val.length >= 10)          score++;
  if (/[A-Z]/.test(val))         score++;
  if (/[0-9]/.test(val))         score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { w: '0%',   c: 'transparent', t: '' },
    { w: '25%',  c: '#e74c3c',     t: 'Lemah' },
    { w: '50%',  c: '#f39c12',     t: 'Cukup' },
    { w: '75%',  c: '#66BB6A',     t: 'Kuat' },
    { w: '100%', c: '#4CAF50',     t: 'Sangat kuat' },
  ];

  const lvl = Math.min(score, 4);

  if (bar) {
    bar.style.width      = levels[lvl].w;
    bar.style.background = levels[lvl].c;
  }

  if (hint) {
    hint.textContent = val.length > 0 ? levels[lvl].t : '';
    hint.style.color = levels[lvl].c;
  }
}

function checkMatch() {
  const passEl    = document.getElementById('password');
  const confirmEl = document.getElementById('confirm');
  const hint      = document.getElementById('matchHint');

  if (!passEl || !confirmEl || !hint) return;

  const pass    = passEl.value;
  const confirm = confirmEl.value;

  if (confirm.length === 0) {
    hint.textContent = '';
    return;
  }

  if (pass === confirm) {
    hint.innerHTML = '&#x2713; Password cocok'; // ? FIX
    hint.style.color = '#66BB6A';
  } else {
    hint.innerHTML = '&#x2715; Password tidak cocok'; // ? FIX
    hint.style.color = '#ff8a80';
  }
}

// ?? TAMBAHAN (CSP-SAFE, TANPA UBAH STRUKTUR)
document.addEventListener('DOMContentLoaded', function () {

  // toggle password untuk semua icon
  document.querySelectorAll('.auth-eye').forEach(function (eye) {
    eye.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      if (targetId) {
        togglePassword(targetId, this);
      }
    });
  });

  // input password ? strength + match
  const password = document.getElementById('password');
  if (password) {
    password.addEventListener('input', function () {
      checkStrength(this.value);
      checkMatch();
    });
  }

  // input confirm ? match
  const confirm = document.getElementById('confirm');
  if (confirm) {
    confirm.addEventListener('input', function () {
      checkMatch();
    });
  }

});