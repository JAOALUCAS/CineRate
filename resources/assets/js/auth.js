const signInBtn = document.getElementById("sign-in-btn");
const signUpBtn =document.getElementById("sign-up-btn");
const container = document.querySelector(".container");
const linkFakesignin = document.getElementById("link-fake-signin");
const linkFakesignup = document.getElementById("link-fake-signup");
const senhaReveal = document.querySelectorAll(".senhaReveal");

const forms = document.querySelectorAll(".form-sign");

function signUpMode(){
        
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

}

function postSend(){
        
    forms.forEach((form) => {
        
        form.addEventListener("submit", (event)=>{

            event.preventDefault();

            const continueSend = validateInputs(form);

            if(continueSend){
                
                let inputSql = document.createElement("input");

                inputSql.name = "action";
                
                inputSql.value = container.classList.contains("sign-up-mode") ? "register" : "select";

                fetch(form.action, {method: form.method, body: new FormData(form)})
                
                
            }

        });

    });

}

function validateEmail(form){

    const emailInput = form.querySelector(".validateEmail");
    
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

    if(!emailRegex.test(emailInput.value.trim())){
                
        return {
            "input": emailInput,
            "msg": "Digite um email válido"
        };

    }

}

function validateSenha(form){

    let error = [];
    
    const senhaInput = form.querySelector(".validateSenha");
    const confirmarSenha = form.querySelector(".confirmarSenha");

    if(senhaInput.value.trim() == "" || senhaInput.value.length < 8){
                
        error.push({
            "input": senhaInput,
            "msg": "Preencha a senha, minimo 8 caracteres"});

    }

    if(confirmarSenha !== null && confirmarSenha !== undefined){

        if(confirmarSenha.value.trim() !== senhaInput.value.trim()){
                
            error.push({
                "input": confirmarSenha,
                "msg": "As senhas precisam ser iguais"});
    
        }

    }

    return error;

}

function validateNome(form){

    const nomeInput = form.querySelector(".validateNome");

    if(nomeInput){

        if(nomeInput.value.trim() == "" || nomeInput.value.length < 3){
                    
            return {
                "input": nomeInput,
                "msg": "Preencha o nome, minimo 3 caracteres"
            };

        }

    }

}

function validateInputs(form){

    let error = [];

    const emailError = validateEmail(form);
    const senhaError = validateSenha(form);
    const nomeError = validateNome(form);

    if(emailError){

        error.push(emailError);

    }
    
    if(senhaError.length !== 0){

        error.push(senhaError);

    }

    if(nomeError){

        error.push(nomeError);

    }

    if(error.length !== 0){

        showError(error);

        return false;

    }

    return true;

}

function showError(error){

    let eyesPassword = [];

    error.forEach((erroOb)=>{

        let pai;
        let msg;

        if(Array.isArray(erroOb)){

            erroOb.forEach((obSeparado)=>{

                pai = obSeparado["input"].parentElement;

                msg = obSeparado["msg"];

            });

        }else{
                
            pai = erroOb["input"].parentElement;

            msg =  erroOb["msg"];

        }
        
        let label = pai.querySelector(".error");
        
        label.innerText = msg;

        let input = pai.getElementsByTagName("input");

        input[0].classList.add("haveError");
        
        let imgEye = pai.querySelector(".senhaReveal");

        if(imgEye){
                
            eyesPassword.push(imgEye);

        }

    });

    if(eyesPassword.length !== 0){

        eyesPassword.forEach((eye)=>{
            
            let eyeStyle = getComputedStyle(eye);

            let newTop = parseInt(eyeStyle.top.replace("px", "").trim());

            eye.style.top = `${newTop - 5}px`;

        });

    }

}

function passwordReveal(){

    senhaReveal.forEach((senha)=>{

        senha.addEventListener("click", ()=>{

            let classListParent = senha.parentElement;

            let inputType = classListParent.getElementsByTagName("input")[0];

            inputType.type = inputType.type == "password" ? "text" : "password";

            senha.src = senha.src == "http://localhost:8000/resources/assets/icons/icons8-visivel-24.png" ? "../../resources/assets/icons/icons8-ocultar-24.png" : "../../resources/assets/icons/icons8-visivel-24.png";

        });

    });

}

passwordReveal();
signUpMode();
postSend();