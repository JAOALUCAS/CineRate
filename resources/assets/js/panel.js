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
const customOption = document.querySelectorAll(".custom-option");
const confirmCustomOption = document.querySelectorAll(".confirm-custom-option");

let labels = [];
let inputs = [];

let liSelected = document.querySelector(".menu ul li.selected");

let toggleActived = false;

let lastRadio;

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
    
    const radioInputs = document.querySelectorAll('input[type="radio"]');

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

    if(radioInputs){

        radioInputs.forEach((radio) => {
            
            radio.addEventListener("change", (event)=>{

                const label = document.querySelector(`label[for="${event.target.id}"]`);

                if(label){
                    
                    const labelBros = (label.parentElement).getElementsByTagName("label");

                    if(labelBros){

                        console.log(lastRadio)

                        Array.from(labelBros).forEach((labelBro)=>{

                            if(labelBro.classList.contains("actived") && labelBro !== label){

                                label.classList.remove("actived");

                            }

                            if(labelBro.contains(lastRadio)){

                                labelBro.classList.remove("actived");

                            }

                        });

                    }
                    
                    label.classList.toggle("actived");

                    opcoes.forEach((opcoe)=>{

                        if(opcoe.contains(label)){

                            opcoe.classList.remove("actived");
                                
                            const parent = opcoe.parentElement;

                            let p = parent?.getElementsByTagName("p")[0];

                            if(p){
                                
                                p.innerHTML = `> ${label.textContent}`;

                            }


                        }

                    });

                    lastRadio = radio;

                }

            });

        });

    }

}

function checkAtived(){

    const checkBox = document.querySelectorAll('.geners input[type="checkbox"]');

    if(checkBox){

        checkBox.forEach((check)=>{

            check.addEventListener("change", (event)=>{

                const label = document.querySelector(`label[for="${event.target.id}"]`);

                if(label){

                    label.classList.toggle("actived");

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

function customModal(){

    if(customOption){

        document.addEventListener("click", (event)=>{

            let p;

            let modal;

            customOption.forEach((customOption)=>{
                    
                p = customOption.getElementsByTagName("p");

                modal = customOption.querySelector(".modal");

            });

            if(p && modal){

                if(p[0].contains(event.target) || modal.contains(event.target)){

                    modal.style.display = "flex";

                }else{

                    modal.style.display = "none";

                }

            }
        
        });

    }

    if(confirmCustomOption){

        confirmCustomOption.forEach((confirm)=>{

            confirm.addEventListener("click", ()=>{

                const parentForm = confirm.parentElement.getElementsByTagName("input");
            
                const modal = document.querySelectorAll(".modal");

                modal.forEach((modal)=>{

                    modal.style.display = "none";

                });

                if(parentForm){

                    if(parentForm[0].length !== 0 || parentForm[0].value !== null){

                        let valorInput = parentForm[0].value.trim().replace(/\s+/g, '-');

                        let verificarInput = true;

                        let i = 1;

                        while(verificarInput){

                            if(document.getElementById(valorInput) !== null){

                                let trocaNum = false;

                                let caracteres = valorInput.split("");
                        
                                for (let j = 0; j < caracteres.length; j++) {

                                    if (!isNaN(parseInt(caracteres[j]))) {

                                        caracteres[j] = i.toString(); 

                                        valorInput = caracteres.join(""); 

                                        trocaNum = true;
                                        
                                        break; 

                                    }

                                }

                                if(!trocaNum){
                                    
                                    valorInput = valorInput + i.toString();

                                }

                                i++;

                            }else{

                                verificarInput = !verificarInput;

                            }

                        }

                        let newInput = document.createElement("input");

                        newInput.type = "radio";

                        newInput.value = valorInput;

                        newInput.name = valorInput;

                        newInput.id = valorInput;

                        let newLabel = document.createElement("label");

                        newLabel.htmlFor = valorInput;

                        newLabel.textContent = valorInput;

                        if(opcoes){

                            opcoes.forEach((opcoe)=>{

                                if(opcoe.contains(confirm)){

                                    opcoe.appendChild(newInput);

                                    opcoe.appendChild(newLabel);

                                }

                            });

                        }

                        let localInputs = JSON.parse(localStorage.getItem("inputs")) || [];

                        let localLabels = JSON.parse(localStorage.getItem("labels")) || [];

                        let parentDiv;

                        customOption.forEach((parent)=>{

                            if(parent.contains(confirm)){

                                parentDiv = parent;

                            }

                        });

                        if(localInputs !== null && localLabels !== null){

                            localLabels.push({
                                "parent-id": parentDiv.id,
                                "element":   newLabel.outerHTML
                            });
                            
                            localInputs.push({
                                "parent-id": parentDiv.id,
                                "element":   newInput.outerHTML
                            });
                            
                            localStorage.setItem("labels", JSON.stringify(localLabels));

                            localStorage.setItem("inputs",JSON.stringify(localInputs));

                        }else{

                            labels.push({
                                "parent-id": parentDiv.id,
                                "element": newLabel.outerHTML
                            });

                            localStorage.setItem("labels", JSON.stringify(labels));

                            inputs.push({
                                "parent-id": parentDiv.id,
                                "element": newInput.outerHTML
                            });

                            localStorage.setItem("inputs",JSON.stringify(inputs));

                        }

                    }

                }

            });

        });

    }

}

function getLocalCustomOption(){

    let localLabel =  JSON.parse(localStorage.getItem("labels"));

    let localInputs = JSON.parse(localStorage.getItem("inputs"));

    if(localLabel && localInputs){
        
        if(opcoes){

            let parent = [];
            let htmlInput = [];
            let htmlLabel = [];

            localLabel.forEach((label)=>{

                htmlLabel.push(label["element"]);

                customOption.forEach((customOption)=>{

                    if(customOption.id == label["parent-id"]){

                        parent.push(customOption);

                    }

                });

            });

            localInputs.forEach((input)=>{

                htmlInput.push(input["element"]);

            });

            opcoes.forEach((opcoe)=>{

                parent.forEach((parent, index)=>{

                    if(opcoe.contains(parent)){
                        
                        let newInput = document.createElement("div");

                        newInput.innerHTML = htmlInput[index];

                        let newLabel = document.createElement("div");

                        newLabel.innerHTML = htmlLabel[index];

                        opcoe.appendChild(newInput);

                        opcoe.appendChild(newLabel);

                    }

                });

            });
            
        }

    }

}

function getCategoryBeforeReload(){

    window.addEventListener("beforeunload", ()=>{
        
        let imgLiSelected = liSelected?.getElementsByTagName("img")[0]?.id;

        localStorage.setItem("categoriaAtivada", imgLiSelected);

    });

    window.addEventListener("load", ()=>{
            
        let localCategoria = localStorage.getItem("categoriaAtivada") ?? null;

        if(localCategoria){

            li.forEach((li)=>{

                let imgLiSelected = li?.getElementsByTagName("img")[0]?.id;

                if(imgLiSelected){
                        
                    if(imgLiSelected == localCategoria){

                        return li.click();

                    }

                }

            });

        }

    });

}

showForm();
showMenu();
addSelected();
apiUse();
selectType();
showOpcoes();
checkAtived();
customModal();
getLocalCustomOption();
getCategoryBeforeReload();