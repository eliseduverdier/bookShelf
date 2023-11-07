const canvas = document.getElementById('book-cover');
const ctx = canvas.getContext('2d');

title = document.querySelector('input[name="title"]').value.toLowerCase();
titleAsArray = title.replaceAll(' ', '').split('').slice(0,10);

author = document.querySelector('input[name="author"]').value.toLowerCase();
authorAsArray = author.replaceAll(' ', '').split('').slice(0,10);

typeStyle = window.getComputedStyle(document.querySelector('select[name="type"]'))
typeColor = typeStyle.getPropertyValue('background-color');

ctx.fillStyle = typeColor;
ctx.fillRect(0, 0, canvas.width, canvas.height);
ctx.globalAlpha = .8;

// author
ctx.fillStyle = 'white';
ctx.beginPath()
for (let i=0 ; i < authorAsArray.length-1 ; i++) {
    ctx.lineTo ( (authorAsArray[i].charCodeAt()-95)*6, (authorAsArray[i+1].charCodeAt()-80)*6)
}
ctx.fill();
// title
ctx.fillStyle = 'black';
ctx.beginPath()
for (let i=0 ; i < titleAsArray.length-1 ; i++) {
    ctx.lineTo ( (titleAsArray[i].charCodeAt()-95)*6, (titleAsArray[i+1].charCodeAt()-80)*6)
}
ctx.fill()

// title display
ctx.fillStyle = typeColor;
ctx.fillRect(0, (canvas.width/3), canvas.width,  canvas.width/3);
ctx.textAlign = 'center';
ctx.fillStyle = 'white';
ctx.font = '18px monospace';

ctx.fillText(title.replaceAll(' ', "\n"), canvas.width/2, canvas.height/3, canvas.width)
