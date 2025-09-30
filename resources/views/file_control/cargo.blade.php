@extends('layouts.vertical', ['title' => 'Gestión de Cargos'])

@section('css')

@endsection

@section('content')

@include("layouts.shared/page-title", ["subtitle" => "Recursos Humanos", "title" => "Cargos"])

<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="modal-editar-cargo"
    class="hs-overlay w-full h-full fixed top-0 left-0 z-70 transition-all duration-500 overflow-x-hidden overflow-y-auto hidden pointer-events-none">
    <div
        class="-translate-y-5 hs-overlay-open:translate-y-0 hs-overlay-open:opacity-100 opacity-0 ease-in-out transition-all duration-500 sm:max-w-lg sm:w-full my-8 sm:mx-auto flex flex-col bg-white shadow-sm rounded">
        <div class="flex flex-col border border-default-200 shadow-sm rounded-lg  pointer-events-auto">
            <div class="flex justify-between items-center py-3 px-4 border-b border-default-200">
                <h3 class="text-lg font-medium text-default-900">
                    Agregar Cargo
                </h3>
                <button type="button" class="cerrar-modal text-default-600 cursor-pointer"
                    data-hs-overlay="#modal-agregar-cargo">
                    <i class="i-tabler-x text-lg"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <label for="txtNombreCargo">Nombre del cargo: </label>
                <input type="text" placeholder="" id="txtNombreCargo"
                    class="w-80 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm">

                {{-- <button type="button" class="btn bg-primary text-white rounded-full mx-auto" 
                  data-hs-overlay="#modal-agregar-cargo">
                      Registrar
                  </button> --}}

                {{-- <p class="mt-1 text-default-600">
                      This is a wider card with supporting text below as a natural lead-in
                      to
                      additional content.
                  </p> --}}
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-default-200">
                <button type="button" class="cerrar-modal btn bg-secondary text-white"
                    data-hs-overlay="#modal-agregar-cargo">
                    <i class="i-tabler-x me-1"></i>
                    Cerrar
                </button>
                <a class="btn bg-primary text-white" href="#">
                    Registrar
                </a>
            </div>

        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-8">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Gestión de Cargos&nbsp;<span 
            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-primary/25 text-primary-800"
            id="txtMensajeNuevo">Nuevo</span></h3>
            
        </div>

        <div class="hidden gap-1 mt-1 items-center justify-center" id="soloEdicion">
            <span>¿Quieres crear un nuevo cargo?</span>
            <button class="btn bg-info text-white rounded-full mx-2" id="nuevoCargo2">Nuevo cargo</button>
        </div>

        <div class="p-6">
            <form id="formSaveCargo">
                <input type="hidden" name="codigoEditar" id="codigoEditar" value="0">

                <div x-data="{ nameCargo: '', description: '', abbreviation: '' }" class="grid lg:grid-cols-1 gap-6">
                    <div class="flex items-center justify-center gap-2">
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" 
                            name="rdTipoCargo" id="opOperativo" checked="" value="1">
                            <label class="ms-1.5" for="opOperativo">Operativo</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" 
                            name="rdTipoCargo" id="opAdmins" value="2">
                            <label class="ms-1.5" for="opAdmins">Administrativo</label>
                        </div>
                    </div>
                    <div>
                        <label for="slcArea" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">
                        Área </label>
                        <select class="form-select" id="slcArea">
                            <option value="" disabled selected>-Seleccionar-</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="nombre"
                            class="text-default-800 text-sm font-medium inline-block mb-2">Nombre</label>
                        <input type="text" id="nombre" class="form-input" x-model="nameCargo" @input="nameCargo = nameCargo.toUpperCase()" placeholder="Nombre del cargo" required>
                    </div>
                    <div>
                        <label for="txtDescripcion" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Descripción (Función del cargo)</label>
                        <input type="text" id="txtDescripcion" class="form-input" x-model="description" @input="description = description.toUpperCase()" placeholder="Descripción del cargo">
                    </div>
                    <div> 
                        <label for="txtAbreviatura" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Abreviatura</label>
                        <input type="text" id="txtAbreviatura" class="form-input" x-model="abbreviation" @input="abbreviation = abbreviation.toUpperCase()" placeholder="Abreviatura del cargo">
                    </div>
                    <div class="grid lg:grid-cols-2 gap-1">
                            <!-- <input type="hidden" id="slcPosicion" value="1"> -->
                        <div>
                            <label for="slcPosicion" 
                            class="text-default-800 text-sm font-medium inline-block mb-2">
                            Servicio </label>
                            <select class="form-select" id="slcPosicion" >
                                <option disabled selected>-Seleccionar-</option>
                            </select>
                        </div>
                        <div id="divSubservicios" class="hidden">
                            <label for="slcGrupo" 
                            class="text-default-800 text-sm font-medium inline-block mb-2">
                            Subservicio </label>
                            <select class="form-select" id="slcGrupo">
                                <option disabled selected>-Seleccionar-</option>
                            </select>
                        </div>
                    </div>
                    

                    <!-- <div class="flex items-center justify-center gap-2">
                        <label>Vigencia</label>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opOperativo" checked="">
                            <label class="ms-1.5" for="opOperativo">si</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-radio text-primary" name="rdTipoCargo" id="opAdmins">
                            <label class="ms-1.5" for="opAdmins">no</label>
                        </div>
                    </div> -->

                    <!-- <div>
                        <label for="txtLabor" 
                        class="text-default-800 text-sm font-medium inline-block mb-2">Labor Esp.</label>
                        <input type="text" id="txtLabor" class="form-input">
                    </div> -->

                    <div class="flex justify-center w-full mt-5">
                        <button type="submit" id="btnRegistrarCargo" class="btn rounded-full bg-success/25 text-success hover:bg-success hover:text-white">Registrar Cargo <i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <div class="card">
        
        <div class="card-header">
            <h3 class="card-title">Listado de Cargos</h3>
        </div>
        <div class="w-full px-5 py-2 mt-3 ">
            <div class="flex justify-center items-center gap-4 mb-4 mt-2">
                <div class="form-check">
                    <input type="radio" class="form-radio text-primary" 
                    name="cargoFiltro" id="radioTodos" value="TODOS" checked>
                    <label class="ms-1.5" for="radioTodos">TODOS  ({{ $todos }})</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-radio text-primary" 
                    name="cargoFiltro" id="radioOper" value="OPERATIVO">
                    <label class="ms-1.5" for="radioOper">OPERATIVO  ({{ $operativo }})</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-radio text-primary" 
                    name="cargoFiltro" id="radioAdmin" value="ADMINISTRATIVO">
                    <label class="ms-1.5" for="radioAdmin">ADMINISTRATIVO  ({{ $administrativo }})</label>
                </div>
            </div>
            <div class="flex justify-between">
                <input type="text" id="buscarCargo" placeholder="Buscar..."
                class="w-40 px-3 py-1 border border-gray-300 rounded-full focus:outline-none focus:border-blue-500 transition-all text-sm" />
                <div x-data="{ soloEliminados: false }" class="flex items-center">
                    <input class="form-switch" type="checkbox" role="switch" id="chkEliminados"
                        x-model="soloEliminados">
                    <label class="ms-1.5" for="chkEliminados">Solo eliminados</label>

                    <div x-effect="
                        soloEliminados 
                            ? aplicarFiltroEliminarCargo(0)
                            : aplicarFiltroEliminarCargo(1);
                    "></div>
                </div>
            </div>

            <div id="tblCargos" class="w-full mt-8"></div>
        </div>
    </div>    
</div>

@endsection

@vite(['resources/js/functions/cargo.js'])
@section('script')

@endsection