

function membership() {
    var group_div = document.getElementsByClassName('btn-action');
    
    for (var i = 0; i < group_div.length; i++) {
        var button = group_div[i];
        
        button.addEventListener('click', function() {
            var button = this;
            var xmlhttp = new XMLHttpRequest();
            var groupId = button.id;
            var value = button.value;

            var params = "id=" + groupId;

            if (value == "Join") {
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        button.classList.toggle('btn-success');
                        button.classList.toggle('btn-primary');
                        button.innerHTML = "Leave";
                        button.value = "Leave";
                    }
                };
                xmlhttp.open("POST", "index.php?group/join", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(params);
            } else {
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        button.classList.toggle('btn-success');
                        button.classList.toggle('btn-primary');
                        button.innerHTML = "Join";
                        button.value = "Join";
                    }
                };
                xmlhttp.open("POST", "index.php?group/leave", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(params);
            }
        }, false);
    }
}

window.addEventListener('load', membership, false);