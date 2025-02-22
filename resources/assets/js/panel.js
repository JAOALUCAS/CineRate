const li = document.querySelectorAll(".menu ul li");
const lineContainer = document.querySelector(".lines-container");
const menu = document.querySelector(".menu");
const dashboard = document.querySelector(".dashboard");
const users = document.querySelector(".users");
const insert =  document.querySelector(".insert");
const content = document.querySelector(".content");
const complaint = document.querySelector(".complaint");
const helpForm =  document.querySelector(".help-form");
const apiConfirm = document.querySelector(".toggle");
const typeInsert = document.querySelectorAll(".type button");
const opcoes = document.querySelectorAll(".opcoes");
const btnInsert = document.querySelectorAll("btn-insert");
const filmManual = document.querySelector(".film-manual-form");
const actorManual = document.querySelector(".actor-manual-form");
const filmApi =  document.querySelector(".film-api-form");
const actorApi = document.querySelector(".actor-api-form");

let liSelected = document.querySelector(".menu ul li.selected");

let toggleActived = false;

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
            
            showCategory(liSelected);

        });

    });

}

function showMenu(){

    lineContainer.addEventListener("click", ()=>{

        menu.classList.toggle("active");

    });

}

function showCategory(liSelected){

    let imgSelected = liSelected.getElementsByTagName("img");

    let containerAtived;

    switch(true){
        case imgSelected[0].id == "dash":
            dashboard.style.display = "flex";
            containerAtived = dashboard;
            break;
        case imgSelected[0].id == "users":
            users.style.display = "flex";
            containerAtived = users;
            break;
        case imgSelected[0].id == "insert":
            insert.style.display = "flex";
            containerAtived = insert;
            break;
        case imgSelected[0].id == "content":
            content.style.display = "flex";
            containerAtived = content;
            break;
        case imgSelected[0].id == "complaint":
            complaint.style.display = "flex";
            containerAtived = complaint;
            break;
        case imgSelected[0].id == "help-form":
            helpForm.style.display = "flex";
            containerAtived = helpForm;
            break;
    }

    let arrayContainer = [dashboard, users, insert, complaint, content, helpForm];

    arrayContainer.forEach((container)=>{

        if(container !== containerAtived){

            container.style.display = "none";

        }

    });

}

function apiUse(){

    if(apiConfirm){

        apiConfirm.addEventListener("click", ()=>{

            apiConfirm.classList.toggle("active");

            toggleActived = !toggleActived;

        });

    }

}

function selectType(){

    let typeActive;

    if(typeInsert){

        typeInsert.forEach((type, index)=>{

            type.addEventListener("click", ()=>{
                
                typeActive = type.classList.contains("active") ?? type;

                if(!typeActive){

                    let inx = index == 0 ? 1 : 0;

                    typeInsert[inx].classList.remove("active");

                }

                if(type.dataset.type == "actor"){

                    type.classList.add("active");
        
                }else if(type.dataset.type == "movie"){

                    type.classList.add("active");

                }

            });            

        });

    }

}

function showOpcoes(){

    if(opcoes){

        opcoes.forEach((opcoe)=>{

            const pai = opcoe.parentElement;

            pai.addEventListener("click", (event)=>{

                if(!opcoe.contains(event.target)){

                    opcoe.classList.toggle("actived");

                }

            });

        });

    }

}

function showForm(){

    document.addEventListener("click", ()=>{

        let typeSelect;

        typeInsert.forEach((type)=>{

            if(type.classList.contains("active")){

                typeSelect = type;

            }

            if(typeSelect){

                if(typeSelect.dataset.type == "actor"){

                    if (toggleActived) {
                        
                        actorManual.style.display = "none";

                        actorApi.style.display = "block";

                    } else {
                        
                        actorManual.style.display = "block";

                        actorApi.style.display = "none";
                        
                    }
                    
                    filmManual.style.display = "none";

                    filmApi.style.display = "none";

                }else if(typeSelect.dataset.type == "movie"){

                    if (toggleActived) {

                        filmManual.style.display = "none";

                        filmApi.style.display = "block";

                    } else {
                        
                        filmManual.style.display = "block";

                        filmApi.style.display = "none";

                    }
                    
                    actorManual.style.display = "none";

                    actorApi.style.display = "none";

                }

            }

        });

    });

}

showForm();
showMenu();
addSelected();
apiUse();
selectType();
showOpcoes();
