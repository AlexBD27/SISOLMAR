
import axios from 'axios';
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import 'tabulator-tables/dist/css/tabulator_simple.min.css';

let codClienteSeleccionado = null;

const langPagination = {
    "default":{
        "pagination":{
            "counter":{
                "showing": "Showing",
                "of": "of",
                "rows": "rows",
                "pages": "pages",
            }
        },
    }
};




//Tabla de Coincidencias
/*const tblPersonsCN = new Tabulator("#tblPersonsCN", {
    height: "100%",
    layout:"fitDataFill",
    responsiveLayout: "collapse",
    columns: [
        {title:"Código", field:"CODI_PERS", hozAlign:"center", width: '10%'},
        {title:"Personal", field:"personal", hozAlign:"left", width: '30%'},
        {title:"Nro Doc", field:"nroDoc", hozAlign:"center", width: '15%'},
        {title:"Sucursal", field:"sucursal", hozAlign:"center", width: '18%'},
    ],
});
*/

//Tabla de Clientes
const tblCliente = new Tabulator("#tblCliente", {  // Suponiendo que 'tblVisible' es la tabla visible
    height:"410px",
    layout:"fitData",
    responsiveLayout:"collapse",
    //pagination: true,
    paginationSize: 10,  // Cantidad de registros por página
    locale: "es",  // Configurar idioma a español
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
                "default": "Filtrar...", // Texto en filtros de encabezado
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
    //filterMode:"remote",
    rowHeader:{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
    columns: [
        { title: "N°", field: "", hozAlign: "center", width: "12%", formatter: "rownum" },
        { title: "Cliente", field: "razon_social", hozAlign: "left", width: '75%' },
        // { title: "RUC", field: "ruc", hozAlign: "center", width: '20%' },
        { title: "", field: "acciones", hozAlign: "center", width: '10%', headerSort: false,
            formatter: function(cell) {
                const cod = cell.getData().codigo;
                const razon_social = cell.getData().razon_social;
                const abreviatura = cell.getData().abreviatura;
                return `<input class="form-radio text-primary radCliente" type="radio" name="opCliente" id="radCliente${cod}"
                 value="${cod}" data-nombre="${razon_social}" data-abre="${abreviatura}">`;
            },
        },
    ],
});

//Elección del cliente


//Tabla de Cargos
const tblCargo = new Tabulator("#tblCargo", {  // Suponiendo que 'tblVisible' es la tabla visible
    height:"410px",
    layout:"fitData",
    responsiveLayout:"collapse",
    //pagination: true,
    paginationSize: 10,  // Cantidad de registros por página
    locale: "es",  // Configurar idioma a español
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
                "default": "Filtrar...", // Texto en filtros de encabezado
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
    //filterMode:"remote",
    rowHeader:{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
    columns: [
        { title: "N°", field: "", hozAlign: "center", width: "12%", formatter: "rownum" },
        { title: "Cargo", field: "nombre", hozAlign: "left", width: '75%' },
        { title: "", field: "acciones", hozAlign: "center", width: '10%', headerSort: false,
            formatter: function(cell) {
                const cod = cell.getData().codigo;

                const nombre = cell.getData().nombre;
                return `<input class="form-radio text-primary radCargo" type="radio" name="opCargo" id="radCargo${cod}" 
                value="${cod}" data-nombre="${nombre}">`;
            },
        },
    ],
});

document.querySelector("#tblCliente").classList.add("disabled-table");
document.querySelector("#tblCargo").classList.add("disabled-table");


document.getElementById('btnTodos').addEventListener('click', function(){
    document.querySelector("#tblCliente").classList.add("disabled-table");
    document.querySelector("#tblCargo").classList.add("disabled-table");
   // tblDocs.clearData();
    getPersonal('', '', 1);
});

document.getElementById('btnFiltros').addEventListener('click', function(){
    document.querySelector("#tblCliente").classList.remove("disabled-table");
    document.querySelector("#tblCargo").classList.remove("disabled-table");
    //tblDocs.clearData();
    getPersonal('---', '---', 0);
});


let valorCliente = '';
let valorCargo = '';
let nombreCliente = '';
let nombreCargo = '';
let abreCliente = '';
let abreCargo = '';




//Tabla de Folios
const tblDocs = new Tabulator("#tblDocs", {
    height: "100%",
    layout:"fitDataFill",
    responsiveLayout: "collapse",
    columns: [
        { title: "Folio", field: "documento", hozAlign: "left", width: '40%' },
        { title: "Emision", field: "fecha_emision", hozAlign: "center", width: '20%',
            formatter: function(cell, formatterParams){
                var emision = cell.getValue();
                if (emision === null){
                    return '-';
                }else{
                    return emision;
                }
            }
         },
        { title: "Caducidad", field: "fecha_caducidad", hozAlign: "center", width: '20%',
            formatter: function(cell, formatterParams) {
                var vigente = cell.getRow().getData().vigente;
                var fechaCaducidad = cell.getValue();
                if (vigente == 1) {
                    return `<span class="text-vigente-800 font-bold">${fechaCaducidad}</span>`
                } else if (vigente == 0) {
                    return `<span class="text-vencido-800 font-bold">${fechaCaducidad}</span>`
                } else {
                    return '-';
                }
            }
         },
        { title: "Acciones", field: "acciones", hozAlign: "center", width: '20%',
            formatter: function(cell, formatterParams, onRendered) {
                var chargeBtn = `<button type="button" class="btn rounded-full charge-btn bg-success/25 text-success hover:bg-success hover:text-white">Cargar</button>`;
                return chargeBtn;
            },
            cellClick: function(e, cell) {
                if (e.target.classList.contains('charge-btn')) {
                    const documento = cell.getRow().getData().documento;
                    const periodo = cell.getRow().getData().periodo;
                    const meses = cell.getRow().getData().meses;
                    const codFolio = cell.getRow().getData().codFolio;
                    const vencimiento = cell.getRow().getData().vencimiento;

                    document.querySelector('#modal-file h3.modal-title').textContent = `Documento: ${documento}`;
                    document.querySelector('#txtPeriodo').textContent = `${periodo}`;
                    document.getElementById('codFolio').value = codFolio;
                    document.getElementById('meses').value = meses;

                    // Verificar si vencimiento es 0 y ocultar el campo de caducidad
                    if (vencimiento == 0) {
                        document.getElementById('divCaducidad').classList.add('hidden');
                        document.getElementById('fecha_caducidad').removeAttribute('required'); 
                    } else {
                        document.getElementById('divCaducidad').classList.remove('hidden');
                        document.getElementById('fecha_caducidad').setAttribute('required', 'required');
                    };

                    document.getElementById('btn-modal-docs').click();
                }
            }
        },
    ],
});

cargarClientes();

function cargarCargos(codCliente){
    axios.get(`${ VITE_URL_APP }/api/get-cargos`, {
        params: {
            cliente: codCliente,
        }
    })
    .then(response => {
        console.log('cargos', response.data);
        const datosTabla = response.data;
        tblCargo.setData(datosTabla);
        
    })
    .catch(error => {
        console.error("Hubo un error:", error);
    });
}

function cargarClientes(){
    axios.get(`${ VITE_URL_APP }/api/get-clientes-legajos`)
    .then(response => {
        console.log(response);
        console.log('clientes', response.data);
        const datosTabla = response.data;
        tblCliente.setData(datosTabla);
        
    })
    .catch(error => {
        console.error("Hubo un error:", error);
    });
}

// Función para actualizar la tabla con el filtro PRINCIPAL o AUXILIAR
function filterTableByTipoFolio() {
    const tipoFolioSeleccionado = document.querySelector('input[name="tipo_folio"]:checked').value;
    tblDocs.setFilter("tipo_folio", "=", tipoFolioSeleccionado);
}

// Escuchar los cambios en los radio buttons
document.querySelectorAll('input[name="tipo_folio"]').forEach(radio => {
    radio.addEventListener('change', filterTableByTipoFolio);
});

//Tabla de Documentos LEGAJOS
const tblLegajos = new Tabulator("#tblDocsLegajo", {
    height: "100%",
    layout: "fitDataFill",
    responsiveLayout: "collapse",
    columns: [
        { title: "Folio", field: "documento", hozAlign: "left", width: '40%' },
        { title: "Emision", field: "fecha_emision", hozAlign: "center", width: '20%' ,
            formatter: function(cell, formatterParams){
                var emision = cell.getValue();
                if (emision === null){
                    return '-';
                }else{
                    return emision;
                }
            }
         },
        { title: "Caducidad", field: "fecha_caducidad", hozAlign: "center", width: '20%',
            formatter: function(cell, formatterParams) {
                var vigente = cell.getRow().getData().vigente;
                var fechaCaducidad = cell.getValue();
                if (vigente == 1) {
                    return `<span class="text-vigente-800 font-bold">${fechaCaducidad}</span>`
                } else if (vigente == 0) {
                    return `<span class="text-vencido-800 font-bold">${fechaCaducidad}</span>`
                } else {
                    return '-';
                }
            }
         },
        { title: "Acciones", field: "accionesy", hozAlign: "center", width: '20%',
            formatter: function(cell, formatterParams, onRendered) {
                var filePath = cell.getRow().getData().ruta_archivo;
                var url = '/storage/' + filePath; // Concatenar el link a la ruta del archivo
                if(filePath){
                    var viewBtn = `<a href="${url}" target="_blank" class="btn rounded-full view-btn bg-info/25 text-info hover:bg-info hover:text-white"><i class="fa fa-eye view-btn"></i></a>`;
                }else{
                    var viewBtn = `<a href="${url}" target="_blank" class="pointer-events-none btn rounded-full view-btn bg-warning/25 text-warning-opa bg-gray-200 hover:bg-gray-200"><i class="fa fa-eye"></i></a>`;
                }
                //var chargeBtn = `<button type="button" class="btn rounded-full charge-btn bg-success/25 text-success hover:bg-success hover:text-white"><i class="fa-solid fa-upload charge-btn"></i></button>`;
                return /*chargeBtn+' '+*/viewBtn;
            },
            cellClick: function(e, cell) {
                if (e.target.classList.contains('charge-btn')) {
                    const documento = cell.getRow().getData().documento;
                    const periodo = cell.getRow().getData().periodo;
                    const meses = cell.getRow().getData().meses;
                    const codFolio = cell.getRow().getData().codFolio;
                    const vencimiento = cell.getRow().getData().vencimiento;

                    document.querySelector('#modal-file h3.modal-title').textContent = `Documento: ${documento}`;
                    document.querySelector('#txtPeriodo').textContent = `${periodo}`;
                    document.getElementById('codFolio').value = codFolio;
                    document.getElementById('meses').value = meses;

                    // Verificar si vencimiento es 0 y ocultar el campo de caducidad
                    if (vencimiento == 0) {
                        document.getElementById('divCaducidad').classList.add('hidden');
                        document.getElementById('fecha_caducidad').removeAttribute('required'); 
                    } else {
                        document.getElementById('divCaducidad').classList.remove('hidden');
                        document.getElementById('fecha_caducidad').setAttribute('required', 'required');
                    };

                    limpiarModal();
                    document.getElementById('btn-modal-docs').click();
                }
            },
            rowFormatter: function(row) {
                row.getElement().classList.add("hover:bg-indigo-500");  // Cambia "indigo-500" al color que desees
            }
        },
    ]
});

//Tabla de Personas
const tblPersonas = new Tabulator("#tblPersonas", {
    height:"410px",
    layout:"fitData",
    responsiveLayout:"collapse",
    pagination: false,
    paginationSize: 10,
    rowHeader:{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
    columns:[
        {title:"Código", field:"CODI_PERS", hozAlign:"center", width: '10%'},
        {title:"Personal", field:"personal", hozAlign:"left", width: '20%'},
        {title:"Nro Doc", field:"nroDoc", hozAlign:"center", width: '10%'},
        {title:"Sucursal", field:"sucursal", hozAlign:"center", width: '10%'},
        /*{title:"Cliente", field:"cliente", hozAlign:"center", width: '15%'},
        {title:"Cargo", field:"cargo", hozAlign:"center", width: '20%'},
        {title:"Acciones", field: "acciones", width: '12%', hozAlign: "center", 
            formatter: function(cell, formatterParams, onRendered) {
                var docsBtn = `<button type="button" class="btn rounded-full docs-btn bg-success/25 text-success hover:bg-success hover:text-white" title="Ver folios">
                <i class="fa-solid fa-book docs-btn"></i></button>`;

                return docsBtn;
            },
            cellClick: function(e, cell) {
                if (e.target.classList.contains('docs-btn')) {

                    const rowData = cell.getRow().getData();

                    var cliente = rowData.codCliente;
                    var cargo = rowData.codCargo;
                    var codi_pers = rowData.CODI_PERS;

                    getLegajos (cliente,cargo,codi_pers);

                    document.getElementById('dataDocsLeg').classList.remove('hidden');
                    document.getElementById('divCoincidencias').classList.add('hidden');
                }
                //updateCardTitle(persona);
            }
        },*/
    ],
});




document.addEventListener('change', function (e) {
    if (e.target.classList.contains('radCliente')) {
        codClienteSeleccionado = e.target.value;
        cargarCargos(codClienteSeleccionado);
    }

    if (e.target.classList.contains('radCargo')) {
        const codCargo = e.target.value;
        if(codClienteSeleccionado){
            getCoincidencias(codClienteSeleccionado,codCargo);
        }else{
            alert('Primero selecciona un cliente.');
        }
    }
});


// document.addEventListener("change", function (event) {
//     if (event.target.matches(".radCliente") || event.target.matches(".radCargo")) {
//         const esCliente = event.target.matches(".radCliente");

//         if (esCliente) {
//             valorCliente = event.target.value;
//             nombreCliente = event.target.dataset.nombre;
//             abreCliente = event.target.dataset.abre;
//         } else {
//             valorCargo = event.target.value;
//             nombreCargo = event.target.dataset.nombre;
         
//         }

//         if (valorCliente && valorCargo) {
//             //cargarFolios(valorCliente, valorCargo);
//            getPersonal(abreCliente, nombreCargo);
//            //getCoincidencias(valorCliente,valorCargo);

           
           
//         }

       
//     }
// });



//Tabla de Legajos
document.addEventListener('DOMContentLoaded', function() {

     // Evento de cambio de fecha de emisión
     document.getElementById('fecha_emision').addEventListener('change', function () {
        const fechaEmision = document.getElementById('fecha_emision').value;

        if (fechaEmision) {
            // Calculamos la fecha de caducidad
            const fechaCalculada = calcularFechaCaducidad(fechaEmision);
            document.getElementById('fecha_caducidad').value = fechaCalculada; // Llenamos la fecha de caducidad
        }
    });
});

// Función para asignar nombre a la card de documentos
function updateCardTitle(nombrePersona) {
    const cardTitle = document.querySelector('.nombrePersDocs');
    cardTitle.textContent = `Folios de ${nombrePersona}`;
    const cardTitleLeg = document.querySelector('.nombrePersLeg');
    cardTitleLeg.textContent = `Legajos para ${nombrePersona}`;
}
// hola aqui
document.getElementById("buscar").addEventListener("keyup", function () {
    let valor = this.value.toLowerCase().trim();
    tblPersonas.setFilter([
        [
            { field: "CODI_PERS", type: 'like',  value: valor },
            { field: "personal", type: 'like',  value: valor },
            { field: "nroDoc", type: 'like', value: valor },
            { field: "sucursal", type: 'like', value: valor },
            { field: "cliente", type: 'like', value: valor },
        ]
    ]);
});
document.getElementById("buscarCliente").addEventListener("keyup", function () {
    let valor = this.value.toLowerCase().trim();
    tblCliente.setFilter([
        [
            { field: "razon_social", type: 'like',  value: valor },
        ]
    ]);
});
document.getElementById("buscarCargo").addEventListener("keyup", function () {
    let valor = this.value.toLowerCase().trim();
    tblCargo.setFilter([
        [
            { field: "nombre", type: 'like',  value: valor },
        ]
    ]);
});

// Función para calcular la fecha de caducidad
function calcularFechaCaducidad(fechaEmision) {
    const periodoVigencia = parseInt(document.getElementById('meses').value);
    const fecha = new Date(fechaEmision);
    fecha.setMonth(fecha.getMonth() + periodoVigencia);
    const anio = fecha.getFullYear();
    const mes = ('0' + (fecha.getMonth() + 1)).slice(-2);
    const dia = ('0' + fecha.getDate()).slice(-2);
    return `${anio}-${mes}-${dia}`;
}

//Función para limpia los campos del modal
function limpiarModal(){
    document.getElementById('fecha_emision').value="";
    documento.getElementById('fecha_caducidad').value="";
}

//========================================== DATA CON AXIOS ==========================================//
// Función para obtener los folios por persona
function getDocsObligatorios(codigo){
    axios.get(`${ VITE_URL_APP }/api/get-documentos/${codigo}`)
    .then(response => {
        tblDocs.setData(response.data);
        // Aplicar filtro "PRINCIPAL" por defecto después de cargar los datos
        filterTableByTipoFolio();
    })
    .catch(error => {
        console.error("Error al obtener los datos:", error);
    });
}
// Función para obtener el listados de personas
//hola aqui
// function getPersonal(cliente, cargo, tipo = 1){
//     axios.get('${ VITE_URL_APP }/api/get-personal-legajos')
//     .then(response => {
//         const datosTabla = response.data;
//         console.log(datosTabla, tipo);
      
//         if(tipo == 0){
//             tblPersonas.setData(datosTabla).then(() => {
//                 tblPersonas.setFilter(function(data) {
//                     return (
//                         data.cliente == cliente &&
//                         data.cargo == cargo
//                     );
//                 });
//             });
//         }
        
//         if(tipo == 1){
          
//             tblPersonas.replaceData(datosTabla).then(() => {
//                 tblPersonas.clearFilter(); //quitar os filtros existentes

//             });
           
//         }
        
//     })
//     .catch(error => {
//         console.error("Hubo un error:", error);
//     });
// }
// Función para obtener los legajos
function getLegajos(cliente, cargo, codigoPer) {
    //alert(codigoPer);
    axios.get(`${ VITE_URL_APP }/api/get-legajos`, {
        params: {
            cliente: cliente,
            cargo: cargo,
            codigo: codigoPer
        }
    })
    .then(function (response) {
        tblLegajos.setData(response.data);
        //document.getElementById('tblDocsLegajo').classList.remove('hidden');
        
        console.log(response.data);
    })
    .catch(function (error) {
        console.error("Error al obtener los legajos:", error);
    });
};

// Función para obtener las coincidencias
function getCoincidencias(cliente, cargo) {
    axios.get(`${ VITE_URL_APP }/api/get-coincidencias`, {
        params: {
            cliente: cliente,

            cargo: cargo
        }
    })
    .then(response => {
        console.log(response.data);
        console.log('aquiii');
        //tblPersonsCN.setData(response.data);
        tblPersonas.setData(response.data);
    })
    .catch(error => {
        console.error("Hubo un error:", error);
    });
};

//================================ GUARDAR LOS DATOS POR AXIOS ================================//
document.getElementById('formFolioPersonal').addEventListener('submit', function(event) {
    event.preventDefault();
    var fechaEmision = document.getElementById('fecha_emision').value;
    var fechaCaducidad = document.getElementById('fecha_caducidad').value;
    var codigoPer = document.getElementById('codPersonal').value;
    var codFolio = document.getElementById('codFolio').value;

    if (fechaEmision /*&& fechaCaducidad*/) {
        // Enviar los datos al servidor usando Axios
        axios.post(`${ VITE_URL_APP }/api/save_folio_persona`, {
            fecha_emision: fechaEmision,
            fecha_caducidad: fechaCaducidad,
            codFolio: codFolio,
            codPersonal: codigoPer,
        })
        .then(function(response) {
            //console.log('Datos guardados:', response.data);
            document.getElementById('btn-modal-docs-close').click();
            getDocsObligatorios(codigoPer);
            document.getElementById('btnTraerFolios').click();
            limpiarModal();
        })
        .catch(function(error) {
            console.error('Error al guardar las fechas:', error);
        });
    }
});