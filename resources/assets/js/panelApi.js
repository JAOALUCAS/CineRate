const apiForms = document.querySelectorAll(".apiForm");
let jsonContainer;
let jsonContentExample;
const castCheck = document.querySelector(".custom-checkbox input");
const upcreasePage = document.querySelectorAll(".upcreasePage");
const decreasePage = document.querySelectorAll(".decreasePage");
var carregando = document.querySelector(".carregando");

const apiFormCadastro = document.querySelectorAll(".api-form .api-cadastro");

let apiPageActor = 1;
let apiPageFilm = 1;

const options = {
    method: 'GET',
    headers: {
    accept: 'application/json',
    Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJhMGRlMzU1MDhiYzZkYjQ1ZDAwYjA5NTNmN2YwZWRkNyIsIm5iZiI6MTczODgwMTE3NS4wNjgsInN1YiI6IjY3YTQwMDE3YmYzNjA0MGM1ZDg1YTZjYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.-Qv4ixwFDsjiwa45lkmGi4x_zwCiXh89oGaKt9Ys6V4'
    }
};

let a = " https://api.themoviedb.org/3/person/popular?&page=1";
let getCast = false;
let callFilms = true;
let films;

function getResponseApi(){

    if(apiForms){

       apiForms.forEach((form)=>{

            form.addEventListener("submit", (event)=>{

                event.preventDefault();

                const parent = form.parentElement;

                if(parent){

                    const parentClassList = parent.classList;

                    parentClassList.forEach((classe)=>{

                        if(classe.includes("actor")){

                            callFilms = false;

                            jsonContainer = document.querySelector(".actor-api-form").querySelector(".json-viewer-container");                           

                            jsonContentExample = document.querySelector(".actor-api-form").querySelector(".json-example");
                            

                        }else{
                            
                            jsonContainer = document.querySelector(".film-api-form").querySelector(".json-viewer-container");                           

                            jsonContentExample = document.querySelector(".film-api-form").querySelector(".json-example");

                        }

                    });

                }
                
                let params;

                let jsons;

                if(callFilms){
                    
                    let nomeFuncao = "getDetails";
                        
                    if(parent.classList.contains("choose-unique-film")){

                        nomeFuncao = "getUniqueFilm";

                        let movieName = form.getElementsByTagName("input")[0].value;

                        params =  String(movieName);

                    }

                    jsons = window[nomeFuncao](params); 
                        
                    if(typeof(jsons) == "object"){

                        jsons.then(json =>{

                            films = json.results;
                            
                            showResponseJson();

                        });

                    }

                }else{

                    jsons = getPopularsActor();
                        
                    if(typeof(jsons) == "object"){

                        jsons.then(json =>{

                            films = json.results;
                            
                            showResponseJson();

                        });

                    }

                }
       
            });

        });

    }

}
 

async function getDetails() {

    try{

        const response = fetch(`https://api.themoviedb.org/3/discover/movie?include_adult=true&language=pt-BR&page=${apiPageFilm}&sort_by=popularity.desc`, options);

        const data = (await response).json();

        return data;

    }catch(error){

        return null;

    }

}

