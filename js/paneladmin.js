document.getElementById('dropdownButton').addEventListener('click', function() {
    var dropdownContent = document.getElementById('dropdownContent');
    dropdownContent.style.display = dropdownContent.style.display === 'none' || dropdownContent.style.display === '' ? 'block' : 'none';
});

document.getElementById('searchInput').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var items = document.querySelectorAll('#dropdownContent .dropdown-item');

    items.forEach(function(item) {
        if (item.textContent.toLowerCase().indexOf(input) > -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

document.getElementById('dropdownButtonProf').addEventListener('click', function() {
    var dropdownContentProf = document.getElementById('dropdownContentProf');
    dropdownContentProf.style.display = dropdownContentProf.style.display === 'none' || dropdownContentProf.style.display === '' ? 'block' : 'none';
});

document.getElementById('searchInputProf').addEventListener('keyup', function() {
    var input = this.value.toLowerCase();
    var items = document.querySelectorAll('#dropdownContentProf .dropdown-item');

    items.forEach(function(item) {
        if (item.textContent.toLowerCase().indexOf(input) > -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

function annulerAction() {
    document.querySelector('form').reset();

}