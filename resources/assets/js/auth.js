const signInBtn = document.getElementById("sign-in-btn");
const signUpBtn = document.getElementById("sign-up-btn");
const container = document.querySelector(".container");
const linkFakesignin = document.getElementById("link-fake-signin");
const linkFakesignup = document.getElementById("link-fake-signup");
const senhaReveal = document.querySelectorAll(".senhaReveal");
const forms = document.querySelectorAll(".form-sign");
const carregando = document.querySelector(".carregando");

function signUpMode() {

    if (linkFakesignin) {

        linkFakesignin.addEventListener("click", () => {

            if (container) container.classList.add("sign-up-mode");

        });

    }

    if (linkFakesignup) {

        linkFakesignup.addEventListener("click", () => {

            if (container) container.classList.remove("sign-up-mode");

        });

    }

    if (signUpBtn) {

        signUpBtn.addEventListener("click", () => {

            if (container) container.classList.add("sign-up-mode");

        });

    }

    if (signInBtn) {

        signInBtn.addEventListener("click", () => {

            if (container) container.classList.remove("sign-up-mode");

        });

    }

}

function passwordReveal() {

    if (senhaReveal.length > 0) {

        senhaReveal.forEach((senha) => {

            if (senha) {

                senha.addEventListener("click", () => {

                    let classListParent = senha.parentElement;

                    if (!classListParent) return;
                    
                    let inputType = classListParent.getElementsByTagName("input")[0];

                    if (inputType) {

                        inputType.type = inputType.type === "password" ? "text" : "password";

                    }

                    senha.src =
                        senha.src === "http://localhost:8000/resources/assets/icons/icons8-visivel-24.png"
                            ? "../../resources/assets/icons/icons8-ocultar-24.png"
                            : "../../resources/assets/icons/icons8-visivel-24.png";
                });

                if (container && container.classList.contains("sign-up-mode")) {

                    let eyeStyle = getComputedStyle(senha);

                    let newTop = parseInt(eyeStyle.top.replace("px", "").trim());
                    
                    if (senha.style) senha.style.top = `${newTop - 5}px`;

                }

            }

        });
        
    }

}

function loadingForm(){

    if(forms){

        forms.forEach((form)=>{

            form.addEventListener("submit", ()=>{

                if(carregando){

                    carregando.style.display = "block";

                }

            });          

        });

    }

}

signUpMode();
passwordReveal();
loadingForm();

if (typeof postSend === "function") {

    postSend();

}
