/**
 * Delegated click handler for password visibility toggles (see x-password-field).
 */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-password-toggle]');
    if (!btn) {
        return;
    }
    e.preventDefault();
    const field = btn.closest('[data-password-field]');
    const input = field?.querySelector('[data-password-input]');
    if (!input) {
        return;
    }
    input.type = input.type === 'password' ? 'text' : 'password';
    const visible = input.type === 'text';
    btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
    btn.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
    const eye = field.querySelector('[data-password-visible-icon]');
    const slash = field.querySelector('[data-password-hidden-icon]');
    if (eye) {
        eye.classList.toggle('hidden', visible);
    }
    if (slash) {
        slash.classList.toggle('hidden', !visible);
    }
});
