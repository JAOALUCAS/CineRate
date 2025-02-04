let signInBtn = document.getElementById("sign-in-btn");
let signUpBtn =document.getElementById("sign-up-btn");
let container = document.querySelector(".container");
let linkFakesignin = document.getElementById("link-fake-signin");
let linkFakesignup = document.getElementById("link-fake-signup");

linkFakesignin.addEventListener("click", ()=>{
    container.classList.add("sign-up-mode");
});

linkFakesignup.addEventListener("click", ()=>{
    container.classList.remove("sign-up-mode");
});

signUpBtn.addEventListener("click", ()=>{
    container.classList.add("sign-up-mode");
});

signInBtn.addEventListener("click", ()=>{
    container.classList.remove("sign-up-mode");
});

let inputs = document.querySelectorAll(".input-field input");
let forms = document.querySelectorAll(".form-sign");

forms.forEach((form) => {
    
    form.addEventListener("submit", (event)=>{

        event.preventDefault();

        returnArrayInputs();


    });

});

function returnArrayInputs(){

    let inputSenha = [];
    let inputEmail = [];
    let inputNome;

    inputs.forEach((input)=>{
        
        if(input.name == "senha"){

            inputSenha.push(input);

        }else if(input.name == "email"){

            inputEmail.push(input);

        }else{

            inputNome = input;

        }

    });

    if(inputEmail.length !== 2 || inputSenha.length !== 2 || inputNome){

        return showError("input");

    }

    validateInput(inputSenha, inputEmail, inputNome);

}

function validateInput(inputSenha, inputEmail, inputNome){




}

function showError(error){

}