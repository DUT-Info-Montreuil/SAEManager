
/**
 * Gestion du boutton "Sélectionner fichier" dans les modal "Rendu" et "Support"
 */
const selectFileButtonList = document.querySelectorAll('[class*="selectFileButton-"]');
selectFileButtonList.forEach(element =>{
  element
})

/**
* Gestion du modal (pop up) "Dépot Rendu"
**/

const modalDepotRendu = document.getElementById('modalDepotRendu')
const dropRenduFileCancelButton = document.getElementById('depotCancelButtonRendu')
const listRenduButtons = document.querySelectorAll('[class*="rendudrop-"]');
const idSaeDepotRendu = document.getElementById('idSaeDepotRendu');

document.getElementById('selectFileButtonRendu').addEventListener('click', function() {
  document.getElementById('fileInputRendu').click();
});

dropRenduFileCancelButton.addEventListener('click', function (){
  modalDepotRendu.classList.remove('d-block');
});

listRenduButtons.forEach(element => {
  const matches = element.className.match(/rendudrop-(\d+)/);
  if(matches){
    const number = matches[1]
    element.addEventListener('click', function (){
      idSaeDepotRendu.value = number;
      modalDepotRendu.classList.add('d-block');
    });
  }
});

/**
* Gestion du modal (pop up) "Dépot Support"
**/

const modalDepotSupport = document.getElementById('modalDepotSupport')
const dropSupportFileCancelButton = document.getElementById('depotCancelButtonSupport')
const listSupportButtons = document.querySelectorAll('[class*="supportdrop-"]');
const idSaeDepotSupport = document.getElementById('idSaeDepotSupport');

document.getElementById('selectFileButtonSupport').addEventListener('click', function() {
  document.getElementById('fileInputSupport').click();
});
dropSupportFileCancelButton.addEventListener('click', function (){
  modalDepotSupport.classList.remove('d-block');
});

listSupportButtons.forEach(element => {
  const matches = element.className.match(/supportdrop-(\d+)/);
  if(matches){
    const number = matches[1]
    element.addEventListener('click', function (){
      console.log("support button clicked");
      idSaeDepotSupport.value = number;
      modalDepotSupport.classList.add('d-block');
    });
  }
});



        
