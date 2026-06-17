function validarCpf(cpf) {
    cpf = cpf.replace(/\D/g, '');

    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    for (let t = 9; t < 11; t++) {
        let sum = 0;
        for (let i = 0; i < t; i++) sum += parseInt(cpf[i]) * (t + 1 - i);
        if (parseInt(cpf[t]) !== ((sum * 10) % 11) % 10) return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', () => {

    const campoCpf = document.getElementById('cpf');

    if (campoCpf) {
        campoCpf.addEventListener('blur', () => {
            const valido = validarCpf(campoCpf.value);
            const erro = document.getElementById('cpf-erro');

            if (!valido && campoCpf.value.replace(/\D/g, '').length > 0) {
                if (!erro) {
                    const msg = document.createElement('span');
                    msg.id = 'cpf-erro';
                    msg.className = 'text-red-600 text-sm';
                    msg.textContent = 'CPF inválido.';
                    campoCpf.closest('div').after(msg);
                }
            } else {
                erro?.remove();
            }
        });
    }

    const senha = document.getElementById('password');

    if (!senha) return;

    senha.addEventListener('input', () => {

        const valor = senha.value;

        document
            .getElementById('rule-length')
            ?.classList.toggle('valid', valor.length >= 8);

        document
            .getElementById('rule-uppercase')
            ?.classList.toggle('valid', /[A-Z]/.test(valor));

        document
            .getElementById('rule-number')
            ?.classList.toggle('valid', /\d/.test(valor));

        document
            .getElementById('rule-special')
            ?.classList.toggle('valid', /[^A-Za-z0-9]/.test(valor));

    });

});