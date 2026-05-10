/**
 * Manejo de formularios de autenticación con AJAX
 */

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form[action*="/login"]');
    const registerForm = document.querySelector('form[action*="/register"]');

    if (loginForm) {
        loginForm.addEventListener('submit', handleAuthForm);
    }

    if (registerForm) {
        registerForm.addEventListener('submit', handleAuthForm);
    }
});

async function handleAuthForm(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const action = form.getAttribute('action');

    try {
        const response = await fetch(action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        });

        const data = await response.json();

        // Limpiar errores previos
        clearErrors(form);

        if (data.success) {
            // Login/registro exitoso
            showSuccess(data.message);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 500);
        } else {
            // Error en validación o autenticación
            if (data.errors) {
                showErrors(form, data.errors);
            } else {
                showError(data.message || 'Ocurrió un error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Error de conexión con el servidor');
    }
}

function clearErrors(form) {
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function showErrors(form, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const input = form.querySelector(`input[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback d-block';
            feedback.textContent = messages[0];
            input.parentElement.appendChild(feedback);
        }
    });
}

function showSuccess(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.setAttribute('role', 'alert');
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.insertBefore(alert, document.body.firstChild);
}

function showError(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show';
    alert.setAttribute('role', 'alert');
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.insertBefore(alert, document.body.firstChild);
}
