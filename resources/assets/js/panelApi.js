const filmForms = document.querySelectorAll(".apiForm");
const jsonContainer = document.querySelector(".json-viewer-container");
const jsonContentExample = document.querySelector(".json-example");

let films;

function getResponseApi(){

    if(filmForms){

        filmForms.forEach((form)=>{

            form.addEventListener("submit", (event)=>{

                event.preventDefault();

                let jsons = getDetails();

                if(typeof(jsons) == "object"){
                 
                    jsons.then(json =>{

                        films = json.results;
                        
                        showResponseJson();

                    });

                }
       
            });

        });

    }

}

async function  getDetails() {

    try{
      
        const options = {
            method: 'GET',
            headers: {
            accept: 'application/json',
            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJhMGRlMzU1MDhiYzZkYjQ1ZDAwYjA5NTNmN2YwZWRkNyIsIm5iZiI6MTczODgwMTE3NS4wNjgsInN1YiI6IjY3YTQwMDE3YmYzNjA0MGM1ZDg1YTZjYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.-Qv4ixwFDsjiwa45lkmGi4x_zwCiXh89oGaKt9Ys6V4'
            }
        };

        const response = fetch('https://api.themoviedb.org/3/discover/movie?include_adult=true&language=pt-BR&page=1&sort_by=popularity.desc', options);

        const data = (await response).json();

        return data;

    }catch(error){

        return null;

    }

}

function showResponseJson(){

    if(films){
    
        films.forEach((film)=>{

            let cloneTemplate =  jsonContentExample.cloneNode(true);

            let chaves = Object.keys(film);

            let values = Object.values(film);

            let range = Object.keys(film).length;

            let stringRows = [];

            for(let i = 0; i < range; i++){                

                let stringRow = `<span class="json-key">"${chaves[i]}"</span>: <span class="json-string">"${values[i]}"</span> <br>`;

                stringRows.push(stringRow);

            }

            console.log(stringRows)

            cloneTemplate.innerHTML += `{
${stringRows.join("")}}`;

            if(cloneTemplate){
                    
                jsonContainer.style.display = "block";

                jsonContainer.appendChild(cloneTemplate);

            }

        });

    }

}

getResponseApi();