const listeSupportDownload = document.querySelectorAll('[class*="lineSupportTelechargeable"]');
const listeDeroulantSupport = document.querySelectorAll('[class*="chevronSupport-"]');
console.log(listeDeroulantSupport);

listeDeroulantSupport.forEach((element) =>{
    element.addEventListener("click",function () {

    console.log("here");
        const id = element.className.match(/chevronSupport-(\d+)/)[1];
        listeSupportDownload.forEach((Support) =>{
            let idSupport = Support.className.match(/lineSupportTelechargeable-(\d+)/)[1];
            console.log(idSupport, id);
            if(idSupport == id)
                if(element.className.includes('fa-chevron-down'))
                    Support.classList.add('d-none');
                else
                    Support.classList.remove('d-none');
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
