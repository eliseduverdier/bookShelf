const typesCanvas = document.getElementById('types')
const ctxType = typesCanvas.getContext('2d');


/* DIAGRAM */
ctxType.font = '14px monospace';
let startType = 0;
let endType = 0;
let colorCountType = 0;
let placeType = 0;
for (let type in types) {
    ctxType.lineWidth = 150;
    /* LINE */
    endType += percentToRadiant(types[type]);

    ctxType.fillStyle = '#ffffff00';
    ctxType.strokeStyle = colors[colorCountType];
    ctxType.beginPath();
    ctxType.arc(typesCanvas.width / 2, typesCanvas.height / 2, notesCanvas.width / 6, startType, endType);
    ctxType.stroke();

    /* LEGEND */
    ctxType.fillStyle = colors[colorCountType];
    ctxType.textAlign = 'center';
    ctxType.lineWidth = 2;
    ctxType.strokeStyle = 'white';

    placeType = (startType + endType) / 2
    xType = typesCanvas.width / 2 + notesCanvas.width / 2.5 * Math.cos(placeType);
    yType = typesCanvas.height / 2 + notesCanvas.width / 2.5 * Math.sin(placeType);
    ctxType.strokeText(type, xType, yType);
    ctxType.fillText(type, xType, yType);
    colorCountType++;

    startType += percentToRadiant(types[type]);
}

function percentToRadiant(percent) {
    return (percent * Math.PI * 2) / 100;
}
