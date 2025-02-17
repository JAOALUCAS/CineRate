const scroll = document.querySelector(".scrolltop");
const scrollHeader = document.querySelector(".scrollheader");
const saveImg = document.getElementById("saveImg");
const navOl = document.querySelectorAll(".ulNav li ol");
const lineContainer = document.querySelectorAll(".line-container");
const menuMobileDiv = document.querySelector(".mobile-menu ul");
const mobileLi = document.querySelectorAll(".mobile-menu ul li");
const user = document.querySelector(".user");
const perfil = document.getElementById("userName");
const settings = document.querySelector(".settings");

if (scrollHeader) headerScrolling();

window.addEventListener("scroll", function () {

    if (scrollHeader) headerScrolling();

    if (scroll) scroll.classList.toggle("active", window.scrollY > 100);

});

if (scroll) {

    scroll.addEventListener("click", backTop);

}

function backTop() {

    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

}

function headerScrolling() {

    if (scrollHeader) {

        return window.scrollY >= 50 ? scrollHeader.classList.add("scrollingHeader") : scrollHeader.classList.remove("scrollingHeader");

    }

}

let mouseIsOver = false;

function saveImgChange() {

    mouseIsOver = !mouseIsOver;

    if (saveImg) {

        saveImg.src = mouseIsOver
            ? "../../resources/assets/icons/salvar-instagramLaranja.png"
            : "../../resources/assets/icons/salvar-instagram.png";
    }

}

function activeLink() {

    if (navOl.length > 0) {

        navOl.forEach((item) => {

            let paiLi = item.parentElement;

            if (!paiLi) return;

            let linkItem = paiLi.getElementsByTagName("a")[0];

            if (!linkItem) return;

            item.addEventListener("mouseenter", () => {

                linkItem.classList.add("active");

            });

            item.addEventListener("mouseleave", () => {

                linkItem.classList.remove("active");

            });

        });

    }

}

let liVisivel = false;

function mobileMenu() {

    if (lineContainer.length > 0 && menuMobileDiv) {

        lineContainer[0].addEventListener("click", () => {

            liVisivel = !liVisivel;

            menuMobileDiv.classList.toggle("menu-active");

            let lines = lineContainer[0].querySelectorAll(".line");

            lines.forEach((line) => {

                line.classList.toggle("menu-active");

            });

            if (liVisivel && mobileLi.length > 0) {

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

}

function showSetting() {

    if (user && perfil && settings) {

        user.addEventListener("mouseover", () => {

            perfil.classList.add("active");

            settings.style.display = "flex";

        });

        user.addEventListener("mouseout", () => {

            perfil.classList.remove("active");

            settings.style.display = "none";

        });

    }

}

activeLink();
mobileMenu();
showSetting();

if (saveImg) {

    saveImg.addEventListener("mouseover", saveImgChange);

    saveImg.addEventListener("mouseout", saveImgChange);
    
}
