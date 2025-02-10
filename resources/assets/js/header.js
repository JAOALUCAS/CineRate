
const scroll = this.document.querySelector(".scrolltop");
const scrollHeader = document.querySelector(".scrollheader");
const saveImg = document.getElementById("saveImg");
const navOl = document.querySelectorAll(".ulNav li ol");
const lineContainer = document.querySelectorAll(".line-container");
const menuMobileDiv = document.querySelector(".mobile-menu ul");
const mobileLi = document.querySelectorAll(".mobile-menu ul li");
const user = document.querySelector(".user");
const perfil = document.getElementById("userName");
const settings = document.querySelector(".settings");

headerScrolling();

window.addEventListener("scroll", function (){

    headerScrolling();

    scroll.classList.toggle("active", window.scrollY  > 100);

});

scroll.addEventListener("click", backTop);

function backTop(){
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

function headerScrolling(){

    return window.scrollY >= 50 ? scrollHeader.classList.add("scrollingHeader") : scrollHeader.classList.remove("scrollingHeader");

}

let mouseIsOver = false;

function saveImgChange(){

    mouseIsOver = !mouseIsOver;

    mouseIsOver == true ? saveImg.src="../../resources/assets/icons/salvar-instagramLaranja.png" : saveImg.src ="../../resources/assets/icons/salvar-instagram.png";

}

function activeLink(){

    navOl.forEach((item)=>{

        let paiLi = item.parentElement;
        let linkItem = paiLi.getElementsByTagName("a")[0];
    
        item.addEventListener("mouseenter", ()=>{
            linkItem.classList.add("active");      
        });
        
        item.addEventListener("mouseleave", ()=>{   
            linkItem.classList.remove("active");      
        });
    
    });

}

let liVisivel = false;

function mobileMenu(){

    lineContainer[0].addEventListener("click", ()=>{

        liVisivel = !liVisivel;

        menuMobileDiv.classList.toggle("menu-active");

        let lines = lineContainer[0].querySelectorAll(".line");

        lines.forEach((line)=>{

            line.classList.toggle("menu-active");

        });


        if (liVisivel) {

            mobileLi.forEach((li, index) => {

                li.style.animation = `navLinkFade 0.5s ease-in ${index / 10}s forwards`;

            });

        } else {

            mobileLi.forEach((li) => {

                li.style.animation = "none";

            });

        }

    });

}

function showSetting(){

    user.addEventListener("mouseover", ()=>{

        perfil.classList.add("active");

        settings.style.display = "flex";

    });
    
    user.addEventListener("mouseout", ()=>{

        perfil.classList.remove("active");

        settings.style.display = "none";

    });

}

activeLink();
mobileMenu();
showSetting();
saveImg.addEventListener("mouseover", saveImgChange);
saveImg.addEventListener("mouseout", saveImgChange);
