@extends('layouts.vertical', ['title' => 'Gestión de matrícula'])
@section('css')
@endsection
@section('content')
@include("layouts.shared/page-title", ["subtitle" => "Capacitación", "title" => "Gestión de matrícula"])


<div class="grid 2xl:grid-cols-2 grid-cols-1 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Lista de cursos</h4>
        </div>

        <div class="card-body">
            <div 
                x-data="{ soloEliminados: false, filtroArea: '', filtroTipoCurso: '' }" 
                class="flex flex-wrap items-end gap-6"
            >
                <div class="flex items-center">
                <input 
                    class="form-switch" 
                    type="checkbox" 
                    role="switch" 
                    id="chkEliminados"
                    x-model="soloEliminados"
                >
                <label class="ms-1.5 font-medium text-sm text-gray-700" for="chkEliminados">
                    Solo eliminados
                </label>
                </div>

                <div class="flex flex-col flex-1 min-w-[200px]">
                <label for="slcFiltroTipoCurso" class="text-sm font-medium text-gray-700 mb-1">
                    Tipo de curso
                </label>
                <select 
                    id="slcFiltroTipoCurso" 
                    x-model="filtroTipoCurso"
                    class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">-- Todos --</option>
                </select>
                </div>

                <div class="flex flex-col flex-1 min-w-[200px]">
                <label for="slcFiltroArea" class="text-sm font-medium text-gray-700 mb-1">
                    Área
                </label>
                <select 
                    id="slcFiltroArea" 
                    x-model="filtroArea"
                    class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">-- Todas --</option>
                </select>
                </div>

                <div x-effect="listarCursos( soloEliminados ? 0 : 1, filtroArea, filtroTipoCurso )"></div>
            </div>

            <div class="mt-5 overflow-y overflow-x">
                <table id="tblCursos" class="datatable responsive-table w-full">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>
            </div>
            </div>


    </div>

</div>


<div id="modal-registro"
    class="hs-overlay w-full h-full fixed top-0 left-0 z-70 transition-all duration-500 overflow-y-auto hidden pointer-events-none">
    <div class="translate-y-10 hs-overlay-open:translate-y-0 hs-overlay-open:opacity-100 opacity-0 ease-in-out transition-all duration-500 sm:max-w-5xl sm:w-full my-8 sm:mx-auto flex flex-col bg-white shadow-lg rounded-2xl">
        
        <!-- Header -->
        <div class="flex justify-between items-center py-3 px-6 border-b border-default-200 bg-gray-50 rounded-t-2xl">
            <h2 class="text-lg font-semibold text-gray-800">Matricular Personal en Curso</h2>
            <button type="button" class="text-gray-600 hover:text-gray-900" data-hs-overlay="#modal-registro">
                <i class="i-tabler-x text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-x-auto">
            <table id="tablaPersonal" class="w-full border text-sm rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Seleccionar</th>
                        <th class="px-4 py-2">DNI</th>
                        <th class="px-4 py-2">Nombres</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Cargo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se llena dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-4 border-t border-default-200 bg-gray-50 rounded-b-2xl">
            <button type="button" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300"
                data-hs-overlay="#modal-registro">Cancelar</button>
            <button type="button" id="btn-confirmar-matricula" 
                class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                Confirmar Matrícula
            </button>
        </div>
    </div>
</div>





@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
@endsection

@vite(['resources/js/functions/capacitacion/gestion_matricula.js'])
@section('script')
@endsection
