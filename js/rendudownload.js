const listeRenduDownload = document.querySelectorAll('[class*="lineRenduTelechargeable"]');
const listeDeroulantRendu = document.querySelectorAll('[class*="chevronRendu-"]');


listeDeroulantRendu.forEach((element) =>{
    element.addEventListener("click",function () {
        const id = element.className.match(/chevronRendu-(\d+)/)[1];
        listeRenduDownload.forEach((rendu) =>{
            let idRendu = rendu.className.match(/lineRenduTelechargeable-(\d+)/)[1];
            console.log(idRendu, id);
            if(idRendu == id)
                if(element.className.includes('fa-chevron-down'))
                    rendu.classList.add('d-none');
                else
                    rendu.classList.remove('d-none');
            })
            if(element.className.includes('fa-chevron-down')){
                element.classList.remove('fa-chevron-down');
                element.classList.add('fa-chevron-up');
            }
            else{
                element.classList.add('fa-chevron-down');
                element.classList.remove('fa-chevron-up');
            }
    })
})
