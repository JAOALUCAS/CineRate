const helpSearch = document.querySelector(".help-search");
const helpLupa = document.querySelector(".help-lupa");

let find = false;

function search() {

    let searching = null;
    let searchingParent = null;
    let value = null;

    const parentSearch = [dashboard, content, complaint, users, helpForm, insert];

    const iconClick = {
        "dash": document.getElementById("dash"),
        "users": document.getElementById("users"),
        "insert": document.getElementById("insert"),
        "content": document.getElementById("content"),
        "complaint": document.getElementById("complaint"),
        "help": document.getElementById("help-form")
    };

    if (helpSearch) {

        helpSearch.addEventListener("input", () => {

            value = formatString(helpSearch.value);

            if (value.length >= 3) {
                
                parentSearch.forEach((parent)=>{

                    const htmlParent = parent.outerHTML;

                    if(htmlParent.includes(value)){

                        searchingParent = parent;

                    }

                });

            }

        });

    }

    if (helpLupa) {

        helpLupa.addEventListener("click", () => {

            if(searchingParent){

                const verify = findTextContent(searchingParent, value);

                if(verify !== null){
                    
                    searching = verify;

                    let loop = true;

                    let numLoops = 1;

                    while(loop){

                        numLoops++;

                        if(numLoops == 50){
                            
                            loop = false;

                            searching = null

                            break;

                        }
                            
                        if(find){

                            loop = false;

                        }else{

                            searching = findTextContent(searching, value);

                        }

                    }

                    find = false;

                }

            }

            if (carregando) {

                carregando.style.display = "block";

                setTimeout(() => {

                    carregando.style.display = "none";

                }, 1000);

            }

            if (searching !== null && searchingParent !== null) {

                for (let icon in iconClick) {

                    if (searchingParent.classList[0].includes(icon)) {

                        let iconElement = iconClick[icon];

                        iconElement.click();

                        break;

                    }

                }

                const posY = parseInt(searching.getBoundingClientRect().top);

                window.scrollTo({
                    top: posY,
                    behavior: "smooth"
                });

            }

        });
    }

}

function findTextContent(element, textToCompare) {

    let textElement = formatString(element.textContent);

    let childrenVerify = element.children;

    if (textElement.includes(textToCompare) && childrenVerify.length == 0){

        find = true;

        return element;

    }

    for (let child of element.children) {

        if (findTextContent(child, textToCompare)) {

            find = true;

            return child;

        }
    }

    return null;

}

function formatString(value) {

    return value
        .toLowerCase()
        .trim()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, '');

}

search();
