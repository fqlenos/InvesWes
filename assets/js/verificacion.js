function insertAfter(nodo_nuevo, nodo_referencia) {
    nodo_referencia.parentNode.insertBefore(nodo_nuevo, nodo_referencia.nextSibling);
}

// llamada AJAX verificacion
if (document.getElementById("verificar")) {

    const verificacion_btn = document.getElementById("verificar");
    verificacion_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("email", document.getElementById("email").value);
        datos.append("token", document.getElementById("token").value);


        if (datos.get("email").length < 1) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un email válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("email"));
            return false; // kills the script
        }

        if (datos.get("token").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un token válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("token"));
            return false; // kills the script
        }

        let xhr = new XMLHttpRequest();
        xhr.addEventListener("readystatechange", function() {
            if (this.readyState == 4) {

                resp = JSON.parse(this.response);

                if (resp.redirect != undefined) {
                    window.location = resp.redirect;
                }

                if (resp.error != undefined) {
                    let small = document.createElement("small");
                    small.setAttribute("style", "color: orange; order:3");
                    small.setAttribute("id", "_error");
                    let _error = document.createTextNode(resp.error);
                    small.appendChild(_error);
                    try {
                        document.getElementById("_error").remove();
                    } catch (e) {}
                    insertAfter(small, document.getElementById("token"));
                }
            }
        });

        xhr.open("POST", "ajax/verificacion.php", true);
        xhr.send(datos);


    });
}