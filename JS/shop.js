function colorSelect(colorID) {
    var idColor = colorID;
    var color = document.getElementById(colorID);
    var colorSelected = document.querySelector('.selected');
    var nameColor = document.getElementById('nameColor');

    var nameSize = document.getElementById('nameSize');
    var input = document.getElementById('ipColor');
    var inputSize = document.getElementById('ipSize');

    var sizes = document.getElementById('opt' + idColor.substring(11, 100));
    var sizesSelected = document.querySelector('.sizes.visible');

    var sizeSelected = document.querySelector('.selected.black');

    var button = document.getElementById('btAdicionar').disabled = true;

    if (color) {
        colorSelected.classList.remove('selected');
        color.classList.add('selected');
        nameColor.innerHTML = color.getAttribute('alt');

        input.setAttribute("value", idColor.substring(11, 100));

        if (colorSelected != color) {
            sizes.classList.add('visible');
            sizesSelected.classList.remove('visible');
            inputSize.setAttribute("value",'');
            nameSize.innerHTML = '';
            sizeSelected.classList.remove('selected');
            sizeSelected.classList.remove('black');
        }
    }
}

function sizeSelect(sizeID) {
    var idSize = sizeID;
    var size = document.getElementById(sizeID);
    var sizeSelected = document.querySelector('.selected.black');
    var nameSize = document.getElementById('nameSize');
    var input = document.getElementById('ipSize');
    var button = document.getElementById('btAdicionar');

    if (size) {
        if (sizeSelected) {
            sizeSelected.classList.remove('selected');
            sizeSelected.classList.remove('black');
            sizeSelected.classList.add('grey');
        }
        size.classList.add('selected');
        size.classList.add('black');
        nameSize.innerHTML = size.getAttribute('alt');
        input.setAttribute('value', idSize.substring(10, 100));

        button.disabled = false;
    }
}