document.addEventListener('DOMContentLoaded', () => {
    const blocks = document.querySelectorAll('.block');
    const timeSlotsContainer = document.getElementById('time-slots');
    const currentDateElement = document.getElementById('current-date').querySelector('span');
    const datePicker = document.getElementById('date-picker');

    const startTimeInput = document.getElementById('start-time');
    const endTimeInput = document.getElementById('end-time');
    const slotDurationInput = document.getElementById('slot-duration');
    const generateSlotsButton = document.getElementById('generate-slots');
    const validateButton = document.getElementById('validate');
    const scheduleDataContainer = document.getElementById('schedule-data');
    const initialDurationTime = document.getElementById('slot-duration').value.toString();

    let currentDate = new Date();
    updateDate();


    function addDragAndDropListeners() {
        const timeSlots = document.querySelectorAll('.time-slot');

        timeSlots.forEach(slot => {
            slot.addEventListener('dragover', (e) => {
                e.preventDefault(); // Permet le drop
                slot.classList.add('bg-light'); // Ajoute un style visuel
            });

            slot.addEventListener('dragleave', () => {
                slot.classList.remove('bg-light'); // Enlève le style visuel
            });

            slot.addEventListener('drop', (e) => {
                e.preventDefault();

                // Vérifie si un bloc existe déjà dans la plage horaire
                const existingBlock = slot.querySelector('.block');
                if (existingBlock) {
                    alert("Cette plage horaire contient déjà un groupe !");
                    slot.classList.remove('bg-light'); 
                    return; 
                }

                const blockId = e.dataTransfer.getData('text/plain'); // Récupère l'id du bloc
                const block = document.querySelector(`.block[data-id='${blockId}']`);

                if (block) {
                    slot.appendChild(block); // Ajoute le bloc au slot
                    block.draggable = !block.parentElement.className.includes("time-slot");
                    updateRemoveButtonVisibility(slot); 
                }

                slot.classList.remove('bg-light'); 
            });
        });
    }

    function updateRemoveButtonVisibility(slot) {
        const removeButton = slot.querySelector('.remove-block');
        const blockExists = slot.querySelector('.block');

        if (blockExists) {
            // Si un bloc est présent, ajoutez le bouton "Retirer" si ce n'est pas déjà fait
            if (!removeButton) {
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Retirer';
                removeButton.className = 'btn btn-warning btn-sm ms-2 remove-block';
                removeButton.addEventListener('click', () => {
                    const block = slot.querySelector('.block');
                    if (block) {
                        // Retirer le bloc de la plage horaire
                        document.getElementById('blocks').appendChild(block);
                        block.draggable = !block.parentElement.className.includes("time-slot");
                    }

                    updateRemoveButtonVisibility(slot); // Met à jour la visibilité du bouton
                });
                slot.prepend(removeButton); // Ajoute le bouton "Retirer" au début de la plage
            }
        } else if (removeButton) {
            // Si aucun bloc n'est présent et que le bouton existe, supprimez-le
            removeButton.remove();
        }
    }


    blocks.forEach(block => {
        block.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', block.dataset.id);
        });

    });

    blocks.forEach(block => {
        block.addEventListener('dragend', (e) => {
            const slot = document.querySelector('.time-slot .block'); // Trouve le slot actif si besoin
            updateRemoveButtonVisibility(slot); // Met à jour la visibilité du bouton "Retirer"
            block.draggable = !block.parentElement.className.includes("time-slot");
        });
    });

    datePicker.addEventListener('change', () => {
        currentDate = new Date(datePicker.value);
        updateDate();
        resetBlocks();
        timeSlotsContainer.innerHTML = '';
    });

    generateSlotsButton.addEventListener('click', () => {
        resetBlocks();

        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const date = datePicker.value;
        const slotDuration = parseInt(slotDurationInput.value, 10);

        if (!startTime || !endTime || isNaN(slotDuration) || slotDuration <= 0) {
            alert('Veuillez entrer une heure de début, une heure de fin et une durée valides.');
            return;
        }

        const [startHour, startMinute] = startTime.split(':').map(Number);
        const [endHour, endMinute] = endTime.split(':').map(Number);

        let currentHour = startHour;
        let currentMinute = startMinute;

        timeSlotsContainer.innerHTML = '';


        let listeGroupe = document.querySelectorAll('[class*="groupeAvecPassage:"]');

        while (currentHour < endHour || (currentHour === endHour && currentMinute < endMinute)) {
            const nextMinute = currentMinute + slotDuration;
            const nextHour = nextMinute >= 60 ? currentHour + 1 : currentHour;
            const adjustedNextMinute = nextMinute % 60;

            if (nextHour > endHour || (nextHour === endHour && adjustedNextMinute > endMinute)) break;

            const slotElement = document.createElement('div');
            slotElement.className = 'p-3 mb-2 border border-dashed rounded time-slot';

            const startTime = `${String(currentHour).padStart(2, '0')}:${String(currentMinute).padStart(2, '0')}`;
            const endTime = `${String(nextHour).padStart(2, '0')}:${String(adjustedNextMinute).padStart(2, '0')}`;

            slotElement.dataset.time = `${startTime} - ${endTime}`;
            slotElement.textContent = `${startTime} - ${endTime}`;

            timeSlotsContainer.appendChild(slotElement);

            currentHour = nextHour;
            currentMinute = adjustedNextMinute;

            let taken = false;
            let groupeId = null
            let i = 0
            let newDurationTime = document.getElementById('slot-duration').value.toString();

            for (i; i < listeGroupe.length; i++) {
                let groupHour = listeGroupe[i].className.match(/(\d{2}:\d{2})/).toString().slice(0, 5); // Récupère l'heure de passage du groupe
                let groupDate =  listeGroupe[i].className.split(":")[3].slice(0,10);
                if (groupHour === startTime && groupDate === date) {
                    taken = true;
                    groupeId = listeGroupe[i].className.split(":")[1];
                    break;
                }
            }

            if (taken && initialDurationTime === newDurationTime) {
                // Code pour placer le groupe
                const blockDiv = document.createElement('div');
                blockDiv.className = 'p-3 mb-2 bg-light border rounded block hasSchedule';
                blockDiv.draggable = true;
                blockDiv.setAttribute('data-id', groupeId);
                blockDiv.textContent = listeGroupe[i].className.split(":")[2];

                blockDiv.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('text/plain', groupeId); // Enregistre l'id pour le drop
                });

                // Créer l'input caché
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'idgroupesoutenance';
                hiddenInput.name = 'idgroupesoutenance';
                hiddenInput.className = 'form-control block-input';

                blockDiv.appendChild(hiddenInput);
                slotElement.appendChild(blockDiv);

                // Mettez à jour la visibilité du bouton "Retirer" immédiatement après l'ajout du bloc
                updateRemoveButtonVisibility(slotElement);
                blockDiv.draggable = !blockDiv.parentElement.className.includes("time-slot");
            }
        }

        addDragAndDropListeners();
    });

    validateButton.addEventListener('click', () => {
        // Vider les données précédentes
        scheduleDataContainer.innerHTML = '';

        const timeSlots = document.querySelectorAll('.time-slot');
        timeSlots.forEach(slot => {
            const block = slot.querySelector('.block');
            if (block) {
                const blockId = block.dataset.id;
                const timeData = slot.dataset.time;

                // Créer un input pour envoyer l'association
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'schedule[]'; // Nom du champ
                input.value = `${blockId}|${timeData}`; // Valeur du champ

                scheduleDataContainer.appendChild(input);

                block.draggable = !block.parentElement.className.includes("time-slot");
            }
        });

        // Ajoutez d'autres données à soumettre si nécessaire
        const datePickerValue = datePicker.value;
        const startTimeValue = startTimeInput.value;
        const endTimeValue = endTimeInput.value;
        const slotDurationValue = slotDurationInput.value;

        // Créer des inputs pour la date et les heures
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'date';
        dateInput.value = datePickerValue;

        const startInput = document.createElement('input');
        startInput.type = 'hidden';
        startInput.name = 'start_time';
        startInput.value = startTimeValue;

        const endInput = document.createElement('input');
        endInput.type = 'hidden';
        endInput.name = 'end_time';
        endInput.value = endTimeValue;

        const durationInput = document.createElement('input');
        durationInput.type = 'hidden';
        durationInput.name = 'duration';
        durationInput.value = slotDurationValue;

        // Ajoutez les nouveaux inputs au conteneur de données
        scheduleDataContainer.appendChild(dateInput);
        scheduleDataContainer.appendChild(startInput);
        scheduleDataContainer.appendChild(endInput);
        scheduleDataContainer.appendChild(durationInput);

        // Soumettre le formulaire
        document.getElementById('schedule-form').submit();
    });



    function resetBlocks() {
        const slots = document.querySelectorAll('.time-slot');
        slots.forEach(slot => {
            const block = slot.querySelector('.block');
            if (block) {
                document.getElementById('blocks').appendChild(block);
            }
            updateRemoveButtonVisibility(slot);
        });

        let newDurationTime = document.getElementById('slot-duration').value.toString();
        if(initialDurationTime === newDurationTime) {
            blocksHasSchellued = document.querySelectorAll('[class*="hasSchedule"]')
            blocksHasSchellued.forEach(block => {
                if (block.parentElement.className.includes("blocks"))
                    block.remove();
            })
        }
    }

    function updateDate() {
        currentDateElement.textContent = currentDate.toISOString().split('T')[0];
        datePicker.value = currentDate.toISOString().split('T')[0];
    }

    blocks.forEach(bloc =>{
        bloc.draggable = !bloc.parentElement.className.includes("time-slot");
    })
});

/**
 *
 *
 * TO-DO : ENLEVER LE BOUTTON RETIRER LORSQU'ON ENLEVE UN GROUPE D'UNE PLAGE HORAIRE
 *
 *
 */
