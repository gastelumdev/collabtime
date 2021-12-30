function start() {
    var file = document.getElementById('customFile');
    file.addEventListener('change', handleFile, false);
}

function handleFile() {
    var splitPath = this.value.split('\\');
    document.getElementById('customFileLabel').innerHTML = splitPath[splitPath.length - 1];

}

window.addEventListener('load', start, false);