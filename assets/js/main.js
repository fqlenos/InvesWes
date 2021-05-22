function insertAfter(nodo_nuevo, nodo_referencia) {
    nodo_referencia.parentNode.insertBefore(nodo_nuevo, nodo_referencia.nextSibling);
}


// busqueda de valores en la tabla, sin backend
function searching() {

    let input, filter, table, tr, td, i;

    input = document.getElementById("buscar");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        // Escondo la línea...
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");

        for (var j = 0; j < td.length; j++) {
            cell = tr[i].getElementsByTagName("td")[j];
            if (cell) {
                if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}

if (document.getElementById("select-crypto")) {
    const select = document.getElementById("select-crypto");
    select.addEventListener("click", function() {


    });
}

if (document.getElementById("guardar_valor")) {
    const btn_guardar_valor = document.getElementById("guardar_valor");
    btn_guardar_valor.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("select-crypto", document.getElementById("select-crypto").value);
        datos.append("precio", document.getElementById("precio").value);
        datos.append("cantidad", document.getElementById("cantidad").value);

        if (datos.get("select-crypto").length < 1 || datos.get("select-crypto").length > 10) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un ticker válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("select-crypto"));
            return false; // kills the script
        }

        if (datos.get("precio").length < 1 || datos.get("precio") < 0) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un ticker válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("precio"));
            return false; // kills the script
        }

        if (datos.get("cantidad").length < 1 || datos.get("cantidad") < 0) {
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
                    insertAfter(small, document.getElementById("select-crypto"));
                }
            }
        });

        xhr.open("POST", "ajax/add-cartera.php", true);
        xhr.send(datos);

    });
}

if (document.getElementById("guardar_valor_canal")) {
    const btn_guardar_valor = document.getElementById("guardar_valor_canal");
    btn_guardar_valor.addEventListener("click", function() {

        let datos = new FormData();
        datos.append("select-crypto", document.getElementById("select-crypto").value);
        datos.append("precio", document.getElementById("precio").value);
        datos.append("cantidad", document.getElementById("cantidad").value);

        if (datos.get("select-crypto").length < 1 || datos.get("select-crypto").length > 10) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un ticker válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("select-crypto"));
            return false; // kills the script
        }

        if (datos.get("precio").length < 1 || datos.get("precio") < 0) {
            let small = document.createElement("small");
            small.setAttribute("style", "color: orange; order:3");
            small.setAttribute("id", "_error");
            let _error = document.createTextNode("Indica un ticker válido.");
            small.appendChild(_error);
            try {
                document.getElementById("_error").remove();
            } catch (e) {}
            insertAfter(small, document.getElementById("precio"));
            return false; // kills the script
        }

        if (datos.get("cantidad").length < 1 || datos.get("cantidad") < 0) {
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
                    insertAfter(small, document.getElementById("select-crypto"));
                }
            }
        });

        xhr.open("POST", "ajax/add-cartera-canal.php", true);
        xhr.send(datos);

    });
}


if (document.getElementById("table")) {
    const tr = document.getElementById("table");
    const clicks = tr.getElementsByClassName("pop");

    for (let i = 0; i < clicks.length; i++) {
        clicks[i].addEventListener("click", function() {
            let datos = new FormData();
            datos.append("id", this.id);

            if (datos.get("id").length < 1 || datos.get("id").length > 10) {
                return false; // kills the script
            }

            let xhr = new XMLHttpRequest();
            xhr.addEventListener("readystatechange", function() {
                if (this.readyState == 4) {

                    resp = JSON.parse(this.response);

                    if (resp.redirect != undefined) {
                        window.location = resp.redirect;
                    }
                }
            });

            xhr.open("POST", "ajax/remove-valor.php", true);
            xhr.send(datos);

        });

    }
}

if (document.getElementById("table-cartera")) {
    const tr = document.getElementById("table-cartera");
    const clicks = tr.getElementsByClassName("pop");

    for (let i = 0; i < clicks.length; i++) {
        clicks[i].addEventListener("click", function() {
            let datos = new FormData();
            datos.append("id", this.id);

            if (datos.get("id").length < 1 || datos.get("id").length > 10) {
                return false; // kills the script
            }

            let xhr = new XMLHttpRequest();
            xhr.addEventListener("readystatechange", function() {
                if (this.readyState == 4) {

                    resp = JSON.parse(this.response);

                    if (resp.redirect != undefined) {
                        window.location = resp.redirect;
                    }
                }
            });

            xhr.open("POST", "ajax/remove-valor-canal.php", true);
            xhr.send(datos);

        });

    }
}