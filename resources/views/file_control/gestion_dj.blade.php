@extends('layouts.vertical', ['title' => 'Gestión DJ'])

@section('css')

@endsection

@section('content')

@include("layouts.shared/page-title", ["subtitle" => "Recursos Humanos", "title" => "File Control"])


<style>
    .fila-formulario {
    margin-bottom: 1.5rem; /* 32px */
    }

</style>


<div class="grid lg:grid-cols-1 gap-6 mt-8">
    <div class="card overflow-hidden">
        <div class="card-header">
            <h4 class="card-title">Listado de Personal</h4>
        </div>

        <div class="w-full px-5 py-2 mt-3 flex justify-between items-center">
            <input type="text" id="buscarPersonal" placeholder="Buscar..." class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm uppercase" autocomplete="off" />
        </div>

        <div class="w-full px-5 py-2 mt-3">
            <div id="tblPersonas" class="w-full mt-5"></div>
        </div>
    </div>
</div>


<div id="divCoincidencias" class="grid lg:grid-cols-1 gap-6 mt-8 hidden">
    <div class="card overflow-hidden">
        <div class="card-header">
            <h4 class="card-title">Listado de COINCIDENCIAS</h4>
        </div>
        <div class="w-full px-5 py-2 mt-3">
            <input type="text" id="buscar" placeholder="Buscar..."
                class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm" />
            <div id="tblPersonasCN" class="w-full mt-8"></div>
        </div>
    </div>
</div>


