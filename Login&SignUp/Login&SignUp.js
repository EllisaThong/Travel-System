// Sign Up Modal functionality
const signupBtn = document.getElementById('signupBtn');
const signupModal = document.getElementById('signupModal');
const closeSignupModal = document.getElementById('closeSignupModal');

// Show signup modal when Sign Up is clicked
signupBtn.addEventListener('click', function(e) {
  e.preventDefault();
  signupModal.style.display = 'block';
});

// Close signup modal when X is clicked
closeSignupModal.addEventListener('click', function() {
  signupModal.style.display = 'none';
});

// Login Modal functionality
const loginBtn = document.getElementById('loginBtn');
const loginModal = document.getElementById('loginModal');
const closeLoginModal = document.getElementById('closeLoginModal');

// Show login modal when Login is clicked
loginBtn.addEventListener('click', function(e) {
  e.preventDefault();
  loginModal.style.display = 'block';
});

// Close login modal when X is clicked
closeLoginModal.addEventListener('click', function() {
  loginModal.style.display = 'none';
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
  if (event.target === signupModal) {
    signupModal.style.display = 'none';
  }
  if (event.target === loginModal) {
    loginModal.style.display = 'none';
  }
});



// Password show/hide toggle for signup
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
if (togglePassword && passwordInput) {
  togglePassword.addEventListener('click', function() {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.textContent = type === 'password' ? '👁' : '🙈';
  });
}

const toggleRePassword = document.getElementById('toggleRePassword');
const rePasswordInput = document.getElementById('repassword');
if (toggleRePassword && rePasswordInput) {
  toggleRePassword.addEventListener('click', function() {
    const type = rePasswordInput.type === 'password' ? 'text' : 'password';
    rePasswordInput.type = type;
    toggleRePassword.textContent = type === 'password' ? '👁' : '🙈';
  });
}

// Password show/hide toggle for login
const toggleLoginPassword = document.getElementById('toggleLoginPassword');
const loginPasswordInput = document.getElementById('login-password');
if (toggleLoginPassword && loginPasswordInput) {
  toggleLoginPassword.addEventListener('click', function() {
    const type = loginPasswordInput.type === 'password' ? 'text' : 'password';
    loginPasswordInput.type = type;
    toggleLoginPassword.textContent = type === 'password' ? '👁' : '🙈';
  });
}

// Cross-modal navigation
const loginLink = document.getElementById('loginLink');
const signupLink = document.getElementById('signupLink');

if (loginLink) {
  loginLink.addEventListener('click', function(e) {
    e.preventDefault();
    signupModal.style.display = 'none';
    loginModal.style.display = 'block';
  });
}

if (signupLink) {
  signupLink.addEventListener('click', function(e) {
    e.preventDefault();
    loginModal.style.display = 'none';
    signupModal.style.display = 'block';
  });
}

