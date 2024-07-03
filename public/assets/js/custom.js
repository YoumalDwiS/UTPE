
function sweetAlert(icon, title, text, footer) {
    //---icon sweet alert
    //success
    //error
    //warning
    //info
    //question

    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        footer: footer,
    });
}

function getUrlParameter() {
    let parameter = {};
    let urlString = window.location.href;
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    for (let pair of queryString.entries()) {
        parameter[pair[0]] = pair[1];
    }
    return parameter;
}