async function showResponseJson(){

    let cleanJson = jsonContainer.querySelectorAll(".json-example");

    cleanJson.forEach((jsonExample, index)=>{

        if(index !== (cleanJson.length -1) && index !== 0){

            jsonContainer.removeChild(jsonExample);

        }

    });

    if(films){
    
        films.forEach(async (film)=>{

            if(film["id"] && getCast){

                const searchById = await getFilmsProduction(film["id"]);

                if(typeof(searchById) !== null){

                    film = searchById;                      

                }

                const elenco = await getFilmsCast(film["id"]);
                
                if(typeof(elenco) !== null){

                    film.cast = (elenco.cast || [])
                        .slice(0, 5)
                        .map(ator => ator.name)
                        .join(', ');
                    
                    film.crew = (elenco.crew || [])
                        .filter(pessoa => pessoa.job === 'Director')
                        .map(diretor => diretor.name)
                        .join(', ');

                }

            }

            let cloneTemplate =  jsonContentExample.cloneNode(true);
    
            let chaves = Object.keys(film);

            let values = Object.values(film);

            let range = Object.keys(film).length;

            let stringRows = [];

            for(let i = 0; i < range; i++){            

                if(typeof(values[i]) == "object"){

                    values[i] = JSON.stringify(values[i]);

                }else if(typeof(values[i]) == "string"){

                    values[i] = values[i].replace(/["']/g, '');

                }
    
                let stringRow = `<span class="json-key">"${chaves[i]}"</span>: <span class="json-${(typeof values[i]).toLowerCase()}">"${values[i]}"</span> <br>`;

                stringRows.push(stringRow);

            }

            cloneTemplate.innerHTML += `{
${stringRows.join("")}}`;

            if(cloneTemplate){
                    
                jsonContainer.style.display = "block";

                jsonContainer.appendChild(cloneTemplate);

            }

        });

    }

}

function verifyCast(){

    if(castCheck){

        castCheck.addEventListener("click", ()=>{

            getCast = !getCast;

        });

    }

}

async function getFilmsCast(movieId){

    try{
        
        const response =  await fetch(`https://api.themoviedb.org/3/movie/${movieId}/credits?language=pt-BR`, options);
 
        const data = (await response).json();

        return data;

    }catch(error){

        return null;

    }

}

async function  getFilmsProduction(movieId) {
    
    try{

        const response =  await fetch(`https://api.themoviedb.org/3/movie/${movieId}?language=pt-BR`, options);
 
        const data = (await response).json();

        return data;

    }catch(error){

        return null;

    }

}

async function getUniqueFilm(movieName){

    try{

        const response = await fetch(`https://api.themoviedb.org/3/search/movie?query=${encodeURIComponent(movieName)}&include_adult=true&language=pt-BR&page=1`, options);
 
        const data = (await response).json();

        return data;

    }catch(error){ 

        return null;

    }

}

async function getPopularsActor() {
    
    try{
        
        const response = await fetch(`https://api.themoviedb.org/3/person/popular?page=${apiPageActor}`, options);
 
        const data = (await response).json();

        return data;

    }catch(error){

        return null;

    }

}

function showApiPage(container = null){
    
    let pageNum = document.querySelectorAll(".pageNum");

    if(pageNum){

        pageNum.forEach((pageNum)=>{

            if(container?.contains(pageNum)){
                    
                pageNum.innerHTML = "Page " + (container.classList.contains("actor-api-form") ? apiPageActor : apiPageFilm);

                setTimeout(()=>{
                    pageNum.classList.add("roll");
                },100);

                pageNum.classList.remove("roll");

            }

        });

    }

}

function pageApiUpdate(){

    if(upcreasePage && decreasePage){

        upcreasePage.forEach((upcreasePage)=>{
                
            upcreasePage.addEventListener("click", ()=>{
                
                let upContainer = upcreasePage.closest(".actor-api-form") || upcreasePage.closest(".film-api-form");
                
                let classes = upContainer.classList;

                classes.forEach((classe)=>{

                    if(classe.includes("actor")){
                            
                        apiPageActor++;

                    }else if(classe.includes("film")){
                            
                        apiPageFilm++;

                    }

                });

                showApiPage(upContainer);

            });

        });

        decreasePage.forEach((decreasePage)=>{
                
            decreasePage.addEventListener("click", ()=>{
                                
                let deContainer = decreasePage.closest(".actor-api-form") || decreasePage.closest(".film-api-form");

                let classes = deContainer.classList;

                classes.forEach((classe)=>{

                    if(classe.includes("actor")){
                            
                        if(apiPageActor !== 1){

                            apiPageActor--;

                        }

                    }else if(classe.includes("film")){
                            
                        if(apiPageFilm !== 1){

                            apiPageFilm--;

                        }

                    }

                });

                showApiPage(deContainer);

            });


        });

    }

}

function formApiDb(){

    if(apiFormCadastro){

        apiFormCadastro.forEach((apiForm)=>{
                
            apiForm.addEventListener("submit", (event)=>{

                event.preventDefault();
                
                carregando.style.display = "block";

                const parent = apiForm.parentElement;  
                
                if(parent){
                
                    const inputJsonInsert = document.createElement("input");

                    inputJsonInsert.type = "hidden";

                    if(parent.classList){

                        parent.classList.forEach((classe)=>{

                            if(classe.includes("film")){

                                inputJsonInsert.name = "jsonFilm";

                            }else if(classe.includes("actor")){

                                inputJsonInsert.name = "jsonActor";

                            }

                            inputJsonInsert.value = JSON.stringify(films);

                        });

                    }

                    if(inputJsonInsert){
                        
                        apiForm.append(inputJsonInsert);

                        apiForm.submit();

                    }

                }

            });

        });

    }

}

getResponseApi();
verifyCast();
pageApiUpdate();
formApiDb();
showApiPage();