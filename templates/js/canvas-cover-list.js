const canvases = Array.from(document.getElementsByTagName('canvas'))
let ctx;
let title;

canvases.forEach((canvas) => {
    ctx = canvas.getContext('2d');

    title = canvas.parentElement.parentElement.querySelector('.title-item').textContent.trim().replaceAll(' ','').toLowerCase();
    titleAsArray = title.split('').slice(0,10);
    author = canvas.parentElement.parentElement.querySelector('.author-item').textContent.trim().replaceAll(' ','').toLowerCase();
    authorAsArray = author.split('').slice(0,10);
    typeStyle = window.getComputedStyle(canvas.parentElement.parentElement.querySelector('.type-item .badge'))
    typeColor = typeStyle.getPropertyValue('background-color');

    ctx.fillStyle = typeColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // author blob
    ctx.fillStyle = 'white';
    ctx.beginPath()
    for (let i=0 ; i < authorAsArray.length-1 ; i++) {
        ctx.lineTo ( (authorAsArray[i].charCodeAt()-93), (authorAsArray[i+1].charCodeAt()-93))
    }
    ctx.fill();
    // title blob
    ctx.fillStyle = 'black';
    ctx.beginPath()
    for (let i=0 ; i < titleAsArray.length-1 ; i++) {
        ctx.lineTo ( (titleAsArray[i].charCodeAt()-93), (titleAsArray[i+1].charCodeAt()-93))
    }
    ctx.fill()
})
