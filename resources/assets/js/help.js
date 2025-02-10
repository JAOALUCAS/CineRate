const question = document.querySelectorAll(".question");
const h2 = document.querySelectorAll(".help h2");
const search = document.querySelector(".help-search");
const bar = document.querySelector(".bar");
const li = document.querySelectorAll(".question");

function revealQuestion(){

    question.forEach((qst)=>{

        qst.addEventListener("click", ()=>{

            qst.classList.toggle("activeQuest");

        });

    });

}

function searchQuestion() {

    search.addEventListener("input", (event) => {
        
        bar.innerHTML = "";

        const value = formatString(event.target.value); 

        let results = [];

        let styleTopResults = [];

        let encontrado = false;

        li.forEach((qst) => {

            const qstText = formatString(qst.textContent);

            if (qstText.includes(value)) {    

                const qstTitle = qst.textContent.split("    ", 2)[0].trim();

                bar.style.display = "flex";

                results.push(qstTitle);

                styleTopResults.push(qst);

                encontrado = true;

            }

        });

        if (!encontrado) {

            bar.style.display = "none";

        }else{

            results.forEach((result)=>{

                const p = document.createElement("p");
                
                p.innerHTML = `-${result}`;

                bar.appendChild(p);

            });

        }
        
        let clickResult = bar.children;

        if(clickResult){

            Array.from(clickResult).forEach((resultScroll, index)=>{

                resultScroll.addEventListener("click", ()=>{

                    const posY = parseInt(styleTopResults[index].getBoundingClientRect().top) - 100;

                    window.scrollTo({
                        top: posY,
                        behavior: "smooth"
                    });

                });

            });

        }

    });

}

function formatString(value) {

    return value
        .toLowerCase()
        .trim()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, "");

}

document.addEventListener("click", ()=>{

    bar.innerHTML="";

    bar.style.display = "none";

});

searchQuestion();
revealQuestion();