function ajaxSearchForm(formId, url, containerId) {
    var form = document.getElementById(formId);
    var formData = new FormData(form);
    var params = new URLSearchParams(formData).toString();

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById(containerId).innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', url + '?' + params, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send();
}
