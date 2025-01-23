const listeChampDownload = document.querySelectorAll('[class*="lineReponsesChamp"]');
const listeDeroulantChamp = document.querySelectorAll('[class*="chevronChamp-"]');


listeDeroulantChamp.forEach((element) =>{
    element.addEventListener("click",function () {
        const id = element.className.match(/chevronChamp-(\d+)/)[1];
        listeChampDownload.forEach((Champ) =>{
            let idChamp = Champ.className.match(/lineReponsesChamp-(\d+)/)[1];
            console.log(idChamp, id);
            if(idChamp == id)
                if(element.className.includes('fa-chevron-down'))
                    Champ.classList.add('d-none');
                else
                    Champ.classList.remove('d-none');
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
