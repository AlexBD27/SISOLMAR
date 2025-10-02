import Swal from "sweetalert2";
import axios from "axios";
import DataTable from "vanilla-datatables";
import {TabulatorFull as Tabulator} from 'tabulator-tables';
import 'tabulator-tables/dist/css/tabulator_simple.min.css';

let cursosData = [];
let tblPersonalMatricula = null;
let cursoActual = null;

document.addEventListener('DOMContentLoaded', async () => {
  
  await listarTipoCurso("slcFiltroTipoCurso", true)
  await listarAreas("slcFiltroArea", true)
  await listarCursos()

  new DataTable(document.getElementById('tblCursos'), {
    perPage: 10,
    searchable: true,
    sortable: true,
    labels: {
      placeholder: "Buscar...",
      perPage: "{select} por página",
      noRows: "No hay datos disponibles",
      info: "Mostrando {start} a {end} de {rows}"
    }
  });


  document.querySelectorAll(".btn-matricula").forEach(btn => {
    btn.addEventListener("click", async (e) => {
        const cursoId = e.target.dataset.cursoId;
        const cursoNombre = e.target.dataset.cursoNombre || "Curso seleccionado";

        console.log("CURSO SELECCIONADO: " + cursoId);
        
        cursoActual = cursoId;
        
        // Mostrar nombre del curso
        document.getElementById("nombreCurso").textContent = cursoNombre;
        
        // Cargar lista de personal antes de mostrar
        await cargarPersonal(cursoId);
        
        // Abrir modal manualmente
        HSOverlay.open('#modal-registro');
    });
});

    // document.querySelectorAll(".btn-matricula").forEach(btn => {
    //     btn.addEventListener("click", async (e) => {
    //         const cursoId = e.target.dataset.cursoId

    //         // cargar lista de personal antes de mostrar
    //         await cargarPersonal(cursoId)

    //         // abrir modal manualmente
    //         HSOverlay.open('#modal-registro')
    //     })
    // })



    // Guardar matrícula
document.getElementById("btnGuardarMatricula").addEventListener("click", async function() {
    const seleccionados = [...document.querySelectorAll("#tblPersonalMatricula input[type=checkbox]:checked")]
        .map(chk => chk.dataset.id);
    
    if (seleccionados.length === 0) {
        alert("Por favor, seleccione al menos una persona para matricular");
        return;
    }
    
    console.log("Matricular en curso " + cursoActual + " a:", seleccionados);
    
    // Deshabilitar botón mientras se procesa
    this.disabled = true;
    this.innerHTML = '<i class="i-tabler-loader animate-spin mr-2"></i> Procesando matrícula...';
    
    try {
        // const response = await axios.post(`${VITE_URL_APP}/api/matricular`, {
        //     cursoId: cursoActual,
        //     personalIds: seleccionados
        // });
        
        // if (response.status === 200) {
        //     alert(`✓ ${seleccionados.length} persona(s) matriculada(s) exitosamente.\nSe enviaron ${seleccionados.length} correos de notificación.`);
        //     HSOverlay.close('#modal-registro');
        //     // Recargar tabla si es necesario
        // }
        
        //Simulación
        setTimeout(() => {
            alert(`${seleccionados.length} persona(s) matriculada(s) exitosamente`);
            HSOverlay.close('#modal-registro');
            this.disabled = false;
            this.innerHTML = '<i class="i-tabler-check mr-2"></i> Matricular Seleccionados';
        }, 1000);
        
    } catch (error) {
        console.error("Error al matricular:", error);
        alert("Ocurrió un error al matricular el personal");
        this.disabled = false;
        this.innerHTML = '<i class="i-tabler-check mr-2"></i> Matricular Seleccionados';
    }
});


})


window.listarCursos = async function(habilitado = 1, area = '', tipoCurso = '') {
  console.log('listarCursos called', { habilitado, area, tipoCurso });
  try {
    const res = await axios.get(`${VITE_URL_APP}/api/get-cursos/${habilitado}`, {
      params: { area, tipoCurso }
    });
    cursosData = res.data;
    renderTablaCursos(cursosData);
  } catch (err) {
    console.error("Error al obtener cursos", err);
    Swal.fire("Error", "No se pudieron cargar los cursos", "error");
  }
}



