const manForm = document.querySelectorAll(".man-form");

function formManDb(){

    manForm.forEach((manForm)=>{

        manForm.addEventListener("submit", (event)=>{
            
            carregando.style.display = "block";
           
            let dados = {};

            event.preventDefault();

            const inputInsert = document.createElement("input");

            inputInsert.type = "hidden";

            const parent = manForm.parentElement;

            if(parent){

                parent.classList.forEach((classe)=>{
                    
                    if(classe.includes("film")){

                        inputInsert.name = "postFilm";

                    }else if(classe.includes("actor")){

                        inputInsert.name = "postActor";

                    }

                });

                const inputs = manForm.getElementsByTagName("input");

                Array.from(inputs).forEach((input)=>{

                    if(input.type !== "radio" && input.type !== "checkbox"){
                        
                        dados[input.name] = input.value;

                    }else if(input.type == "checkbox"){

                        const inputParent = input.parentElement;

                        const labels = inputParent?.getElementsByTagName("label");

                        let generos = [];

                        Array.from(labels).forEach((label)=>{

                            if(label.classList.contains("actived")){

                                generos.push(label.textContent.split(" ")[0]);

                            }

                        });

                        if(generos.length > 0){

                            dados["generos"] = generos;

                        }

                    }else{
                        
                        const label = document.querySelector(`label[for="${input.id}"]`);

                        if(label.classList.contains("actived")){
                                
                            dados[input.name] = input.value;

                        }

                    }

                });

                if(Object.keys(dados).length > 0){

                    inputInsert.value = JSON.stringify(dados);

                }

            }
            
            if(inputInsert){
                        
                manForm.append(inputInsert);

                manForm.submit();

            }

        });

    });

}

formManDb();