document.addEventListener('DOMContentLoaded', () => {

    const inputs = document.querySelectorAll('.code-inputs input');

    const button = document.getElementById('confirm-btn');

    inputs.forEach((input, index) => {

        input.addEventListener('input', (event) => {

            if (event.target.value && index < inputs.length - 1) {

                inputs[index + 1].focus();

            }

        });

    });
    
    button.addEventListener('click', () => {

        let codigo = '';

        inputs.forEach(input => {

            codigo += input.value;

        });
        
        if (codigo.length === 6) {

            alert('Código verificado com sucesso!');

        } else {

            alert('Por favor, insira um código válido de 6 dígitos.');

        }

    });
    
});