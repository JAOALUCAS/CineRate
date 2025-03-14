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

let liSelected = document.querySelector(".menu ul li.selected");

let toggleActived = false;

let lastRadio;

let tablePagina = 1;

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
    
    let radioInputs = document.querySelectorAll('input[type="radio"]');

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

            let confirm;

            customOption.forEach((customOption)=>{

                if(customOption.contains(event.target)){   
                            
                    p = customOption.getElementsByTagName("p");

                    modal = customOption.querySelector(".modal");

                    confirm = customOption.querySelector(".confirm-custom-option");

                }

            });

            if(p && modal && confirm){

                if(p[0].contains(event.target) || modal.contains(event.target) && !confirm.contains(event.target)){

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

                    if(modal.contains(confirm)){

                        modal.style.display = "none !important";

                    }

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

                        newLabel.innerHTML += newInput.outerHTML;

                        if(opcoes){

                            opcoes.forEach((opcoe)=>{

                                if(opcoe.contains(confirm)){

                                    document.appendChild(newLabel);

                                    opcoe.appendChild(newLabel);

                                }

                            });

                        }

                        let localLabels = JSON.parse(localStorage.getItem("labels")) || [];

                        let parentDiv;

                        customOption.forEach((parent)=>{

                            if(parent.contains(confirm)){

                                parentDiv = parent;

                            }

                        });

                        if(localLabels !== null){

                            localLabels.push({
                                "parent-id": parentDiv.id,
                                "element":   newLabel.outerHTML
                            });
                            
                            localStorage.setItem("labels", JSON.stringify(localLabels));

                        }else{

                            labels.push({
                                "parent-id": parentDiv.id,
                                "element": newLabel.outerHTML
                            });

                            localStorage.setItem("labels", JSON.stringify(labels));

                        }

                    }

                }

            });

        });

    }

}