<!-- Modal del Formulario -->
<div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto flex py-10">
  <!-- Contenedor -->
  <div class="bg-white rounded-lg shadow-lg max-w-2xl w-auto mx-auto my-10 max-h-[70vh] overflow-y-auto">
    <form id="formDatos" class="p-6 space-y-8">
      
      <!-- Mis Datos Personales -->
        <div class="border rounded-lg shadow mb-2">
                <div class="bg-primary text-white px-4 py-2 font-semibold rounded-t-lg"> MIS DATOS PERSONALES</div>
        <div class="p-6 space-y-10">

            <!-- Nombre + Foto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 fila-formulario items-center">
                <div class="lg:col-span-1.5">
                    <label for="nombres-apellidos" class="text-sm font-medium inline-block mb-2">
                        Nombres y Apellidos
                    </label>
                    <input type="text" id="nombres-apellidos" class="form-input w-full" placeholder="Ingrese nombres y apellidos completos" required>

                    <a href="https://eldni.com/pe/buscar-datos-por-dni" target="_blank" 
                        class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Consultar DNI
                    </a>
                </div>

                <div class="flex flex-col items-center">
                    <!-- Contenedor del cuadro -->
                    <div class="flex items-center justify-center rounded-lg w-32 h-32 relative">
                        <!-- Placeholder (ícono + texto) con borde punteado --> 
                        <div class="text-center w-full h-full flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg" id="placeholderFoto">
                            <i class="fa-solid fa-camera text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">FOTO</p>
                        </div>

                        <!-- Imagen previsualización -->
                        <img id="previewFoto" class="hidden w-full h-full object-cover rounded-lg" />

                        <!-- Input oculto -->
                        <input type="file" id="inputFoto" accept="image/*" class="hidden" />
                    </div>

                    <!-- Botones -->
                    <div class="mt-2 flex gap-2">
                        <button type="button" id="btnSubirFoto"
                        class="btn border rounded-md bg-gray-100 text-gray-700 px-3 py-1 hover:bg-gray-200">
                        Subir Foto
                        </button>
                        <button type="button" id="btnEliminarFoto"
                        class="hidden btn border rounded-md bg-red-100 text-red-700 px-3 py-1 hover:bg-red-200">
                        Eliminar
                        </button>
                    </div>
                </div>

                
            </div>

            <!-- DNI / Caduca / Estado civil / Sexo -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 fila-formulario">
                <div>
                <label for="dni" class="text-sm font-medium inline-block mb-2">DNI</label>
                <input type="text" id="dni" maxlength="8" required class="form-input w-full" placeholder="12345678">
                </div>
                <div>
                <label for="caduca" class="text-sm font-medium inline-block mb-2">Caduca</label>
                <input type="date" id="caduca" class="form-input w-full">
                </div>
                <div>
                <label for="estado-civil" class="text-sm font-medium inline-block mb-2">Estado Civil</label>
                <select id="estado-civil" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Soltero(a)</option>
                    <option>Casado(a)</option>
                    <option>Divorciado(a)</option>
                    <option>Viudo(a)</option>
                    <option>Conviviente</option>
                </select>
                </div>
                <div>
                <label for="sexo" class="text-sm font-medium inline-block mb-2">Sexo</label>
                <select id="sexo" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Masculino</option>
                    <option>Femenino</option>
                </select>
                </div>
            </div>

            <!-- Fecha Nacimiento / Ciudad / Sabe nadar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
                <div>
                <label for="fecha-nacimiento" class="text-sm font-medium inline-block mb-2">Fecha Nacimiento</label>
                <input type="date" id="fecha-nacimiento" required class="form-input w-full">
                </div>
                <div>
                <label for="ciudad" class="text-sm font-medium inline-block mb-2">Ciudad</label>
                <input type="text" id="ciudad" class="form-input w-full" placeholder="Ciudad de nacimiento">
                </div>
                <div>
                <label for="sabe-nadar" class="text-sm font-medium inline-block mb-2">Sabe nadar</label>
                <select id="sabe-nadar" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Sí</option>
                    <option>No</option>
                </select>
                </div>
            </div>

            <!-- Tipo Sangre / Peso / Talla / Celular -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 fila-formulario">
                <div>
                <label for="tipo-sangre" class="text-sm font-medium inline-block mb-2">Tipo Sangre</label>
                <select id="tipo-sangre" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>O+</option><option>O-</option>
                    <option>A+</option><option>A-</option>
                    <option>B+</option><option>B-</option>
                    <option>AB+</option><option>AB-</option>
                </select>
                </div>
                <div>
                <label for="peso" class="text-sm font-medium inline-block mb-2">Peso (Kg.)</label>
                <input type="number" id="peso" step="0.1" class="form-input w-full" placeholder="70">
                </div>
                <div>
                <label for="talla" class="text-sm font-medium inline-block mb-2">Talla (Mt.)</label>
                <input type="number" id="talla" step="0.01" class="form-input w-full" placeholder="1.75">
                </div>
                <div>
                <label for="celular" class="text-sm font-medium inline-block mb-2 text-red-800">Celular</label>
                <input type="text" id="celular" maxlength="9" required class="form-input w-full border-red-800 focus:ring-red-800 focus:border-red-800 text-red-800 placeholder-red-400" placeholder="999 999 999">
                </div>
            </div>

            <!-- Correo / WhatsApp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 fila-formulario">
                <div>
                <label for="correo" class="text-sm font-medium inline-block mb-2 text-red-800">Correo electrónico</label>
                <input type="email" id="correo" required class="form-input w-full border-red-800 focus:ring-red-800 focus:border-red-800 text-red-800 placeholder-red-400" placeholder="ejemplo@correo.com">
                </div>
                <div>
                <label for="whatsapp" class="text-sm font-medium inline-block mb-2 text-red-800">WhatsApp</label>
                <input type="text" id="whatsapp" maxlength="9" class="form-input w-full border-red-800 focus:ring-red-800 focus:border-red-800 text-red-800 placeholder-red-400" placeholder="999 999 999">
                </div>
            </div>

            <!-- Sistema previsional / Essalud / Pensionista -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
                <div>
                <label for="sistema-previsional" class="text-sm font-medium inline-block mb-2">Sistema Previsional</label>
                <select id="sistema-previsional" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>ONP</option>
                    <option>AFP</option>
                </select>
                </div>
                <div>
                <label for="essalud" class="text-sm font-medium inline-block mb-2">ESSALUD Vida</label>
                <select id="essalud" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Sí</option>
                    <option>No</option>
                </select>
                </div>
                <div>
                <label for="pensionista" class="text-sm font-medium inline-block mb-2">Pensionista</label>
                <select id="pensionista" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Sí</option>
                    <option>No</option>
                </select>
                </div>
            </div>

            <!-- Grado de instrucción / Institución / Carrera / Año -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 fila-formulario">
                <div>
                <label for="grado-instruccion" class="text-sm font-medium inline-block mb-2">Grado de Instrucción</label>
                <select id="grado-instruccion" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Primaria</option>
                    <option>Secundaria</option>
                    <option>Técnico</option>
                    <option>Universitario</option>
                    <option>Postgrado</option>
                </select>
                </div>
                <div>
                <label for="institucion" class="text-sm font-medium inline-block mb-2">Institución</label>
                <input type="text" id="institucion" class="form-input w-full" placeholder="Nombre de la institución">
                </div>
                <div>
                <label for="carrera" class="text-sm font-medium inline-block mb-2">Carrera</label>
                <input type="text" id="carrera" class="form-input w-full" placeholder="Carrera profesional">
                </div>
                <div>
                <label for="ano-egreso" class="text-sm font-medium inline-block mb-2">Año de egreso</label>
                <input type="number" id="ano-egreso" class="form-input w-full" min="1950" max="2030" placeholder="2020">
                </div>
            </div>

            <!-- Embargos / Sustancias ilícitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 fila-formulario">
                <div>
                <label for="embargos" class="text-sm font-medium inline-block mb-2">Embargos en instituciones financieras</label>
                <select id="embargos" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Sí</option>
                    <option>No</option>
                </select>
                </div>
                <div>
                <label for="sustancias" class="text-sm font-medium inline-block mb-2">Consumo de sustancias ilícitas</label>
                <select id="sustancias" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option>Sí</option>
                    <option>No</option>
                </select>
                </div>
            </div>

            <!-- DEPARTAMENTO/PROVINCIA/DISTRITO -->
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
                <div>
                <label for="departamento" class="text-sm font-medium inline-block mb-2">Departamento</label>
                <select id="departamento" class="form-select w-full">
                    <option value="">Seleccionar</option>
                </select>
                </div>
                <div>
                <label for="provincia" class="text-sm font-medium inline-block mb-2">Provincia</label>
                <select id="provincia" class="form-select w-full">
                    <option value="">Seleccionar</option>
                </select>
                </div>
                <div>
                <label for="distrito" class="text-sm font-medium inline-block mb-2">Distrito</label>
                <select id="distrito" class="form-select w-full">
                    <option value="">Seleccionar</option>
                </select>
                </div>
            </div>

            <!-- Dirección actual / DNI -->
            <div class="space-y-4 fila-formulario">
                <div>
                <label for="direccion-actual" class="text-sm font-medium inline-block mb-2">Dirección Actual</label>
                <textarea id="direccion-actual" required rows="2" class="form-input w-full" placeholder="Ingrese su dirección actual completa"></textarea>
                </div>
                <div>
                <label for="direccion-dni" class="text-sm font-medium inline-block mb-2">Dirección DNI</label>
                <textarea id="direccion-dni" rows="2" class="form-input w-full" placeholder="Dirección registrada en el DNI"></textarea>
                </div>
            </div>

            <!-- Emergencia -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
                <div>
                <label for="emergencia-llamar" class="text-sm font-medium inline-block mb-2">En caso de Emergencia llamar a</label>
                <input type="text" id="emergencia-llamar" class="form-input w-full" placeholder="Nombre completo">
                </div>
                <div>
                <label for="celular-emergencia" class="text-sm font-medium inline-block mb-2">Número de celular</label>
                <input type="text" id="celular-emergencia" maxlength="9" class="form-input w-full" placeholder="999 999 999">
                </div>
                <div>
                <label for="parentesco" class="text-sm font-medium inline-block mb-2">Parentesco</label>
                <input type="text" id="parentesco" class="form-input w-full" placeholder="Ej: Hermano, Padre, etc.">
                </div>
            </div>

            </div>
        </div>

        <!-- Mis Datos Laborales -->
        <div class="border rounded-lg shadow  mb-2">
        <div class="bg-primary text-white px-4 py-2 font-semibold rounded-t-lg">
            MIS DATOS LABORALES
        </div>
        <div class="p-6 space-y-6">

            <!-- Curso SUCAMEC / SMO / Institución -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
            <div>
                <label for="curso-sucamec" class="text-sm font-medium inline-block mb-2">Curso SUCAMEC</label>
                <select id="curso-sucamec" class="form-select w-full">
                    <option value="">Seleccionar</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div>
                <label for="smo" class="text-sm font-medium inline-block mb-2">S.M.O.</label>
                <input type="text" id="smo" class="form-input w-full" placeholder="Número SMO">
            </div>
            <div id="institucion-container" class="hidden">
                <label for="institucion-laboral" class="text-sm font-medium inline-block mb-2">Institución</label>
                <input type="text" id="institucion-laboral" class="form-input w-full" placeholder="Institución donde realizó el curso">
            </div>
            </div>

            <!-- Licencia / Tipo arma / Arma propia -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
            <div>
                <label for="licencia-arma" class="text-sm font-medium inline-block mb-2">Licencia de Arma</label>
                <input type="text" id="licencia-arma" class="form-input w-full" placeholder="Número de licencia">
            </div>
            <div>
                <label for="tipo-arma" class="text-sm font-medium inline-block mb-2">Tipo de Arma</label>
                <select id="tipo-arma" class="form-select w-full">
                <option value="">Seleccionar</option>
                <option value="pistola">Pistola</option>
                <option value="revolver">Revólver</option>
                <option value="escopeta">Escopeta</option>
                <option value="rifle">Rifle</option>
                </select>
            </div>
            <div>
                <label for="arma-propia" class="text-sm font-medium inline-block mb-2">Arma Propia</label>
                <select id="arma-propia" class="form-select w-full">
                <option value="">Seleccionar</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
                </select>
            </div>
            </div>

            <!-- Brevete -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 fila-formulario">
            <div>
                <label for="brevete" class="text-sm font-medium inline-block mb-2">N° Brevete</label>
                <input type="text" id="brevete" class="form-input w-full" placeholder="Número de brevete">
            </div>
            <div>
                <label for="clase-brevete" class="text-sm font-medium inline-block mb-2">Clase</label>
                <select id="clase-brevete" class="form-select w-full">
                <option value="">Seleccionar</option>
                <option value="A-I">A-I</option>
                <option value="A-IIa">A-IIa</option>
                <option value="A-IIb">A-IIb</option>
                <option value="A-III">A-III</option>
                <option value="B-I">B-I</option>
                <option value="B-IIa">B-IIa</option>
                <option value="B-IIb">B-IIb</option>
                <option value="B-IIc">B-IIc</option>
                </select>
            </div>
            <div>
                <label for="tipo-vehiculo" class="text-sm font-medium inline-block mb-2">Tipo Vehículo</label>
                <input type="text" id="tipo-vehiculo" class="form-input w-full" placeholder="Tipo de vehículo">
            </div>
            <div>
                <label for="vehiculo-propio" class="text-sm font-medium inline-block mb-2">Vehículo Propio</label>
                <select id="vehiculo-propio" class="form-select w-full">
                <option value="">Seleccionar</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
                </select>
            </div>
            </div>

            <!-- Experiencia Laboral -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 fila-formulario">
            <div>
                <label for="empresa-anterior" class="text-sm font-medium inline-block mb-2">Empresa Anterior</label>
                <input type="text" id="empresa-anterior" class="form-input w-full" placeholder="Nombre de la empresa">
            </div>
            <div>
                <label for="cargo-anterior" class="text-sm font-medium inline-block mb-2">Cargo</label>
                <input type="text" id="cargo-anterior" class="form-input w-full" placeholder="Cargo desempeñado">
            </div>
            <div>
                <label for="duracion" class="text-sm font-medium inline-block mb-2">Duración</label>
                <input type="text" id="duracion" class="form-input w-full" placeholder="Ej: 2 años, 6 meses">
            </div>
            </div>

            <!-- Profesión Alterna -->
            <div class="fila-formulario">
            <label for="profesion-alterna" class="text-sm font-medium inline-block mb-2">Profesión u Ocupación Alterna</label>
            <input type="text" id="profesion-alterna" class="form-input w-full" placeholder="Otra profesión u ocupación">
            </div>

        </div>
        </div>



        <!-- Mis Datos Familiares -->
        <div class="border rounded-lg shadow  mb-2">
        <div class="bg-primary text-white px-4 py-2 font-semibold rounded-t-lg">
            MIS DATOS FAMILIARES
        </div>
        <div class="p-6 space-y-6">

            <!-- Contenedor dinámico de familiares -->
            <div id="familyContainer" class="space-y-4">
            <!-- Familiar (item) -->
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
                <div class="flex gap-2">
                <div class="flex-1">
                    <label class="text-sm font-medium inline-block mb-2">Fecha Nacimiento</label>
                    <input type="date" name="fechaNacimiento[]" class="form-input w-full">
                </div>
                <button type="button" class="remove-family self-end px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                    Eliminar
                </button>
                </div>
            </div>
            </div>

            <!-- Botón agregar -->
            <button id="addFamilyMember" type="button" class="w-full px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200">
            Agregar Familiar
            </button>
        </div>
        </div>



        <!-- Aceptación de Procedimientos -->
        <!-- <div class="border rounded-lg shadow">
        <div class="bg-primary text-white px-4 py-2 font-semibold rounded-t-lg">
            MI ACEPTACIÓN DE LOS PROCEDIMIENTOS DE LA EMPRESA
        </div>
        <div class="p-6 space-y-6">

            <div class="flex items-start space-x-3 fila-formulario">
            <input type="checkbox" id="sip-acceptance" name="sip_acceptance" class="mt-1">
            <div class="space-y-2">
                <label for="sip-acceptance" class="text-sm font-medium leading-none">
                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                    1.
                </span>
                MI SISTEMA DE INFORMACIÓN PERSONAL - SIP
                </label>
                <div class="text-sm text-gray-600 space-y-1 pl-6">
                <p><strong>a.</strong> Utilizaré la plataforma virtual personal SIP que la empresa me proporciona con usuario y clave.</p>
                <p><strong>b.</strong> Visitaré el SIP, las veces que sea necesario para recibir información relacionada con mis funciones, obligaciones y derechos.</p>
                <p><strong>c.</strong> La información en el SIP es de propiedad de mi empleador por lo que cuidaré de la confidencialidad de su contenido.</p>
                </div>
            </div>
            </div>

            <div class="flex items-start space-x-3 fila-formulario">
            <input type="checkbox" id="declarations-acceptance" name="declarations_acceptance" class="mt-1">
            <div class="space-y-2">
                <label for="declarations-acceptance" class="text-sm font-medium leading-none">
                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                    2.
                </span>
                MIS DECLARACIONES Y BOLETAS DE REMUNERACIONES
                </label>
                <div class="text-sm text-gray-600 pl-6">
                <p><strong>a.</strong> Acepto que mi correo electrónico personal, sea utilizado por la empresa para declarar mis remuneraciones en el T-Registro de SUNAT.</p>
                <p><strong>b.</strong> Utilizaré el Sistema de Información Personal - SIP que me proporciona mi empleador con Usuario y Clave para recibir mis Boletas de</p>
                <p><strong>  </strong> Remuneraciones y firmar el Cargo de Recepción correspondiente.</p>
                </div>
            </div>
            </div>

            <div class="flex items-start space-x-3 fila-formulario">
            <input type="checkbox" id="comunication-acceptance" name="comunication_acceptance" class="mt-1">
            <div class="space-y-2">
                <label for="comunication-acceptance" class="text-sm font-medium leading-none">
                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                    3.
                </span>
                MIS CANALES DE COMUNICACIONES
                </label>
                <div class="text-sm text-gray-600 pl-6">
                <p><strong>a.</strong> Autorizo de manera libre y voluntaria a mi empleador para enviarme documentos e información vinculada a mi relación laboral,</p>
                <p><strong>  </strong> a través de mi correo electrónico y/o WhatsApp personales, siendo éstos, los medios de comunicación oficiales entre ambas partes.</p>
                <p><strong>b.</strong> Atenderé las llamadas que la empresa realice a mi teléfono celular personal para coordinaciones relacionadas al servicio, estando</p>
                <p><strong>  </strong> obligado a contestar estas llamadas o devolverlas en todos los casos.</p>
                </div>
            </div>
            </div>

            <div class="flex items-start space-x-3 fila-formulario">
            <input type="checkbox" id="signature-acceptance" name="signature_acceptance" class="mt-1">
            <div class="space-y-2">
                <label for="signature-acceptance" class="text-sm font-medium leading-none">
                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                    4.
                </span>
                MI FIRMA Y HUELLAS REGISTRADAS
                </label>
                <div class="text-sm text-gray-600 pl-6">
                <p><strong>a.</strong> Autorizo que mi firma y huella registradas, sean utilizadas para los reportes de procesos internos que me involucren.</p>
                <p><strong>b.</strong> Conozco y acepto que mi firma física en un formato o mi firma digital en el sistema, se utilicen en reportes de la empresa empleando</p>
                <p><strong>  </strong> mi firma manuscrita escaneada.</p>
                </div>
            </div>
            </div>

            <div class="flex items-start space-x-3 fila-formulario">
            <input type="checkbox" id="training-acceptance" name="training_acceptance" class="mt-1">
            <div class="space-y-2">
                <label for="training-acceptance" class="text-sm font-medium leading-none">
                <span class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded mr-2">
                    5.
                </span>
                MIS CAPACITACIONES
                </label>
                <div class="text-sm text-gray-600 pl-6">
                <p><strong>a.</strong> Acepto la modalidad de capacitación que la empresa ha establecido para el mejor cumplimiento de mis funciones.</p>
                <p><strong>b.</strong> Asistiré a las capacitaciones presenciales y virtuales registrando mi firma de manera física y electrónica respectivamente.</p>
                <p><strong>c.</strong> Cuando firme asistencia empleando los sistemas de capacitación virtuales, acepto que se consigne mi firma digital en los reportes</p>
                <p><strong>  </strong> correspondientes.</p>
                </div>
            </div>
            </div>

        </div>
        </div> -->


        



    </form>

    <!-- Footer fijo -->
    <div class="border-t bg-white px-6 py-4 flex justify-end space-x-3 sticky bottom-0">
      <button id="cerrarModal" type="button"
        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
        Cancelar
      </button>
      <button type="submit" form="formDatos"
        class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary-dark">
        Guardar
      </button>
    </div>


  </div>
</div>

<script>
  
</script>



@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
@endsection

@vite(['resources/js/functions/gestion_dj.js'])
@section('script')

@endsection