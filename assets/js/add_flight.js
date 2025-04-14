document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('flightForm');
    
    // Enhanced form validation
    form.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Real-time validation for departure and arrival
    document.getElementById('departure').addEventListener('change', validateDateTime);
    document.getElementById('arrival').addEventListener('change', validateDateTime);
});

function validateForm() {
    let isValid = true;
    const now = new Date();
    const departure = new Date(document.getElementById('departure').value);
    const arrival = new Date(document.getElementById('arrival').value);
    
    // Validate departure time
    if (departure <= now) {
        document.getElementById('departure').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('departure').classList.remove('is-invalid');
    }
    
    // Validate arrival time
    if (arrival <= departure) {
        document.getElementById('arrival').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('arrival').classList.remove('is-invalid');
    }
    
    // Validate numeric fields
    const numericFields = [
        'economy_seats', 'business_seats', 'first_class_seats',
        'economy_price', 'business_price', 'first_class_price'
    ];
    
    numericFields.forEach(field => {
        const element = document.getElementById(field);
        const value = field.includes('price') 
            ? parseFloat(element.value) 
            : parseInt(element.value);
        
        if (isNaN(value)) {
            element.classList.add('is-invalid');
            isValid = false;
        } else if (value < 0) {
            element.classList.add('is-invalid');
            isValid = false;
        } else if (!field.includes('price') && !Number.isInteger(value)) {
            element.classList.add('is-invalid');
            isValid = false;
        } else {
            element.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

function validateDateTime() {
    const departure = document.getElementById('departure');
    const arrival = document.getElementById('arrival');
    
    if (departure.value && arrival.value) {
        const depTime = new Date(departure.value);
        const arrTime = new Date(arrival.value);
        
        if (arrTime <= depTime) {
            arrival.classList.add('is-invalid');
        } else {
            arrival.classList.remove('is-invalid');
        }
    }
}

// Show toast notification if there are success/error messages in URL
function checkUrlForMessages() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showToast(urlParams.get('success'), 'success');
    } else if (urlParams.has('error')) {
        showToast(urlParams.get('error'), 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            ${type === 'success' ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-exclamation-triangle-fill"></i>'}
        </div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x"></i>
        </button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Check for messages when page loads
checkUrlForMessages();