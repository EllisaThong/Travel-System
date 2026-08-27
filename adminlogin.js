
// Toggle between sign-in and register
function showSignIn(e) {
    e.preventDefault();
    document.getElementById('registerForm').style.display = 'none';
    document.getElementById('signInForm').style.display = 'block';
    document.getElementById('formTitle').textContent = 'Sign in';
    document.getElementById('registerMessage').textContent = '';
}

// Password visibility toggle (works for both forms)
function togglePasswordVisibility(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = passwordInput.nextElementSibling;
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = 'Hide';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = 'Show';
    }
}

