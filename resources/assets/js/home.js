const alert = document.querySelector(".alert");

function desapearAlert(){

    if(alert){

        alert.addEventListener('animationend', ()=>{

            alert.style.display = 'none';

        });

    }

}

desapearAlert();