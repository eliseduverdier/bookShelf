const notesCanvas = document.getElementById('notes')
const ctx = notesCanvas.getContext('2d');

const colors = [
    '#6a5392',
    '#088395',
    '#05bfdb',
    '#8eac50',
    '#d2ad12',
    '#d6770a',
    '#e05215',
    '#93078e',
    '#555555',

];
/* DIAGRAM */
ctx.font = '14px monospace';
let start = 0;
let end = 0;
let colorCount = 0;
let place, x, y;
for (let note in notes) {
    ctx.lineWidth = 150;
    /* LINE */
    end += percentToRadiant(notes[note]);

    ctx.fillStyle = '#ffffff00';
    ctx.strokeStyle = colors[colorCount];
    ctx.beginPath();
    ctx.arc(notesCanvas.width / 2, notesCanvas.height / 2, notesCanvas.width / 6, start, end);
    ctx.stroke();

    /* LEGEND */
    ctx.fillStyle = colors[colorCount];
    ctx.textAlign = 'center';
    ctx.lineWidth=2
    ctx.strokeStyle = 'white';

    place = (start + end) / 2
    x = notesCanvas.width / 2 + notesCanvas.width / 2.5 * Math.cos(place);
    y = notesCanvas.height / 2 + notesCanvas.width / 2.5 * Math.sin(place)
    ctx.strokeText(note, x, y,);
    ctx.fillText(note, x, y,);
    colorCount++;

    /* COUNT */
    ctx.fillStyle = 'white';
    ctx.fillText(
        Math.round(notes[note])+'%',
        notesCanvas.width / 2 + notesCanvas.width / 6 * Math.cos(place),
        notesCanvas.height / 2 + notesCanvas.width / 6 * Math.sin(place)
    )

    start += percentToRadiant(notes[note])
}

function percentToRadiant(percent) {
    return (percent * Math.PI * 2) / 100;
}
