const canvases = Array.from(document.getElementsByTagName('canvas'))
let ctx;
let title, titleText, titleAsArray, author, authorText, authorAsArray, typeStyle, typeColor, dropdownForType;

canvases.forEach((canvas) => {
    ctx = canvas.getContext('2d');

    title = canvas.parentElement.parentElement.querySelector('.title-item')
    titleText = title ? title.textContent.trim().replaceAll(' ', '').toLowerCase() : '';
    titleAsArray = titleText.split('').slice(0, 10);
    author = canvas.parentElement.parentElement.querySelector('.author-item')
    authorText = author ? title.textContent.trim().replaceAll(' ', '').toLowerCase() : '';
    authorAsArray = authorText.split('').slice(0, 10);
    dropdownForType = canvas.parentElement.parentElement.querySelector('.type-item .badge');
    if (dropdownForType) {
        typeStyle = window.getComputedStyle(dropdownForType)
    }
    typeColor = typeStyle ? typeStyle.getPropertyValue('background-color') : 'white';

    ctx.fillStyle = typeColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // author blob
    ctx.fillStyle = 'white';
    ctx.beginPath()
    for (let i = 0; i < authorAsArray.length - 1; i++) {
        ctx.lineTo((authorAsArray[i].charCodeAt() - 93), (authorAsArray[i + 1].charCodeAt() - 93))
    }
    ctx.fill();
    // title blob
    ctx.fillStyle = 'black';
    ctx.beginPath()
    for (let i = 0; i < titleAsArray.length - 1; i++) {
        ctx.lineTo((titleAsArray[i].charCodeAt() - 93), (titleAsArray[i + 1].charCodeAt() - 93))
    }
    ctx.fill()
})
