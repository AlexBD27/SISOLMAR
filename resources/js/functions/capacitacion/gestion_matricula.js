import Swal from "sweetalert2";
import axios from "axios";
import DataTable from "vanilla-datatables";

let cursosData = [];

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
        const cursoId = e.target.dataset.cursoId

        // cargar lista de personal antes de mostrar
        await cargarPersonal(cursoId)

        // abrir modal manualmente
        HSOverlay.open('#modal-registro')
    })
    })



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
                data-curso-id="${curso.codigo}">Matricular</button>
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
  // aquí deberías hacer un fetch al backend
  // const res = await fetch(`/api/cursos/${cursoId}/personal`)
  // const personal = await res.json()

  // datos de ejemplo
  const personal = [
    { id: 1, nombre: "Juan Pérez", matriculado: true },
    { id: 2, nombre: "Ana Torres", matriculado: false },
    { id: 3, nombre: "Luis García", matriculado: false }
  ]

  const tbody = document.querySelector("#tablaPersonal")
  tbody.innerHTML = ""

  personal.forEach(p => {
    const tr = document.createElement("tr")
    tr.innerHTML = `
      <td class="px-4 py-2">${p.nombre}</td>
      <td class="px-4 py-2 text-center">
        <input type="checkbox" value="${p.id}" ${p.matriculado ? "checked disabled" : ""}>
      </td>
    `
    tbody.appendChild(tr)
  })

  // filtro buscador
  const inputBuscar = document.getElementById("buscarPersonal")
  inputBuscar.value = ""
  inputBuscar.onkeyup = () => {
    const filtro = inputBuscar.value.toLowerCase()
    tbody.querySelectorAll("tr").forEach(row => {
      const nombre = row.querySelector("td").textContent.toLowerCase()
      row.style.display = nombre.includes(filtro) ? "" : "none"
    })
  }

  // guardar matrícula
  document.getElementById("btnGuardarMatricula").onclick = () => {
    const seleccionados = [...tbody.querySelectorAll("input[type=checkbox]:checked:not([disabled])")]
      .map(chk => chk.value)
    console.log("Matricular en curso " + cursoId + " a:", seleccionados)

    // aquí haces tu POST al backend
    // fetch('/api/matricular', { method:'POST', body: JSON.stringify({ cursoId, seleccionados }) })
  }
}
