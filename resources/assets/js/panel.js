const li = document.querySelectorAll(".menu ul li");
const lineContainer = document.querySelector(".lines-container");
const menu = document.querySelector(".menu");

let liSelected = document.querySelector(".menu ul li.selected");

function addSelected() {

    li.forEach((li) => {

        li.addEventListener("click", () => {
            
            if (liSelected) {

                liSelected.classList.remove("selected");

                let imgSelected = liSelected.getElementsByTagName("img")[0];

                if (imgSelected) {

                    imgSelected.style.filter = "brightness(1.5)";

                    imgSelected.src = imgSelected.src.replace("-selected.png", ".png");

                }

            }

            li.classList.add("selected");

            let imgSelected = li.getElementsByTagName("img")[0];

            if (imgSelected) {

                imgSelected.style.filter = "none";

                imgSelected.src = imgSelected.src.replace(".png", "-selected.png");

            }

            liSelected = li; 

        });

    });

}

function showMenu(){

    lineContainer.addEventListener("click", ()=>{

        menu.classList.toggle("active");

    });

}

showMenu();
addSelected();
