
let scroll = this.document.querySelector(".scrolltop");
let scrollHeader = document.querySelector(".scrollheader");
let saveImg = document.getElementById("saveImg");

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

function saveImgChande(){

    mouseIsOver = !mouseIsOver;

    mouseIsOver == true ? saveImg.src="../../resources/assets/icons/salvar-instagramLaranja.png" : saveImg.src ="../../resources/assets/icons/salvar-instagram.png";

}

saveImg.addEventListener("mouseover", saveImgChande);
saveImg.addEventListener("mouseout", saveImgChande);
