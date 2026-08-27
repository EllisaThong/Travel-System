document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    });
});

function switchTab(tabName) {
    // HIDE
    const panels = document.querySelectorAll('.panel');
    panels.forEach(panel => panel.classList.remove('active'));
    
    // REMOVE
    const tabs = document.querySelectorAll('.navTab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // SHOW
    document.getElementById(tabName + 'Section').classList.add('active');
    
    // ADD
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}

function confirmDelete(packageName) {
    return confirm(`Are you sure you want to delete the package "${packageName}"?\n\nThis action cannot be undone.`);
}

function validateForm() {
    
    const name = document.querySelector('input[name="packageName"]').value.trim();
    if (name.length < 3) {
        alert('Package name must be at least 3 characters long');
        return false;
    }
    
    const description = document.querySelector('textarea[name="packageDescription"]').value.trim();
    if (description.length < 10) {
        alert('Package description must be at least 10 characters long');
        return false;
    }
    
    return true;
}

// Real time validation
const priceInput = document.querySelector('input[name="packagePrice"]');
if (priceInput) {
    priceInput.addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (this.value && value <= 0) {
            this.classList.add('user-invalid');
            this.classList.remove('user-valid');
        } else if (this.value && value > 0) {
            this.classList.add('user-valid');
            this.classList.remove('user-invalid');
        } else {
            this.classList.remove('user-invalid', 'user-valid');
        }
    });
}

// Add validation for all required inputs
const requiredInputs = document.querySelectorAll('input[required], textarea[required], select[required]');
requiredInputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim()) {
            this.classList.add('user-valid');
            this.classList.remove('user-invalid');
        } else if (this.hasAttribute('data-touched')) {
            this.classList.add('user-invalid');
            this.classList.remove('user-valid');
        }
    });

    input.addEventListener('input', function() {
        this.setAttribute('data-touched', 'true');
    });
});