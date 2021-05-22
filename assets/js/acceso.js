function insertAfter(nodo_nuevo, nodo_referencia) {
    nodo_referencia.parentNode.insertBefore(nodo_nuevo, nodo_referencia.nextSibling);
}

window.onload = function() {
    try {
        // para acceso y registro
        document.getElementById("email").value = "";
        document.getElementById("password").value = "";
        document.getElementById("nombre").value = "";
        document.getElementById("apellidos").value = "";
    } catch (e) {}
}

// llamada AJAX registro
if (document.getElementById("registro")) {

    const registro_btn = document.getElementById("registro");
    registro_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("email", document.getElementById("email").value);
        datos.append("nombre", document.getElementById("nombre").value);
        datos.append("apellidos", document.getElementById("apellidos").value);
        datos.append("password", document.getElementById("password").value);

        if (datos.get("email").length < 1 || datos.get("email").length > 60) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un correo válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("email"));
            return false; // kills the script
        }

        if (datos.get("nombre").length < 1 || datos.get("nombre").length > 20) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un nombre válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("nombre"));
            return false; // kills the script
        }

        if (datos.get("apellidos").length < 1 || datos.get("apellidos").length > 60) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica unos apellidos válidos.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("apellidos"));
            return false; // kills the script
        }

        if (datos.get("password").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una contraseña válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password"));
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
                    insertAfter(small, document.getElementById("email"));
                }
            }
        });

        xhr.open("POST", "ajax/registro.php", true);
        xhr.send(datos);


    });
}

// llamada AJAX acceso
if (document.getElementById("acceso")) {

    const acceso_btn = document.getElementById("acceso");
    acceso_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("email", document.getElementById("email").value);
        datos.append("password", document.getElementById("password").value);

        if (datos.get("email").length < 1 || datos.get("email").length > 60) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un correo válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("email"));
            return false; // kills the script
        }

        if (datos.get("password").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una contraseña válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password"));
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
                    insertAfter(small, document.getElementById("email"));
                }
            }
        });

        xhr.open("POST", "ajax/acceso.php", true);
        xhr.send(datos);


    });
}