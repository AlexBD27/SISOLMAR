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
  <div
    class="translate-y-10 hs-overlay-open:translate-y-0 hs-overlay-open:opacity-100 opacity-0 ease-in-out transition-all duration-500 sm:max-w-lg sm:w-full my-8 sm:mx-auto flex flex-col bg-white shadow-sm rounded">
    <div class="flex flex-col border border-default-200 shadow-sm rounded-lg pointer-events-auto">

      <!-- Header -->
      <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
        <h3 class="text-lg font-semibold">Seleccionar Personal</h3>
        <button type="button" class="text-default-600 cursor-pointer" data-hs-overlay="#modal-registro">
          <i class="i-tabler-x text-lg"></i>
        </button>
      </div>

      <!-- Buscador -->
      <div class="p-4">
        <input type="text" id="buscarPersonal" class="form-input w-full"
          placeholder="Buscar personal..." />
      </div>

      <!-- Tabla -->
      <div class="overflow-y-auto max-h-[300px]">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-50 sticky top-0">
            <tr>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Matricular</th>
            </tr>
          </thead>
          <tbody id="tablaPersonal"></tbody>
        </table>
      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-2 p-4 border-t border-default-200">
        <button class="btn bg-gray-200" data-hs-overlay="#modal-registro">Cancelar</button>
        <button id="btnGuardarMatricula" class="btn bg-success text-white">Guardar</button>
      </div>

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
