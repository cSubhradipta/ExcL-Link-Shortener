const shortenBtn = document.getElementById('shorten-btn');
// console.log(window.innerWidth);

setInterval(() => {
    if(window.innerWidth < 786){
        shortenBtn.classList.add("px-4");
        shortenBtn.classList.remove("px-5");
        shortenBtn.innerHTML = '<i class="uil uil-link"></i>';
    } else {
        shortenBtn.classList.add("px-5");
        shortenBtn.classList.remove("px-4");
        shortenBtn.innerHTML = 'Shorten';
    }
}, 500);

const form = document.getElementById('main-field'),
fullURL = document.getElementById('org_url'),
modalBox = document.getElementById('modal'),
modalOpener = document.getElementById('modal-opener'),
mainModal = document.getElementById('main-modal'),
infoBox = document.getElementById('alert'),
infoMsg = document.getElementById('alert-msg'),
modalFooter = document.querySelector('.modal-footer'),
closeBtn = document.getElementById('dismiss-btn'),
form2 = modalBox.querySelector("form"),
shortenURL = document.getElementById('field'),
title = document.getElementById('title'),
longurl = document.getElementById('longurl'),
copyIcon = document.getElementById('copy-btn'),
saveBtn = document.getElementById('save-btn'),
delBtn = document.querySelectorAll('.delBtn'),
errMsg = document.getElementById('validation-error');


shortenBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "assets/components/save.php", true);
    xhr.onload = ()=>{
        if(xhr.readyState == 4 && xhr.status == 200){
            let txt = xhr.response;
            var data = JSON.parse(txt);
            var count = Object.keys(data).length;
            // console.log("data ==> ", data);
            // console.log("data len => ", count);
            // console.log("txt len => ", txt.length);
            if(count > 1){
                $('#modal').modal('show');
                closeBtn.classList.add('d-none');
                let domain = "localhost/excl/"; 
                shortenURL.value = domain + data.short_url;
                title.value = data.title;
                longurl.value = data.url;
                copyIcon.onclick = ()=>{
                    shortenURL.select();
                    document.execCommand("copy");
                }

                saveBtn.onclick = ()=>{
                    form2.onsubmit = (e)=>{
                        e.preventDefault();
                    }

                    let xhr2 = new XMLHttpRequest();
                    xhr2.open("POST", "assets/components/customize.php", true);
                    xhr2.onload = ()=>{
                        if(xhr2.readyState == 4 && xhr2.status == 200){
                            let data = xhr2.response;
                            if(data == "success"){
                                location.reload();
                            }else{
                                infoBox.classList.remove("d-none");
                                infoBox.classList.add("alert-danger");
                                infoMsg.innerText = data;
                                // console.log(data);
                            }
                        }
                    }
                    let shorten_url1 = shortenURL.value;
                    let title1 = title.value;
                    console.log("shorten: ", shorten_url1);
                    let hidden_url = data.short_url;
                    console.log("hidden: ", hidden_url);
                    xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr2.send("shorten_url="+shorten_url1+"&hidden_url="+hidden_url+"&title="+title1);
                }
            }else{
                // console.log("unsuccessful data: ", data.error);
                errMsg.innerHTML = data.error;
                setTimeout(function(){
                    errMsg.innerHTML = "&nbsp;";
                }, 3000);
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

function customizeURL(newTitle, fullURL, shortURL){
    if(infoMsg.innerText == "Your shortlink is ready."){
        infoBox.classList.add('d-none');
    }
    closeBtn.classList.remove('d-none');
    $('#modal').modal('show');
    let domain = "localhost/excl/";
    var key = shortURL;
    shortenURL.value = domain + shortURL;
    title.value = newTitle;
    longurl.value = fullURL;
    copyIcon.onclick = ()=>{
        shortenURL.select();
        document.execCommand("copy");
    }
    saveBtn.onclick = ()=>{

        form2.onsubmit = (e)=>{
            e.preventDefault();
        }

        let xhr3 = new XMLHttpRequest();
        xhr3.open("POST", "assets/components/customize.php", true);
        xhr3.onload = ()=>{
            if(xhr3.readyState == 4 && xhr3.status == 200){
                let data = xhr3.response;
                if(data == "success"){
                    location.reload();
                }else{
                    infoBox.classList.remove("d-none");
                    infoBox.classList.add("alert-danger");
                    infoMsg.innerText = data;
                    console.log(data);
                }
            }
        }

        let shorten_url1 = shortenURL.value;
        let title1 = title.value;
        console.log("shorten: ", shorten_url1);
        let hidden_url = key;
        console.log("hidden: ", hidden_url);
        xhr3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr3.send("shorten_url="+shorten_url1+"&hidden_url="+hidden_url+"&title="+title1);
    }
}

closeBtn.onclick = ()=>{
    infoBox.classList.add('d-none');
}

function copyLink(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

var readOnlyLength = 15;
// console.log("field", readOnlyLength);
$('#field').on('keypress, keydown', function(event) {
var $field = $(this);
if ((event.which != 37 && (event.which != 39)) && ((this.selectionStart < readOnlyLength)
	|| ((this.selectionStart == readOnlyLength) && (event.which == 8)))) {
	    return false;
	}
});