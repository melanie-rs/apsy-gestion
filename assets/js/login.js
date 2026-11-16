
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {

    // Comprobar si la contraseña está oculta
    const estaOculta = passwordInput.type === 'password';

    // Cambiar el tipo de campo
    passwordInput.type = estaOculta ? 'text' : 'password';

    // Actualizar el texto accesible del botón
    togglePassword.setAttribute(
        'aria-label',
        estaOculta ? 'Ocultar contraseña' : 'Mostrar contraseña'
    );

    // Cambiar el ícono
    if (estaOculta) {
        eyeIcon.innerHTML = `
            <path d="M3 3l18 18"/>
            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
            <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a16 16 0 0 1-3.2 4.1"/>
            <path d="M6.2 6.2C3.5 8 2 12 2 12s3.5 7 10 7c1.1 0 2.1-.2 3-.5"/>
        `;
    } else {
        eyeIcon.innerHTML = `
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    }
});