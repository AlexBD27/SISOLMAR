
import axios from 'axios';
import Swal from 'sweetalert2';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import 'tabulator-tables/dist/css/tabulator_simple.min.css';

import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';


document.addEventListener('DOMContentLoaded', function () {

    getPersonal();

    //Tabla de Personas
    const tblPersonas = new Tabulator("#tblPersonas", {
        height: "100%",
        layout:"fitData",
        responsiveLayout:"collapse",
        pagination: true,
        paginationSize: 10,
        rowHeader:{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
        locale: "es",
        langs: {
            "es": {
                "pagination": {
                    "first": "Primero",
                    "first_title": "Primera Página",
                    "last": "Último",
                    "last_title": "Última Página",
                    "prev": "Anterior",
                    "prev_title": "Página Anterior",
                    "next": "Siguiente",
                    "next_title": "Página Siguiente",
                    "all": "Todo"
                },
                "headerFilters": {
                    "default": "Filtrar...",
                },
                "ajax": {
                    "loading": "Cargando datos...",
                    "error": "Error al cargar datos"
                },
                "data": {
                    "empty": "No hay datos disponibles"
                }
            }
        },
        columns:[
            {title:"N°", formatter:"rownum", hozAlign:"center", width:60},

            {
                title:"Nombres",
                field:"nombres",
                hozAlign:"left",
                widthGrow:3,
                formatter: function(cell){
                    let data = cell.getData();
                    return `${data.nombres ?? ''} ${data.apellido1 ?? ''} ${data.apellido2 ?? ''} `.trim();
                }
            },

            {title:"DNI", field:"dni", hozAlign:"center", widthGrow:2},

            {
                title:"Acciones",
                field:"acciones",
                hozAlign:"center",
                headerSort:false,
                widthGrow:1,
                formatter: function(cell){
                    return `<button type="button" class="btn rounded-full form-btn bg-success/25 text-success hover:bg-success hover:text-white">Formulario</button>`;
                },
                cellClick: function(e, cell) {

                    if (e.target.classList.contains('form-btn')) {
                        var registro = cell.getRow().getData();

                        abrirFormulario(registro);
                    }
                }
            },
        ],
        layout:"fitColumns",

    });

    //Tabla de Coincidencias
    const tblPersonasCN = new Tabulator("#tblPersonasCN", {
        height: "100%",
        layout:"fitDataFill",
        responsiveLayout: "collapse",
        columns: [
            {title:"Código", field:"CODI_PERS", hozAlign:"center", width: '10%'},
            {title:"Personal", field:"personal", hozAlign:"left", width: '30%'},
            {title:"Nro Documento", field:"nroDoc", hozAlign:"center", width: '15%'},
            {title:"Sucursal", field:"sucursal", hozAlign:"center", width: '18%'},
        ],
    });

    document.getElementById("buscarPersonal").addEventListener("keyup", function () {
        let valor = this.value.toLowerCase().trim();
        
        tblPersonas.setFilter([
            [
                { field: "nombres", type: "like", value: valor },
                { field: "dni", type: "like", value: valor },
            ]
        ]);

        tblPersonas._ultimoFiltro = valor;

        setTimeout(() => resaltarTexto(valor), 10);
    });

    document.getElementById('btnNuevaDJ').addEventListener('click', function() {
        abrirFormulario();
    });


    document.getElementById('btnPrevisualizar').addEventListener('click', function () {
        const form = document.getElementById('formDatos');
        const formData = new FormData(form);

        console.log("Generando vista previa...");

        fetch('<?= base_url("DeclaracionJurada/previsualizar") ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al generar la previsualización');
            return response.blob();
        })
        .then(blob => {
            const url = URL.createObjectURL(blob);
            window.open(url, '_blank');
        })
        .catch(error => {
            console.error(error);
            alert("No se pudo generar la previsualización");
        });
    });



    document.addEventListener('click', function (event) {
        const modal = document.getElementById('formModal');
        const contenedor = modal.querySelector('.bg-white');

        if (event.target.closest('#btnNuevaDJ')) return;
        
        if (!modal.classList.contains('hidden')) { 
            if (!contenedor.contains(event.target) && !event.target.classList.contains('form-btn')) {
                cerrarFormulario();
            }
        }
    });

    document.getElementById('cerrarModal').addEventListener('click', function() {
        cerrarFormulario();
    });

    //Función para resaltar el texto del que se hace la búsqueda
    function resaltarTexto(valor){
        tblPersonas.getRows().forEach(row => {
            row.getElement().querySelectorAll(".tabulator-cell").forEach((cell, i, cells) => {
                if (i === cells.length - 1) return; // excluir última columna

                const text = cell.textContent;
                if (valor && text.toLowerCase().includes(valor)) {
                    const regex = new RegExp(`(${valor})`, "gi");
                    cell.innerHTML = text.replace(regex, "<span class='bg-warning/25'>$1</span>");
                } else {
                    cell.innerHTML = text;
                }
            });
        });
    };

    // Cada vez que se renderiza una página en la tabla de personal
    tblPersonas.on("renderComplete", function () {
        if (tblPersonas._ultimoFiltro) {
            resaltarTexto(tblPersonas._ultimoFiltro);
        }
    });

    // Función para obtener el listados de personas
    function getPersonal(){
        axios.get(`${ VITE_URL_APP }/api/get-postulantes`)
        .then(response => {
            const datosTabla = response.data;
            tblPersonas.setData(datosTabla);

        })
        .catch(error => {
            console.error("Hubo un error:", error);
        });
    }

    // Gestión del formulario de familiares
    const container = document.getElementById('familyContainer');
    const addBtn = document.getElementById('addFamilyMember');

    function makeFamilyRow() {
        return `
        <div class="family-row grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg relative">
            <div>
            <label class="text-sm font-medium inline-block mb-2">Parentesco</label>
            <select name="parentesco[]" class="form-select w-full">
                <option value="">Seleccionar</option>
                <option value="padre">Padre</option>
                <option value="madre">Madre</option>
                <option value="esposo">Esposo</option>
                <option value="esposa">Esposa</option>
                <option value="hijo">Hijo</option>
                <option value="hija">Hija</option>
                <option value="hermano">Hermano</option>
                <option value="hermana">Hermana</option>
                <option value="abuelo">Abuelo</option>
                <option value="abuela">Abuela</option>
            </select>
            </div>
            <div>
            <label class="text-sm font-medium inline-block mb-2">Apellidos y Nombres</label>
            <input type="text" name="apellidosNombres[]" class="form-input w-full" placeholder="Apellidos y nombres completos">
            </div>
            <div class="flex gap-2 items-end">
            <div class="flex-1">
                <label class="text-sm font-medium inline-block mb-2">Fecha Nacimiento</label>
                <input type="date" name="fechaNacimiento[]" class="form-input w-full">
            </div>
            <button type="button" class="remove-family self-end px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                Eliminar
            </button>
            </div>
        </div>
        `;
    }

    // Agregar fila
    if (addBtn) {
        addBtn.addEventListener('click', function (e) {
        e.preventDefault();
        container.insertAdjacentHTML('beforeend', makeFamilyRow());
        });
    }

    // Eliminar fila con delegación
    container.addEventListener('click', function (e) {
        const btn = e.target.closest('button.remove-family');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation(); // evita cierre del modal

        const row = btn.closest('.family-row');
        if (row) row.remove();
    });

    window.abrirFormulario = function (data = null) {

        limpiarFormulario();

        if(data){
            document.getElementById("cod_postulante").value = data.id;
            document.getElementById("nombres_apellidos").value = data.nombres + ' ' + data.apellido1 + ' ' + data.apellido2;
            document.getElementById("dni").value = data.dni ?? '';
            document.getElementById("fecha_nacimiento").value = data.fecha_nacimiento ?? '';

            //Seleccionar departamento
            const departamentoSelect = document.getElementById("departamento-actual");
            const provinciaSelect = document.getElementById("provincia-actual");
            const distritoSelect = document.getElementById("distrito-actual");

            if (data.departamento) {
                departamentoSelect.value = data.departamento;

                departamentoSelect.dispatchEvent(new Event("change"));

                setTimeout(() => {
                    if (data.provincia) {
                        provinciaSelect.value = data.provincia;
                        provinciaSelect.dispatchEvent(new Event("change"));

                        setTimeout(() => {
                            if (data.distrito) {
                                distritoSelect.value = data.distrito;
                            }
                        }, 150);
                    }
                }, 150);
            }

            document.getElementById("celular").value = data.celular ?? '';
            document.getElementById("correo").value = data.correo ?? '';
            document.getElementById("grado_instruccion").value = data.grado_instruccion ?? '';
            document.getElementById("curso_sucamec").value = (data.sucamec && data.sucamec.toUpperCase() === "SI") ? 1 : 0;

            const inputLicencia = document.getElementById("licencia_arma");
            const tagify = new Tagify(inputLicencia, {
                maxTags: 2
            });

            let licencias = data.licencia_arma;

            tagify.removeAllTags();

            if (typeof licencias === "string") {
                try {
                    licencias = JSON.parse(licencias);
                } catch(e) {
                    licencias = [licencias]; 
                }
            }

            if (licencias && Array.isArray(licencias)) {
                tagify.addTags(licencias);
            }



        }

        inputFoto.value = '';
        preview.src = '';
        preview.classList.add("hidden");
        container.innerHTML = '';
        container.insertAdjacentHTML('beforeend', makeFamilyRow());
        document.getElementById('formModal').classList.remove('hidden');
    };

    window.cerrarFormulario = function () {
        document.getElementById('formModal').classList.add('hidden');
    };


    function limpiarFormulario() {

        console.log("LIMPIAR FORMULARIO");

        const form = document.getElementById('formDatos');
        form.reset();

        // const inputFoto = document.getElementById("inputFoto");
        // const previewFoto = document.getElementById("previewFoto");

        // const departamentoSelect = document.getElementById("departamento-actual");
        // const provinciaSelect = document.getElementById("provincia-actual");
        // const distritoSelect = document.getElementById("distrito-actual");

        // const departamentoSelectDni = document.getElementById("departamento-dni");
        // const provinciaSelectDni = document.getElementById("provincia-dni");
        // const distritoSelectDni = document.getElementById("distrito-dni");

        // departamentoSelect.innerHTML = '<option value="">Seleccionar</option>';
        // provinciaSelect.innerHTML = '<option value="">Seleccionar</option>';
        // distritoSelect.innerHTML = '<option value="">Seleccionar</option>';

        // departamentoSelectDni.innerHTML = '<option value="">Seleccionar</option>';
        // provinciaSelectDni.innerHTML = '<option value="">Seleccionar</option>';
        // distritoSelectDni.innerHTML = '<option value="">Seleccionar</option>';

        
        // previewFoto.src = '';
        // previewFoto.classList.add("hidden");


    }


    const inputFoto = document.getElementById("inputFoto");
    const preview = document.getElementById("previewFoto");
    const placeholder = document.getElementById("placeholderFoto");
    const btnSubir = document.getElementById("btnSubirFoto");
    const btnEliminar = document.getElementById("btnEliminarFoto");

    const cursoSucamec = document.getElementById("curso_sucamec");
    const institucionContainer = document.getElementById("institucion_container");
    const institucionInput = document.getElementById("institucion_laboral");

    cursoSucamec.addEventListener("change", () => {
        if (cursoSucamec.value === "1") {
        institucionContainer.classList.remove("hidden");
        } else {
        institucionContainer.classList.add("hidden");
        institucionInput.value = "";
        }
    });


    // Abrir selector al dar click en Subir
    btnSubir.addEventListener("click", () => {
        inputFoto.click();
    });

    // Cuando selecciona una foto
    inputFoto.addEventListener("change", () => {
        const file = inputFoto.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove("hidden");
            placeholder.classList.add("hidden");
            btnEliminar.classList.remove("hidden"); // Mostrar "Eliminar"
            };
            reader.readAsDataURL(file);
        }
    });

    // Eliminar foto y restaurar placeholder
    btnEliminar.addEventListener("click", () => {
        inputFoto.value = ""; // limpia input
        preview.src = "";
        preview.classList.add("hidden");
        placeholder.classList.remove("hidden");
        btnEliminar.classList.add("hidden"); // ocultar botón eliminar
    });


    const departamentoSelect = document.getElementById("departamento-actual");
    const provinciaSelect = document.getElementById("provincia-actual");
    const distritoSelect = document.getElementById("distrito-actual");

    const departamentoSelectDni = document.getElementById("departamento-dni");
    const provinciaSelectDni = document.getElementById("provincia-dni");
    const distritoSelectDni = document.getElementById("distrito-dni");

    const API_BASE = `${VITE_URL_APP}/api/ubicacion`;

    // Cargar departamentos al inicio
    axios.get(`${API_BASE}/departamentos`)
        .then(response => {
            response.data.forEach(dep => {
                let option1 = new Option(dep.depa_descripcion, dep.depa_codigo);
                let option2 = new Option(dep.depa_descripcion, dep.depa_codigo);
                departamentoSelect.add(option1);
                departamentoSelectDni.add(option2);
            });
        })
        .catch(error => {
            console.error("Error cargando departamentos:", error);
        });

    departamentoSelect.addEventListener("change", function () {
        const departamentoId = this.value;
        provinciaSelect.innerHTML = '<option value="">Seleccionar</option>';
        distritoSelect.innerHTML = '<option value="">Seleccionar</option>';

        if (departamentoId) {
            axios.get(`${API_BASE}/provincias/${departamentoId}`)
                .then(response => {
                    response.data.forEach(prov => {
                        let option = new Option(prov.provi_descripcion, prov.provi_codigo);
                        provinciaSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error("Error cargando provincias:", error);
                });
        }
    });

    provinciaSelect.addEventListener("change", function () {
        const provinciaId = this.value;
        distritoSelect.innerHTML = '<option value="">Seleccionar</option>';

        if (provinciaId) {
            axios.get(`${API_BASE}/distritos/${provinciaId}`)
                .then(response => {
                    response.data.forEach(dist => {
                        let option = new Option(dist.dist_descripcion, dist.dist_codigo);
                        distritoSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error("Error cargando distritos:", error);
                });
        }
    });

    departamentoSelectDni.addEventListener("change", function () {
        const departamentoId = this.value;
        provinciaSelectDni.innerHTML = '<option value="">Seleccionar</option>';
        distritoSelectDni.innerHTML = '<option value="">Seleccionar</option>';

        if (departamentoId) {
            axios.get(`${API_BASE}/provincias/${departamentoId}`)
                .then(response => {
                    response.data.forEach(prov => {
                        let option = new Option(prov.provi_descripcion, prov.provi_codigo);
                        provinciaSelectDni.add(option);
                    });
                })
                .catch(error => {
                    console.error("Error cargando provincias:", error);
                });
        }
    });

    provinciaSelectDni.addEventListener("change", function () {
        const provinciaId = this.value;
        distritoSelectDni.innerHTML = '<option value="">Seleccionar</option>';

        if (provinciaId) {
            axios.get(`${API_BASE}/distritos/${provinciaId}`)
                .then(response => {
                    response.data.forEach(dist => {
                        let option = new Option(dist.dist_descripcion, dist.dist_codigo);
                        distritoSelectDni.add(option);
                    });
                })
                .catch(error => {
                    console.error("Error cargando distritos:", error);
                });
        }
    });

});


