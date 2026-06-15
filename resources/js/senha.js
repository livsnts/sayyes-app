window.togglePassword = function (fieldId, button) {

    const input = document.getElementById(fieldId);

    if (input.type === 'password') {
        input.type = 'text';
        button.innerHTML = '<i class="fa-regular fa-eye-slash" style="color: #19539d;"></i>';
    } else {
        input.type = 'password';
        button.innerHTML = '<i class="fa-regular fa-eye" style="color: #19539d;"></i>';
    }

};