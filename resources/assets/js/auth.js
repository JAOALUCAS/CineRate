const signInBtn = document.getElementById("sign-in-btn");
const signUpBtn =document.getElementById("sign-up-btn");
const container = document.querySelector(".container");
const linkFakesignin = document.getElementById("link-fake-signin");
const linkFakesignup = document.getElementById("link-fake-signup");
const senhaReveal = document.querySelectorAll(".senhaReveal");

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

function passwordReveal(){

    senhaReveal.forEach((senha)=>{

        senha.addEventListener("click", ()=>{

            let classListParent = senha.parentElement;

            let inputType = classListParent.getElementsByTagName("input")[0];

            inputType.type = inputType.type == "password" ? "text" : "password";

            senha.src = senha.src == "http://localhost:8000/resources/assets/icons/icons8-visivel-24.png" ? "../../resources/assets/icons/icons8-ocultar-24.png" : "../../resources/assets/icons/icons8-visivel-24.png";

        });

        if(container.classList.contains("sign-up-mode")){
            
            let eyeStyle = getComputedStyle(senha);

            let newTop = parseInt(eyeStyle.top.replace("px", "").trim());

            eye.style.top = `${newTop - 5}px`;

        }

    });

}

passwordReveal();
signUpMode();
postSend();