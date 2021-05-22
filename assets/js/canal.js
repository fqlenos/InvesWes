function insertAfter(nodo_nuevo, nodo_referencia) {
    nodo_referencia.parentNode.insertBefore(nodo_nuevo, nodo_referencia.nextSibling);
}

// llamada AJAX crear canal
if (document.getElementById("crear_canal")) {

    const crear_canal_btn = document.getElementById("crear_canal");
    crear_canal_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("nombre", document.getElementById("nombre").value);
        datos.append("descripcion", document.getElementById("descripcion").value);
        datos.append("precio_diario", document.getElementById("precio_diario").value);
        datos.append("wallet", document.getElementById("wallet").value);

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

        if (datos.get("descripcion").length < 1) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una descripción válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("descripcion"));
            return false; // kills the script
        }

        if (datos.get("precio_diario").length < 1 || datos.get("precio_diario") < 5) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un precio válido, mínimo 5 TLMCoins.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("precio_diario"));
            return false; // kills the script
        }

        if (datos.get("wallet").length < 1 || datos.get("wallet").length > 10) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un wallet válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("wallet"));
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
                    insertAfter(small, document.getElementById("nombre"));
                }
            }
        });

        xhr.open("POST", "ajax/crear-canal.php", true);
        xhr.send(datos);

    });
}

// llamada AJAX crear canal
if (document.getElementById("retirar_sobrante")) {
    const retirar_btn = document.getElementById("retirar_sobrante");
    retirar_btn.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("cantidad", document.getElementById("cantidad").value);
        datos.append("wallet_dst", document.getElementById("wallet_dst").value);

        if (datos.get("cantidad").length < 1 || datos.get("cantidad") <= 0) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una cantidad válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("cantidad"));
            return false; // kills the script
        }

        if (datos.get("wallet_dst").length < 1 || datos.get("wallet_dst") <= 0) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una wallet válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("wallet_dst"));
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
                    insertAfter(small, document.getElementById("wallet_dst"));
                }
            }
        });

        xhr.open("POST", "ajax/retirar-sobrante.php", true);
        xhr.send(datos);

    });

}

if (document.getElementById("baja")) {
    const baja_btn = document.getElementById("baja");
    baja_btn.addEventListener("click", function() {

        window.location = "/ajax/abandonar-canal.php";

    });
}

if (document.getElementById("wallet-canal")) {
    const wallet_canal = document.getElementById("wallet-canal");
    wallet_canal.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("wallet", document.getElementById("wallet").value);
        datos.append("password", document.getElementById("password").value);

        if (datos.get("wallet").length < 1) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica una wallet válida.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("wallet"));
            return false; // kills the script
        }

        if (datos.get("password").length < 1) {
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
                    insertAfter(small, document.getElementById("password"));
                }
            }
        });

        xhr.open("POST", "../ajax/wallet-canal.php", true);
        xhr.send(datos);

    });

}