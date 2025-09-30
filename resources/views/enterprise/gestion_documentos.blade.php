@extends('layouts.vertical', ['title' => 'Gestión de documentos'])
@section('css')
@endsection
@section('content')
@include("layouts.shared/page-title", ["subtitle" => "Enterprise", "title" => "Gestión de documentos"])


<div class="grid 2xl:grid-cols-2 grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Registro de documentos empresariales</h4>
        </div>
        <div class="card-body">
            <div x-data="{ soloEliminados: false }" class="flex items-center">
                <input class="form-switch" type="checkbox" role="switch" id="chkEliminados"
                    x-model="soloEliminados">
                <label class="ms-1.5" for="chkEliminados">Solo eliminados</label>

                <div x-effect="
                    soloEliminados 
                        ? gestionListarCursos(0)
                        : gestionListarCursos(1);
                "></div>
            </div>
            <div class="mt-5 overflow-y overflow-x">
                <table id="tblCursos" class="datatable responsive-table" >
                    <thead>
                        <th >#</th>
                        <th >Nombre</th>
                        <th >Periodo</th>
                        <th >Acciones</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            

    
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
            Gestión de documentos
                <span 
                class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-primary/25 text-primary-800"
                id="txtMensajeNuevo">Nuevo</span>
            </h4>
        </div>
        <div class="flex items-center justify-center gap-2 mt-4 hidden" id="viewEditCreate">
            <span>¿Quieres registrar un curso?</span>
            <button type="button" id="btnCambiarEdit" onclick="restaurarFormCurso()"
            class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                Crear curso
            </button>
        </div>
        <div class="card-body" x-data="formCursoGestion()">
            <input type="hidden" name="codGestionEditar" x-model="codigo" id="codGestionEditar">
            <div class="w-full mt-4">
                <h3 class="text-lg font-semibold text-default-700 text-center mb-1">Datos del curso</h3>
                <hr>
            </div>
            <div class="w-full grid gap-6 mt-4 lg:grid-cols-1 pb-8">
                <div>
                    <label for="txtNombreCurso" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Nombre del curso
                    </label>
                    <input type="text" id="txtNombreCurso"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm "
                    x-model="nombre" />
                </div>
                <div>
                    <span class="text-gray-800 text-base font-semibold mb-2 block">Periodo</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dtInicio" class="text-gray-800 text-sm font-medium inline-block mb-2">Inicio</label>
                            <input type="date" id="dtInicio"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                            x-model="fecha1" :min="fechaActual"/>
                        </div>
                        <div>
                            <label for="dtFin" class="text-gray-800 text-sm font-medium inline-block mb-2">Fin</label>
                            <input type="date" id="dtFin"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                            x-model="fecha2"
                            :min="fecha1"
                            :disabled="!fecha1"/>
                        </div>
                        <div 
                        x-effect="
                            if (fecha1 && (!fecha2 || fecha2 < fecha1)) {
                                fecha2 = fecha1;
                            }
                        ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full mt-8">
                <h3 class="text-lg font-semibold text-default-700 text-center mb-1">Datos del Examen</h3>
                <hr>
            </div>
            <div class="w-full grid gap-6 mt-4 lg:grid-cols-1 pb-8"  >
                <div>
                    <label for="txtNombreExamen" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Nombre del examen
                    </label>
                    <input 
                        type="text" 
                        id="txtNombreExamen"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                        x-model="nombreExa"
                        x-effect="nombreExa = nombre ? `Examen de ${nombre}` : ''"
                        readonly
                        />
                </div>
                <div>
                    <label for="txtDescripcion" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Descripción
                    </label>
                    <textarea id="txtDescripcion"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    x-model="descripcion"></textarea>
                </div>
                <div>
                    <span class="text-gray-800 text-base font-semibold mb-2 block">Vigencia</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dtDesde" class="text-gray-800 text-sm font-medium inline-block mb-2">Desde</label>
                            <input type="date" id="dtDesde"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                            x-model="fecha1exa"
                            x-effect="fecha1exa = fecha1 ? fecha1 : ''"
                             readonly/>
                        </div>
                        <div>
                            <label for="dtHasta" class="text-gray-800 text-sm font-medium inline-block mb-2">Hasta</label>
                            <input type="date" id="dtHasta"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                             x-model="fecha2exa"
                             x-effect="fecha2exa = fecha2 ? fecha2 : ''"
                             readonly/>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="txtLimite" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Límite de tiempo (minutos)
                    </label>
                    <input type="number" id="txtLimite"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                     x-model="limiteTiempo"
                    />
                </div>
                <div>
                    <label for="txtNota" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Nota mínima
                    </label>
                    <input type="number" id="txtNota"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                     x-model="nota"
                    />
                </div>
                <div>
                    <label for="txtIntentos" class="text-gray-800 text-base font-medium inline-block mb-2">
                    Número de intentos
                    </label>
                    <input type="number" id="txtIntentos"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                    x-model="intentos"
                    />
                </div>
            </div>
            <div class="flex justify-center w-full py-8">
                <button type="button" id="btnGestion" @click="registrar"
                class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                    Registrar Curso&nbsp;<i class="fa-solid fa-floppy-disk"></i>
                </button>
                <button type="button" id="btnGestionEditar" onclick="editarFormGestionCurso()"
                class="hidden btn rounded-full bg-warning/25 text-warning hover:bg-warning hover:text-white ">
                    Actualizar curso
                </button>
            </div>
        </div>
    </div>

</div>





@endsection
@section('script')
@endsection
@vite(['resources/js/functions/enterprise/gestion_documentos.js'])