
// 2021-06-03 OG NEW - Toggle the class 'hide' to hide and show the mobile navigation 
function navButton() {
    var navList = document.getElementById('side-nav');
    if (navList.className === 'hide') {
        navList.className = '';
        document.getElementById('nav').style.paddingLeft = '250px';
    } else {
        navList.className = 'hide';
        document.getElementById('nav').style.paddingLeft = 0;
    }
}

// 2021-06-03 OG NEW - The start function sets up all the adds all the events 
//                     and calls all the functionsneeded when the page loads. 
//                     It is called when onload
function start() {
    // 2021-06-03 OG NEW - Set the navigation ul for mobile to hide when the page loads 
    var navList = document.getElementById('side-nav');
    navList.className = 'hide';
}

window.addEventListener('load', start, false);