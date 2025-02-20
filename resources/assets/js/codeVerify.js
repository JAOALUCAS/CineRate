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
    
});