function getLocalCustomOption(){

    let localLabel =  JSON.parse(localStorage.getItem("labels"));

    if(localLabel){
        
        if(opcoes){

            let parent = [];
            let htmlLabel = [];

            localLabel.forEach((label)=>{

                htmlLabel.push(label["element"]);

                customOption.forEach((customOption)=>{

                    if(customOption.id == label["parent-id"]){

                        parent.push(customOption);

                    }

                });

            });

            opcoes.forEach((opcoe)=>{

                parent.forEach((parent, index)=>{

                    if(opcoe.contains(parent)){
                        
                        let template = document.createElement("div");

                        template.innerHTML = htmlLabel[index];
                        
                        document.body.append(template);

                        opcoe.appendChild(template);

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

            carregando.style.display = "block";
            
            setTimeout(()=>{

                carregando.style.display = "none";

            }, 500);

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

function adminForm(){

    const newAdminForm = document.querySelector(".new-admin");

    newAdminForm.addEventListener("submit", (event)=>{

        event.preventDefault();

        if(carregando){

            carregando.style.display = "flex";

        }

        const input = document.createElement("input");

        input.type = "hidden";

        input.name = "newAdmin";

        newAdminForm.appendChild(input);

        newAdminForm.submit();

    });

}

function deleteAdmin(){

    const deleteForm = document.querySelectorAll(".delete-admin");

    if(deleteForm){

        deleteForm.forEach((deleteF)=>{

            deleteF.addEventListener("submit", (event)=>{

                event.preventDefault();
    
                if(carregando){
    
                    carregando.style.display = "flex";
        
                }
        
                const input = document.createElement("input");
        
                input.type = "hidden";
        
                input.name = "deleteAdmin";
        
                deleteF.appendChild(input);
                
                deleteF.submit();
    
            });

        });

    }

}

function tablePagination(){

    const table = document.querySelector(".table-container table");

    const limitePagination = 4;

    if(table){

        const tr = table.querySelectorAll("tbody tr");
        
        const qtdLinhas = tr.length;

        const qtdPaginas = Math.ceil(qtdLinhas/limitePagination);

        tablePagina = Math.max(1, Math.min(tablePagina, qtdPaginas));

        tr.forEach((linha, index)=>{

            const inicio = (tablePagina - 1) * limitePagination;

            const fim = inicio + limitePagination;

            if (index >= inicio && index < fim) {

                linha.style.display = ""; 

            } else {

                linha.style.display = "none"; 

            }

        });
        
        atualizarPaginacao(qtdPaginas);

    }

}

function proximaPagina() {
    tablePagina++;
    tablePagination();
}

function paginaAnterior() {
    tablePagina--;
    tablePagination();
}
function atualizarPaginacao(qtdPaginas) {

    const pageIndicator = document.getElementById("pageIndicator");

    pageIndicator.textContent = `${tablePagina}`;

    const prevBtn = document.getElementById("prevBtn");

    const nextBtn = document.getElementById("nextBtn");

    
    prevBtn.removeEventListener("click", paginaAnterior);
    nextBtn.removeEventListener("click", proximaPagina);

    prevBtn.addEventListener("click", paginaAnterior);
    nextBtn.addEventListener("click", proximaPagina);

    prevBtn.disabled = tablePagina === 1;

    nextBtn.disabled = tablePagina === qtdPaginas;

}

function defineManutenance(){

    const manutenanceF = document.querySelector(".manutenance-form");

    if(manutenanceF){

        manutenanceF.addEventListener("submit", (event)=>{

            event.preventDefault();

            const newInput = document.createElement("input");

            newInput.type = "hidden";

            newInput.name = "manutenance";

            manutenanceF.appendChild(newInput);

            manutenanceF.submit();

        });

    }

}

function filterContent(){

    const filterF = document.querySelector(".filter-content");

    
    if(filterF){

        filterF.addEventListener("submit", (event)=>{

            event.preventDefault();

            const newInput = document.createElement("input");

            newInput.type = "hidden";

            newInput.name = "filterInsert";

            filterF.appendChild(newInput);

            filterF.submit();

        });

    }

}

let valoresIniciais = [];

function showEspecififcRelatorio(){

    const especificBtn = document.querySelectorAll(".especific-btn");

    let btnFechar;

    let btnFecharparent;

    let btnF;

    if(especificBtn){

        especificBtn.forEach((btn)=>{

            btn.addEventListener("click", ()=>{

                const parentEsspecific = (btn.parentElement).parentElement;

                if(parentEsspecific){

                    btnFecharparent = parentEsspecific;

                    const especificsRelatorio = parentEsspecific.querySelector(".most-especific-infos");

                    const inputsE = especificsRelatorio.querySelectorAll("input");

                    inputsE.forEach((input)=>{

                        input.dataset.inicial = input.value;

                        input.addEventListener("input", () => {

                            if (input.value !== input.dataset.inicial) {

                                input.classList.add("modificado");

                            }
                        });

                    });

                    especificsRelatorio.style.display = "flex";

                    btnFechar = especificsRelatorio.querySelector(".btn-cadastro");
                        
                    if(btnFechar){

                        btnFechar.addEventListener("click", ()=>{

                                especificsRelatorio.style.display = "none";

                        });

                    }

                    btnF = especificsRelatorio.querySelector(".btn-danger");
                    
                    if(btnF){

                        btnF.addEventListener("click", ()=>{

                            const newForm = document.createElement("form");

                            newForm.method = "post";

                            const newInput = document.createElement("input");

                            newInput.type = "hidden";
                            
                            const tabela = (especificsRelatorio.parentElement).querySelector(".infos .tabela-especific");

                            let validate = tabela.textContent == "filmes" ? "Film" : "Actor";

                            newInput.name = "update" + validate;

                            inputsE.forEach((input) => {

                                if (input.value !== input.dataset.inicial) {

                                    let editInput = document.createElement("input");

                                    editInput.type = "hidden";

                                    editInput.name = input.name;
                                    
                                    editInput.value = input.value; 

                                    newForm.appendChild(editInput);

                                }

                            });
                            

                            let value = Number((especificsRelatorio.querySelector(".update-film-id")).textContent.replaceAll(" ",'').split('').filter(elemento => !isNaN(elemento)).join('')); 

                            newInput.value = value;

                            newForm.appendChild(newInput);

                            document.body.appendChild(newForm);

                            newForm.submit();

                        });

                    }

                }

            });

        });

    }

}

function customImgGraphics(){

    const graficD = document.querySelectorAll(".grafic-d");

    graficD.forEach((grafic)=>{

        grafic.addEventListener("mouseover", ()=>{

            const imgSelected = grafic.querySelector(".graf-text img");

            if(imgSelected){

                imgSelected.src = imgSelected.src.replace(".png", "-selected.png");

            }

        });

        grafic.addEventListener("mouseout", ()=>{

            const imgSelected = grafic.querySelector(".graf-text img");

            if(imgSelected){

                imgSelected.src = imgSelected.src.replace("-selected.png", ".png");

            }

        });

    });

}

showForm();
showMenu();
addSelected();
apiUse();
selectType();
checkAtived();
customModal();
getLocalCustomOption();
getCategoryBeforeReload();
showOpcoes();
adminForm();
deleteAdmin();
tablePagination();
defineManutenance();
filterContent();
showEspecififcRelatorio();
customImgGraphics();