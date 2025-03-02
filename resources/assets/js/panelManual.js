const manForm = document.querySelectorAll(".man-form");

function formManDb(){

    manForm.forEach((manForm)=>{

        manForm.addEventListener("submit", (event)=>{
           
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

                    if(input.type !== "radio"){
                        
                        dados[input.name] = input.value;


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