async function listarTipoCurso(selectId, esFiltro = false) {
  try {
    const res = await axios.get(`${VITE_URL_APP}/api/get-capacitacion-tipo-cursos`);
    const tipoCursosData = res.data;
    const select = document.getElementById(selectId);

    select.innerHTML = esFiltro 
      ? '<option value="">-- Todos --</option>' 
      : '<option value="">-- Seleccione --</option>';

    tipoCursosData.forEach(curso => {
      const option = document.createElement("option");
      option.value = curso.codigo;
      option.textContent = curso.descripcion;
      select.appendChild(option);
    });
  } catch (err) {
    console.error("Error al obtener tipos de cursos", err);
    Swal.fire("Error", "No se pudieron cargar los tipos de cursos", "error");
  }
}


async function listarAreas(selectId, esFiltro = false) {
  try {
    const res = await axios.get(`${VITE_URL_APP}/api/get-capacitacion-areas`);
    const areasData = Array.isArray(res.data) ? res.data : [];

    const select = document.getElementById(selectId);

    select.innerHTML = esFiltro
      ? '<option value="">-- Todas --</option>'
      : '<option value="">-- Seleccione --</option>';

    if (areasData.length === 0) {
      console.warn("No hay áreas disponibles");
    } else {
      areasData.forEach(area => {
        const option = document.createElement("option");
        option.value = area.codigo;
        option.textContent = area.descripcion;
        select.appendChild(option);
      });
    }
  } catch (err) {
    console.error("Error al obtener las áreas", err);
    Swal.fire("Error", "No se pudieron cargar las áreas", "error");
  }
}

async function obtenerCursoXId(id) {
  try {
    const res = await axios.get(`${VITE_URL_APP}/api/get-curso-id/${id}`)
    return res.data;
  } catch (err) {
    console.error("Error al obtener cursos", err)
    Swal.fire("Error", "No se pudieron cargar los cursos", "error");
    return false;
  }
}

function renderTablaCursos(data) {
  const tbody = document.querySelector("#tblCursos tbody")
  if (!tbody) return

  tbody.innerHTML = ""

  if(data.length > 0){
    data.forEach((curso, index) => {
        const tr = document.createElement("tr")
        tr.style.backgroundColor = curso.habilitado == '1' ? "" : '#fff1f1';

        //SE OCULTO EL CAMPO DE PERIODO
        tr.innerHTML = `
        <td>${index + 1}</td>
         <td>${curso.codigoCurso}</td>
        <td>${curso.nombre}</td>
        <td>
          ${curso.habilitado == '1'
            ? `<button type="button" 
                class="btn-matricula btn rounded-full form-btn bg-success/25 text-success hover:bg-success hover:text-white"
                data-curso-id="${curso.codigo}" data-curso-nombre="${curso.nombre}">Matricular</button>
                `
            : `<span class="text-gray-400 italic">No disponible</span>`
          }
        </td>
        `
        tbody.appendChild(tr)
    })
  }else{

    const tr = document.createElement("tr")
    tr.innerHTML = `
      <td colspan="4" class="text-center text-gray-500 py-4">
        Sin registros
      </td>
    `
    tbody.appendChild(tr)
    return
  }
}


