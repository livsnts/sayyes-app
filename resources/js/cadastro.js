document.addEventListener('DOMContentLoaded', () => {

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