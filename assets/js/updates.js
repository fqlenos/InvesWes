function insertAfter(nodo_nuevo, nodo_referencia) {
    nodo_referencia.parentNode.insertBefore(nodo_nuevo, nodo_referencia.nextSibling);
}

// llamada AJAX update información personal
if (document.getElementById("cuenta")) {

    const cuenta_btn = document.getElementById("cuenta");
    cuenta_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("nombre", document.getElementById("nombre").value);
        datos.append("apellidos", document.getElementById("apellidos").value);

        if (datos.get("nombre").length < 1) {
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

        if (datos.get("apellidos").length < 1) {
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

        xhr.open("POST", "/ajax/cuenta.php", true);
        xhr.send(datos);

    });
}

// llamada AJAX update password
if (document.getElementById("update_password")) {
    const update_password_btn = document.getElementById("update_password");
    update_password_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("password_new", document.getElementById("password_new").value);
        datos.append("password_new_repeat", document.getElementById("password_new_repeat").value);
        datos.append("password_current", document.getElementById("password_current").value);

        if (datos.get("password_new").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una contraseña más larga.");
            small.append(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password_new"));
            return false;
        }
        if (datos.get("password_new_repeat").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una contraseña más larga.");
            small.append(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password_new_repeat"));
            return false;
        }
        if (datos.get("password_current").length < 4) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una contraseña más larga.");
            small.append(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password_current"));
            return false;
        }
        if (datos.get("password_new") !== datos.get("password_new_repeat")) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Las contraseñas no coinciden.");
            small.append(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("password_new_repeat"));
            return false;
        }
        if (datos.get("password_new") === datos.get("password_new_repeat")) {
            if (datos.get("password_new") === datos.get("password_current")) {
                let small = document.createElement("small");
                small.setAttribute("style", "color: orange; order:3");
                small.setAttribute("id", "_error");
                let _error = document.createTextNode("La contraseña debe ser diferente a la actual.");
                small.append(_error);
                try {
                    document.getElementById("_error").remove();
                } catch (e) {}
                insertAfter(small, document.getElementById("password_new_repeat"));
                return false;
            }
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
                    insertAfter(small, document.getElementById("password_current"));
                }
            }
        });

        xhr.open("POST", "/ajax/password.php", true);
        xhr.send(datos);


    });
}