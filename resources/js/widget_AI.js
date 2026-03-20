window.toggleW =toggleW 

// ══ WIDGET ══
let wOpen=false;
function toggleW(){wOpen=!wOpen;document.getElementById('wpanel').classList.toggle('open',wOpen)}
setTimeout(()=>{if(!wOpen){wOpen=true;document.getElementById('wpanel').classList.add('open')}},8000);