async function cargarPersonal(cursoId) {
    
    //Ruta con axios
    const response = await axios.get(`${VITE_URL_APP}/api/get-personal`);
    const personal = response.data;

    // Inicializar o actualizar Tabulator
    if (tblPersonalMatricula) {
        tblPersonalMatricula.destroy();
    }

    tblPersonalMatricula = new Tabulator("#tblPersonalMatricula", {
        data: personal,
        height: "100%",
        layout: "fitColumns",
        responsiveLayout: "collapse",
        pagination: true,
        paginationSize: 10,
        paginationSizeSelector: [5, 10, 20, 50],
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
                    "page_size": "Registros por página"
                }
            }
        },
        rowHeader: {
            formatter: "responsiveCollapse",
            width: 30,
            minWidth: 30,
            hozAlign: "center",
            resizable: false,
            headerSort: false
        },
        columns: [
            {
                title: "Seleccionar",
                field: "seleccionar",
                width: 100,
                hozAlign: "center",
                headerHozAlign: "center",
                formatter: function(cell) {
                    const data = cell.getRow().getData();

                    if (data.matriculado) {
                        return `<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded">
                                    <i class="i-tabler-check mr-1"></i> Matriculado
                                </span>`;
                    }
                    return `<input type="checkbox" class="form-checkbox h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500" 
                                data-id="${data.CODI_PERS}">`;
                },
                headerSort: false
            },
            {
                title: "Nombre Completo",
                field: "personal",
                minWidth: 200,
                responsive: 0,
                formatter: function(cell) {
                    const data = cell.getRow().getData();
                    return `<div class="font-medium text-gray-900">${data.personal}</div>`;
                }
            },
            {
                title: "DNI",
                field: "nroDoc",
                width: 120,
                responsive: 1,
                hozAlign: "center",
                headerHozAlign: "center"
            },
            {
                title: "Sucursal",
                field: "sucursal",
                width: 150,
                responsive: 2,
                formatter: function(cell) {
                    return `<span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded">
                                ${cell.getValue()}
                            </span>`;
                }
            },
            {
                title: "Estado",
                field: "matriculado",
                width: 120,
                responsive: 3,
                hozAlign: "center",
                headerHozAlign: "center",
                formatter: function(cell) {
                    return cell.getValue() 
                        ? '<span class="text-green-600 font-medium">✓ Activo</span>'
                        : '<span class="text-gray-500">Disponible</span>';
                }
            }
        ]
    });


    // ESPERAR A QUE TABULATOR TERMINE DE CARGAR
    tblPersonalMatricula.on("tableBuilt", function() {
        
        // Actualizar contadores iniciales
        actualizarContadores(personal);
        
        // Configurar el buscador DESPUÉS de que la tabla esté lista
        configurarBuscador();
        
        // Configurar listeners de checkboxes
        configurarCheckboxes();
    });

    // actualizarContadores(personal);

    // const inputBuscar = document.getElementById("buscarPersonal");
    // inputBuscar.value = "";
    // inputBuscar.addEventListener("keyup", function() {
    //     const filtro = this.value;
    //     tblPersonalMatricula.setFilter([
    //         {field: "personal", type: "like", value: filtro},
    //         {field: "nroDoc", type: "like", value: filtro},
    //         {field: "sucursal", type: "like", value: filtro}
    //     ], "or");
    // });

    // setTimeout(() => {
    //     document.querySelectorAll("#tblPersonalMatricula input[type=checkbox]").forEach(chk => {
    //         chk.addEventListener("change", () => {
    //             actualizarContadorSeleccionados();
    //         });
    //     });
    // }, 100);
}


function configurarBuscador() {
    const inputBuscar = document.getElementById("buscarPersonal");
    
    if (!inputBuscar) {
        console.error("No se encontró el input de búsqueda");
        return;
    }
    
    // Limpiar el input
    inputBuscar.value = "";
    
    // REMOVER event listeners anteriores
    inputBuscar.replaceWith(inputBuscar.cloneNode(true));
    const nuevoInput = document.getElementById("buscarPersonal");
    
    // Agregar el event listener
    nuevoInput.addEventListener("keyup", function() {
        const filtro = this.value.trim();
        
        console.log("Buscando:", filtro);
        
        if (filtro === "") {
            tblPersonalMatricula.clearFilter();
        } else {
            // Usar filterMode para ignorar mayúsculas/minúsculas
            tblPersonalMatricula.setFilter([
                [
                    {field: "personal", type: "like", value: filtro},
                    {field: "nroDoc", type: "like", value: filtro},
                    {field: "sucursal", type: "like", value: filtro}
                ]
            ]);
        }
    });
}

// Función separada para configurar checkboxes
function configurarCheckboxes() {
    setTimeout(() => {
        document.querySelectorAll("#tblPersonalMatricula input[type=checkbox]").forEach(chk => {
            chk.addEventListener("change", () => {
                actualizarContadorSeleccionados();
            });
        });
    }, 100);
}


function actualizarContadores(personal) {
    const matriculados = personal.filter(p => p.matriculado).length;
    const disponibles = personal.filter(p => !p.matriculado).length;
    
    document.getElementById("countMatriculados").textContent = matriculados;
    document.getElementById("countDisponibles").textContent = disponibles;
    document.getElementById("countSeleccionados").textContent = "0";
}

function actualizarContadorSeleccionados() {
    const seleccionados = document.querySelectorAll("#tblPersonalMatricula input[type=checkbox]:checked").length;
    document.getElementById("countSeleccionados").textContent = seleccionados;
    
    const mensaje = seleccionados > 0 
        ? `${seleccionados} persona${seleccionados > 1 ? 's' : ''} seleccionada${seleccionados > 1 ? 's' : ''} para matricular`
        : "Seleccione el personal a matricular";
    
    document.getElementById("mensajeSeleccion").textContent = mensaje;
}


