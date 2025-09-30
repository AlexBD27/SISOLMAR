
import axios from 'axios';
import Swal from 'sweetalert2';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import 'tabulator-tables/dist/css/tabulator_simple.min.css';


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
            {title:"Cód.", field:"CODI_PERS", hozAlign:"center", width: '15%'},
            {title:"Personal", field:"personal", hozAlign:"left", width: '40%'},
            {title:"Nro Doc.", field:"nroDoc", hozAlign:"center", width: '20%'},
            {title: "Acciones", field: "acciones", width: '25%', hozAlign: "center", headerSort: false,
                formatter: function(cell, formatterParams, onRendered) {
                    var formBtn = `<button type="button" class="btn rounded-full form-btn bg-success/25 text-success hover:bg-success hover:text-white" >Formulario</button>`;

                    return formBtn;
                },

                cellClick: function(e, cell) {
                    if (e.target.classList.contains('form-btn')) {
                        abrirFormulario(); 
                    }
                }
            },
        ],
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
                { field: "CODI_PERS", type: 'like',  value: valor },
                { field: "personal", type: 'like',  value: valor },
                { field: "nroDoc", type: 'like', value: valor },
                { field: "sucursal", type: 'like', value: valor },
                { field: "col", type: 'like', value: valor },
            ]
        ]);

        // Guardar el valor para usarlo tras cambios de página
        tblPersonas._ultimoFiltro = valor;

        setTimeout(() => resaltarTexto(valor), 10);

    });


    document.addEventListener('click', function (event) {
        const modal = document.getElementById('formModal');
        const contenedor = modal.querySelector('.bg-white');
        
        if (!modal.classList.contains('hidden')) { 
            // Si el modal está abierto
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
        axios.get(`${ VITE_URL_APP }/api/get-personal`)
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

    window.abrirFormulario = function () {
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


    const inputFoto = document.getElementById("inputFoto");
    const preview = document.getElementById("previewFoto");
    const placeholder = document.getElementById("placeholderFoto");
    const btnSubir = document.getElementById("btnSubirFoto");
    const btnEliminar = document.getElementById("btnEliminarFoto");

    const cursoSucamec = document.getElementById("curso-sucamec");
    const institucionContainer = document.getElementById("institucion-container");
    const institucionInput = document.getElementById("institucion-laboral");

    cursoSucamec.addEventListener("change", () => {
        if (cursoSucamec.value === "si") {
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


    const departamentoSelect = document.getElementById("departamento");
    const provinciaSelect = document.getElementById("provincia");
    const distritoSelect = document.getElementById("distrito");

    // Cargar departamentos al inicio
    fetch("/ubicacion/departamentos")
        .then(res => res.json())
        .then(data => {
            data.forEach(dep => {
                let option = new Option(dep.depa_descripcion, dep.depa_codigo);
                departamentoSelect.add(option);
            });
        });

    // Cuando cambie el departamento
    departamentoSelect.addEventListener("change", function () {
        let departamentoId = this.value;
        provinciaSelect.innerHTML = '<option value="">Seleccionar</option>';
        distritoSelect.innerHTML = '<option value="">Seleccionar</option>';

        if (departamentoId) {
            fetch(`/ubicacion/provincias/${departamentoId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(prov => {
                        let option = new Option(prov.provi_descripcion, prov.provi_codigo);
                        provinciaSelect.add(option);
                    });
                });
        }
    });

    // Cuando cambie la provincia
    provinciaSelect.addEventListener("change", function () {
        let provinciaId = this.value;
        distritoSelect.innerHTML = '<option value="">Seleccionar</option>';

        if (provinciaId) {
            fetch(`/ubicacion/distritos/${provinciaId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(dist => {
                        let option = new Option(dist.dist_descripcion, dist.dist_codigo);
                        distritoSelect.add(option);
                    });
                });
        }
    });

});


